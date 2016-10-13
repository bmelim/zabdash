<?php

require_once '../include/config.inc.php';
require_once('config.php');
require_once 'inc/functions.inc.php';

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

//Translate
$labels = include_once 'locales/en.php';

$dbHostsCount = DBselect( 'SELECT SUM(case when status = 0 then 1 else 0 end) AS active, SUM(case when status = 1 then 1 else 0 end) AS inactive, SUM(case when status = 3 then 1 else 0 end) AS template FROM hosts WHERE flags = 0');
$hostsCount = DBFetch($dbHostsCount);	

$dbTrig = DBselect( 'SELECT COUNT(hostid) AS hc FROM hosts WHERE status = 1 AND flags = 0');
$trigCount = DBFetch($dbTrig);	

$trigger = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'priority',
	'sortorder' => 'DESC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active
	/*'withUnacknowledgedEvents' => '1', */
	'expandDescription' => '1',
	'selectHosts' => 1							
));	


$triggerUnack = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'priority',
	'sortorder' => 'DESC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active
	/*'withUnacknowledgedEvents' => '1',*/ 
	'expandDescription' => '1',
	'selectHosts' => 1							
));


$hostsGroups = $api->hostgroupGet(array(
	'output' => 'extend',	
	'sortfield' => 'name',
	'sortorder' => 'ASC'
	/*'real_hosts' => '1'*/
));


$users = $api->userGet(array(
	'output' => 'extend'	
));	

?>

<!DOCTYPE html>
<html>
<head>
    <title>Zabdash - Home </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">           
    
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet">        		   
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    
     <!-- this page specific styles 
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" /> -->    

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 	<script src="js/jquery.js"></script> 
   <link href="css/styles.css" rel="stylesheet" type="text/css" />
   <link href="css/style-dash.css" rel="stylesheet" type="text/css" />    
    
    <!-- odometer -->
	<link href="css/odometer.css" rel="stylesheet">
	<script src="js/odometer.js"></script>		
	
	<!-- Datatables -->	
	<script src="js/media/js/jquery.dataTables.min.js"></script>
	<link href="js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
	<script src="js/media/js/dataTables.bootstrap.js"></script>
	<link href="js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />
    
	<!-- <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss"> -->
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->         
	<!-- <link href="fonts/fonts.css" rel="stylesheet" type="text/css" /> -->
     
  	 <?php 
 	 	echo '<link rel="stylesheet" type="text/css" href="css/skin-material.css">'; 
	 	echo '<link rel="stylesheet" type="text/css" href="css/style-material.css">';				
 	 ?>  	

	<style type="text/css">
		@media screen and (min-width: 1201px) and (max-width: 2200px) {
	  	#footer-bar {
	    margin-top: 5px;
	    height: 20px;
	  	 }
		}
		.triggersUnack_filter { display:none !important; }
	</style>	
	
</head>

<body>	 
<?php    
	$ano = date("Y");
	$month = date("Y-m");
	$hoje = date("Y-m-d");
?>

<div class="site-holder">
<!-- top -->
<!-- .box-holder -->
<!-- .content -->
<div class="content animated fadeInBig corpo col-md-12 col-sm-12 align">
    <!-- main-content 
   <div class="main-content masked-relative masked"> -->
      
						<div id="panels" class="row" style="margin-top: 1%; margin-left: 1%; margin-right:1%;">
							<!-- COLUMN 1 -->															
								  <div class="col-sm-3 col-md-3 stat">
									 <div class="dashbox shad panel panel-default db-red">
										<div class="panel-body">

										   <div class="panel-right right" style='cursor:pointer;' onclick="window.open('../hosts.php');">
												<span style="float:left; margin-top:18px;"><i class="fa fa-desktop fa-3x"></i></span>
                        				<span class="chamado"><?php echo __('Hosts'); ?></span><br>
										     	<div id="odometer1" class="odometer" style="font-size: 25px;">   </div><p></p>
	                        				<span class="date" title="Active/Inactive"><b><?php echo $hostsCount['active']." / ".$hostsCount['inactive']; ?> </b></span>												
										   </div>
										</div>
									 </div>
								  </div>
								  
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-blue">
										<div class="panel-body">

										   <div class="panel-right right" style='cursor:pointer;' onclick="window.open('../tr_status.php');">	
										   	<span style="float:left; margin-top:18px;"><i class="fa fa-warning fa-3x"></i></span>									 
                        				<span class="chamado"><?php echo __('Triggers'); ?></span><br>
												<div id="odometer2" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="date" title="Unack/Ack"><b><?php echo count($triggerUnack)." / ". (count($trigger) - count($triggerUnack)); ?></b></span>
										   </div>
										</div>
									 </div>
								  </div>																		
                     								
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-yellow">
										<div class="panel-body">

										   <div class="panel-right right" style='cursor:pointer;' onclick="window.open('../hostgroups.php');">
										   	<span style="float:left; margin-top:18px;"><i class="fa fa-sitemap fa-3x"></i></span>
                        				<span class="chamado"><?php echo $labels['Host Groups']; ?></span><br>
												<div id="odometer3" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="date"><b>&nbsp;</b></span>
										   </div>										   
										</div>
									 </div>
								  </div>
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-orange">
										<div class="panel-body">

								   		<div class="panel-right right" style='cursor:pointer;' onclick="window.open('../users.php');">	
								   		<span style="float:left; margin-top:18px;"><i class="fa fa-users fa-3x"></i></span>
                        				<span class="chamado"><?php echo $labels['Users']; ?></span><br>                        				
												<div id="odometer4" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="date"><b>&nbsp;</b></span>
										   </div>
										</div>
									 </div>
								  </div>																	                          				                           							
						</div>        
                
<div class="container-fluid">  
      
<script type="text/javascript" >
window.odometerOptions = {
   format: '( ddd).dd'
};

setTimeout(function(){
    odometer1.innerHTML = <?php echo ($hostsCount['active'] + $hostsCount['inactive']); ?>;
    odometer2.innerHTML = <?php echo count($trigger); ?>;
    odometer3.innerHTML = <?php echo count($hostsGroups); ?>;
    odometer4.innerHTML = <?php echo count($users); ?>;
}, 1000);

</script> 

<div id='content-main' class="container-fluid1 align col-md-12 row">  

<div id="tickets_total" class="widget2 widget-table action-table striped" style="margin-top: 10px; margin-bottom:-5px;">
   <div class="widget-header2" style="width:100%;">                
   	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo 'Triggers by Host - Top 10'; ?></h3>      
   </div> 
      <!-- /widget-header -->
	<div id="grfrow" class="row-fluid col-sm-11 col-md-11 card" style="width:100%; height:420px;">	
      <!--<h4> <?php //echo __('Tickets Evolution'); ?> </h4>
      	<p id="choices" style=" margin-right: 20px; margin-top: 5px; text-align:right; width:100%;"></p>
      -->	  	
		<div class="demo-container" style="margin-bottom:10px;">						
			<div id="graflinhas1" class="demo-placeholder" style="float:left; width:100%; border: 0px solid #f5f5f5;"></div>									
		</div>
	</div>
	   <?php 
			include ("charts/triggers_hosts.inc.php");
		?>						
</div>

<div id="widgets" class="row" style="margin-top: 0px;">	
	
	<div class="col-sm-12 col-md-12 align" style="margin-left: 0px;"> 	 				              
	   <div id="tickets_status" class="widget2 widget-table action-table striped card1" >
	      <div class="widget-header">                 
	      	<h3><i class="fa fa-pie-chart" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Unacknowledged Triggers'); ?></h3>
	      	 <span  class="fa fa-timesx hide-widgetx"></span>               
	      </div> 
	      <!-- /widget-header -->      
	      <div id="pie1" style="min-height:800px !important;">	 			
					<?php
						include ("charts/triggers_unack.inc.php");
					?> 	 						            
			</div> 
		</div>
	</div>

</div> <!-- end widgets -->                   		

</div> 
 
<script type="text/javascript">

	function scrollWin()
	{
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}

	
	$(document).ready(function() {
		
	    $('#triggersUnack').DataTable({
			  "select": false,
			  "paging":   true,
	        "ordering": true,
	        "info":     true,
	        "filter":	true,	 
	        "lengthChange":	false,	 	               
	        "order": [[ 0, "desc" ]],
	        pagingType: "full_numbers",        
			  displayLength: 20,
	        lengthMenu: [[15,25, 50, 100, -1], [15,25, 50, 100, "All"]],	    	    	   
	    
	    });
	});

</script>	


<div id="go-top" class="go-top" onclick="scrollWin()">
   <i class="fa fa-chevron-up"></i>&nbsp; Top     							    
</div> 
 	
</div> <!-- end content -->   

</div>
<!-- /.site-holder -->


<script src="js/jquery-ui.min.js" type="text/javascript"></script> 
<script src="js/jquery.accordion.js"></script>            
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/jquery.easy-pie-chart.js"></script> 
<script src="js/jquery.address-1.6.min.js"></script>

<script src="js/bootstrap-switch.js"></script> 
<script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<script src="js/modules/exporting.js" type="text/javascript" ></script>
<script src="js/modules/no-data-to-display.js" type="text/javascript" ></script>

<script src="js/extensions/Select/js/dataTables.select.min.js"></script>

<script src="js/theme.js"></script>         
<script src="js/jquery.jclock.js"></script>

<script src="js/widgets.js"></script>

<!-- Highcharts export xls, csv -->
<script src="js/export-csv.js"></script>

<!-- Remove below two lines in production -->  
<script src="js/theme-options.js"></script>       
<script src="js/core.js"></script>
</body>
</html>
