<?php
	
$dbHosts = DBselect( 'SELECT hostid, name, status, available, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts ORDER BY name ASC'	);	

//get group name
$groupName = $labels['Hosts Storage'];

echo '	
	<div class="col-md-12 col-sm-12">
		<h3 style="color:#000 !important; margin-top:15px; margin-bottom:15px;"> ' .$groupName.'</h3>
	</div>	';
				
$md = 12;											

while ($hosts = DBFetch($dbHosts)) {
		
	if($hosts['status'] == 0 && $hosts['flags'] == 0) {
		
		if($hosts['available'] == 1 ) { $keyValue = 'vfs.fs.size'; }
		else { $keyValue = 'inbytes'; }						
		
		 // get all items
		 $disks = $api->itemGet(array(
		     'output' => 'extend',
		     'hostids' => $hosts['hostid'],
		     'search' => array('key_' => $keyValue)
		 ));
		
		 // print disks ID with graph name
		foreach($disks as $disk) {    
		 				 	
		 	if($hosts['available'] == 1 ) { $searchValSize = 'total'; $searchValUsed = 'used'; }
			else { $searchValSize = 'hrStorageSizeinBytes'; $searchValUsed = 'hrStorageUsedinBytes'; }
							           
		   $diskSize = get_item_values($disk->itemid, $searchValSize);
		   $diskUsed = get_item_values($disk->itemid, $searchValUsed);
		
			//Size
			if(strchr(get_item_label($diskSize['key_']),"A:") != '') {
				if($diskSize['value_max'] != 0) {							
					$arrSize[]= get_item_label($diskSize['key_']).",".$diskSize['value_max'];
				}
			}		
		
			else {						
				if($diskSize['value_max'] != 0 || get_item_label($diskSize['key_']) != '') {						
					$arrSize[]= get_item_label($diskSize['key_']).",".$diskSize['value_max'];
				}
			}
			
			//Used
			if(strchr(get_item_label($diskUsed['key_']),"A:") == '') {
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
		
		//print disks size
		for($n=0;$n<count($arrUsed);$n++) {
		
			$u = explode(",",$arrUsed[$n]); 		
			
			if($u[0] != 0 || $u[0] != '') {	
				if(strchr($u[0],":") == '') {				
					$arrUsed2[] = $u[0].",".$u[1];
				}	
			}
		}

			if($arrSize[0] != '') {
					
				//hosts 				
				if($hosts['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; } else { $conn = "Online"; $cor = "#4BAC64"; }				
			
				$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hosts['hostid']);
				$IP = DBFetch($dbIP);
				
				echo "<div class='hostdivx col-md-".$md." col-sm-".$md."' style='margin-bottom:0px;'>";
							
				echo "<table class='tabs box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2; '>
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
						
					if(isset($u[2])) {
						$s[1] = $s[2];						
						$u[1] = $u[2];
					}						
				
					if( stripos($s[0] , 'Memory') != true ) {
						
						if($s[1] != 0) {
							$barra = round((100*$u[1])/$s[1],1);	
						}
						else { $barra = 0; }	
					
						// cor barra
						if($barra >= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
						if($barra >= 80 and $barra <= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
						if($barra >= 61 and $barra <= 79) { $cor = "progress-bar-warning"; $perc_cor = "#fff"; }
						if($barra >= 26 and $barra <= 60) { $cor = " "; $perc_cor = "#fff"; }
						if($barra >= 0 and $barra <= 25) { $cor = "progress-bar-success"; $perc_cor = "#000";}	
						if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }			
										
						echo "<tr style='text-align:left;'>";	
						echo "	<td colspan='2'>". $s[0] ."</td>";
						echo "	<td colspan='1'>". formatBytes($u[1],1) ."</td>";
						echo "	<td colspan='1'>". formatBytes($s[1],1) ."</td>";
						echo "<td width='15%' style='padding-right:15px; '>
									<div style='font-size:13px; position:absolute; vertical-align:middle; color:".$perc_cor.";'>&nbsp;".$barra."%</div>
									<div class='progress-bar ". $cor ." progress-bar ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='text-align:left; width: ".$barra."%;'>&nbsp; </div>
		   					</td>";
						echo "</tr>\n";	
											
					}
				}
				
				unset($arrSize);				
				unset($arrUsed);				
				unset($arrUsed2);
								
				echo "</tbody></table>\n";
				echo "</div>\n";	
				echo "<div style='margin-bottom:60px;'></div>\n";								
			}
		}
	}
?>
