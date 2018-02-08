<?php

//Access control
if(!$_COOKIE["zabdash_session"]) {
	header("location:index.php");
}

require_once '../include/config.inc.php';
require_once('config.php');
require_once 'inc/functions.inc.php';

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$triggerAll = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'lastchange',
	'sortorder' => 'DESC',
	'only_true' => '1',  //recents
	'active' => '1', 
	/*'withUnacknowledgedEvents' => '1',*/ 
	'expandDescription' => '1',
	'selectHosts' => 1								
));		

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Language" content="pt-br">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv='refresh' content='300'>
	<title>ZabDash - Triggers</title>	

	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />		

	<!-- Datatables -->	
	<script src="js/media/js/jquery.dataTables.min.js"></script>
	<link href="js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
	<script src="js/media/js/dataTables.bootstrap.js"></script>
<!--	<script src="js/extensions/Buttons/js/dataTables.buttons.min.js"></script>-->
	<link href="js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />	
	<script src="js/extensions/Select/js/dataTables.select.min.js"></script>	
	<link rel="stylesheet" type="text/css" href="css/skin-material.css"> 
	<link rel="stylesheet" type="text/css" href="css/style-material.css">		

<link href="css/loader.css" type="text/css" rel="stylesheet" />

<script type="text/javascript">
 jQuery(window).load(function () {
	$(".loader").fadeOut("slow"); //retire o delay quando for copiar!  delay(1500).
	$("#container-fluid").toggle("fast");    
});          
</script>
	
</head>

<body style="background-color: #fff !important;" >
<div id="loader" class="loader"></div>
 <div class='container-fluid'>
	<div class="row col-md-12 col-sm-12" style="margin-top:40px; margin-bottom: 70px; float:none; margin-right:auto; margin-left:auto; text-align:center;">
	<?php
	echo "			
		<div id='triggers_all' class='align col-md-12 col-sm-12' style='margin-bottom: 30px;'>
			<table id='triggers' class='box table table-striped table-hover table-condensed' border='0' style='height:250px;'>
			<thead>
				<tr>
					<th style='text-align:center; width:15%;'>". _('Last change')."</th>
					<th style='text-align:center;'>". _('Severity')."</th>
					<th style='text-align:center;'>". _('Host')."</th>
					<th style='text-align:center;'>". _('Status')."</th>
					<th style='text-align:center;'>". _('Description')."</th>					
				</tr>\n								
			</thead>\n
			<tbody>\n ";
		
	 foreach($triggerAll as $tu) {  
	 
	  	if($tu->value == 0) { $priority = 9; $statColor = '#34AA63';} 	
	  	else { $priority = $tu->priority; $statColor = '#E33734'; } 	 
	
		echo "<tr>";			            
			echo "<td style='text-align:center; vertical-align: middle !important;' data-order=".$tu->lastchange.">".from_epoch($tu->lastchange)."</td>\n";				            			
			echo "<td style='vertical-align: middle !important;'>
						<div class='hostdiv nok". $priority ." hostevent trig_radius' style='height:21px !important; margin-top:0px; !important;' onclick=\"window.open('/zabbix/tr_status.php?filter_set=1&hostid=". $tu->hosts[0]->hostid ."&show_triggers=1')\">
						<p class='severity' style='margin-top: -2px;'>". _(get_severity($tu->priority)) ."</p>									
						</div>
					</td>";				            
			echo "<td style='text-align:left; vertical-align: middle !important;'><a href='../zabdash/host_detail.php?hostid=".$tu->hosts[0]->hostid."' target='_blank'>". get_hostname($tu->hosts[0]->hostid)."</a></td>";
			echo "<td style='text-align:center; vertical-align: middle !important; color:".$statColor." !important;'>"._(set_status($tu->value))."</td>";				            						            			            						            
			echo "<td style='text-align:left; vertical-align: middle !important;'>".$tu->description."</td>";				            						            
		echo "</tr>\n";			            
			
	 }
	
	echo "</tbody> </table></div>\n";	
	?>
	
	</div>
	</div>

	<script type="text/javascript">
	
		$(document).ready(function() {
		
	    $('#triggers').DataTable({	    		       
			  "select": true,
			  "paging":   true,	        
	        "info":     true,
	        "filter":	true,	 
	        "lengthChange":	true,
	        "ordering": true,	 	               
	        "order": [[ 0, "desc" ]],
	        pagingType: "full_numbers",        
			  displayLength: 25,
	        lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],	        
 	    	   	    
	    });
		});
		
	</script>

</body>
</html>




