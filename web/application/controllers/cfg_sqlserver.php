<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class cfg_sqlserver extends Front_Controller {
    function __construct(){
		parent::__construct();
    $this->load->model('cfg_sqlserver_model','sqlserver');
    $this->load->model('cfg_os_model','cfg_os');
    $this->load->model("cfg_license_model","license");
		$this->load->library('form_validation');
	
	}
    
    /**
     * 首页
     */
    public function index(){
        parent::check_privilege();
        
        $host=isset($_GET["host"]) ? $_GET["host"] : "";
        $tags=isset($_GET["tags"]) ? $_GET["tags"] : "";
        $setval["tags"]=$tags;
        $setval["host"]=$host;
        $data["setval"]=$setval;
        $ext_where=''; 
        if(!empty($host)){
            $ext_where=$ext_where."  and host like '%$host%' ";
        }
        if(!empty($tags)){
            $ext_where=" and tags like '%$tags%' ";
        }
        
        $sql="select * from db_cfg_sqlserver   where is_delete=0 $ext_where order by id asc";
        
        $result=$this->sqlserver->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        
        $this->layout->view("cfg_sqlserver/index",$data);
    }
    
    /**
     * 回收站
     */
    public function trash(){
        parent::check_privilege();
        $sql="select * from db_cfg_redis  where is_delete=1 order by id asc";
        $result=$this->sqlserver->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        $this->layout->view("cfg_sqlserver/trash",$data);
    }
    
    /**
     * 添加
     */
    public function add(){
        parent::check_privilege();
        
        /*
		 * 提交添加后处理
		 */
		$data['error_code']=0;
		if(isset($_POST['submit']) && $_POST['submit']=='add')
        {
			$this->form_validation->set_rules('host',  'lang:host', 'trim|required');
      $this->form_validation->set_rules('port',  'lang:port', 'trim|required|min_length[4]|max_length[6]|integer');
      $this->form_validation->set_rules('username',  'lang:username', 'trim|required');
      $this->form_validation->set_rules('password',  'lang:password', 'trim|required');
			$this->form_validation->set_rules('tags',  'lang:tags', 'trim|required');
			$this->form_validation->set_rules('threshold_warning_processes',  'lang:alarm_threshold', 'trim|required|integer');
			$this->form_validation->set_rules('threshold_critical_processes',  'lang:alarm_threshold', 'trim|required|integer');
			$this->form_validation->set_rules('threshold_warning_processes_running',  'lang:alarm_threshold', 'trim|required|integer');
			$this->form_validation->set_rules('threshold_critical_processes_running',  'lang:alarm_threshold', 'trim|required|integer');
			$this->form_validation->set_rules('threshold_warning_processes_waits',  'lang:alarm_threshold', 'trim|required|integer');
			$this->form_validation->set_rules('threshold_critical_processes_waits',  'lang:alarm_threshold', 'trim|required|integer');
			if ($this->form_validation->run() == FALSE)
			{
				$data['error_code']='validation_error';
			}
			else
			{
        //验证license
        $license_quota = $this->license->get_license_quota('mssql_watch');
        $sql="select * from db_cfg_sqlserver where is_delete=0";
        $query = $this->db->query($sql);
        $mssql_count = $query->num_rows();
      
        if(empty($license_quota)){
            redirect(site_url('error/no_license'));
                return ;
        }else if($mssql_count >= $license_quota){
            redirect(site_url('error/out_quota'));
                return ;
        }
        
					$data['error_code']=0;
					$data = array(
						'host'=>$this->input->post('host'),
						'port'=>$this->input->post('port'),
            'username'=>$this->input->post('username'),
						'password'=>$this->input->post('password'),
					  'tags'=>$this->input->post('tags'),
            'monitor'=>$this->input->post('monitor'),
            'send_mail'=>$this->input->post('send_mail'),
						'send_sms'=>$this->input->post('send_sms'),
            'send_mail_to_list'=>$this->input->post('send_mail_to_list'),
						'send_sms_to_list'=>$this->input->post('send_sms_to_list'),
						'alarm_processes'=>$this->input->post('alarm_processes'),
						'alarm_processes_running'=>$this->input->post('alarm_processes_running'),
						'alarm_processes_waits'=>$this->input->post('alarm_processes_waits'),
						'threshold_warning_processes'=>$this->input->post('threshold_warning_processes'),
						'threshold_warning_processes_running'=>$this->input->post('threshold_warning_processes_running'),
						'threshold_warning_processes_waits'=>$this->input->post('threshold_warning_processes_waits'),
						'threshold_critical_processes'=>$this->input->post('threshold_critical_processes'),
						'threshold_critical_processes_running'=>$this->input->post('threshold_critical_processes_running'),
						'threshold_critical_processes_waits'=>$this->input->post('threshold_critical_processes_waits'),
					);
					$this->sqlserver->insert($data);
                    redirect(site_url('cfg_sqlserver/index'));
            }
        }
    
        $this->layout->view("cfg_sqlserver/add",$data);
    }
    
    /**
     * 编辑
     */
    public function edit($id){
        parent::check_privilege();
        $id  = !empty($id) ? $id : $_POST['id'];
        /*
		 * 提交编辑后处理
		 */
        $data['error_code']=0;
		if(isset($_POST['submit']) && $_POST['submit']=='edit')
        {
            $this->form_validation->set_rules('host',  'lang:host', 'trim|required');
            $this->form_validation->set_rules('port',  'lang:port', 'trim|required|min_length[4]|max_length[6]|integer');
            $this->form_validation->set_rules('username',  'lang:username', 'trim|required');
            $this->form_validation->set_rules('password',  'lang:password', 'trim|required');
            $this->form_validation->set_rules('tags',  'lang:tags', 'trim|required');
            $this->form_validation->set_rules('threshold_warning_processes',  'lang:alarm_threshold', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_critical_processes',  'lang:alarm_threshold', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_warning_processes_running',  'lang:alarm_threshold', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_critical_processes_running',  'lang:alarm_threshold', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_warning_processes_waits',  'lang:alarm_threshold', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_critical_processes_waits',  'lang:alarm_threshold', 'trim|required|integer');
            if ($this->form_validation->run() == FALSE)
			{
				$data['error_code']='validation_error';
			}
			else
			{
					$data['error_code']=0;
                $data = array(
                    'host'=>$this->input->post('host'),
                    'port'=>$this->input->post('port'),
                    'username'=>$this->input->post('username'),
                    'password'=>$this->input->post('password'),
                    'tags'=>$this->input->post('tags'),
                    'monitor'=>$this->input->post('monitor'),
                    'send_mail'=>$this->input->post('send_mail'),
                    'send_sms'=>$this->input->post('send_sms'),
                    'send_mail_to_list'=>$this->input->post('send_mail_to_list'),
                    'send_sms_to_list'=>$this->input->post('send_sms_to_list'),
                    'alarm_processes'=>$this->input->post('alarm_processes'),
                    'alarm_processes_running'=>$this->input->post('alarm_processes_running'),
                    'alarm_processes_waits'=>$this->input->post('alarm_processes_waits'),
                    'threshold_warning_processes'=>$this->input->post('threshold_warning_processes'),
                    'threshold_warning_processes_running'=>$this->input->post('threshold_warning_processes_running'),
                    'threshold_warning_processes_waits'=>$this->input->post('threshold_warning_processes_waits'),
                    'threshold_critical_processes'=>$this->input->post('threshold_critical_processes'),
                    'threshold_critical_processes_running'=>$this->input->post('threshold_critical_processes_running'),
                    'threshold_critical_processes_waits'=>$this->input->post('threshold_critical_processes_waits'),
                );
					$this->sqlserver->update($data,$id);
					if($this->input->post('monitor')!=1){
						$this->sqlserver->db_status_remove($id);	
					}
                    redirect(site_url('cfg_sqlserver/index'));
            }
        }
        
		$record = $this->sqlserver->get_record_by_id($id);
		if(!$id || !$record){
			show_404();
		}
        else{
            $data['record']= $record;
        }
          
        $data["cur_nav"]="cfg_edit";
        $this->layout->view("cfg_sqlserver/edit",$data);
    }
    
    /**
     * 删除
     */
    function delete($id){
        parent::check_privilege();
        if($id){
            $this->sqlserver->delete($id);
            redirect(site_url('cfg_sqlserver/index'));
        }
    }
    
    /**
     * 恢复
     */
    function recover($id){
        parent::check_privilege('cfg_sqlserver/trash');
        
        if($id){
            $data = array(
				'is_delete'=>0
            );
		    $this->sqlserver->update($data,$id);
            redirect(site_url('cfg_sqlserver/trash'));
        }
    }  
    
    /**
     * 彻底删除
     */
    function forever_delete($id){
        parent::check_privilege('cfg_sqlserver/trash');
        if($id){
            //检查该数据是否是回收站数据
            $record = $this->sqlserver->get_record_by_id($id);
            $is_delete = $record['is_delete'];
            if($is_delete==1){
                $this->sqlserver->delete($id);
            }
            redirect(site_url('cfg_sqlserver/trash'));
        }
        
    }
    
     /**
     * 添加 Mirror
     */
    public function add_mirror(){
        #parent::check_privilege();
        $sql="select * from db_cfg_sqlserver where is_delete=0 order by id asc";
        $result=$this->sqlserver->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        
        $sql="select t.id,
                    t.mirror_name,
                    t.db_name,
                    p.id   as pri_id,
                    p.host as pri_host,
                    p.port as pri_port,
                    p.tags as pri_tags,
                    s.id   as sta_id,
                    s.host as sta_host,
                    s.port as sta_port,
                    s.tags as sta_tags
            from db_cfg_sqlserver_mirror t, db_cfg_sqlserver p, db_cfg_sqlserver s
            where t.primary_db_id = p.id
                and t.standby_db_id = s.id
                and p.is_delete = 0
                and s.is_delete = 0
            order by t.display_order asc";
        $result=$this->sqlserver->get_total_record_sql($sql);
        $data["mirror_list"]=$result['datalist'];
        $data["mirror_count"]=$result['datacount'];
        
        $data["mirror_quota"]= $this->license->get_license_quota('mssql_recover');

		
    	/*
		 	* 提交添加后处理
		 	*/
			$data['error_code']=0;
			if(isset($_POST['submit']) && $_POST['submit']=='add_mirror')
    	{
					$this->form_validation->set_rules('mirror_name',  'lang:mirror_name', 'trim|required');
					$this->form_validation->set_rules('primary_db',  'lang:primary_db', 'trim|required');
					$this->form_validation->set_rules('standby_db',  'lang:standby_db', 'trim|required');
					$this->form_validation->set_rules('db_name',  'lang:db_name', 'trim|required');
           
					if ($this->form_validation->run() == FALSE)
					{
							$data['error_code']='validation_error';
					}
					else
					{
		        //验证license
		        $license_quota = $this->license->get_license_quota('mssql_recover');
		        $sql="select * from db_cfg_sqlserver_mirror where is_delete=0";
		        $query = $this->db->query($sql);
		        $mirror_count = $query->num_rows();
		      
		      	$mirror_name = $this->input->post('mirror_name');
		      	$primary_db = $this->input->post('primary_db');
		      	$standby_db = $this->input->post('standby_db');
		      	$db_name = $this->input->post('db_name');
		      	
		      	$name_exists = $this->sqlserver->mirror_name_exists($mirror_name);
		      	$group_exists = $this->sqlserver->mirror_group_exists($primary_db, $standby_db, $db_name);
		      	
		        if(empty($license_quota)){
								$data['error_code']=-1;
								$data['error_message']="没有License，请检查授权文件!";
		            #redirect(site_url('error/no_license'));
		            #return ;
		        }elseif($mirror_count >= $license_quota){
								$data['error_code']=-1;
								$data['error_message']="您已经超出了镜像组授权限制，请删除后再添加!";
		            #redirect(site_url('error/out_quota'));
		            #return ;
		        }elseif($name_exists == 1){
								$data['error_code']=-1;
								$data['error_message']= "镜像名已存在!";
		        }elseif($group_exists == 1){
								$data['error_code']=-1;
								$data['error_message']= "镜像组已存在!";
		        }else{
								$data['error_code']=0;
								$data_dr = array(
								'mirror_name'=>$this->input->post('mirror_name'),
								'primary_db_id'=>$this->input->post('primary_db'),
								'standby_db_id'=>$this->input->post('standby_db'),
								'db_name'=>$this->input->post('db_name'),
								);
								
								$this->sqlserver->insert_mirror($data_dr);
						}
					
					
						$this->layout->setLayout("layout_blank");
        		$this->layout->view("cfg_sqlserver/json_data",$data);
          }
    	}else{
    			$this->layout->view("cfg_sqlserver/add_mirror",$data);
  		}
    }

    
    
    public function edit_mirror($id){
        //parent::check_privilege();
        $id  = !empty($id) ? $id : $_POST['id'];
        
        
        /*
				 * 提交编辑后处理
				*/
        $data['error_code']=0;
				if(isset($_POST['submit']) && $_POST['submit']=='edit_mirror')
        {
					$this->form_validation->set_rules('mirror_name',  'lang:mirror_name', 'trim|required');
					$this->form_validation->set_rules('primary_db',  'lang:primary_db', 'trim|required');
					$this->form_validation->set_rules('standby_db',  'lang:standby_db', 'trim|required');
					$this->form_validation->set_rules('db_name',  'lang:db_name', 'trim|required');
					
					if ($this->form_validation->run() == FALSE)
					{
						$data['error_code']='validation_error';
					}
					else
					{
		      	$id = $this->input->post('group_id');
		      	$mirror_name = $this->input->post('mirror_name');
		      	$primary_db = $this->input->post('primary_db');
		      	$standby_db = $this->input->post('standby_db');
		      	$db_name = $this->input->post('db_name');
		      	
		      	$name_exists = $this->sqlserver->mirror_name_exists($mirror_name, $id);
		      	$group_exists = $this->sqlserver->mirror_group_exists($primary_db, $standby_db, $db_name, $id);
		      	
		      	if($name_exists == 1){
								$data['error_code']=-1;
								$data['error_message']= "镜像名已存在!";
		        }elseif($group_exists == 1){
								$data['error_code']=-1;
								$data['error_message']= "镜像组已存在!";
		        }else{
								$data['error_code']=0;
								$data_dr = array(
								'mirror_name'=>$this->input->post('mirror_name'),
								'primary_db_id'=>$this->input->post('primary_db'),
								'standby_db_id'=>$this->input->post('standby_db'),
								'db_name'=>$this->input->post('db_name'),
								);
								
								$this->sqlserver->update_mirror($data_dr,$id);
						}
					
					
						$this->layout->setLayout("layout_blank");
        		$this->layout->view("cfg_sqlserver/json_data",$data);
        		
		      
	        }
      	}else{
	      	$sql="select * from db_cfg_sqlserver where is_delete=0 order by id asc";
	        $result=$this->sqlserver->get_total_record_sql($sql);
	        $data["datalist"]=$result['datalist'];
	        $data["datacount"]=$result['datacount'];
	        
	        $sql="select t.id,
	                    t.mirror_name,
	                    t.db_name,
	                    p.id   as pri_id,
	                    p.host as pri_host,
	                    p.port as pri_port,
	                    p.tags as pri_tags,
	                    s.id   as sta_id,
	                    s.host as sta_host,
	                    s.port as sta_port,
	                    s.tags as sta_tags
	            from db_cfg_sqlserver_mirror t, db_cfg_sqlserver p, db_cfg_sqlserver s
	            where t.primary_db_id = p.id
	                and t.standby_db_id = s.id
	                and p.is_delete = 0
	                and s.is_delete = 0
	            order by t.display_order asc";
	        $result=$this->sqlserver->get_total_record_sql($sql);
	        $data["mirror_list"]=$result['datalist'];
	        $data["mirror_count"]=$result['datacount'];
	        
	        
	        $data["group_id"]=$id;
	        $sql="select * from db_cfg_sqlserver_mirror  where is_delete=0 and id = $id ";
	        $result=$this->sqlserver->get_total_record_sql($sql);
	        $data["mirror"]=$result['datalist'];
	        
	        
	       
	        $this->layout->view("cfg_sqlserver/add_mirror",$data);
	      }
    }
    
    
    /**
    * 删除 镜像 链路
    */
    function delete_mirror($id){
        #parent::check_privilege();
        if($id){
		    		$this->sqlserver->delete_mirror($id);
            redirect(site_url('cfg_sqlserver/add_mirror'));
        }
    }
        
    /**
     * 连接测试
     */
    function check_connection(){
        $ip = $_POST["ip"];
        $port = $_POST["port"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $setval["connect"] = -1;
 				
				try{
 					$serverName = "dblib:host=". $ip . ":" . $port;
 					#errorLog($serverName);
					$conn = new PDO($serverName,$username,$password);
					
  				if (!$conn) {
    				errorLog('Error: Unable to connect to SQLServer.');
        		$setval["connect"] = 1;
					}else{
						#errorLog('Succ'); 
        		$setval["connect"] = 0;
        		$conn=null;			#关闭连接
					}
					
        	$data["setval"]=$setval;
        	
					$this->layout->setLayout("layout_blank");
        	$this->layout->view("cfg_sqlserver/json_data",$data);
				}
				catch(PDOException $e){
 					errorLog($e->getMessage());
				}
    }
    
    
}

/* End of file cfg_sqlserver.php */
/* Location: ./application/controllers/cfg_sqlserver.php */