<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv='refresh' content='600'>

<title>ZabDash - Hosts</title>

<!-- Bootstrap -->
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script src="js/media/js/jquery.dataTables.min.js"></script>
<link href="js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
<script src="js/media/js/dataTables.bootstrap.js"></script>

<script src="js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

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
 <div class='container-fluid'>			
			
	<div class="row col-md-12 col-sm-12" style="margin-top:40px; margin-bottom: 0px; float:none; margin-right:auto; margin-left:auto; text-align:center;">
	
	<?php		
	
	require_once 'lib/ZabbixApi.class.php';
	use ZabbixApi\ZabbixApi;
	$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');
	
	if(isset($_REQUEST['groupid']) && $_REQUEST['groupid'] != '' && $_REQUEST['groupid'] != 0) {	
		$include = 1;
		$groupID = $_REQUEST['groupid'];		
	}

	$dbHostsCount = DBselect( 'SELECT COUNT(hostid) AS hc FROM hosts WHERE status <> 3 AND flags = 0');
	$hostsCount = DBFetch($dbHostsCount);	
	
	$dbHosts = DBselect('SELECT hostid, name, status, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts WHERE status <> 3 AND flags = 0 ORDER BY name ASC');
				
	$md = 11;	
	
	echo "			
		<div id='hosts' class='align col-md-".$md." col-sm-".$md."' style='margin-bottom:80px;'>
			<table id='tab_hosts' class='box table table-striped table-hover' border='0' >
			<thead>
				<tr>
					<th width='5px;' style='padding:3px !important;'></th>
					<th style='text-align:center;'>Hosts (".$hostsCount['hc'].")</th>
					<th style='text-align:center;'>". $labels['O.S.']."</th>
					<th style='text-align:center;'>". _('IP')."</th>
					<th style='text-align:center;'>". _('Status')."</th>
				</tr>								
			</thead>
			<tbody> ";
		
			while ($hosts = DBFetch($dbHosts)) {				
			
				if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; $value = 1; } 
				else { $conn = "Online"; $cor = "#4BAC64"; $value = 0; } 	
					
				$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
				$IP = DBFetch($dbIP);
				
				$hostOS = getOS($hosts['hostid']);	
				
				$trigger = $api->triggerGet(array(
					'output' => 'extend',
					'hostids' => $hosts['hostid'],
					'sortfield' => 'priority',
					'sortorder' => 'DESC',
					//'only_true' => '1',
					//'active' => '1', // include trigger state active not active
					//'withUnacknowledgedEvents' => '1' // show only unacknowledgeevents						
				));	
				
				if ($trigger) {
	
				// Highest Priority error
				$hostdivprio = $trigger[0]->priority;
		
		 	   $priority = $event->priority;
		 		$description = $event->description;
				
				echo "
						<tr>
							<td  style='background-color:".$cor.";' title='".$conn."' data-order='".$value."'>
							</td>
							<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
								<a href='host_detail.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
							</td>
							<td style='text-align:center;' data-order='".$hostOS."'>
								<img src='img/os/".$hostOS.".png' title='".$hostOS."' alt=''/>
							</td>
							<td style='text-align:center; vertical-align:middle; '>
								".$IP['ip']."
							</td>

							<td style='text-align:center; vertical-align:middle'>
								". hostStatus($hosts['status']) ."
							</td>
						</tr>";								
				}
				else {
					
					echo "
						<tr>
							<td  style='background-color:".$cor.";' title='".$conn."' data-order='".$value."'>
							</td>
							<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
								<a href='host_detail.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
							</td>
							<td style='text-align:center;' data-order='".$hostOS."'>
								<img src='img/os/".$hostOS.".png' title='".$hostOS."' alt=''/>
							</td>
							<td style='text-align:center; vertical-align:middle; '>
								".$IP['ip']."
							</td>

							<td style='text-align:center; vertical-align:middle'>
								". hostStatus($hosts['status']) ."
							</td>
						</tr>";					
				}						
		}

echo "		</tbody>
			</table>						
		</div>\n";	
?>

<script type="text/javascript">

	$(document).ready(function() {
		
	    $('#tab_hosts').DataTable({
	
			  "select":   false,
			  "paging":   true,
	        "info":     true,
	        "filter":   true,
	        "ordering": true,
	        "order": [[ 1, "asc" ]],
	        pagingType: "full_numbers",        
			  displayLength: 15,
	        lengthMenu: [[15,25, 50, 100, -1], [15,25, 50, 100, "All"]],	    	    	   
	    
	    });
	});

</script>

</div>		
</div>		
</body>
</html>
		