#!/bin/env python
#-*-coding:utf-8-*-

import MySQLdb
import string
import sys 
reload(sys) 
sys.setdefaultencoding('utf8')
import ConfigParser

def get_item(data_dict,item):
    try:
       item_value = data_dict[item]
       return item_value
    except:
       pass


def get_parameters(conn):
    try:
        curs=conn.cursor()
        data=curs.execute('select name,value from v$parameter');
        data_list=curs.fetchall()
        parameters={}
        for item in data_list:
            parameters[item[0]] = item[1]

    except Exception,e:
        print e

    finally:
        curs.close()

    return parameters


def get_sysstat(conn):
    try:
        curs=conn.cursor()
        data=curs.execute('select name,value value from v$sysstat');
        data_list=curs.fetchall()
        sysstat={}
        for item in data_list:
            sysstat[item[0]] = item[1]

    except Exception,e:
        print e

    finally:
        curs.close()

    return sysstat


def get_instance(conn,field):
    try:
        curs=conn.cursor()
        curs.execute("select %s from v$instance" %(field) );
        result = curs.fetchone()[0]

    except Exception,e:
        result = ''
        print e

    finally:
        curs.close()

    return result


def get_database(conn,field):
    try:
        curs=conn.cursor()
        curs.execute("select %s from v$database" %(field) );
        result = curs.fetchone()[0]

    except Exception,e:
        result = ''
        print e

    finally:
        curs.close()

    return result


def get_version(conn):
    try:
        curs=conn.cursor()
        curs.execute("select product,version from product_component_version where product like '%Database%'");
        result = curs.fetchone()[1]

    except Exception,e:
        print e

    finally:
        curs.close()

    return result


def get_sessions(conn):
    try:
        curs=conn.cursor()
        curs.execute("select count(*) from v$session");
        result = curs.fetchone()[0]
        return result

    except Exception,e:
        return null    
        print e

    finally:
        curs.close()



def get_actives(conn):
    try:
        curs=conn.cursor()
        curs.execute("select count(*) from v$session where username not in('SYS','SYSTEM') and username is not null and STATUS='ACTIVE'");
        result = curs.fetchone()[0]
        return result

    except Exception,e:
        return null
        print e

    finally:
        curs.close()


def get_waits(conn):
    try:
        curs=conn.cursor()
        curs.execute("select count(*) from v$session where event like 'library%' or event like 'cursor%' or event like 'latch%'  or event like 'enq%' or event like 'log file%'");
        result = curs.fetchone()[0]
        return result

    except Exception,e:
        return null
        print e

    finally:
        curs.close()


def get_dg_stats(conn):
    try:
        curs=conn.cursor()
        curs.execute("SELECT substr((SUBSTR(VALUE,5)),0,2)*3600 + substr((SUBSTR(VALUE,5)),4,2)*60 + substr((SUBSTR(VALUE,5)),7,2) AS seconds,VALUE FROM v$dataguard_stats a WHERE NAME ='apply lag'");
        list = curs.fetchone()
        if list:
            result = 1
        else:
            result = 0
        return result

    except Exception,e:
        return null
        print e

    finally:
        curs.close()



def get_dg_delay(conn):
    try:
        curs=conn.cursor()
        curs.execute("SELECT substr((SUBSTR(VALUE,5)),0,2)*3600 + substr((SUBSTR(VALUE,5)),4,2)*60 + substr((SUBSTR(VALUE,5)),7,2) AS seconds,VALUE FROM v$dataguard_stats a WHERE NAME ='apply lag'");
        list = curs.fetchone()
        if list:
            result = list[0] 
        else:
            result = '---'
        return result

    except Exception,e:
        return null
        print e

    finally:
        curs.close()


def get_sysdate(conn):
    try:
        curs=conn.cursor()
        curs.execute("select to_char(sysdate, 'yyyymmddhh24miss') from dual");
        result = curs.fetchone()[0]
        return result

    except Exception,e:
        return null
        print e

    finally:
        curs.close()


def get_dg_p_info(conn, dest_id):
    try:
        curs=conn.cursor()
        curs.execute("""select *
                            from (select dest_id,
                                        thread#,
                                        sequence#+1,
                                        archived,
                                        applied,
                                        current_scn,
                                        to_char(scn_to_timestamp(current_scn), 'yyyy-mm-dd hh24:mi:ss') curr_db_time,
                                        row_number() over(partition by thread# order by sequence# desc) rn
                                    from v$archived_log t, v$database d
                                    where t.dest_id = %s)
                            where rn = 1 """ %(dest_id));
        result = curs.fetchall()
        
        return result
    except Exception,e:
        return None
        print e

    finally:
        curs.close()



def get_dg_s_ms(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select ms.thread#,
                               ms.sequence#,
                               ms.block#,
                               ms.delay_mins
                          from v$managed_standby  ms
                         where ms.process in ('MRP0')
                           and ms.sequence# <> 0 """);
        result = curs.fetchone()

        return result
    except Exception,e:
        return None
        print e

    finally:
        curs.close()


def get_dg_s_al(conn, scn):
    try:
        curs=conn.cursor()
        curs.execute(""" select thread#,sequence# from v$archived_log where first_change#<%s and next_change#>=%s """ %(scn,scn));
        result = curs.fetchone()

        return result
    except Exception,e:
        return None
        print e

    finally:
        curs.close()


def get_dg_s_rate(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select *
												  from (select rp.sofar avg_apply_rate
												          from v$recovery_progress rp
												         where rp.item = 'Average Apply Rate'
												         order by start_time desc)
												 where rownum < 2 """);
        result = curs.fetchone()

        return result
    except Exception,e:
        return None
        print e

    finally:
        curs.close()


def get_dg_s_mrp(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select status from gv$session where program like '%(MRP0)' """);
        list = curs.fetchone()
        
        if list:
            result = 1
        else:
            result = 0

        return result
    except Exception,e:
        return 0
        print e

    finally:
        curs.close()
        

def get_time_by_scn(conn, scn):
    try:
        result=None
        curs=conn.cursor()
        curs.execute("""select to_char(scn_to_timestamp(%s), 'yyyy-mm-dd hh24:mi:ss') curr_db_time  from v$database """ %(scn));
        res = curs.fetchone()

        if res:
            result = res[0]
        else:
            result = None
            
        return result
    except Exception,e:
        #print e
        return None

    finally:
        curs.close()
        


def get_time_from_restorepoint(conn, scn):
    try:
        result=None
        curs=conn.cursor()
        curs.execute("""select to_char(time, 'yyyy-mm-dd hh24:mi:ss') curr_db_time  from v$restore_point where scn = %s """ %(scn));
        res = curs.fetchone()

        if res:
            result = res[0]
        else:
            result = None
            
        return result
    except Exception,e:
        print e
        return None

    finally:
        curs.close()
        
        
def get_pri_id_by_server(conn, id):
    try:
        result=None
        curs=conn.cursor()
        curs.execute("""select CASE is_switch
                                            WHEN 0 THEN standby_db_id 
                                            ELSE primary_db_id
                                        END as sta_id
                                   from db_cfg_oracle_dg
                                  where primary_db_id = %s or standby_db_id = %s  """ %(id, id));
        res = curs.fetchone()
        
        if res:
            result = res[0]
        else:
            result = None

        return result
    except Exception,e:
        print e
        return None

    finally:
        curs.close()
        
                 
        

def get_earliest_fbscn(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select min(scn) from v$restore_point """);
        result = curs.fetchone()[0]

        return result
    except Exception,e:
        return None
        print e

    finally:
        curs.close()


def get_earliest_fbtime(conn,flashback_retention):
    try:
        curs=conn.cursor()
        curs.execute("""select to_char(min(time), 'yyyy-mm-dd hh24:mi:ss') mintime from v$restore_point where time > sysdate -%s/24/60 """ %(flashback_retention));
        mintime = curs.fetchone()
        
        result = 'null'
        if mintime[0]:
            result = mintime[0]

        return result
    except Exception,e:
        print e
        return None

    finally:
        curs.close()


def get_last_fbtime(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select to_char(max(time), 'yyyymmddhh24miss') maxtime from v$restore_point """);
        lasttime = curs.fetchone()

        result = 'null'
        if lasttime[0]:
            result = lasttime[0]
            
        return result
    except Exception,e:
        print e
        return None

    finally:
        curs.close()


def get_flashback_space_used(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select percent_space_used from v$flash_recovery_area_usage where file_type='FLASHBACK LOG' """);
        fb_space = curs.fetchone()
        
        result = 0
        if fb_space:
           result = fb_space[0]

        return result
    except Exception,e:
        print e
        return None

    finally:
        curs.close()


def get_restorepoint(conn, flashback_retention):
    try:
        curs=conn.cursor()
        curs.execute("select name from v$restore_point where time > sysdate -%s/60/24 order by name desc " %(flashback_retention));
        list = curs.fetchall()
        return list

    except Exception,e:
        return None
        print e

    finally:
        curs.close()


def get_expire_restore_list(conn, flashback_retention):
    try:
        curs=conn.cursor()
        curs.execute("select name from v$restore_point where time < sysdate - %s/60/24 " %(flashback_retention));
        list = curs.fetchall()
        return list

    except Exception,e:
        return None
        print e

    finally:
        curs.close()
        
        
def get_tablespace(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select tpsname,status,mgr,max_size,curr_size, max_used
											  from (SELECT d.tablespace_name tpsname,
											               d.status status,
											               d.segment_space_management mgr,
											               TO_CHAR(NVL(trunc(A.maxbytes / 1024 / 1024), 0), '99999990') max_size,
											               TO_CHAR(NVL(trunc(a.bytes / 1024 / 1024), 0), '99999990') curr_size,
											               TO_CHAR(NVL((a.bytes - NVL(f.bytes, 0)) / a.bytes * 100, 0),
											                       '990D00') c_used,
											               TO_CHAR(NVL((a.bytes - NVL(f.bytes, 0)) / a.maxbytes * 100, 0),
											                       '990D00') max_used
											          FROM sys.dba_tablespaces d,
											               (SELECT tablespace_name,
											                       sum(bytes) bytes,
											                       SUM(case autoextensible
											                             when 'NO' then
											                              BYTES
											                             when 'YES' then
											                              MAXBYTES
											                             else
											                              null
											                           end) maxbytes
											                  FROM dba_data_files
											                 GROUP BY tablespace_name) a,
											               (SELECT tablespace_name,
											                       SUM(bytes) bytes,
											                       MAX(bytes) largest_free
											                  FROM dba_free_space
											                 GROUP BY tablespace_name) f
											         WHERE d.tablespace_name = a.tablespace_name
											           AND d.tablespace_name = f.tablespace_name(+))
											 order by max_used desc """);
        list = curs.fetchall()
        return list

    except Exception,e:
        return None
        print e

    finally:
        curs.close()
        

def get_diskgroup(conn):
    try:
        curs=conn.cursor()
        curs.execute("""select name,
											       state,
											       type,
											       total_mb,
											       free_mb,
											       trunc(((total_mb - free_mb) / total_mb) * 100, 2) used_rate
											  from v$asm_diskgroup """);
        list = curs.fetchall()
        return list

    except Exception,e:
        return None
        print e

    finally:
        curs.close()
        
                
def get_tables(conn):
    try:
        curs=conn.cursor()
        curs.execute("select owner, owner || '.' || table_name from dba_tables ");
        list = curs.fetchall()
        return list

    except Exception,e:
        return None
        print e

    finally:
        curs.close()
