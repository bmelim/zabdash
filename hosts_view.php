<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');
//include('inc/functions.inc.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');


if(isset($_REQUEST['groupid']) && $_REQUEST['groupid'] != '' && $_REQUEST['groupid'] != 0) {
	
	$include = "1";
	$groupID = $_REQUEST['groupid'];
		
}

else {
	$include = "0";		
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv='refresh' content='90'>

<title>Zabbix Hosts</title>

<!-- Bootstrap -->
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script src="js/media/js/jquery.dataTables.min.js"></script>
<link href="js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
<script src="js/media/js/dataTables.bootstrap.js"></script>

<script src="js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="js/media/pdfmake.min.js"></script>
<script src="js/media/vfs_fonts.js"></script>
<script src="js/media/jszip.min.js"></script>

<script src="js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

</head>

<body>

<div class="row col-md-12 col-sm-12" style="margin-top:40px; margin-bottom: 0px; float:none; margin-right:auto; margin-left:auto; text-align:center;">

	<?php	
	
	if($include == 0) {
		include('all_hosts.php');
	}
		
	
	if($include == 1) {
		include('group_hosts.php');
	}	
				
?>

</div>	
</body>
</html>
