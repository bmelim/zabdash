<?php

foreach( $groupID as $g ) {
	
	$dbHosts = DBselect( 'SELECT h.hostid, h.name, h.status, h.available, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags FROM hosts h, hosts_groups hg WHERE hg.groupid = '.$g.' AND h.hostid = hg.hostid ORDER BY h.name ASC');
	//$dbHostsOk = DBselect( 'SELECT h.hostid, h.name, h.status, h.available, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags FROM hosts h, hosts_groups hg WHERE hg.groupid = '.$g.' AND h.hostid = hg.hostid ORDER BY h.name ASC');

	//get group name
	$group = get_hostgroup_by_groupid($g);
	$groupName = $group['name'];	

	echo '	
		<div class="col-md-12 col-sm-12">
			<h3 style="color:#000 !important; margin-top: 15px; margin-bottom:15px;"> '.$groupName.'</h3>
		</div>	';
				
		$md = 12;											

		while ($hosts = DBFetch($dbHosts)) {
			
			if($hosts['status'] == 0 && $hosts['flags'] == 0) {
								
				if($hosts['available'] == 1 ) 
					{ $keyValue = 'vm.memory.size'; }
				else { $keyValue = 'inbytes'; }							
				
				 // get all items
				 $mems = $api->itemGet(array(
				     'output' => 'extend',
				     'hostids' => $hosts['hostid'],
				     'search' => array('key_' => $keyValue)
				 ));

//print_r($mems);
				 
				// print Mem				
				foreach($mems as $mem) {    
				  	
				 	if($hosts['available'] == 1 ) { 
				 	
					 	$searchValSize = 'total'; $searchValUsed = 'used'; 
										           
					   $memSize = get_item_values($mem->itemid, $searchValSize);
					   $memUsed = get_item_values($mem->itemid, $searchValUsed);
					   //$memUsed = ($memSize['value_max'] - $memUsed['value_max']);
					
						//Size				
						if($memSize['value_max'] != 0 || get_item_label($memSize['key_']) != '') {						
							$arrSizeMem[] = get_item_label($memSize['key_']).",".$memSize['value_max'];
						}
				
						if($memUsed['name'] != '') {
							if($memUsed['value_max'] != 0) {
								$arrUsedMem[] = get_item_label($memUsed['key_']).",". $memUsed['value_max'];
							}
						}
						
						$zbx_agent = 1;
					}	
					
					else { 
					
						$searchValSize = 'hrStorageSizeinBytes'; $searchValUsed = 'hrStorageUsedinBytes'; 
					
					   $memSize = get_item_values($mem->itemid, $searchValSize);
					   $memUsed = get_item_values($mem->itemid, $searchValUsed);
								
						//Size								
						if($memSize['value_max'] != 0 || get_item_label($memSize['key_']) != '') {						
							$label = get_item_label($memSize['key_']);
													
								if(stripos($label,"memory") != '') {						
									$arrSizeMem[] = $label.",".$memSize['value_max'];
								}
						}
				
						if($memUsed['name'] != '') {
							if($memUsed['value_max'] != 0) {
								
								$label = get_item_label($memUsed['key_']);
													
								if(stripos($label,"memory") != '') {						
									$arrUsedMem[] = $label.",".$memUsed['value_max'];
								}	
							}
						}
						
						$zbx_agent = 0;				
					}				
				}
				 
				sort($arrSizeMem);
				sort($arrUsedMem);
				
				//print mem size
				for($n=0;$n<count($arrUsedMem);$n++) {
				
					$u = explode(",",$arrUsedMem[$n]); 		
					
					if($u[0] != 0 || $u[0] != '') {	
						if(strstr($u[0],":") == '') {				
							$arrUsedMem2[] = $u[0].",".$u[1];
						}	
					}
				}
					
					echo "
					<div class='col-md-".$md." col-sm-".$md."'>";
					
					if($arrSizeMem[0] != '') {
							
						//hosts						 				
						if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; } else { $conn = "Online"; $cor = "#4BAC64"; }		
					
						$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
						$IP = DBFetch($dbIP);
						
									
						echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2;'>
								<thead>
									<tr>					
										<th style='background:".$cor."; width:1%;' title='".$conn."'></th>
										<th colspan='1' class='linkb' style='width:50%; font-weight:bold; text-align:left;'><a href='host_detail.php?hostid=".$hosts['hostid']."'> ".$hosts['name']." </a></th>
										<th colspan='1' style='width:18%; text-align:left;'>". $labels['Used'] ."</th>
										<th colspan='1' style='text-align:left;'> ". _('Total') ." </th>
										<th colspan='1' style='text-align:left;'> % ". $labels['Used'] ." </th>
									</tr>
								</thead>
								<tbody>\n"; 										

						//memory size					
						for($n=0;$n<count($arrSizeMem);$n++) {		
							$s = explode(",",$arrSizeMem[$n]);										
							$arrSizeMem2[] = $s[0].",".$s[1];				
						}	
						
						// memory used
						for($n=0;$n<count($arrUsedMem);$n++) {		
							$u = explode(",",$arrUsedMem2[$n]); 															
							$arrUsedMem3[] = $u[0].",".$u[1];				
						}
						
						for($i=0;$i < count($arrUsedMem2);$i++) {
					
							$s = explode(",",$arrSizeMem2[$i]);
							$u = explode(",",$arrUsedMem3[$i]); 									
							
							if($s[1] != 0) {
								if($zbx_agent == 0) {$barra = round((100*($u[1]))/$s[1],1);}	
								if($zbx_agent == 1) {$barra = round((100*($s[1] - $u[1]))/$s[1],1);}	
							}
							else { $barra = 0; }	
							
							$barraValue = $barra;
						
							// cor barra
							if($barra >= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; $barraValue = 100;}
							if($barra >= 80 and $barra <= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
							if($barra >= 61 and $barra <= 79) { $cor = "progress-bar-warning"; $perc_cor = "#fff"; }
							if($barra >= 26 and $barra <= 60) { $cor = " "; $perc_cor = "#fff"; }
							if($barra >= 0 and $barra <= 25) { $cor = "progress-bar-success"; $perc_cor = "#000";}	
							if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }			
				
							if($s[0] != 'total') { $memName = $s[0]; }
							else { $memName = "Memory"; }				
							
							if($zbx_agent == 0) {$usada = formatBytes($u[1],1);}	
							if($zbx_agent == 1) {$usada = formatBytes($s[1] - $u[1],1);}			
											
							echo "<tr style='text-align:left;'>";	
							echo "	<td colspan='2'>". $memName ."</td>";
							echo "	<td colspan='1'>". $usada ."</td>";
							echo "	<td colspan='1'>". formatBytes($s[1],1) ."</td>";
							echo "<td width='15%' style='padding-right:15px; '>
										<div style='font-size:13px; position:absolute; vertical-align:middle; color:".$perc_cor.";'>&nbsp;".$barra."%</div>
										<div class='progress-bar ". $cor ." progress-bar ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='text-align:left; width: ".$barraValue."%;'>&nbsp; </div>
			   					</td>";
							echo "</tr>\n";						
							//}
						}
					}
												
					unset($arrSizeMem);				
					unset($arrSizeMem2);				
					unset($arrUsedMem);				
					unset($arrUsedMem2);
					unset($arrUsedMem3);
									
					echo "</tbody></table>\n";										
					echo "</div>\n";									
				}
			}								
}	
?>