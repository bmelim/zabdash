
<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$_REQUEST['groupid'] = 8;

if(isset($_REQUEST['groupid']) && $_REQUEST['groupid'] != '' && $_REQUEST['groupid'] != 0) {	
	$include = "1";
	$groupID = $_REQUEST['groupid'];		
	
}

else {
	$include = "0";		
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

<title>Zabbix Hosts</title>

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

<script src="js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="js/media/pdfmake.min.js"></script>
<script src="js/media/vfs_fonts.js"></script>
<script src="js/media/jszip.min.js"></script>

<script src="js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

<style type="text/css">
	tr{height:38px;}
	td{height:100%}
</style>

</head>

<body>
	<div class="row col-md-12 col-sm-12" style="margin-top:30px; margin-bottom: 20px; float:none; margin-right:auto; margin-left:auto; text-align:center;">
	
	<?php	
				
	$hoje = date("d/m/Y");  //hoje
	$today = date("Y-m-d");  //hoje
	$umdia = date('Y-m-d', strtotime('-15 days'));
	
	$group = get_hostgroup_by_groupid($groupID);
	$groupName = $group['name'];

   $dbHostsCount = DBselect( 'SELECT COUNT(h.hostid) AS hc FROM hosts h, hosts_groups hg WHERE h.status <> 3 AND h.flags = 0 AND h.hostid = hg.hostid AND hg.groupid = '.$groupID );
	$hostsCount = DBFetch($dbHostsCount);	
	
	$dbHosts = DBselect( 'SELECT h.hostid, h.name, h.status, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags, g.name AS gname FROM hosts h, hosts_groups hg, groups g WHERE h.status <> 3 AND h.flags = 0 AND h.hostid = hg.hostid AND g.groupid = hg.groupid AND hg.groupid = '.$groupID.' ORDER BY h.name ASC'	);
				
	$md = 11;	
	
	echo "			
		<div class='align col-md-".$md." col-sm-".$md."' style='margin-bottom:50px;' >
				<h3 style='color:#000 !important; margin-top:-2 px; text-align:center;'> " .$groupName."</h3>
			<table id='tab_hosts' class='box table table-striped table-hover' border='0' >
			<thead style='background:#fff;'>
				<tr>
					<th width='4px;' style='padding:3px !important; text-align:right;'></th>
					<th style='text-align:center; min-width:180px;'>Hosts (".$hostsCount['hc'].")</th>\n";
			
					$date = date_create($umdia);
	
					for( $i = 1; $i <= 15; $i++ ) {
					   date_add($date, date_interval_create_from_date_string('1 days'));
					   $arr_days[] = date_format($date, 'd/m/Y');
					   
						echo "<th style='text-align:center;'>". date_format($date, 'd/m')."</th>\n";
					}							
	echo "
				</tr>								
			</thead>
			<tbody>\n ";	
	
while ($hosts = DBFetch($dbHosts)) {				
	
		if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; $value = 1; } 
		else { $conn = "Online"; $cor = "#4BAC64"; $value = 0; } 	
		
		$trigger = $api->triggerGet(array(
			'output' => 'extend',
			'hostids' => $hosts['hostid'],
			'sortfield' => 'priority',
			'search' => array('description' => '{HOST.NAME} is unavailable by ICMP'),
			'active' => '1', 	
		));	

		if ($trigger) {

			// Highest Priority error
			$hostdivprio = $trigger[0]->priority;
	
	 	   $priority = $event->priority;
	 		$description = $event->description;
	 		
	 		foreach ($trigger as $event) {
						$conta = $count++ ;
			}			 

			echo "
					<tr>
						<td  style='background-color:".$cor.";' title='".$conn."' data-order='".$value."'>
						</td>
						<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
							<a href='disp_host.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
						</td>\n";

				for( $i = 0; $i < 15; $i++ ) {
							$obj = $api->eventGet(array(
							'output' => 'extend',
							"objectids" => $trigger[0]->triggerid,
				        	//"sortfield" => "clock",
				        	"value" => '1',
				        	"limit" => '1',
				        	"time_from" => to_timestamp_ini($arr_days[$i]),
				         "time_till" => to_timestamp_fin($arr_days[$i]),
				        	"sortorder" => "DESC"		
						   ));
					  					   
						echo "<td style='text-align:center; vertical-align:middle'>\n";
						
						if($obj[0]->value == 1) {
							echo "<img src='img/error128.png' alt='Off' width='18' />\n" ;
						}
						else { echo "";}	
					}	

			echo "  </td>
					</tr>\n";								
			}	
}

	echo "		</tbody>
			</table>						
		</div>\n";
		
	?>

<script type="text/javascript">

$(document).ready(function() {
	
    $('#tab_hosts').DataTable({

		  "select": false,
		  "filter": false,
		  "paging":   false,
        "ordering": false,
        "info":     false,
        "order": [[ 1, "asc" ]],
        pagingType: "full_numbers",        
		  displayLength: 25,
        lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],	    	    	   
    
    });
});

</script>
	
	</div>	
	</body>
</html>
