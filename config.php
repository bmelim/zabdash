<?php

require_once 'inc/functions.inc.php';

$zabServer = "zabbix.mpro.mp.br";
$zabUser = "deare";
$zabPass = ".#deare#.";
$zabURL = "https://zabbix.mpro.mp.br/zabbix/";

$useridlang = get_userid(CWebUser::getSessionCookie());
$lang = get_user_lang($useridlang);

$version = '1.1.3';

//Translate option: en_US or pt_BR
$labels = include_once 'locales/'.$lang.'.php';

?>
