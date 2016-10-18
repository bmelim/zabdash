<?php

	$dbHostsCount = DBselect( 'SELECT COUNT(hostid) AS hc FROM hosts WHERE status <> 3 AND flags = 0');
	$hostsCount = DBFetch($dbHostsCount);	
	
	$dbHosts = DBselect( 'SELECT hostid, name, status, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts WHERE status <> 3 AND flags = 0 ORDER BY name ASC'	);
				
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
					<th style='text-align:center;'>". _('Triggers')."</th>
					<th style='text-align:center;'>". _('Status')."</th>
				</tr>								
			</thead>
			<tbody> ";
		
			while ($hosts = DBFetch($dbHosts)) {				
			
				if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F";  } else { $conn = "Online"; $cor = "#4BAC64"; } 	
					
				$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
				$IP = DBFetch($dbIP);
				
				$hostOS = getOS($hosts['hostid']);	
				
				$trigger = $api->triggerGet(array(
					'output' => 'extend',
					'hostids' => $hosts['hostid'],
					'sortfield' => 'priority',
					'sortorder' => 'DESC',
					'only_true' => '1',
					'active' => '1', // include trigger state active not active
					'withUnacknowledgedEvents' => '1' // show only unacknowledgeevents						
				));	
				
				if ($trigger) {
	
				// Highest Priority error
				$hostdivprio = $trigger[0]->priority;
		
		 	   $priority = $event->priority;
		 		$description = $event->description;
				
				echo "
						<tr>
							<td  style='background-color:".$cor.";' title='".$conn."'>
							</td>
							<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
								<a href='host_detail.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
							</td>
							<td style='text-align:center;'>
								<img src='img/os/".$hostOS.".png' title='".$hostOS."'/>
							</td>
							<td style='text-align:center; vertical-align:middle; '>
								".$IP['ip']."
							</td>
							<td style='text-align:center; width:155px;'>
								<div class='hostdiv nok". $hostdivprio ." hostevent trig_radius' onclick=\"window.open('/zabbix/tr_status.php?filter_set=1&hostid=".$hosts['hostid']."&show_triggers=1')\">
									<span class='eventdate'>". from_epoch($trigger[0]->lastchange)."</span>									
								</div>
							</td>
							<td style='text-align:center; vertical-align:middle'>
								". hostStatus($hosts['status']) ."
							</td>
						</tr>";								
				}
				else {
					
					echo "
						<tr>
							<td  style='background-color:".$cor.";' title='".$conn."'>
							</td>
							<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
								<a href='host_detail.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
							</td>
							<td style='text-align:center;'>
								<img src='img/os/".$hostOS.".png' title='".$hostOS."'/>
							</td>
							<td style='text-align:center; vertical-align:middle; '>
								".$IP['ip']."
							</td>
							<td style='text-align:center; width:155px;'>
								<div class='hostdiv ok hostevent trig_radius' onclick=\"window.open('/zabbix/tr_status.php?filter_set=1&hostid=".$hosts['hostid']."&show_triggers=1')\">
									<span class='eventdate' style='color:#fff !important;'> <i class='fa fa-check'></i> </span>
								</div>
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

		  "select": false,
		  "paging":   true,
        "ordering": false,
        "info":     true,
        "filter":     true,
        pagingType: "full_numbers",        
		  displayLength: 15,
        lengthMenu: [[15,25, 50, 100, -1], [15,25, 50, 100, "All"]],	    	    	   
    
    });
});

</script>
		