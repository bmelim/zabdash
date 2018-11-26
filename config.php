<?php

require_once 'inc/functions.inc.php';

$zabServer = "zabbix.example.com";
$zabUser = "Admin";
$zabPass = "zabbix";
$zabURL = "https://zabbix.example.com/zabbix/";

$useridlang = get_userid(CWebUser::getSessionCookie());
$lang = get_user_lang($useridlang);

$version = '1.1.4';

//Translate option: en_US or pt_BR
$labels = include_once 'locales/'.$lang.'.php';

?>
