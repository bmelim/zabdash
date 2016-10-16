<?php


function formatBytesx($size, $precision = 0)
{
    $base = log($size, 1024);
    $suffixes = array('B','KB','MB', 'GB', 'TB');   

    $result = round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    
    if(stripos($result, 'NAN ')) {
    	return 0;
    }
    else {
    	return $result;	
    }    
}

function formatBytes($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' ';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

function formatBytesNet($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' ';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' Kb';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' Mb';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' Gb';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' Tb';
    } else {
        return $bytes . ' b';
    }
}


function hostStatus($stat) {

	if($stat == 0) {
		$status = "<span class='label label-success'>Ativo</span>\n";	
	}	
	else {
		$status = "<span class='label label-danger'>Inativo</span>\n";
	}

return $status;

}


function getOS($hostid) {

	$dbHosts = DBselect( 'SELECT hostid, os, os_full FROM host_inventory WHERE hostid ='.$hostid.'');
	$dbOS = DBFetch($dbHosts);	
	
	$os = $dbOS['os'];
	$osFull = $dbOS['os_full'];
	
	if (strpos($os, 'Windows') !== false || strpos($osFull, 'Windows') !== false) {
	    $SO = 'Windows';		   
	}
	
	elseif (strpos($os, 'Linux') !== false || strpos($osFull, 'Linux') !== false) {
	    $SO = 'Linux';	    
	}
	
	elseif (strpos($os, 'VMware') !== false || strpos($osFull, 'VMware') !== false) {
	    $SO = 'vmware';	    
	}	
		
	else {		
		$SO = "none";
	}
	
	return $SO;
}


function getOS1($hostid) {

	$dbHosts = DBselect( 'SELECT hostid, os, os_full FROM host_inventory WHERE hostid ='.$hostid.'');
	$dbOS = DBFetch($dbHosts);	
	
	$os = $dbOS['os'];
	$osFull = $dbOS['os_full'];
	
	if (strpos($os, 'Windows') !== false || strpos($osFull, 'Windows') !== false) {
	    $SO = 'Windows';		   
	}
	
	elseif (strpos($os, 'Linux') !== false || strpos($osFull, 'Linux') !== false) {
	    $SO = 'Linux';	    
	}
	
		elseif (strpos($os, 'VMware') !== false || strpos($osFull, 'VMware') !== false) {
	    $SO = 'vmware';	    
	}	
		
	else {		
		$SO = "none";
	}
	
	return $SO;
}


function get_item_by_itemid_disk($itemid) {
	$row = DBfetch(DBselect(
		'SELECT i.itemid,i.name,i.key_,i.status,i.type,i.flags'.			
		' FROM items i'.
		' WHERE i.itemid='.$itemid));
	if ($row) {
		return $row;
	}
	error(_s('No item with itemid "%1$s".', $itemid));
	return false;
}


function get_item_label($key) {
	
	$keyname = trim(strstr(strstr($key, '['), ']', true), '[]');
	return $keyname;
}

//SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num, t.value_min, t.value_avg,t.value_max,from_unixtime(t.clock)
//SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num, MAX(t.value_min), Mt.value_avg),MAX(t.value_max) AS value_max,from_unixtime(MAX(t.clock))

function get_item_values($itemid, $key) {

	$dbItems = DBselect("SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num,t.value_min,t.value_avg,t.value_max,from_unixtime(t.clock)			
				FROM items i, trends_uint t, hosts h
				WHERE i.hostid=h.hostid
				AND t.itemid=i.itemid			
				AND i.key_ LIKE '%".$key."%'		  	   	
				AND i.itemid=".$itemid."
				ORDER BY t.clock DESC			
				LIMIT 1");
	
	$row = DBFetch($dbItems);		
	
	if ($row) {
		return $row;
	}
	else {
		return false;
	}
}


function time_ext($date) {

$time = $date; // time duration in seconds

 if ($time == 0){
        return '';
    }

	$days = floor($time / (60 * 60 * 24));
	$time -= $days * (60 * 60 * 24);
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


function get_host_name($hostid) {

	$dbHosts = DBselect( 'SELECT hostid, name FROM host_inventory WHERE hostid ='.$hostid.'');
	$dbName = DBFetch($dbHosts);	
	
	if($dbName['name'] != '' ) {
		$hostName = $dbName['name'];
	}
	else {
		$hostName = '';
	}
	
	return $hostName;
}


function from_epoch($epoch) {

    return date('d-m-y H:i', $epoch); // output as RFC 2822 date - returns local time 
	
}	

/*

function getLang() {

	$dbLang = DBselect( 'SELECT lang FROM users WHERE userid = 3');
	$getLang = DBFetch($dbLang);	
	//$lang = $dbLang['lang'];
	$lang = "pt_BR";
	
	$_SESSION['zablang'] = $lang;
	
	//return $_SESSION['zablang'];

}
*/

?>