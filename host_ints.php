<?php
/*ini_set('error_reporting',E_ALL);
ini_set('display_errors',"1");
ini_set('log_errors',"0");*/

// A better way to display SNMP host discovered Interfaces.
// Designed to work best for exactly one host received in the URL, as in:
//    http://apiServer/wherever/thisscript.php?host=targethost
//
// Uses the PhpZabbixApi by confirm IT solutions GmbH

// load ZabbixApi
require_once '../include/config.inc.php';
/*require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';*/

include('config.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

// get the target host or default
if (isset($_REQUEST["host"])) { $theHost = $_REQUEST["host"]; } else { $theHost = "defaultHost"; }

try
{

	$host = $_REQUEST["host"];
	
	$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');
    // connect to Zabbix API
    //$api = new ZabbixApi('http://zabbixServer/zabbix/api_jsonrpc.php', 'zabbixUser', 'zabbixPassword');

// get all items for the host that are Interfaces
    $intItems = $api->itemGet(array(
        'output' => array("key_","name","lastvalue"),
        'hostids' => $host,
        'application' => 'Interfaces'
    ));

    printf("<HTML><BODY>");
    printf("<PRE>");
    $array1 = array();
    $array2 = array();
    // go thru the results and get all the columns (measurement types), rows (interfaces), and last values
    foreach($intItems as $intItem) {
        // printf("key:%s name:%s lastvalue:%s\n", $intItem->key_, $intItem->name, $intItem->lastvalue);
        $key1 = $intItem->key_;
        if ($key1 == 'ifNumber') { continue; }
        $key2 = $intItem->name;
        $key1mod = preg_replace('/.*\[(.*)\]/', '$1', $key1);
        $key2mod = preg_replace('/(.*) o[fn] interface .*/', '$1', $key2);
        // printf("key1='%s' key1mod='%s' key2='%s' key2mod='%s'\n", $key1,$key1mod,$key2,$key2mod);
        if (! array_key_exists($key1mod,$array1)) { $array1[$key1mod] = 1; }
        if (! array_key_exists($key2mod,$array2)) { $array2[$key2mod] = 1; }
        $array3[$key1mod][$key2mod] = $intItem->lastvalue;
    }
    printf("</PRE>\n");

    // print the results table
    printf("<h3>%s</h3>\n", $theHost);
    printf("<TABLE>\n");
    // first print some column headers
    // the Description column is a duplicate of the key (Interface), so drop it
    if (array_key_exists('Description',$array2)) { unset($array2['Description']); }
    $arr2keys = array_keys($array2);
    printf("<TR><TH>Interface");
    foreach ($arr2keys as $arr2key) {
        printf("<TH>%s", $arr2key);
    }
    printf("</TR>\n");
    // loop through the rows (interfaces) and columns (measurement types) and print
    foreach ($array1 as $akey1 => $value1) {
        printf("<TR><TD>%s", $akey1);
        foreach ($array2 as $akey2 => $value2) {
            $thevalue = $array3[$akey1][$akey2];
            printf("<TD>%s", $thevalue);
        }
        printf("</TR>\n");
    }
    printf("</TABLE>\n");
    printf("<BODY><HTML>");
}
catch(Exception $e)
{
    // Exception in ZabbixApi catched
    echo $e->getMessage();
}
?>