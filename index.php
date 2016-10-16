<?php

require_once '../include/config.inc.php';
/*require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';*/
require_once('config.php');
require_once 'inc/functions.inc.php';

//Translate
//$labels = include_once 'locales/en.php';

 switch (date("m")) {
 case "01": $mes = $labels['January']; break;
 case "02": $mes = $labels['February']; break;
 case "03": $mes = $labels['March']; break;
 case "04": $mes = $labels['April']; break;
 case "05": $mes = $labels['May']; break;
 case "06": $mes = $labels['June']; break;
 case "07": $mes = $labels['July']; break;
 case "08": $mes = $labels['August']; break;
 case "09": $mes = $labels['September']; break;
 case "10": $mes = $labels['October']; break;
 case "11": $mes = $labels['November']; break;
 case "12": $mes = $labels['December']; break;
 }

switch (date("w")) {
 case "0": $dia = $labels['Sunday']; break;    
 case "1": $dia = $labels['Monday']; break;
 case "2": $dia = $labels['Tuesday']; break;
 case "3": $dia = $labels['Wednesday']; break;
 case "4": $dia = $labels['Thursday']; break;
 case "5": $dia = $labels['Friday']; break;
 case "6": $dia = $labels['Saturday']; break;  
 }  
 

//User id 
$userid = get_userid(CWebUser::getSessionCookie());
//echo "user ".$userid;  
//$ses = CWebUser::getSessionCookie();
//echo $ses;
  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zabdash - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">
    <!--<meta http-equiv="refresh" content= "600"/>-->
    
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet">     

    <!-- Styles -->   
    <!-- Color theme -->       		   
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    
     <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />    
    
    <!-- odometer -->
	<link href="css/odometer.css" rel="stylesheet">
	<script src="js/odometer.js"></script>
    
   <!-- <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
     <![endif]-->         
     <!-- <link href="fonts/fonts.css" rel="stylesheet" type="text/css" /> -->
      
 	<link rel="stylesheet" type="text/css" href="./css/skin-material.css"> 
 	<link rel="stylesheet" type="text/css" href="./css/style-material.css">
 	<link href="css/font-awesome.css" rel="stylesheet">  	 
 	<script src="js/jquery.min.js"></script> 
 	 
 	 <script type="text/javascript">
		function scrollWin()
		{
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}
 	 </script>
</head>

<body>
   <div class="site-holder">
       <!-- .navbar -->
       <nav class="navbar navbar-default nav-delighted navbar-fixed-top shad2" role="navigation" >
           <a href="#" class="toggle-left-sidebar">
               <i class="fa fa-th-list"></i>
           </a>

           <!-- Brand and toggle get grouped for better mobile display -->
           <div class="navbar-header" style="color:#fff;" >
               <a class="navbar-brand" href="../index.php" target="_blank">
                   <span><img src="img/zabbix.png" alt="Zabbix" style="height:28px !important; "></img></span></a>
           </div>
		<!-- NAVBAR LEFT  -->					
		<ul id="navbar-left" class="nav navbar-nav pull-left hidden-xs">
		    <li class="logo">
		        <a href="./index.php" style="margin-top:6px;">           
		            <span class="name" style="color: #FFF; font-size:14pt;">
		                Zabdash  
		            </span>            
		        </a>
		    </li>
		</ul>
       								
		<!-- /NAVBAR LEFT -->					
		<ul class="nav navbar-nav pull-right hidden-xs">
			<li id="header-user" class="user" style="color:#FFF; margin-top: 8px; margin-right:8px;">
				<!--<a  href="#" style="color:#FFF; font-size:10pt; margin-top:5px;">-->							
				<span class="username">				
					<script type="text/javascript">
					
						$(function($) {
							var options = {
							timeNotation: '24h',
							am_pm: true,
							fontSize: '14px'
						}
							$('#clock').jclock(options);
						});
												
						var d_names = <?php echo '"'.$dia.'"' ; ?>;
						var m_names = <?php echo '"'.$mes.'"' ; ?>;
						
						var d = new Date();
						var curr_day = d.getDay();
						var curr_date = d.getDate();
						var curr_month = d.getMonth();
						var curr_year = d.getFullYear();
						
						document.write("<i class='fa fa-calendar-o' style='color:#fff;'> </i>  " + d_names + ", " + curr_date + " " + m_names + " " + curr_year );									
					</script> 
				</span>
				<div id="clock" style="text-align:right;"></div>											
				<!--</a> -->
				
			</li>
		</ul>     
   <!-- /.navbar-collapse -->																																	
	               
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li>                                    
                    </li>
                    <li>                                   
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->                         
           </nav>

           <!-- .box-holder -->
           <div class="box-holder">

               <!-- .left-sidebar -->
               <div class="left-sidebar">
                   <div class="sidebar-holder">
                       <!-- User   -->
                       <div class="user-menu" style="background:url('img/web.jpg') !important;">
                           <!--<img src="" alt="" title="" class="avatar" style="margin-left: -8px;" />-->
                           <div class="user-info">
                               <div class="welcome"><?php //echo __('Welcome'); ?> </div>
                               <div class="username"></a></div>
                               
                           </div>                                  
                       </div>
                       <!-- /.User   -->

                       <!-- Menu -->
                       <ul class="nav nav-list">
                       
                           <li class=''>
                           <a href='#' onclick="window.open('index.php','_self'); scrollWin();" data-original-title='Zabdash'>
                               <i class='fa fa-dashboard'></i>
                               <span class='hidden-minibar'>Zabdash</span>
                           </a>
                           </li>                           
                       		
                       		<li class=''>
                           <a href='#' onclick="window.open('hosts_view.php','iframe1'); scrollWin();" data-original-title='Hosts'>
                               <!--<i class='fa fa-desktop'></i>-->
                               <i><img src="img/icon/host.png" alt="" style="width:20px;"></img></i>
                               <span class='hidden-minibar'><?php echo __('Hosts'); ?>
                               </span>
                           </a>  
                       		</li>
                     
                       		<li class=''>
                           <a href='#' onclick="window.open('hosts_groups.php','iframe1'); scrollWin();" data-original-title='Hosts'>
                               <!--<i><img src="img/icon/group.png" alt="" style="width:20px;"></img></i>-->
                               <i class="fa fa-sitemap"></i>
                               <span class='hidden-minibar'><?php echo $labels['Host Groups']; ?>
                               </span>
                           </a>  
                       		</li>                              
                       		
                       	  <li class=''>
	                          <a href='#' onclick="window.open('groups.php','_blank'); scrollWin();" data-original-title='Panel'>
                               <i class="fa fa-desktop" aria-hidden="true"></i>
                               <span class='hidden-minibar'><?php echo $labels['Hosts Panel']; ?>
                               </span>
                           </a>  
                       		</li>
                           
                           <li class='submenu'>
                              <a class='dropdown' onClick='return false;' href='#' data-original-title='Health'>
                                  <i class='fa fa-medkit'></i>
                                  <span class='hidden-minibar'><?php echo $labels['Hosts Health'];?>
                                      <i class='fa fa-chevron-right  pull-right'></i>
                                  </span>
                              </a>
										<ul  class="animated fadeInDown">
                               <li class=' '>
                                   <a href='#' onclick="window.open('./hosts_storage.php','iframe1'); scrollWin();" data-original-title=' Storage'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo $labels['Storage']; ?> </span>
                                   </a>
                               </li>
                               <li class=' '>
                                    <a href='#' onclick="window.open('./hosts_memory.php','iframe1'); scrollWin();" data-original-title=' Memory'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo $labels['Memory']; ?> </span>
                                   </a>
                               </li>
                               <!-- <li class=' '>
                                     <a href="./tickets/select_grupo.php" data-original-title=' por Grupo' target="_blank">
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo __('by Group'); ?> </span>
                                   </a>
                               </li>   
                               
                              <li class='submenu'>
                                 <a class='dropdown' onClick='return false;' href='#' data-original-title='Mapas'>
                                     <i class='fa fa-angle-right'></i>
                                     <span class='hidden-minibar'><?php echo __('Map');?>
                                         <i class='fa fa-angle-right  pull-right'></i>
                                     </span>
                                 </a>
                                 <ul  class="animated fadeInDown menu2">
                                  <li class=' '>
                                      <a class='' href="./map/index.php" data-original-title=' Mapa' target="_blank">
                                          <i class="fa fa-angle-right"></i>
                                          <span class='hidden-minibar'> <?php echo __('by Entity'); ?> </span>
                                      </a>
                                  </li>
                                  <li class=' '>
                                      <a href="./map/map_loc.php" data-original-title=' Mapa' target="_blank">
                                          <i class="fa fa-angle-right"></i>
                                          <span class='hidden-minibar'> <?php echo __('by Location'); ?> </span>
                                      </a>
                                  </li>
                                  </ul>
                                 </li> -->                             
                           	</ul>                                    
                       </li>
                                  
                    		<li class=' '>
	                        <a href='#' onclick="window.open('triggers.php','iframe1'); scrollWin();" data-original-title='Events'>
	                            <i class='fa fa-edit'></i>
	                            <span class='hidden-minibar'><?php echo $labels['Triggers']; ?>
	                            </span>
	                        </a>  
                    		</li>  
                  		
					    		<li class=' '>
	                        <a href='#' onclick="window.open('logout.php','_self'); scrollWin();" data-original-title='Events'>
	                            <i class='fa fa-sign-out'></i>
	                            <span class='hidden-minibar'><?php echo $labels['Logout']; ?>
	                            </span>
	                        </a>  
                    		</li>                                		
                                                                                     												                                                       
                       <!-- <li class='submenu'>
                           <a class='dropdown' onClick='return false;' href='#' data-original-title='Gráficos'>
                               <i class="fa fa-bar-chart-o"></i>
                               <span class='hidden-minibar'><?php echo $labels['Charts']; ?>
                                   <i class='fa fa-chevron-right  pull-right'></i>
                               </span>
                           </a>
                           <ul  class="animated fadeInDown">
                                <li class=' '>
                                   <a href='#' onclick="window.open('./graficos/geral.php','iframe1'); scrollWin();"  data-original-title=' Geral'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo __('Overall'); ?></span>
                                   </a>
                               </li>
                                 <li class=' '>
                                   <a href='#' onclick="window.open('./graficos/tecnicos.php','iframe1'); scrollWin();"  data-original-title=' Técnicos'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo __('Technician'); ?></span>
                                   </a>
                               </li>
                               <li class=' '>
                                   <a href='#' onclick="window.open('./graficos/usuarios.php','iframe1'); scrollWin();"  data-original-title=' Usuários'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo __('Requester'); ?></span>
                                   </a>
                               </li>
                               
                  
                           </ul>
                       </li>  				
                       
                       <li class=' '>
                           <a href='metrics/index.php' target="_blank" data-original-title='Metrics'>
                               <i class='fa fa-line-chart'></i>
                               <span class='hidden-minibar'><?php echo $labels['Metrics']; ?>
                               </span>
                           </a>  
                       </li>
                                                      
                       <li class=' '>
                           <a href='#' onclick="window.open('./config.php','iframe1'); scrollWin();" target="iframe1"  data-original-title='Config'>
                               <i class='fa fa-gears'></i>
                               <span class='hidden-minibar'><?php echo $labels['Setup']; ?>
                               </span>
                           </a>  
                       </li>
                       
                       <li class=' '>
                            <a href='#' onclick="window.open('info.php','iframe1'); scrollWin();" target="iframe1" data-original-title='Info'>
                               <i class='fa fa-info-circle'></i>
                               <span class='hidden-minibar'><?php echo $labels['Info']; ?>
                               </span>
                           </a>  
                       </li> 
                       
                     <li class=' '>
                           <a href='https://forge.glpi-project.org/projects/dashboard/wiki' target="_blank" data-original-title='Help'>
                               <i class='fa fa-question-circle'></i>
                               <span class='hidden-minibar'><?php echo $labels['Help']; ?>
                               </span>
                           </a>  
                     </li>  -->                              
	 						<li></li>                                                                                                                               
   				</ul>
  		<!-- /.Menu -->
  		</div>
		<!-- /.left-sidebar Holder-->
		<?php
		 
		if(file_exists('/etc/hosts')) { 
		        
			echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>&nbsp;  Server Info</h5>
				
				<ul class="list-unstyled list-info-sidebar" style="color: #cecece; margin-left:-15px;">
					<li class="data-row">
						<span class="data-name" >OS:</span>
						<span class="data-value">'; include './sh/issue.php'; 
					
			echo		'</span>
					</li>
			
					<li class="data-row">
						<span class="data-name" >UP:</span>
						<span class="data-value">'; include './sh/uptime.php'; 
						
			echo		'</span>
					</li>
			
					<li class="data-row">
						<span class="data-name" >MEM:</span>
						<span class="data-value">'; include './sh/mem.php'; 
			
			echo '<div class="progress" style="height: 5px;">
			    		<div class="progress-bar progress-bar-striped active '.$corm.' " style="width: '.$umem.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$umem.'" role="progressbar"></div>
					</div>
						</span>		
					</li>
			
					<li class="data-row">
						<span class="data-name" >DISK:</span>
						<span class="data-value">'; include './sh/df.php'; 
			
			echo '<div class="progress" style="height: 5px;">
			    		<div class="progress-bar progress-bar-striped active '.$cord.'" style="width: '.$udisk.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$udisk.'" role="progressbar"></div>
					</div>
						</span>			
					</li>	';			
		}		
		?>	   

<!--	<div id="donate" style="margin-top:30px; margin-left:60px;">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="3SN6KVC4JSB98">
		<input type="image" src="./img/paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="./img/paypal.png" width="1" height="1">
		</form>
	</div>-->
                           
</div>
 <!-- /.left-sidebar -->

<!-- .content -->                        
<div class="container-fluid " style="margin-top:60px;">            
  
<script type="text/javascript" >
	window.odometerOptions = {
	   format: '( ddd).dd'
	};
/*	
	setTimeout(function(){
	    odometer1.innerHTML = <?php echo $total_hoje['total']; ?>;
	    odometer2.innerHTML = <?php echo $total_mes['total']; ?>;
	    odometer3.innerHTML = <?php echo $total_ano['total']; ?>;
	    odometer4.innerHTML = <?php echo $total_users['total']; ?>;
	}, 1000);
*/
</script> 

</div>   
 <iframe id="iframe1" name="iframe1" class="iframe iframe-side" src="main.php" scrolling="yes" style="position: absolute; height: 100%; margin-bottom: 40px; border: none; display:block;"></iframe>
<!--
	<script>
	function scrollWin()
	{
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
	</script> 

	<div id="go-top" class="go-top" onclick="scrollWin()">
	   <i class="fa fa-chevron-up"></i>&nbsp; Top     							    
	</div>      
-->        	
</div>		
<!-- end main-content -->	
</div>
</div>
<!-- /.box-holder -->
<!-- transparent them footer -->
<style type="text/css">
	@media screen and (min-width: 1201px) and (max-width: 2200px) {
	  	#footer-bar {
	 margin-top: 5px;
	 height: 20px;
	  	 }
	}
</style>

	
</div>
<!-- /.site-holder -->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
   
<script src="js/bootstrap.js"></script> 
<script src="js/bootstrap-switch.js"></script> 
<script src="js/jquery.accordion.js"></script>            
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/jquery.address-1.6.min.js"></script>
<script src="js/jquery.easy-pie-chart.js"></script> 

<!--
<script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<script src="js/modules/exporting.js" type="text/javascript" ></script>
<script src="js/modules/no-data-to-display.js" type="text/javascript" ></script>
-->
<!-- Highcharts export xls, csv 
<script src="js/export-csv.js"></script>


<a href="#" data-toggle="tooltip" data-placement="top" title="Hooray!">Hover</a>
<a href="#" data-toggle="tooltip" data-placement="bottom" title="Hooray!">Hover</a>
<a href="#" data-toggle="tooltip" data-placement="left" title="Hooray!">Hover</a>
<a href="#" data-toggle="tooltip" data-placement="right" title="Hooray!">Hover</a>

-->
<!-- knob 
<script src="js/jquery.knob.js"></script>
-->

<!-- flot charts   
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.flot.pie.min.js"></script>
<script src="js/jquery.flot.valuelabels.js"></script>
-->
<script src="js/theme.js"></script>         
<script src="js/jquery.jclock.js"></script>

 <!-- Remove below two lines in production -->  
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
 
</body>
</html>
