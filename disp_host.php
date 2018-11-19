
<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

<<<<<<< HEAD
//$_REQUEST['hostid'] = 10111;

=======
>>>>>>> 1.1.2
if(isset($_REQUEST['hostid']) && $_REQUEST['hostid'] != '' && $_REQUEST['hostid'] != 0) {	
	$include = "1";
	$hostid = $_REQUEST['hostid'];			
}

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

<title>Zabdash - Disponibilidade</title>

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

<style type="text/css">
	tr{height:38px;}
	td{height:100%}
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
 <div class='container-fluid'>
	<div class="row col-md-12 col-sm-12" style="margin-top:20px; margin-bottom: 0px; float:none; margin-right:auto; margin-left:auto; text-align:center;">
	
	<?php	
				
	$hoje = date("d/m/Y");  //hoje
	$today = date("Y-m-d");  //hoje
	$umdia = date('Y-m-d', strtotime('-15 days'));
		
	$dbHosts = DBselect( 'SELECT hostid, name, status, available, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts WHERE hostid='.$hostid);
	$host = DBFetch($dbHosts);	
			
	$md = 11;	
	
	echo "			
		<div class='align col-md-".$md." col-sm-".$md."' style='margin-bottom:40px;'>
			<h3 style='color:#000 !important; margin-top:2 px; text-align:center;'> " .$host['name']."</h3>
				
			<table id='tab_hosts' class='box table table-striped table-hover table-bordered table-condensed' border='0'>
			<thead style='background:#fff;'>
				<tr>

					<th style='text-align:center;'>Início</th>				
					<th style='text-align:center;'>Fim</th>					
					<th style='text-align:center;'>Duração</th>
				</tr>								
			</thead>
			<tbody>\n ";	
				
	
		if($host['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; $value = 1; } 
		else { $conn = "Online"; $cor = "#4BAC64"; $value = 0; } 	
		
		$trigger = $api->triggerGet(array(
			'output' => 'extend',
			'hostids' => $host['hostid'],
			'sortfield' => 'priority',
			'search' => array('description' => '{HOST.NAME} is unavailable by ICMP'),
			'active' => '1', 	
		));	

		$dbevents = DBSelect("SELECT eventid, objectid, value, clock, DATE_FORMAT(FROM_UNIXTIME(`clock`), '%d/%m/%Y') AS data
			FROM `events` 
			WHERE `objectid` = ".$trigger[0]->triggerid ."
			AND value = 1 
			ORDER BY clock DESC 
			LIMIT 100");	 	

	while ($events = DBFetch($dbevents)) {
		
		$r_event = get_revent($events['eventid']);
		$time_init = from_epoch(event_time($events['eventid']));
		$time_rec = from_epoch(revent_time($r_event));
		
		if(revent_time($r_event) != '') {
			$diff = (revent_time($r_event) - event_time($events['eventid']));
			$period = time_ext($diff);			
		}
		else {$period = '';}	

		echo "
				<tr>						

					<td style='vertical-align:middle; text-align:center; padding:5px;' data-order='".$events['clock']."'>
						". $time_init ."
					</td>
					<td style='vertical-align:middle; text-align:center; padding:5px;'>
						". $time_rec ."
					</td>
					<td style='vertical-align:middle; text-align:center; padding:5px;'>
						".$period."
					</td>
				</tr>\n";	
	}

	echo "		</tbody>
			</table>						
		</div>\n";			
	?>

	<script type="text/javascript">
	
	$(document).ready(function() {
		
	    $('#tab_hosts').DataTable({
	
			  "select": false,
			  "filter": true,
			  "paging":   true,
	        "ordering": true,
	        "info":     true,
	        "order": [[ 0, "desc" ]],
	        pagingType: "full_numbers",        
			  displayLength: 15,
	        lengthMenu: [[15, 50, 100, -1], [15, 50, 100, "All"]],	    	    	   
	    
	    });
	});	
	</script>
	
	</div>	
	</div>	
	</body>
</html>
