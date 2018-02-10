<?php

	$group = get_hostgroup_by_groupid($groupID);
	$groupName = $group['name'];

   $dbHostsCount = DBselect( 'SELECT COUNT(h.hostid) AS hc FROM hosts h,  hosts_groups hg WHERE h.status <> 3 AND h.flags = 0 AND h.hostid = hg.hostid AND hg.groupid = '.$groupID );
	$hostsCount = DBFetch($dbHostsCount);	
	
	$dbHosts = DBselect( 'SELECT h.hostid, h.name, h.status, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags, g.name AS gname FROM hosts h, hosts_groups hg, groups g WHERE h.status <> 3 AND h.flags = 0 AND h.hostid = hg.hostid AND g.groupid = hg.groupid AND hg.groupid = '.$groupID.' ORDER BY h.name ASC'	);
				
	$md = 11;	
	
	echo "	
		<div class='align col-md-".$md." col-sm-".$md."' style='margin-bottom:80px;' >
				<h3 style='color:#000 !important; margin-top:-2 px; text-align:center;'> " .$groupName."</h3>
			<table id='tab_hosts' class='box table table-striped table-hover' border='0' >
			<thead>
				<tr>
					<th width='4px;' style='padding:3px !important; text-align:right;'></th>
					<th style='text-align:center;'>Hosts (".$hostsCount['hc'].")</th>
					<th style='text-align:center;'>".$labels['O.S.']."</th>
					<th style='text-align:center;'>". _('IP')."</th>
					<th style='text-align:center;'>". _('Triggers')."</th>
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
		'only_true' => '1',
		'active' => '1', // include trigger state active not active
		'withUnacknowledgedEvents' => '1' // show only unacknowledgeevents	
		
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
							<a href='host_detail.php?hostid=".$hosts['hostid']."' target='_self' >".$hosts['name']."</a>
						</td>
						<td style='text-align:center;' data-order='".$hostOS."'>
							<img src='img/os/".$hostOS.".png' title='".$hostOS."' alt=''/>
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
        "ordering": true,
        "info":     true,
        "order": [[ 1, "asc" ]],
        pagingType: "full_numbers",        
		  displayLength: 15,
        lengthMenu: [[15,25, 50, 100, -1], [15,25, 50, 100, "All"]],	    	    	   
    
    });
});

</script>
