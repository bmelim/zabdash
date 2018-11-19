<?php

require_once 'inc/functions.inc.php';

<<<<<<< HEAD
$zabServer = "10.20.30.40";
$zabUser = "admin";
$zabPass = "zabbix";
$zabURL = "http://10.20.30.40/zabbix/";
=======
$zabServer = "zabbix.example.com";
$zabUser = "Admin";
$zabPass = "zabbix";
$zabURL = "https://zabbix.example.com/zabbix/";
>>>>>>> 1.1.2

$useridlang = get_userid(CWebUser::getSessionCookie());
$lang = get_user_lang($useridlang);

<<<<<<< HEAD
$version = '1.1.0';
=======
$version = '1.1.2';
>>>>>>> 1.1.2

//Translate option: en_US or pt_BR
$labels = include_once 'locales/'.$lang.'.php';

?>
