<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Wl_os extends Front_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model("os_model","os");
        $this->load->model('cfg_mysql_model','server');
        
	}
    
    public function index2(){
        $data['os_cpu_load_top5'] = $this->db->query("select ip,load_1,load_5,load_15,process value from os_resource order by load_1 desc limit 5;")->result_array();
        $data['os_cpu_usage_top10'] = $this->db->query("select ip,(cpu_user_time+cpu_system_time) value from os_resource order by value desc limit 10;")->result_array();
        $data['os_disk_usage_top10'] = $this->db->query("select ip,mounted,total_size,used_size,avail_size,used_rate from os_diskinfo group by CONCAT(ip,mounted) order by cast(SUBSTRING_INDEX(used_rate,'%',1) as unsigned) desc limit 10;")->result_array();
        $data['os_disk_io_top10'] = $this->db->query("select io.ip,io.fdisk,io.disk_io_reads,io.disk_io_writes,io.create_time,application.display_name application 
from os_diskio io  join db_application application on io.application_id=application.id
group by CONCAT(ip,left(fdisk,3)) order by (disk_io_reads+disk_io_writes)  desc limit 10;")->result_array();
        //print_r($data['mysql_threads_connected_ranking']);
        $this->layout->view("os/index",$data);
    }
    
    
    public function index()
	{
        parent::check_privilege();
        $result=$this->os->get_status_total_record();
        $data['datalist']=$result['datalist'];
        $data['datacount']=$result['datacount'];

        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        
        $data["setval"]=$setval;
        $this->layout->view("os/index",$data);
	}
    
    public function cpu()
	{
        parent::check_privilege();
        $result=$this->os->get_total_resource_record_snmp_on();
        $data['datalist']=$result['datalist'];
        $data['datacount']=$result['datacount'];

        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        $data["setval"]=$setval;
        $this->layout->view("os/cpu",$data);
	}
    
    public function memory()
	{
        parent::check_privilege();
        $result=$this->os->get_total_resource_record_snmp_on();
        $data['datalist']=$result['datalist'];
        $data['datacount']=$result['datacount'];
     
        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        $data["setval"]=$setval;
        $this->layout->view("os/memory",$data);
	}
    
    public function disk()
	{
        parent::check_privilege();
        $result=$this->os->get_total_host();
        if(!empty($result)){
            foreach($result as $key=>$item)
            {
                $host = $item['ip'];
                $result[$key]['diskinfo'] = $this->os->get_diskinfo_record($host);
            }
        }
        
        //print_r($result);
        $data['datalist']=$result;
    
        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        $data["setval"]=$setval;
        $this->layout->view("os/disk",$data);
	}
    
    public function disk_chart(){
        
        parent::check_privilege();
        $host = $this->uri->segment(3);
        $host=!empty($host) ? $host : "";
        $data['diskinfo'] = $this->os->get_disk_record($host);
        $data['cur_host']=$host;
        $this->layout->view('os/disk_chart',$data);
    }
    
    public function disk_io()
	{
        parent::check_privilege();
        $result=$this->os->get_total_diskio_record();
        $data['datalist']=$result['datalist'];
        $data['datacount']=$result['datacount'];
  
        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
        $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
        $data["setval"]=$setval;
        $this->layout->view("os/disk_io",$data);
	}
    
    public function disk_io_chart(){
        
        parent::check_privilege();
        $host = $this->uri->segment(3);
        $host=!empty($host) ? $host : "";
        $fdisk = $this->uri->segment(4);
        $begin_time = $this->uri->segment(5);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
    

        $data['fdisk']=$fdisk;
      
        $data['begin_time']=$begin_time;
        $data['cur_host']=$host;
        $this->layout->view('os/disk_io_chart',$data);
    }
    
    

    public function disk_io_data(){
        
        $host = $this->uri->segment(3);
        $host=!empty($host) ? $host : "";
        $fdisk = $this->uri->segment(4);
        
        $begin_time = $this->uri->segment(5);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        
        if($host!=""){
        		$data['disk_io_data']=$this->os->get_diskio_data($host, $fdisk, $begin_time);
        }
				
        $data['begin_time']=$begin_time;
        $data['fdisk']=$fdisk;
        $data['cur_host']=$host;
				$this->layout->setLayout("layout_blank");
        $this->layout->view("os/disk_io_data",$data);
    }
    
    public function chart(){
        
        parent::check_privilege();
        $host = $this->uri->segment(3);
        $host=!empty($host) ? $host : "";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "30";
        
      
        $data['begin_time']=$begin_time;
        $data['cur_host']=$host;
        $this->layout->view('os/chart',$data);
    }
    
    
    public function chart_data(){
        
        $host = $this->uri->segment(3);
        $host=!empty($host) ? $host : "";
        
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        
        if($host!=""){
        		$data['chart_data']=$this->os->get_chart_data($host, $begin_time);
        }
				
        $data['begin_time']=$begin_time;
        $data['cur_host']=$host;
				$this->layout->setLayout("layout_blank");
        $this->layout->view("os/chart_data",$data);
    }
    
}

/* End of file os.php */
/* Location: ./application/controllers/os.php */
