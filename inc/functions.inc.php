<?php

//Translate
//$labels = include_once 'locales/pt.php';

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

	$dbHosts = DBselect( 'SELECT hostid, os, os_full, hardware FROM host_inventory WHERE hostid ='.$hostid.'');
	$dbOS = DBFetch($dbHosts);	
	
	$os = $dbOS['os'];
	$osFull = $dbOS['os_full'];
	$hard = $dbOS['hardware'];
	
	$arrOS = array('Linux','Windows','VMware','Cisco','H3C','HP','3Com','Dell');	
	
	foreach($arrOS as $v) {
		
		if (strpos($hard, $v) !== false || strpos($osFull, $v) !== false) {	
			$OS = $v;
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


//convert date and time
function from_epoch($epoch) {
    return date('d/m/Y H:i', $epoch); // output as RFC 2822 date - returns local time 
	
}	


function get_severity($value) {

	$severity = $value;
	
	switch ($severity) {
	 case "1": $severity = 'Information'; break;
	 case "2": $severity = 'Warning'; break;
	 case "3": $severity = 'Average'; break;
	 case "4": $severity = 'High'; break;
	 case "5": $severity = 'Disaster'; break; 
	 }
	 
	 return $severity;

}

function getLang() {

	$dbLang = DBselect( 'SELECT lang FROM users WHERE userid = 3');
	$getLang = DBFetch($dbLang);	
	//$lang = $dbLang['lang'];
	$lang = "pt_BR";
	
	$_SESSION['zablang'] = $lang;
	
	//return $_SESSION['zablang'];

}


/// Translation functions
/// since version 0.84

/**
 * For translation
 *
 * @param $str      string
 * @param $domain   string domain used (default is glpi, may be plugin name)
 *
 * @return translated string
**/
function __($str) {
   global $TRANSLATE;

   if (is_null($TRANSLATE)) { // before login
      return $str;
   }
   $trans = $TRANSLATE->translate($str);
   // Wrong call when plural defined
   if (is_array($trans)) {
      return $trans[0];
   }
   return  $trans;
}


/**
 * For translation
 *
 * @param $str      string
 * @param $domain   string domain used (default is glpi, may be plugin name)
 *
 * @return protected string (with htmlentities)
**/
function __s($str) {
   return htmlentities(__($str), ENT_QUOTES, 'UTF-8');
}


/**
 * For translation
 *
 * @since version 0.84
 *
 * @param $ctx       string    context
 * @param $str       string   to translate
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return protected string (with htmlentities)
**/
function _sx($ctx, $str) {
   return htmlentities(_x($ctx, $str), ENT_QUOTES, 'UTF-8');
}


/**
 * to delete echo in translation
 *
 * @param $str      string
 * @param $domain   string domain used (default is glpi, may be plugin name)
 *
 * @return echo string
**/
function _e($str) {
   echo __($str);
}


/**
 * For translation
 *
 * @param $sing      string in singular
 * @param $plural    string in plural
 * @param $nb               to select singular or plurial
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return translated string
**/
/*
function _n($sing, $plural, $nb) {
   global $TRANSLATE;

   return $TRANSLATE->translatePlural($sing, $plural, $nb);
}
*/

/**
 * For translation
 *
 * @since version 0.84
 *
 * @param $sing      string in singular
 * @param $plural    string in plural
 * @param $nb               to select singular or plurial
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return protected string (with htmlentities)
**/
function _sn($sing, $plural, $nb) {
   global $TRANSLATE;

   return htmlentities(_n($sing, $plural, $nb), ENT_QUOTES, 'UTF-8');
}


/**
 * For context in translation
 *
 * @param $ctx       string   context
 * @param $str       string   to translate
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return string
**/
/*
function _x($ctx, $str) {

   // simulate pgettext
   $msg   = $ctx."\004".$str;
   $trans = __($msg);

   if ($trans == $msg) {
      // No translation
      return $str;
   }
   return $trans;
}
*/

/**
 * Echo for context in translation
 *
 * @param $ctx       string   context
 * @param $str       string   to translated
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return string
**/
function _ex($ctx, $str) {

   // simulate pgettext
   $msg   = $ctx."\004".$str;
   $trans = __($msg);

   if ($trans == $msg) {
      // No translation
      echo $str;
   }
   echo $trans;
}


/**
 * For context in plural translation
 *
 * @param $ctx       string   context
 * @param $sing      string   in singular
 * @param $plural    string   in plural
 * @param $nb                 to select singular or plurial
 * @param $domain    string domain used (default is glpi, may be plugin name)
 *
 * @return string
**/
/*
function _nx($ctx, $sing, $plural, $nb) {

   // simulate pgettext
   $singmsg    = $ctx."\004".$sing;
   $pluralmsg  = $ctx."\004".$plural;
   $trans      = _n($singmsg, $pluralmsg, $nb);

   if ($trans == $singmsg) {
      // No translation
      return $sing;
   }
   if ($trans == $pluralmsg) {
      // No translation
      return $plural;
   }
   return $trans;
}

*/



/*
    $epoch = 1340000000;
    echo date('r', $epoch); // output as RFC 2822 date - returns local time
    echo gmdate('r', $epoch); // returns GMT/UTC time

    $epoch = 1344988800; 
    $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
    echo $dt->format('Y-m-d H:i:s'); // output = 2012-08-15 00:00:00 


function get_item_by_itemid_disk1($itemid) {
	$row = DBfetch(DBselect(
		'SELECT i.itemid,i.name,i.key_,i.status,i.type,t.num, t.value_min,'. 
		't.value_avg,t.value_max,from_unixtime(t.clock)'.			
		'FROM items i, trends_uint t, hosts h'.
		'WHERE i.hostid=h.hostid'.
		'AND t.itemid=i.itemid'.		  	   	
		'AND i.itemid=27435'.
		'ORDER BY t.clock DESC'.
		'LIMIT 1'));
	if ($row) {
		return $row;
	}
	error(_s('No item with itemid "%1$s".', $itemid));
	return false;
}
*/

/*

SELECT i.itemid,i.name,i.key_,i.delay,i.history,i.status,i.type,t.num, t.value_min, 
t.value_avg,t.value_max,from_unixtime(t.clock)			
FROM items i, trends_uint t, hosts h
WHERE i.itemid=27435
AND i.hostid=h.hostid
AND t.itemid=i.itemid
AND t.value_max > 0   	   	
ORDER BY t.clock DESC
LIMIT 1
 		
 		
function get_item_by_itemid_disk($itemid) {
	$row = DBfetch(DBselect(
		'SELECT i.itemid,i.name,i.key_,i.delay,i.history,i.status,i.type,'.
			'i.value_type,i.data_type,i.logtimefmt,'.			
			'i.valuemapid,i.delay_flex,i.params,i.ipmi_sensor,i.flags,i.description,i.inventory_link,'.
			't.num, t.value_min, t.value_avg,t.value_max,from_unixtime(t.clock)'.			
		' FROM items i, trends_uint t, hosts h'.
		' WHERE i.itemid=27435'.
		'AND i.hostid=h.hostid'.
		'AND t.itemid=i.itemid'.
   	'AND t.value_max > 0'.   	   	
 		'ORDER BY t.clock DESC'.
 		'LIMIT 5'));
	if ($row) {
		return $row;
	}
	error(_s('No item with itemid "%1$s".', $itemid));
	return false;
}

*/

?>