<?php

foreach( $groupID as $g ) {
	
	$dbHosts = DBselect( 'SELECT h.hostid, h.name, h.status, h.available, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags FROM hosts h, hosts_groups hg WHERE hg.groupid = '.$g.' AND h.hostid = hg.hostid ORDER BY h.name ASC'	);
	$dbHostsOk = DBselect( 'SELECT h.hostid, h.name, h.status, h.available, h.snmp_available AS sa, h.snmp_disable_until AS sd, h.flags FROM hosts h, hosts_groups hg WHERE hg.groupid = '.$g.' AND h.hostid = hg.hostid ORDER BY h.name ASC'	);

	//get group name
	$group = get_hostgroup_by_groupid($g);
	$groupName = $group['name'];	


	echo '	
		<div class="col-md-12 col-sm-12">
			<h3 style="color:#000 !important; margin-top:-2 px;"> ' .$groupName.'</h3>
		</div>	';
				
		$md = 12;											

		while ($hosts = DBFetch($dbHosts)) {
			
			if($hosts['status'] == 0 && $hosts['flags'] == 0) {
				
				if($hosts['available'] == 1 && $hosts['sa'] == 0) { $keyValue = 'vm.memory'; }
				else { $keyValue = 'inbytes'; }							
				
				 // get all items
				 $disks = $api->itemGet(array(
				     'output' => 'extend',
				     'hostids' => $hosts['hostid'],
				     'search' => array('key_' => $keyValue)
				 ));
				
				 // print disks ID with graph name
				 foreach($disks as $disk) {    
				
				 	 if($hosts['available'] == 1 && $hosts['sa'] == 0) { $searchValSize = 'total'; $searchValUsed = 'available'; }
					 else { $searchValSize = 'hrStorageSizeinBytes'; $searchValUsed = 'hrStorageUsedinBytes'; }
									           
				    $diskSize = get_item_values($disk->itemid, $searchValSize);
				    $diskUsed = get_item_values($disk->itemid, $searchValUsed);
				
					//Size
					if(strchr(get_item_label($diskSize['key_']),":") != '') {
						if($diskSize['value_max'] != 0) {												
							$arrSize[]= get_item_label($diskSize['key_']).",".$diskSize['value_max'];
						}
					}		

					else {				
						//if($diskSize['value_max'] != 0) {
						if($diskSize['value_max'] != 0 || get_item_label($diskSize['key_']) != '') {						
							$arrSize[]= get_item_label($diskSize['key_']).",".$diskSize['value_max'];
						}
					}
					
					//Used
					if(strchr(get_item_label($diskUsed['key_']),":") == '') {
						if($diskUsed['name'] != '') {
							$arrUsed[]= get_item_label($diskUsed['key_']).",".$diskUsed['value_max'];						
						}
					}
					else {
						if($diskUsed['name'] != '') {
							if($diskUsed['value_max'] != 0) {
								$arrUsed[]= get_item_label($diskUsed['key_']).",".$diskUsed['value_max'];
							}
						}
					}
							
				 }
				 
					sort($arrSize);
					sort($arrUsed);
					

						if($arrSize[0] != '') {
							
						//hosts
						 				
						if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; } else { $conn = "Online"; $cor = "#4BAC64"; }		
					
						$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
						$IP = DBFetch($dbIP);
						
						echo "
						<div class='col-md-".$md." col-sm-".$md."'>";
									
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
										
		
						for($i=0;$i<count($arrUsed);$i++) {
						
							$s = explode(",",$arrSize[$i]);
							$u = explode(",",$arrUsed[$i]); 
							
							if($s[0] == 'total')  { $itemLabel = 'total'; $value = 0; $label = 'Memory'; }
							else  { $itemLabel = 'Memory'; $value = true; $label = $s[0]; }
																							
								
							if( stripos($s[0], $itemLabel) == $value) {
														
												
								if($s[1] != 0) {
									$barra = round((100*$u[1])/$s[1],1);	
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
												
								echo "<tr style='text-align:left;'>";	
								echo "	<td colspan='2'>". $label ."</td>";
								echo "	<td colspan='1'>". formatBytes($u[1],1) ."</td>";
								echo "	<td colspan='1'>". formatBytes($s[1],1) ."</td>";
								echo "<td width='15%' style='padding-right:15px; '>
											<div style='font-size:13px; position:absolute; vertical-align:middle; color:".$perc_cor.";'>&nbsp;".$barra."%</div>
											<div class='progress-bar ". $cor ." progress-bar ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='text-align:left; width: ".$barraValue."%;'>&nbsp; </div>
				   					</td>";
								echo "</tr>\n";						
							}
						}
					}
						
						
						unset($arrSize);				
						unset($arrUsed);				
						unset($arrUsed2);
										
						echo "</tbody></table>\n";										
						echo "</div>\n";									
						}
					}				
				
}	
?>