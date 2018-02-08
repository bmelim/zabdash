<?php

require_once 'inc/functions.inc.php';

$zabServer = "10.20.30.40";
$zabUser = "admin";
$zabPass = "zabbix";
$zabURL = "http://10.20.30.40/zabbix/";

$useridlang = get_userid(CWebUser::getSessionCookie());
$lang = get_user_lang($useridlang);

$version = '1.1.0';

//Translate option: en_US or pt_BR
$labels = include_once 'locales/'.$lang.'.php';

?>
