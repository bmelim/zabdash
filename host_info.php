<?php

 // get all items
 $disks = $api->itemGet(array(
     'output' => 'extend',
     'hostids' => $hostid,
     'search' => array('key_' => 'inbytes')
     
 ));

 // print disks ID with graph name
 foreach($disks as $disk) {    
	              
    $diskSize = get_item_values($disk->itemid, 'hrStorageSizeinBytes');
    $diskUsed = get_item_values($disk->itemid, 'hrStorageUsedinBytes');

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

//CPU Load
 $cpus = $api->itemGet(array(
     'output' => 'extend',
     'hostids' => $hostid,
     'search' => array('key_' => 'processorLoad')     
 ));

 
 foreach($cpus as $cpu) {
   
    $cpuLoad = get_item_values($cpu->itemid, 'processorload');    
   	$arrCPU[] = $cpuLoad['value_max'];             
 }
 
$cpuNum = count($arrCPU); 

if($cpuNum > 0) {
	$avgCPU = round(array_sum($arrCPU)/$cpuNum,1);
}
else {
	$avgCPU = 0;
}	

//uptime
 $times = $api->itemGet(array(
     'output' => 'extend',
     'hostids' => $hostid,
     'search' => array('key_' => 'sysuptime')     
 ));
 
 foreach($times as $t) {      
               
    $time = get_item_values($t->itemid, 'sysuptime');
    if($time['value_max'] != 0) {		       	       
     
   	$arrTime[] = $time['value_max'];         
     } 
 }
 
 
// get all network interfaces
$ifs = $api->itemGet(array(
  'output' => 'extend',
  'hostids' => $hostid,
  'search' => array('key_' => 'if'),
  'sortfield' => 'name'
));

 // print graph ID with graph name
foreach($ifs as $if) {    			            

   $ifSize = get_item_values($if->itemid, 'ifInOctets');
	$ifUsed = get_item_values($if->itemid, 'ifOutOctets');
				
	if($ifSize['value_max'] != '') {			
		$arrIfSize[]= get_item_label($ifSize['key_']).",".$ifSize['value_max'];
	}
	
	if($ifUsed['value_max'] != '') {
		$arrIfUsed[]= get_item_label($ifUsed['key_']).",".$ifUsed['value_max'];
	}

	if($ifSize['key_'] != '' ) {			
		$arrIfDesc[]= get_item_label($ifSize['key_']).",".$ifSize['value_max'];		
	}
			
 }

sort($arrIfSize); 
sort($arrIfUsed); 
sort($arrIfDesc); 


// get all graphs
 $graphs = $api->graphGet(array(
     'output' => 'extend',
     'hostids' => $hostid,
     //'search' => array('key_' => 'if'),
     'sortfield' => 'name'
 ));
 
 ?>