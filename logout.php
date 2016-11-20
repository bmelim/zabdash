<?php

require_once '../include/config.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$api->userLogout(array(
	'output' => 'extend'					
));

//setcookie("zbx_sessionid", "", time() - 3600, "/zabbix/zabdash/");
setcookie("zabdash_session", "", time() - 3600, "/");
header("location:index.php");

?>