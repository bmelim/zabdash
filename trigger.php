<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';
require_once '../include/items.inc.php';

include('config.php');
include('inc/functions.inc.php');

//include('inc/functions.inc.php');
require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

//$hostid = 10143;
$hostid = $_REQUEST['hostid'];

 // get all graphs named "CPU"
	$trigger = $api->triggerGet(array(
		'output' => 'extend',
		/*'hostids' => $hostid,*/
		'sortfield' => 'priority',
		'sortorder' => 'DESC',
		'only_true' => '1',
		'active' => '1', // include trigger state active not active
		'withUnacknowledgedEvents' => '1', 
		'expandDescription' => '1',
		'selectHosts' => 1
		//'countOutput' => '1'							
	));	
	
//print_r($trigger);	
	
 // print graph ID with graph name
 foreach($trigger as $disk) {    
			            
	echo $disk->triggerid.",";				            			            
	echo $disk->priority.",";				            
	echo get_hostname($disk->hosts[0]->hostid).",";				            
	echo $disk->description.",";				            
	echo from_epoch($disk->lastchange)."<br>";				            

		
 }
 
echo "conta: ".count($trigger); 


ksort($arrSize);
ksort($arrUsed);
sort($arrDesc);


/*
$diff = array_diff_key($arrSize,$arrUsed);
print_r($diff);
echo "<br><p>"; 

$int = array_intersect_key($arrSize,$arrUsed);
print_r($int);
echo "<br><p>"; 

$arrUsed2 = array_merge($arrUsed,$diff);
sort($arrUsed2);
print_r($arrUsed2);
echo "<br><p>";
*/
//$arrKeys = array_keys($arrUsed2);
//print_r($arrKeys);

echo "<br><p>";


/*
echo "<br><p>";
print_r($arrDesc);
echo "Desc <br><p>";

print_r($arrSize);
echo "Size <br><p>";
print_r($arrUsed);
echo "used<br><p>";
*/


//CPU Load
 $cpus = $api->itemGet(array(
     'output' => 'extend',
     'hostids' => $hostid,
     'search' => array('key_' => 'processor')     
 ));

 
 foreach($cpus as $cpu) {
   
    $cpuLoad = get_item_values($cpu->itemid, 'processor');    
   	$arrCPU[] = $cpuLoad['value_max'];             
 }
 
$cpuNum = count($arrCPU); 

if($cpuNum > 0) {
	$avgCPU = round(array_sum($arrCPU)/$cpuNum,1);
}
else {
	$avgCPU = 0;
}	

//print_r($arrCPU);

/*
for($n=0;$n<count($arrUsed);$n++) {

	//$s = explode(",",$arrSize[$n]);
	$u = explode(",",$arrUsed[$n]); 		
	
//echo strpos($u[0],":\\");	
	
	if($u[0] != 0 || $u[0] != '') {	
		if(strchr($u[0],":") == '') {				
			$arrUsed2[] = $u[0].",".$u[1];
		}	
	}
}

//echo strpos("A:\\",":");

print_r($arrUsed2);
echo "used2 <br><p>";

for($n=0;$n<count($arrUsed2);$n++) {

	$s = explode(",",$arrSize[$n]);
	$u = explode(",",$arrUsed2[$n]); 		
	
	echo $s[0]." ".$s[1]." ".$u[1]."<br>";
}

echo "result 1 <br><p>";

//memoria

for($n=0;$n<count($arrSize);$n++) {

	$s = explode(",",$arrSize[$n]);
	//$u = explode(",",$arrUsed[$n]); 			
	
	//if($u[0] != 0 || $u[0] != '') {
	if( stripos($s[0] , 'Memory') == true ) {					
		$arrSize2[] = $s[0].",".$s[1];	
	}
//	}
}

print_r($arrSize2);
echo "size 2<br><p>";


for($n=0;$n<count($arrUsed);$n++) {

	//$s = explode(",",$arrSize[$n]);
	$u = explode(",",$arrUsed2[$n]); 			
	
	//if($u[0] != 0 || $u[0] != '') {
	if( stripos($u[0] , 'Memory') == true ) {					
		$arrUsed3[] = $u[0].",".$u[1];	
	}
//	}
}

print_r($arrUsed3);
echo "used 3<br><p>";

for($i=0;$i < count($arrUsed2);$i++) {

	$s = explode(",",$arrSize2[$i]);
	$u = explode(",",$arrUsed3[$i]); 
		
		
	if( stripos($s[0] , 'Memory') == true ) {
						
		echo $s[0]." ".$s[1]." ".$u[1]."<br>";					
	}
}

echo "result 2 <br><p>";

*/
/*
for($i=0; $i<count($arrUsed2); $i++){
    for($j=0; $j<count($arrSize); $j++){
        if(key($arrUsed2[$i]) == key($arrSize[$j])){
        		echo $arrSize[$j]." Disk size: ".$arrSize[$j]." Disk used: ".$arrUsed2[$i]."<br>";					
			}		
			else {
				echo $arrSize[$j]." Disk size: ".$arrSize[$j]." Disk used: 0 <br>";								
			}        	
        	
        }
     }
*/     
 //}

//print_r(array_merge($arrSize,$arrUsed));

/*
foreach($arrUsed as $k){
	
	$a = explode(",",$k);
	echo $a[0]." ".$a[1]." ".$a[2]."<br>";		
}

foreach($arrSize as $k){
	
	$a = explode(",",$k);
	echo $a[0]." ".$a[1]." ".$a[2]."<br>";		
}

*/


/*
foreach($arrSize as $x){
	
	$s = explode(",",$x);
	
	foreach($arrUsed as $z){	
		$u = explode(",",$z);				
	}
	echo $s[0]." ".$s[1]." ".$s[2]." ".$u[2]."<br>";		
}	
*/	
//}

//function get_items_keys($keys) {


/*

27422,27423,27424,27425,27427,27426,27434,27435,27436,27437,27439,27438

 // get all graphs named "CPU"
 $trends = $api->trendGet(array(
     'output' => 'extend',
     'itemids' => '27434',
     'limit' => 1
 ));

 // print graph ID with graph name
 foreach($trends as $trend) {
    //printf("itemid:%d name:%s <br>\n", $disk->itemid, $graph->name);
    printf("itemid:%d <br>\n", $trend->itemid);
    //print_r($trend->itemid);
    echo "<br><p>";
 }

*/

/*

//trends DB

select i.itemid,from_unixtime(MAX(t.clock)),t.num,t.value_min,t.value_avg,t.value_max,i.key_,i.name		
  from hosts h,
       items i,
       trends_uint t
 where i.hostid=h.hostid
   and t.itemid=i.itemid
   and t.value_max > 0
   and i.itemid=27435
   and i.key_ LIKE 'hrStorageusedinbytes%'
 order by t.clock desc
 limit 1


SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num, t.value_min, t.value_avg,t.value_max,from_unixtime(t.clock)			
FROM items i, trends_uint t, hosts h
WHERE i.hostid=h.hostid
AND t.itemid=i.itemid		  	   	
AND i.hostid = 10143
GROUP BY t.clock
ORDER BY t.clock DESC
LIMIT 100


memoria

SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num, t.value_min, t.value_avg,t.value_max,from_unixtime(MAX(t.clock))			
			FROM items i, trends_uint t, hosts h
			WHERE i.hostid=h.hostid
			AND t.itemid=i.itemid
			AND i.key_ LIKE '%Bytes[Physical Memory%'		  	   	
			AND i.hostid=10143
			ORDER BY t.clock DESC
         LIMIT 2
         
         
SELECT i.itemid,i.key_,i.status,i.type,t.value_min, t.value_avg,t.value_max,from_unixtime(MAX(t.clock)) 
FROM items i, trends_uint t, hosts h 
WHERE i.hostid=h.hostid 
AND t.itemid=i.itemid 
AND t.value_max <> 0 
AND i.key_ LIKE '%inBytes%' 
AND i.hostid=10143 
GROUP BY i.itemid 
ORDER BY `i`.`key_` ASC      
         
vale do vento, kaguia, vidas ao vento, 
professor de piano, bennys video

*/

?>