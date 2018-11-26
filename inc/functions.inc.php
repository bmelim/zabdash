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


function hostStatus($stat,$ms) {

	if($stat == 0 && $ms == 0) {
		$status = "<span class='label label-success'>". _('Enabled')."</span>\n";	
	}	
	if($stat == 1) {
		$status = "<span class='label label-danger'>". _('Disabled')."</span>\n";
	}

	if($stat == 0 && $ms == 1) {
		$status = "<span class='label label-warning'>". _('Maintenance')."</span>\n";
	}
	
return $status;

}


function set_status($value) {

	$status = $value;
	
	switch ($status) {
	 case "0": $status = "<span class='label label-success'>&nbsp;&nbsp;&nbsp;&nbsp;". _('OK')."&nbsp;&nbsp;&nbsp;&nbsp;</span>\n"; break;
	 case "1": $status = "<span class='label label-danger'>". _('Problem')."</span>\n"; break;

	 }
	 
	 return $status;
}


function hostIP($hostid) {
	$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$hostid);
	$IP = DBFetch($dbIP);
	return $IP;
}



function getOS($hostid) {

	$dbHosts = DBselect( 'SELECT hostid, os, os_full, os_short, hardware FROM host_inventory WHERE hostid ='.$hostid.'');
	$dbOS = DBFetch($dbHosts);	

	$osShort = $dbOS['os_short'];
	
	if($osShort !== '') {
		
		strtolower($osShort);
		
		$arrOS = array('ubuntu','suse','opensuse','debian','slackware','redhat','freebsd','centos','3COM','H3C');
		
		foreach($arrOS as $v) {
		
			if (strpos($osShort, $v) !== false) {	
				$OS = $v;
			}
		} 
				
	}	 
	
	elseif($osShort == '') {
			
		$os = $dbOS['os'];
		$osFull = $dbOS['os_full'];
		$hard = $dbOS['hardware'];
		
		$arrOS = array('Linux','Windows','VMware','Cisco','H3C','HP','3COM','Dell');	
		
		foreach($arrOS as $v) {
			
			if (strpos($hard, $v) !== false || strpos($osFull, $v) !== false) {	
				$OS = $v;
			}
		} 
	}
		
	if($OS != '') {
		return strtolower($OS);	
	}
	
	else {
		$OS = 'none';
		return ;
	}
		
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

function get_item_values_storage($itemid, $key) {

	$dbItems = DBselect("SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num,t.value_min,t.value_avg,t.value_max,from_unixtime(t.clock)			
				FROM items i, trends_uint t, hosts h
				WHERE `key_` NOT LIKE '%hrStorageUsedInBytesPerc%'
				AND `key_` LIKE '%hrStorageUsedInBytes%'  				
				AND t.itemid=i.itemid			
				AND i.hostid=h.hostid	  	   	
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


function get_item_values_or($itemid, $key, $key1) {

	$dbItems = DBselect("SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num,t.value_min,t.value_avg,t.value_max,from_unixtime(t.clock)			
				FROM items i, trends_uint t, hosts h
				WHERE (i.key_ LIKE '%".$key."%' OR i.key_ LIKE '%".$key1."%')
				AND i.hostid=h.hostid	
				AND t.itemid=i.itemid	  	   	
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



function zbx_get_item_values($itemid, $key) {

	$dbItems = DBselect("SELECT i.itemid,i.name,i.key_,i.status,i.type,t.value AS value_max,from_unixtime(t.clock)			
				FROM items i, history t, hosts h
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


function get_hostname($hostid) {

	$dbHosts = DBselect( 'SELECT hostid, name FROM hosts WHERE hostid ='.$hostid.'');
	$dbName = DBFetch($dbHosts);	
	
	if($dbName['name'] != '' ) {
		$hostName = $dbName['name'];
	}
	else {
		$hostName = '';
	}
	
	return $hostName;
}



function get_userid($session) {

	$dbUser = DBselect( 'SELECT sessionid, userid, lastaccess FROM sessions WHERE sessionid ="'.$session.'"');
	$dbID = DBFetch($dbUser);	
	$userid = $dbID['userid'];

	return $userid;
}


function get_user_lang($userid) {

	$dbUser = DBselect( 'SELECT lang FROM users WHERE userid ="'.$userid.'"');
	$dbLang = DBFetch($dbUser);	
	
	if($dbLang['lang'] == 'pt_BR' ) {
		$userLang = $dbLang['lang'];
	}
	else {
		$userLang = 'en_US';
	}
	
	return $userLang;
}


//convert date and time
function from_epoch($epoch) {
    return date('d/m/Y H:i', $epoch); // output as RFC 2822 date - returns local time 	
}	

//convert only date
function from_epoch_date($epoch) {
    return date('d/m/Y', $epoch); // output as RFC 2822 date - returns local time 	
}


function get_severity($value) {

	$severity = $value;
	
	switch ($severity) {
		 case "0": $severity = 'Not classified'; break;
		 case "1": $severity = 'Information'; break;
		 case "2": $severity = 'Warning'; break;
		 case "3": $severity = 'Average'; break;
		 case "4": $severity = 'High'; break;
		 case "5": $severity = 'Disaster'; break; 
	}
	 
	 return $severity;
}


function percent ( $value, $total ) {
	return round(( $value * 100 ) / $total,1);
}


function color_bar($value) {

	$barra = $value;	
	$barraValue = $barra;

	// cor barra disks
	if($barra >= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; $barraValue = 100;}
	if($barra >= 80 and $barra <= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
	if($barra >= 61 and $barra <= 79) { $cor = "progress-bar-warning"; $perc_cor = "#fff"; }
	if($barra >= 26 and $barra <= 60) { $cor = " "; $perc_cor = "#fff"; }
	if($barra >= 0 and $barra <= 25) { $cor = "progress-bar-success"; $perc_cor = "#000";}	
	if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }
	
	$arrBar = array($barra,$cor,$perc_cor);	
	return $arrBar;	
														
}


function to_timestamp_ini($date) {

	//$date1 = $arr_days[0];
	list($day, $month, $year) = explode('/', $date);
	$timeStamp = mktime(0, 0, 0, $month, $day, $year);
	//echo $timeStamp;
	return $timeStamp;
}


function to_timestamp_fin($date) {

	//$date1 = $arr_days[0];
	list($day, $month, $year) = explode('/', $date);
	$timeStamp = mktime(23, 59, 59, $month, $day, $year);
	//echo $timeStamp;
	return $timeStamp;
}


//get recovery event id
function get_revent($eventid) {

	$dbGetEvent = DBselect( 'SELECT r_eventid FROM event_recovery WHERE eventid ="'.$eventid.'"');
	$dbevent = DBFetch($dbGetEvent);
	
	$r_event = $dbevent['r_eventid'];	
	
	return $r_event;
}


//event time
function event_time($eventid) {

	$dbGetEvent = DBselect( 'SELECT clock FROM events WHERE eventid ="'.$eventid.'"');
	$dbeventtime = DBFetch($dbGetEvent);		
	$eventtime = $dbeventtime['clock'];	
	return $eventtime;
}

//recovery event time
function revent_time($reventid) {

	$dbGetEvent = DBselect( 'SELECT clock FROM events WHERE eventid ="'.$reventid.'"');
	$dbreventtime = DBFetch($dbGetEvent);	
	
	if($dbreventtime['clock'] != '') {
		$reventtime = $dbreventtime['clock'];
	}
	else {
		$reventtime = '';
	}
	
	return $reventtime;
}


?>
