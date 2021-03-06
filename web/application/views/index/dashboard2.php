<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

        
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="renderer" content="webkit" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
  
  <base href="<?php echo base_url().'application/views/static/'; ?>" />
	<script src="lib/bootstrap/js/rem.js"></script>
	<link href="lib/bootstrap/css/dashboard.css" rel="stylesheet"/>
		
  <script type="text/javascript" src="lib/bootstrap/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="lib/bootstrap/js/echarts.min.js"></script>
  <script type="text/javascript" src="lib/bootstrap/js/chalk.js"></script>
  <!---<script type="text/javascript" src="lib/bootstrap/js/charts_demo.js"></script>--->
		
  <title></title>
</head>
      <!-- 上下翻页用样式 -->
<style>
      .box .datanum .scroll {
      height: 100%;
      overflow: hidden;
      }
      
      .box .datanum ul {
      height: 100% !important;
}
.box .datanum ul > li {
float: left;
width: 10%;
height: 100%;
padding: 0 1%;
display: flex;
align-items: center;
justify-content: center;
}

.box .datanum ul > li p {
text-align: center;
padding-bottom: 0.1rem;
font-size: 0.2rem;
}

.box .datanum ul > li span {
display: block;
}

.box .datanum ul > li span img {
max-width: 6vw;
max-height: 8vh;
}


.scroll{height: 100%;overflow: hidden;}
.box .datanum ul{height:100% !important;}
      </style>
			<!-- 上下翻页 一个ul里循环10个li,超过10个新增一个ul.一个ul翻一页-->

<script type="text/javascript">
$(function () {
setInterval(function () {
$(".scroll ul").eq(0).slideUp(400, function () {
$(this).appendTo($(this).parent()).show();
})
}, 2000)
})
</script>

<body style="visibility: visible;">
  <div class="container-flex">
    <div class="box">
      <div class="pagetit">
      	
        <div class="time1" >当前时间：&nbsp;<span id="Timer"></span></div>
        <div class="time2">最新检测时间：&nbsp;<span><?php if(!empty($wlblazers_status)){ echo $wlblazers_status['wlblazers_checktime'];} else {echo $this->lang->line('the_monitoring_process_is_not_started');} ?></span></div>
        <h1><a href="<?php echo site_url('index/index'); ?>">DRM监控平台</a></h1>
      </div>
      <div class="datanum">
        <!-- <div class="dtit">数据库的连接状态</div> -->
        <img src="lib/bootstrap/img/bj-1.png" alt="" class="bj-1">
        <img src="lib/bootstrap/img/bj-2.png" alt="" class="bj-2">
        <img src="lib/bootstrap/img/bj-3.png" alt="" class="bj-3">
        <img src="lib/bootstrap/img/bj-4.png" alt="" class="bj-4">

      	<div class="scroll">
      	<?php if(!empty($db_instance_total)) {?>
      	<?php $li_count = 0; ?>
      	<?php foreach ($db_instance_total as $item): ?>
        <?php if($li_count%10 ==0){echo '<ul class="cf">';} ?> 
        <li>
          <span>
            <p><?php echo $item['tags'] ?></p>
            <img src="<?php if($item['connect']==1){echo "lib/bootstrap/img/db1.png";}else{echo "lib/bootstrap/img/db2.png";} ?>" alt="">
          </span>
        </li>
        <?php $li_count = $li_count + 1; ?> 
        <?php if($li_count == sizeof($db_instance_total)){echo '</ul>';}elseif($li_count%10 ==0){echo '</ul>';} ?> 
        <?php endforeach;?>
        <?php } ?>  
        
      </div>
      </div>
      <div class="left1">

        <img src="lib/bootstrap/img/bj-1.png" alt="" class="bj-1">
        <img src="lib/bootstrap/img/bj-2.png" alt="" class="bj-2">
        <img src="lib/bootstrap/img/bj-3.png" alt="" class="bj-3">
        <img src="lib/bootstrap/img/bj-4.png" alt="" class="bj-4">
        <div class="datarow cf" <?php if($center_db_count < 1){echo "style='display:none'";} ?>>
          <div class="d1">
            <h1><?php echo $db_tag_1[tags] ?></h1>
          </div>
          <div class="d2">
            <h2>性能指数</h2>
            <div id="left2" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d3">
            <h2>Total Sessions 和 Active Sessions</h2>
            <div id="left3" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d4">
            <h2>空间使用率</h2>
            <ul>
            	<?php if(!empty($space_1)) {?>
		        	<?php foreach ($space_1 as $item): ?>
		          <li>
                <div class="progress">
                  <div class="progress-value"><?php echo $item['name'] ?>:<span class="pdata"><?php echo $item['max_rate'] ?>%</span></div>
                  <div class="progress-bar">
                    <div class="progress-data"></div>
                  </div>
                </div>
              </li>
		          <?php endforeach;?>
		          <?php } ?>  
          
            </ul>
          </div>
          <div class="d5">
            <h2>每小时日志量</h2>
            <div id="left5" style="width: 100%;height:100%;"></div>
          </div>
        </div>
        
        <!--- 第二个 ---> 
        <div class="datarow cf" <?php if($center_db_count < 2){echo "style='display:none'";} ?>>
          <div class="d1">
            <h1><?php echo $db_tag_2[tags] ?></h1>
          </div>
          <div class="d2">
            <div id="left22" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d3">
            <div id="left23" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d4">
            <ul>
            	<?php if(!empty($space_2)) {?>
		        	<?php foreach ($space_2 as $item): ?>
		          <li>
                <div class="progress">
                  <div class="progress-value"><?php echo $item['name'] ?>:<span class="pdata"><?php echo $item['max_rate'] ?>%</span></div>
                  <div class="progress-bar">
                    <div class="progress-data"></div>
                  </div>
                </div>
              </li>
		          <?php endforeach;?>
		          <?php } ?>  
            </ul>
          </div>
          <div class="d5">
            <div id="left25" style="width: 100%;height:100%;"></div>
          </div>
        </div>
        
        <!--- 第三个 ---> 
        <div class="datarow cf" <?php if($center_db_count < 3){echo "style='display:none'";} ?> >
          <div class="d1">
            <h1><?php echo $db_tag_3[tags] ?></h1>
          </div>
          <div class="d2">
            <div id="left32" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d3">
            <div id="left33" style="width: 100%;height:100%;"></div>
          </div>
          <div class="d4">
            <ul>
            	<?php if(!empty($space_3)) {?>
		        	<?php foreach ($space_3 as $item): ?>
		          <li>
                <div class="progress">
                  <div class="progress-value"><?php echo $item['name'] ?>:<span class="pdata"><?php echo $item['max_rate'] ?>%</span></div>
                  <div class="progress-bar">
                    <div class="progress-data"></div>
                  </div>
                </div>
              </li>
		          <?php endforeach;?>
		          <?php } ?>  
            </ul>
          </div>
          <div class="d5">
            <div id="left35" style="width: 100%;height:100%;"></div>
          </div>
        </div>
        
      </div>
      <div class="right1">
        <div class="dtit">核心库指标</div>
        <img src="lib/bootstrap/img/bj-1.png" alt="" class="bj-1">
        <img src="lib/bootstrap/img/bj-2.png" alt="" class="bj-2">
        <img src="lib/bootstrap/img/bj-3.png" alt="" class="bj-3">
        <img src="lib/bootstrap/img/bj-4.png" alt="" class="bj-4">
        <div class="right11" id="right11"></div>
        <div class="right12" id="right12"></div>
      </div>
      <div class="right2">
        <div class="dtit">容灾状态</div>
        <img src="lib/bootstrap/img/bj-1.png" alt="" class="bj-1">
        <img src="lib/bootstrap/img/bj-2.png" alt="" class="bj-2">
        <img src="lib/bootstrap/img/bj-3.png" alt="" class="bj-3">
        <img src="lib/bootstrap/img/bj-4.png" alt="" class="bj-4">
        <ul>
          <li>
            <div class="progress">
              <div class="progress-name">Oracle</div>
              <div class="progress-bar">
                <hr>
                <hr>
                <hr>
                <hr>
                <!-- color1,color2,color3分别对应正常,告警,异常的颜色 -->
                <div class="progress-data <?php echo check_repl_color($oracle_normal, $oracle_waring, $oracle_critical) ?>" style="height:<?php echo check_repl_rate($oracle_normal, $oracle_waring, $oracle_critical) ?>%"></div>
              </div>
            </div>
            <div class="proright">
              <ul class="cf">
                <li class="co1">
                  <div>
                    <p><?php echo $oracle_normal ?></p>
                    <p>正常</p>
                  </div>
                </li>
                <li class="co2">
                  <div>
                    <p><?php echo $oracle_waring ?></p>
                    <p>告警</p>
                  </div>
                </li>

                <li class="co3">
                  <div>
                    <p><?php echo $oracle_critical ?></p>
                    <p>异常</p>
                  </div>
                </li>
              </ul>

            </div>
          </li>
          <li>
              <div class="progress">
                <div class="progress-name">MySQL</div>
                <div class="progress-bar">
                  <hr>
                  <hr>
                  <hr>
                  <hr>
                  <!-- color1,color2,color3分别对应正常,告警,异常的颜色 -->
                  <div class="progress-data <?php echo check_repl_color($mysql_normal, $mysql_waring, $mysql_critical) ?>" style="height:<?php echo check_repl_rate($mysql_normal, $mysql_waring, $mysql_critical) ?>%"></div>
                </div>
              </div>
              <div class="proright">
                <ul class="cf">
                  <li class="co1">
                    <div>
                      <p><?php echo $mysql_normal ?></p>
                      <p>正常</p>
                    </div>
                  </li>
                  <li class="co2">
                    <div>
                      <p><?php echo $mysql_waring ?></p>
                      <p>告警</p>
                    </div>
                  </li>
  
                  <li class="co3">
                    <div>
                      <p><?php echo $mysql_critical ?></p>
                      <p>异常</p>
                    </div>
                  </li>
                </ul>
  
              </div>
            </li>
            <li>
                <div class="progress">
                  <div class="progress-name">SQLServer</div>
                  <div class="progress-bar">
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <!-- color1,color2,color3分别对应正常,告警,异常的颜色 -->
                    <div class="progress-data <?php echo check_repl_color($sqlserver_normal, $sqlserver_waring, $sqlserver_critical) ?>" style="height:<?php echo check_repl_rate($sqlserver_normal, $sqlserver_waring, $sqlserver_critical) ?>%"></div>
                  </div>
                </div>
                <div class="proright">
                  <ul class="cf">
                    <li class="co1">
                      <div>
                        <p><?php echo $sqlserver_normal ?></p>
                        <p>正常</p>
                      </div>
                    </li>
                    <li class="co2">
                      <div>
                        <p><?php echo $sqlserver_waring ?></p>
                        <p>告警</p>
                      </div>
                    </li>
    
                    <li class="co3">
                      <div>
                        <p><?php echo $sqlserver_critical ?></p>
                        <p>异常</p>
                      </div>
                    </li>
                  </ul>
    
                </div>
              </li>
        </ul>
      </div>
      <div class="foot1">
        <div class="dtit">告警信息</div>

        <img src="lib/bootstrap/img/bj-1.png" alt="" class="bj-1">
        <img src="lib/bootstrap/img/bj-2.png" alt="" class="bj-2">
        <img src="lib/bootstrap/img/bj-3.png" alt="" class="bj-3">
        <img src="lib/bootstrap/img/bj-4.png" alt="" class="bj-4">
        <div class="finfo">
          <ul>
            <li>
              <span class="circlespan c1"></span>
                一级：红色
            </li>
            <li>
              <span class="circlespan c2"></span>
                二级：黄色
            </li>
            <li>
              <span class="circlespan c3"></span>
                三级（正常）：绿色
            </li>
          </ul>
        </div>
        <div class="fg-box" id="box">
          <ul>
          	<?php if(!empty($alarm)) {?>
           	<?php foreach ($alarm  as $item):?>
              <li>
                  <table class="table">
                    <tr style="color:red;">
                        <td style="width:140px;<?php if($item['level']=='ok'){echo 'color:#58DB1B;';}elseif($item['level']=='warning'){echo 'color:yellow;';}else{echo 'color:#DB1B44;';} ?>"><?php echo $item['create_time'] ?></td>
                        <td style="width:60px;<?php if($item['level']=='ok'){echo 'color:#58DB1B;';}elseif($item['level']=='warning'){echo 'color:yellow;';}else{echo 'color:#DB1B44;';} ?>"><?php echo $item['db_type'] ?></td>
                        <td style="width:100px;<?php if($item['level']=='ok'){echo 'color:#58DB1B;';}elseif($item['level']=='warning'){echo 'color:yellow;';}else{echo 'color:#DB1B44;';} ?>"><?php echo $item['tags'] ?></td>
                        <td style="<?php if($item['level']=='ok'){echo 'color:#58DB1B;';}elseif($item['level']=='warning'){echo 'color:yellow;';}else{echo 'color:#DB1B44;';} ?>"><?php echo $item['message'] ?></td>
                    </tr>
                  </table>
              </li>
          	<?php endforeach;?>
          	<?php }else{  ?>
              <li>
                  <table class="table">
          					<tr>
                			<td colspan="16">
                			<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
                			</td>
              			</tr>
                  </table>
              </li>
          	<?php } ?>    
          </ul>
        </div>
      </div>

    </div>
  </div>
</body>


<script type="text/javascript">
var left2 = echarts.init(document.getElementById("left2"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' <?php if($db1_type=="oracle"){echo "DB Time/Elapsed Time";}else if($db1_type=="sqlserver"){echo "Buffer Cache hit ratio";} ?>';
                }
            }
        },
        data: [
        		<?php if(!empty($db_time_1)) {?>
						<?php foreach ($db_time_1  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
         axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        }
    },
    series: [
        {
          data: [
        		<?php if(!empty($db_time_1)) {?>
						<?php foreach ($db_time_1  as $item):?>
										"<?php echo $item['rate'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left2.setOption(option);




var left3 = echarts.init(document.getElementById("left3"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color:  ['#5793f3', '#675bba'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 会话';
                }
            }
        },
        data: [
        		<?php if(!empty($db_session_1)) {?>
						<?php foreach ($db_session_1  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        { name: "total",
          data: [
        		<?php if(!empty($db_session_1)) {?>
						<?php foreach ($db_session_1  as $item):?>
										"<?php echo $item['total_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        },
        { name: "active",
          data: [
        		<?php if(!empty($db_session_1)) {?>
						<?php foreach ($db_session_1  as $item):?>
										"<?php echo $item['active_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left3.setOption(option);


var left5 = echarts.init(document.getElementById("left5"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 日志量';
                }
            }
        },
        data: [
        		<?php if(!empty($redo_1)) {?>
						<?php foreach ($redo_1  as $item):?>
										"<?php echo substr($item['key_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        {
          data: [
        		<?php if(!empty($redo_1)) {?>
						<?php foreach ($redo_1  as $item):?>
										"<?php echo $item['redo_log'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left5.setOption(option);


//中间区域第二行
var left22 = echarts.init(document.getElementById("left22"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' <?php if($db2_type=="oracle"){echo "DB Time/Elapsed Time";}else if($db2_type=="sqlserver"){echo "Buffer Cache hit ratio";} ?>';
                }
            }
        },
        data: [
        		<?php if(!empty($db_time_2)) {?>
						<?php foreach ($db_time_2  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        {
          data: [
        		<?php if(!empty($db_time_2)) {?>
						<?php foreach ($db_time_2  as $item):?>
										"<?php echo $item['rate'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left22.setOption(option);




var left23 = echarts.init(document.getElementById("left23"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color:  ['#5793f3', '#675bba'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 会话';
                }
            }
        },
        data: [
        		<?php if(!empty($db_session_2)) {?>
						<?php foreach ($db_session_2  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        { name: "total",
          data: [
        		<?php if(!empty($db_session_2)) {?>
						<?php foreach ($db_session_2  as $item):?>
										"<?php echo $item['total_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        },
        { name: "active",
          data: [
        		<?php if(!empty($db_session_2)) {?>
						<?php foreach ($db_session_2  as $item):?>
										"<?php echo $item['active_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left23.setOption(option);


var left25 = echarts.init(document.getElementById("left25"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 日志量';
                }
            }
        },
        data: [
        		<?php if(!empty($redo_2)) {?>
						<?php foreach ($redo_2  as $item):?>
										"<?php echo substr($item['key_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        {
          data: [
        		<?php if(!empty($redo_2)) {?>
						<?php foreach ($redo_2  as $item):?>
										"<?php echo $item['redo_log'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left25.setOption(option);


//中间区域第三行
var left32 = echarts.init(document.getElementById("left32"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' <?php if($db3_type=="oracle"){echo "DB Time/Elapsed Time";}else if($db3_type=="sqlserver"){echo "Buffer Cache hit ratio";} ?>';
                }
            }
        },
        data: [
        		<?php if(!empty($db_time_3)) {?>
						<?php foreach ($db_time_3  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        {
          data: [
        		<?php if(!empty($db_time_3)) {?>
						<?php foreach ($db_time_3  as $item):?>
										"<?php echo $item['rate'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left32.setOption(option);




var left33 = echarts.init(document.getElementById("left33"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color:  ['#5793f3', '#675bba'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 会话';
                }
            }
        },
        data: [
        		<?php if(!empty($db_session_3)) {?>
						<?php foreach ($db_session_3  as $item):?>
										"<?php echo substr($item['end_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        { name: "total",
          data: [
        		<?php if(!empty($db_session_3)) {?>
						<?php foreach ($db_session_3  as $item):?>
										"<?php echo $item['total_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        },
        { name: "active",
          data: [
        		<?php if(!empty($db_session_3)) {?>
						<?php foreach ($db_session_3  as $item):?>
										"<?php echo $item['active_session'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left33.setOption(option);


var left35 = echarts.init(document.getElementById("left35"), "chalk");
var option = {

    tooltip: {
        trigger: 'axis'
    },
    legend: {
        orient: 'vertical',
        data: ['']
    },
    grid: {
        left: '3%',
        right: '6%',
        top: '30px',
        bottom: '0',
        containLabel: true
    },
    color: ['#a4d8cc', '#25f3e6'],
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: true
            },
            dataView: {
                show: true,
                readOnly: false
            },
            magicType: {
                show: true,
                type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },

    calculable: true,
    xAxis: {
    		splitLine:{show: false},
        type: "category",
        boundaryGap: false,
        axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            onZero: false,
            lineStyle: {
            }
        },
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
        axisPointer: {
            label: {
                formatter: function(params) {
                    return params.value + ' 日志量';
                }
            }
        },
        data: [
        		<?php if(!empty($redo_3)) {?>
						<?php foreach ($redo_3  as $item):?>
										"<?php echo substr($item['key_time'],11,5) ?>",
						<?php endforeach;?>
						<?php } ?>
        ]
    },
    yAxis: {
    		splitLine:{show: false},
        type: "value",
        axisLabel: {
            textStyle: {
                fontSize: '9'
            }

        },
    },
    series: [
        {
          data: [
        		<?php if(!empty($redo_3)) {?>
						<?php foreach ($redo_3  as $item):?>
										"<?php echo $item['redo_log'] ?>",
						<?php endforeach;?>
						<?php } ?>
          ],
          type: "line",
          areaStyle: {}
        }
    ]
};
left35.setOption(option);



var right11 = echarts.init(document.getElementById("right11"), "chalk");
var option = {
    backgroundColor: 'rgba(0,0,0,0)',
    tooltip: {
        trigger: 'item',
        formatter: "{b} <br/>{c} ({d}%)"
    },
    
    color: ['#af89d6', '#4ac7f5', '#0089ff', '#f36f8a', '#f5c847','#ff5800','#839557'],
    //color: ['#ff0000', '#ff9600', '#ffff00', '#00ff00', '#00ff96', '#0000ff', '#ff00ff'],
    //[ "#ff5800", "#EAA228", "#4bb2c5", "#839557", "#958c12", "#953579", "#4b5de4", "#d8b83f", "#ff5800", "#0085cc"]
    legend: {
        orient: 'vertical',
        x: 'left',
        textStyle: {
            color: '#ccc'
        },
        data:[]
    },
    series: [{
        name: '行业占比',
        type: 'pie',
        clockwise: false, //饼图的扇区是否是顺时针排布
        minAngle: 20, //最小的扇区角度（0 ~ 360）
        center: ['55%', '60%'], //饼图的中心（圆心）坐标
        radius: [0, '80%'], //饼图的半径
        avoidLabelOverlap: true, ////是否启用防止标签重叠
        itemStyle: { //图形样式
            normal: {
                borderColor: '#1e2239',
                borderWidth: 2,
            },
        },
        label: { //标签的位置
            normal: {
                show: true,
                position: 'inside', //标签的位置
                formatter: "{d}%",
                textStyle: {
                    color: '#fff',
                }
            },
            emphasis: {
                show: true,
                textStyle: {
                    fontWeight: 'bold'
                }
            }
        },
        data: [
        
        		<?php if(!empty($db_time_per_day)) {?>
						<?php foreach ($db_time_per_day  as $item):?>
        		{
                value: <?php echo $item['db_time'] ?>,
                name: '<?php echo $item['end_time'] ?>: DB Time'
            },
						<?php endforeach;?>
						<?php } ?>
        ],
    }]
};

right11.setOption(option);


var right12 = echarts.init(document.getElementById("right12"), "chalk");
var option = {

    tooltip: {},
    legend: {
        data: ['']
    },
    radar: {
        // shape: 'circle',
        name: {
            textStyle: {
                color: '#ccc',
            }
        },
        center: ["45%", "50%"],
        radius: 60,
        nameGap : 0,
        indicator: [{name: 'CPU',max: 100},
            				{name: '内存',max: 100},
            				{name: 'Swap',max: 100},
            				{name: '磁盘',max: 100}
        					]
    },
    series: [{
        name: '核心主机性能指标（空闲率）',
        type: 'radar',
        areaStyle: {},
        data: [{
            value: [<?php echo $core_os['cpu_idle_time'] ?>, 
            				<?php echo 100-$core_os['mem_usage_rate'] ?>, 
            				<?php echo floor(($core_os['swap_avail']/$core_os['swap_total'])*100) ?>, 
            				<?php echo 100-$core_os_disk['max_used'] ?>]
        }
        ]
    }]
};

right12.setOption(option);


window.addEventListener("resize", function () {
     left2.resize();
     left3.resize();
     left5.resize();
     left22.resize();
     left23.resize();
     left25.resize();
     left32.resize();
     left33.resize();
     left35.resize();
     right11.resize();
     right12.resize();
});  	
  	
</script>
  
<script type="text/javascript">
  $(document).ready(function () {

    // d4进度条
    var getValue = $('.pdata');
    for (var i = 0; i < getValue.length; i++) {
      var get_w = $(getValue[i]).text();
      $(getValue[i]).parent().next().find('.progress-data').css('width', get_w);
    }

    var _box = $('#box');
    var _interval = 1000; //刷新间隔时间3秒
    function gdb() {
      $("<p><span class='circlespan c3'></span>2019年4月25日 XXXX</p>").appendTo('#box');
      $('#box').scrollTop($('#box')[0].scrollHeight);
      /* var _last=$('#box dl dd:last');
      _last.animate({height: '+53px'}, "slow"); */
      setTimeout(function () {
        gdb();
      }, _interval);
    };
    // gdb();
  });



  // 滚动文字
  $(function () {
    setInterval("GetTime()", 1000);
  });
  //获取时间并设置格式
  function GetTime() {
    var mon, day, now, hour, min, ampm, time, str, tz, end, beg, sec;
    /*
    mon = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
            "Sep", "Oct", "Nov", "Dec");
    */
    mon = new Array("一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月",
      "九月", "十月", "十一月", "十二月");
    /*
    day = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    */
    day = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
    now = new Date();
    hour = now.getHours();
    min = now.getMinutes();
    sec = now.getSeconds();
    if (hour < 10) {
      hour = "0" + hour;
    }
    if (min < 10) {
      min = "0" + min;
    }
    if (sec < 10) {
      sec = "0" + sec;
    }
    $("#Timer").html(
      now.getFullYear() + "年" + (now.getMonth() + 1) + "月" + now.getDate() + "日" + "  " + hour + ":" + min + ":" + sec
    );
    //$("#Timer").html(
    //        day[now.getDay()] + ", " + mon[now.getMonth()] + " "
    //                + now.getDate() + ", " + now.getFullYear() + " " + hour
    //                + ":" + min + ":" + sec);
  }
</script>

<script type="text/javascript">
    // 滚动效果
    (function($) {
        $.fn.myScroll = function(options) {
            var defaults = {
                speed: 40, 
                rowHeight: 24
            };

            var opts = $.extend({}, defaults, options),
                intId = [];

            function marquee(obj, step) {

                obj.find("ul").animate({
                    marginTop: '-=1'
                }, 0, function() {
                    var s = Math.abs(parseInt($(this).css("margin-top")));
                    if (s >= step) {
                        $(this).find("li").slice(0, 1).appendTo($(this));
                        $(this).css("margin-top", 0);
                    }
                });
            }

            this.each(function(i) {
                var sh = opts["rowHeight"],
                    speed = opts["speed"],
                    _this = $(this);
                intId[i] = setInterval(function() {
                    if (_this.find("ul").height() <= _this.height()) {
                        clearInterval(intId[i]);
                    } else {
                        marquee(_this, sh);
                    }
                }, speed);

                _this.hover(function() {
                    clearInterval(intId[i]);
                }, function() {
                    intId[i] = setInterval(function() {
                        if (_this.find("ul").height() <= _this.height()) {
                            clearInterval(intId[i]);
                        } else {
                            marquee(_this, sh);
                        }
                    }, speed);
                });

            });

        }

    })(jQuery);
    $(function() {
        $("div.fg-box").myScroll({
            speed: 200, //数值越大，速度越慢
            rowHeight: 37 //li的高度
        });
    });
</script>
    
    
<script type="text/javascript">
function refresh()
{
       window.location.reload();
}
setTimeout('refresh()',60000); //指定60秒刷新一次
</script>

</html>


