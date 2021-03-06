<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

     
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_Servers Configure'); ?></li><span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_OS'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('cfg_os/edit') ?>" >
<input type="hidden" name="submit" value="edit"/> 
<input type='hidden'  name='id' value=<?php echo $record['id'] ?> />
<input type='hidden'  name='host_old' value=<?php echo $record['host'] ?> />
<div class="btn-toolbar">
    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> <?php echo $this->lang->line('save'); ?></button>
    <a class="btn btn " href="<?php echo site_url('cfg_os/index') ?>"><i class="icon-list"></i> <?php echo $this->lang->line('list'); ?></a>
  <div class="btn-group"></div>
</div>

<?php if ($error_code!==0) { ?>
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>
<?php echo validation_errors(); ?>
</div>
<?php } ?>

<div class="well">
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('host'); ?></label>
    <div class="controls">
      <input type="text" id=""  name="host" value="<?php echo $record['host']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   
   <!--
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('protocol'); ?> </label>
    <div class="controls">
        <select name="protocol" id="protocol" onchange="protocol_change(this)">
         <option value="snmp" <?php echo set_selected("snmp",$record['protocol']) ?>><?php echo $this->lang->line('snmp'); ?></option>
         <option value="winrm" <?php echo set_selected("winrm",$record['protocol']) ?>><?php echo $this->lang->line('winrm'); ?></option>
        </select>
      <span class="help-inline"></span>
    </div>
   </div>-->
   
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('host_type'); ?></label>
    <div class="controls">
        <select name="host_type" id="host_type" class="input-medium" onchange="host_type_change(this)" >
         <option value="0" <?php echo set_selected(0,$record['host_type']) ?>>Linux</option>
         <option value="1" <?php echo set_selected(1,$record['host_type']) ?>>AIX</option>
         <option value="2" <?php echo set_selected(2,$record['host_type']) ?>>HP-UX</option>
         <option value="3" <?php echo set_selected(3,$record['host_type']) ?>>Solaris</option>
         <option value="4" <?php echo set_selected(4,$record['host_type']) ?>>Windows</option>
        </select>
    </div>
    </div>
    
    <div id="div_port" class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('port'); ?></label>
    <div class="controls">
      <input type="text" id="port"  name="port" value="<?php echo $record['port']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   
   <div id="div_user" class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('username'); ?></label>
    <div class="controls">
      <input type="text" id="username"  name="username" value="<?php echo $record['username']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   
   <div id="div_passwd" class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('password'); ?></label>
    <div class="controls">
      <input type="password" id="password"  name="password" value="<?php echo $record['password']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('tags'); ?></label>
    <div class="controls">
      <input type="text" id=""  name="tags" value="<?php echo $record['tags']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>

    <hr />
   
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('monitor'); ?></label>
    <div class="controls">
        <select name="monitor" id="monitor" class="input-small">
         <option value="1"  <?php echo set_selected(1,$record['monitor']) ?>><?php echo $this->lang->line('on'); ?></option>
         <option value="0"  <?php echo set_selected(0,$record['monitor']) ?>><?php echo $this->lang->line('off'); ?></option>
        </select>
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('send_mail'); ?></label>
    <div class="controls">
        <select name="send_mail" id="send_mail" class="input-small">
         <option value="1"  <?php echo set_selected(1,$record['send_mail']) ?>><?php echo $this->lang->line('on'); ?></option>
         <option value="0"  <?php echo set_selected(0,$record['send_mail']) ?>><?php echo $this->lang->line('off'); ?></option>
        </select>
         &nbsp;&nbsp;<?php echo $this->lang->line('alarm_mail_to_list'); ?>
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input type="text" id="send_mail_to_list"  class="input-xlarge" placeholder="<?php echo $this->lang->line('many_people_separation'); ?>" name="send_mail_to_list" value="<?php echo $record['send_mail_to_list']; ?>" >
        </div>
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('send_sms'); ?></label>
    <div class="controls">
        <select name="send_sms" id="send_sms" class="input-small">
         <option value="1"  <?php echo set_selected(1,$record['send_sms']) ?>><?php echo $this->lang->line('on'); ?></option>
         <option value="0"  <?php echo set_selected(0,$record['send_sms']) ?>><?php echo $this->lang->line('off'); ?></option>
        </select>
         &nbsp;&nbsp;<?php echo $this->lang->line('alarm_sms_to_list'); ?>
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input type="text" id="send_sms_to_list"  class="input-xlarge" placeholder="<?php echo $this->lang->line('many_people_separation'); ?>" name="send_sms_to_list" value="<?php echo $record['send_sms_to_list']; ?>" >
        </div>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('process'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_process" id="alarm_os_process" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_process']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_process']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_process" class="input-small" placeholder="" name="threshold_warning_os_process" value="<?php echo $record['threshold_warning_os_process']; ?>" >
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_process" class="input-small" placeholder="" name="threshold_critical_os_process" value="<?php echo $record['threshold_critical_os_process']; ?>" >
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('load'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_load" id="alarm_os_load" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_load']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_load']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_load" class="input-small" placeholder="" name="threshold_warning_os_load" value="<?php echo $record['threshold_warning_os_load']; ?>" >
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_load" class="input-small" placeholder="" name="threshold_critical_os_load" value="<?php echo $record['threshold_critical_os_load']; ?>" >
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('network'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_network" id="alarm_os_network" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_network']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_network']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_network" class="input-small" placeholder="" name="threshold_warning_os_network" value="<?php echo $record['threshold_warning_os_network']; ?>" >
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_network" class="input-small" placeholder="" name="threshold_critical_os_network" value="<?php echo $record['threshold_critical_os_network']; ?>" >
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('cpu'); ?> <?php echo $this->lang->line('usage_rate'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_cpu" id="alarm_os_cpu" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_cpu']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_cpu']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_cpu" class="input-small" placeholder="" name="threshold_warning_os_cpu" value="<?php echo $record['threshold_warning_os_cpu']; ?>" >%
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_cpu" class="input-small" placeholder="" name="threshold_critical_os_cpu" value="<?php echo $record['threshold_critical_os_cpu']; ?>" >%
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('disk'); ?> <?php echo $this->lang->line('usage_rate'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_disk" id="alarm_os_disk" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_disk']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_disk']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_disk" class="input-small" placeholder="" name="threshold_warning_os_disk" value="<?php echo $record['threshold_warning_os_disk']; ?>" >%
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_disk" class="input-small" placeholder="" name="threshold_critical_os_disk" value="<?php echo $record['threshold_critical_os_disk']; ?>" >% &nbsp;&nbsp;<?php echo $this->lang->line('filter'); ?><?php echo $this->lang->line('disk'); ?>&nbsp;<input type="text" id="filter_os_disk" class="input-large" placeholder="" name="filter_os_disk" value="<?php echo $record['filter_os_disk']; ?>" >
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('memory'); ?> <?php echo $this->lang->line('usage_rate'); ?> <?php echo $this->lang->line('alarm'); ?></label>
    <div class="controls">
        <select name="alarm_os_memory" id="alarm_os_memory" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['alarm_os_memory']) ?>   ><?php echo $this->lang->line('on'); ?></option>
         <option value="0" <?php echo set_selected(0,$record['alarm_os_memory']) ?>   ><?php echo $this->lang->line('off'); ?></option>
        </select>
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_warning'); ?>&nbsp;<input type="text" id="threshold_warning_os_memory" class="input-small" placeholder="" name="threshold_warning_os_memory" value="<?php echo $record['threshold_warning_os_memory']; ?>" >%
        &nbsp;&nbsp;<?php echo $this->lang->line('threshold_critical'); ?>&nbsp;<input type="text" id="threshold_critical_os_memory" class="input-small" placeholder="" name="threshold_critical_os_memory" value="<?php echo $record['threshold_critical_os_memory']; ?>" >%
    </div>
   </div>
  
    
   
</div>

</form>

<script type="text/javascript">
$(document).ready(function(){
		if($("#host_type").val() == "4"){
				$("#div_user").show();
				$("#div_passwd").show();
				$("#div_port").show();
		}else{
				$("#div_user").hide();
				$("#div_passwd").hide();
				$("#div_port").hide();
		}
});


function host_type_change(e){
		if(e.value == "4"){
				$("#div_user").show();
				$("#div_passwd").show();
				$("#div_port").show();
				$("#port").val("5985");
		}
		else{
				$("#div_user").hide();
				$("#div_passwd").hide();
				$("#div_port").hide();
				$("#username").val("");
				$("#password").val("");
				$("#port").val("");
		}
}
</script>

