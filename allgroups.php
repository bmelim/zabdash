<?php

foreach( $groupID as $g ) {
	
	$dbHosts = DBselect( 'SELECT hostid, name, status, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts ORDER BY name ASC'	);
	$dbHostsOk = DBselect( 'SELECT hostid, name, status, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts ORDER BY name ASC'	);
	
	$groupName = "Hosts";


	echo '	
		<div class="col-md-12 col-sm-12">
			<h3 style="color:#fff !important; margin-top:-2 px;"> ' .$groupName.'</h3>
		</div>	';
				
		$md = 2;											

		while ($hosts = DBFetch($dbHosts)) {
			
			if($hosts['status'] == 0 && $hosts['flags'] == 0) {		

				if($hosts['sd'] != 0) { 

					//hosts offline				
					$cor = "#E3573F"; 
					$icon = "fa fa-exclamation-triangle"; 				
				
					$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
					$IP = DBFetch($dbIP);
					
					echo "
					<div class='hostdiv col-md-".$md." col-sm-".$md."' style='background-color:".$cor.";'>
						<table border='0' width='100%' height='100%' style='color:#fff;'>
							<tr>
								<td rowspan='2' style='font-size:20px;' width='22px' height='50%'>
									<i class='".$icon."'></i>
								</td>
								<td class='link'>
<<<<<<< HEAD
									<a href='".$zabURL."tr_status.php?fullscreen=0&groupid=0&source=0&hostid=".$hosts['hostid']."' target='_blank' >".$hosts['name']."</a>
=======
									<a href='".$zabURL."zabbix.php?action=problem.view&page=1&filter_hostids[]=".$hosts['hostid']."&filter_show=1&filter_application=&filter_name=&filter_severity=0&filter_inventory[0][field]=type&filter_inventory[0][value]=&filter_evaltype=0&filter_tags[0][tag]=&filter_tags[0][operator]=0&filter_tags[0][value]=&filter_show_tags=3&filter_tag_name_format=0&filter_tag_priority=&filter_set=1' target='_blank' >".$hosts['name']."</a>
>>>>>>> 1.1.2
								</td>
								<td rowspan='2' style='font-size:20px;' width='22px' height='50%'>
									<img src='img/os/".getOS($hosts['hostid']).".png' alt=''/>
								</td>
							</tr>
							<tr>
								<td>
									".$IP['ip']."
								</td>
							</tr>					
						</table>
						
					</div>\n";									
				}
			}
		}
		
		//hosts online								
		while ($hosts = DBFetch($dbHostsOk)) {
			
			if($hosts['status'] == 0 && $hosts['flags'] == 0) {
		
				if($hosts['sd'] == 0) { 
									
					$icon = "fa fa-thumbs-up"; 
					
					$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
					$IP = DBFetch($dbIP);
				
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
						//$hostdivprio = $trigger[0]->priority;
						if($trigger[0]->value == 0) { $hostdivprio = 9;} 	
	  					else { $hostdivprio = $trigger[0]->priority;} 

						// View       							
	       					$icon = "fa fa-exclamation-circle";		
	       					echo "
								<div class='hostdiv nok". $hostdivprio ." col-md-".$md." col-sm-".$md."'>
									<table border='0' width='100%' height='100%' style='color:#fff;' class='link". $hostdivprio ."'>
										<tr>
											<td rowspan='2' style='font-size:20px;' width='22px' height='50%'>
												<i class='".$icon."'></i>
											</td>
											<td class='link". $hostdivprio ."'>
<<<<<<< HEAD
												<a href='".$zabURL."tr_status.php?fullscreen=0&groupid=0&source=0&hostid=".$hosts['hostid']."' target='_blank' >".$hosts['name']."</a>
=======
												<a href='".$zabURL."zabbix.php?action=problem.view&page=1&filter_hostids[]=".$hosts['hostid']."&filter_show=1&filter_application=&filter_name=&filter_severity=0&filter_inventory[0][field]=type&filter_inventory[0][value]=&filter_evaltype=0&filter_tags[0][tag]=&filter_tags[0][operator]=0&filter_tags[0][value]=&filter_show_tags=3&filter_tag_name_format=0&filter_tag_priority=&filter_set=1' target='_blank' >".$hosts['name']."</a>
>>>>>>> 1.1.2
											</td>
											<td rowspan='2' style='font-size:20px;' width='22px' height='50%'>
												<img src='img/os/".getOS($hosts['hostid']).".png' alt=''/>
											</td>										
										</tr>
										<tr>
											<td>
												".$IP['ip']."
											</td>
										</tr>					
									</table>									
								</div>\n";					
						}
					
					else {
																			
						echo "
						<div class='hostdiv ok col-md-".$md." col-sm-".$md."'>
							<table border='0' width='100%' height='100%' style='color:#fff;'>
								<tr>
									<td rowspan='2' style='color:#fff; font-size:20px;' width='22px' height='50%'>
										<i class='".$icon."'></i>
									</td>
									<td class='link'>
<<<<<<< HEAD
										<a href='".$zabURL."tr_status.php?fullscreen=0&groupid=0&source=0&hostid=".$hosts['hostid']."' target='_blank' >".$hosts['name']."</a>
=======
										<a href='".$zabURL."zabbix.php?action=problem.view&page=1&filter_hostids[]=".$hosts['hostid']."&filter_show=1&filter_application=&filter_name=&filter_severity=0&filter_inventory[0][field]=type&filter_inventory[0][value]=&filter_evaltype=0&filter_tags[0][tag]=&filter_tags[0][operator]=0&filter_tags[0][value]=&filter_show_tags=3&filter_tag_name_format=0&filter_tag_priority=&filter_set=1' target='_blank' >".$hosts['name']."</a>
>>>>>>> 1.1.2
									</td>
									<td rowspan='2' style='font-size:20px;' width='22px' height='50%'>
										<img src='img/os/".getOS($hosts['hostid']).".png' alt=''/>
									</td>
								</tr>
								<tr>
									<td>
										".$IP['ip']."
									</td>
								</tr>					
							</table>							
						</div>\n";
					}
				} 								
			}
		}
}	
?>