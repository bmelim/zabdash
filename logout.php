<?php

require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

setcookie("zbx_sessionid", "", time() - 3600, "/zabbix/zabdash/");
redirect('../index.php');


?>