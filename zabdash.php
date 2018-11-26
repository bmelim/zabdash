<?php

require_once '../include/config.inc.php';
require_once('config.php');
require_once 'inc/functions.inc.php';

//Access control
if(!$_COOKIE["zabdash_session"]) {
	header("location:index.php");
}

switch (date("m")) {
 case "01": $mes = _('January'); break;
 case "02": $mes = _('February'); break;
 case "03": $mes = _('March'); break;
 case "04": $mes = _('April'); break;
 case "05": $mes = _('May'); break;
 case "06": $mes = _('June'); break;
 case "07": $mes = _('July'); break;
 case "08": $mes = _('August'); break;
 case "09": $mes = _('September'); break;
 case "10": $mes = _('October'); break;
 case "11": $mes = _('November'); break;
 case "12": $mes = _('December'); break;
}

switch (date("w")) {
 case "0": $dia = _('Sunday'); break;    
 case "1": $dia = _('Monday'); break;
 case "2": $dia = _('Tuesday'); break;
 case "3": $dia = _('Wednesday'); break;
 case "4": $dia = _('Thursday'); break;
 case "5": $dia = _('Friday'); break;
 case "6": $dia = _('Saturday'); break;  
}   

//User id 
$userid = get_userid(CWebUser::getSessionCookie());
	  
//check new version 																																																			
/*
$urlv = "https://sourceforge.net/p/zabdash/screenshot/".$version.".png";
$headers = get_headers($urlv, 1);										

if($headers[0] != '') {

	if ($headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
		$newversion = "<a href='https://sourceforge.net/projects/zabdash/' target='_blank' style='margin-top:10px; margin-right: 12px; color:#fff;' class='blink_me'><i class='fa fa-refresh'></i><span>&nbsp;&nbsp;".  $labels['New version avaliable']. " </span></a>";		
	}
}  
  */
?>

<!DOCTYPE html>
<html>
<head>
    <title>ZabDash - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">
<!--    <meta http-equiv="refresh" content= "600"/>-->
    
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet">     
    <link href="css/bootstrap.css.map" rel="stylesheet">     
    <script src="js/jquery.min.js"></script> 

    <!-- Styles -->   
    <!-- Color theme -->       		   
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    
     <!-- this page specific styles -->
<!--    <link rel="stylesheet" href="css/index.css" type="text/css" media="screen" />    -->

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
<!--    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />    -->
    
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
 	 
 	 <script type="text/javascript">
		function scrollWin()
		{
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}
 	 </script>

<style type="text/css">
	.loader { height: 140% !important;}
</style>

<link href="css/loader.css" type="text/css" rel="stylesheet" />

<script type="text/javascript">
	jQuery(window).load(function () {
		$(".loader").fadeOut("slow"); //retire o delay quando for copiar!  delay(1500).
		$("#container-fluid").toggle("fast");    
	});
</script>
 	  	 
</head>

<body>
<div id="loader" class="loader"></div>
   <div class='container-fluidx'>
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
		        <a href="./zabdash.php" style="margin-top:6px;">           
		            <span class="name" style="color: #FFF; font-size:14pt;">
		                ZabDash  
		            </span>            
		        </a>
		    </li>
		</ul>
       								
		<!-- /NAVBAR LEFT -->					
		<ul class="nav navbar-nav pull-right hidden-xs">
			<li id="header-user" class="user" style="color:#FFF; margin-top: 8px; margin-right:8px;">
				<span><?php echo $newversion; ?></span>						
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
						
						document.write("<i class='fa fa-calendar' style='color:#fff;'> </i>  " + d_names + ", " + curr_date + " " + m_names + " " + curr_year );									
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
                               <div class="welcome"></div>
                               <div class="username"></a></div>
                               
                           </div>                                  
                       </div>
                       <!-- User -->

                       <!-- Menu -->
                       <ul class="nav nav-list">
                       
                           <li class=' '>
                           <a href='#' onclick="window.open('zabdash.php','_self'); scrollWin();" data-original-title='ZabDash'>
                               <i class='fa fa-dashboard'></i>
                               <span class='hidden-minibar'>Zabdash</span>
                           </a>
                           </li>      
                           
                           <li class=' '>
                           <a href='#' onclick="window.open('all_hosts.php','iframe1'); scrollWin();" data-original-title='Hosts'>                               
                               <i><img src="img/icon/host.png" alt="" style="width:20px;"></img></i>
                               <span class='hidden-minibar'><?php echo _('Hosts'); ?></span>
                           </a>  
                       		</li>                     
                       		                  
                       		<li class=' '>
                           <a href='#' onclick="window.open('hosts_groups.php','iframe1'); scrollWin();" data-original-title='Hosts Groups'>
                               <!--<i><img src="img/icon/group.png" alt="" style="width:20px;"></img></i>-->
                               <i class="fa fa-sitemap"></i>
                               <span class='hidden-minibar'><?php echo _('Hosts groups'); ?></span>
                           </a>  
                       		</li>                              
                       		
                       	  <li class=' '>
	                          <a href='#' onclick="window.open('groups.php','iframe1'); scrollWin();" data-original-title='Panel'>
                               <i class="fa fa-desktop" aria-hidden="true"></i>
                               <span class='hidden-minibar'><?php echo $labels['Hosts Panel']; ?></span>
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
                                   <a href='#' onclick="window.open('hosts_storage.php','iframe1'); scrollWin();" data-original-title=' Storage'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo $labels['Storage']; ?> </span>
                                   </a>
                               </li>
                               <li class=' '>
                                    <a href='#' onclick="window.open('hosts_memory.php','iframe1'); scrollWin();" data-original-title=' Memory'>
                                       <i class="fa fa-angle-right"></i>
                                       <span class='hidden-minibar'> <?php echo $labels['Memory']; ?> </span>
                                   </a>
                               </li>                                                       
                           	</ul>                                    
                         </li>
                         <li class=' '>
                              <a href='#' onclick="window.open('hosts_disp.php','iframe1'); scrollWin();" data-original-title=' Availability'>
                                 <i class='fa fa-clock-o'></i>
                                 <span class='hidden-minibar'> <?php echo $labels['Availability']; ?> </span>
                             </a>
                         </li>   
                                  
                    		<li class=' '>
	                        <a href='#' onclick="window.open('triggers.php','iframe1'); scrollWin();" data-original-title='Events'>
	                            <i class='fa fa-edit'></i>
	                            <span class='hidden-minibar'><?php echo $labels['Triggers']; ?></span>
	                        </a>  
                    		</li> 
                    		<li class=' '>
	                        <a href='#' onclick="window.open('map/index.php','iframe1'); scrollWin();" data-original-title='Events'>
	                            <i class='fa fa-map-marker'></i>
	                            <span class='hidden-minibar'><?php echo $labels['Hosts Map']; ?></span>
	                        </a>  
                    		</li>                     	 
                    		<li class=' '>
                            <a href='#' onclick="window.open('info.php','iframe1'); scrollWin();" data-original-title='Info'>
                               <i class='fa fa-info-circle'></i>
                               <span class='hidden-minibar'><?php echo $labels['About']; ?></span>
                           </a>  
                        </li> 
                  		
					    		<li class=' '>
	                        <a href='#' onclick="window.open('logout.php','_self'); scrollWin();" data-original-title='Events'>
	                            <i class='fa fa-sign-out'></i>
	                            <span class='hidden-minibar'><?php echo $labels['Exit']; ?></span>
	                        </a>  
                    		</li>                                		                                                                                     												                                                                                                        
	 						<li></li>                                                                                                                               
   				</ul>
  		<!-- Menu -->
  		</div>
		<!-- left-sidebar Holder-->
		<?php
		 
		if(file_exists('/etc/hosts')) { 
		        
			echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>&nbsp;  Server Info</h5>
				
				<ul class="list-unstyled list-info-sidebar" style="color: #cecece; margin-left:-15px;">
					<li class="data-row">
						<span class="data-name" >OS:</span>
						<span class="data-value">'; include './sh/issue.php'; 
					
			echo '</span>
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
<div class="container-fluid" style="margin-top:60px;">            
  
<script type="text/javascript" >
	window.odometerOptions = {
	   format: '( ddd).dd'
	};

</script> 

</div>   
 <iframe id="iframe1" name="iframe1" class="iframe iframe-side" src="main.php" scrolling="yes" style="position: absolute; height: 100%; margin-bottom: 70px; border: none; display:block;"></iframe>
        	
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
</div>
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

<script src="js/theme.js"></script>         
<script src="js/jquery.jclock.js"></script>

 <!-- Remove below two lines in production -->  
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
 
</body>
</html>
