<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');
include('inc/functions.inc.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$groupID = array();

if(isset($_REQUEST['groupid']) && $_REQUEST['groupid'] != '' && $_REQUEST['groupid'] != 0) {

		$groupID = explode(",",$_REQUEST['groupid']);
		
		if(in_array(-1, $groupID)) {		
			$include = "0";	
		}
		
		else {		
			$include = "1";
		}
}

else {
	$include = "0";
	$groupID[] = "-1";		
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

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<body style="background: #3c3c3c !important;">

<div class="row col-md-12 col-sm-12" style="margin-top:0px; margin-bottom: 20px; background:#3c3c3c; float:none; margin-right:auto; margin-left:auto; text-align:center;">

	<div class="col-md-12 col-sm-12" style="margin-top:12px;margin-bottom:1px;">
		<div class="col-md-2"><a href="<?php echo $zabURL; ?>" target="_blank"><img src="img/zabbix.png" alt="Zabbix" style="height:28px;"></img></a></div> 
		<div class="col-md-8 col-sm-8">
			<h3 style="color:#fff !important; margin-top:-2 px;"><?php //echo "  ".$groupName; ?></h3>
		</div>	
	   <div class="col-md-2" id="date" style="color:#fff; "><?php echo date("d F Y", time())." - "; echo date("H:i:s", time()); ?></div>	    
	</div>

<?php 

if($include == 0) {
	include('allgroups.php');
}
	
if($include == 1) {
	include('somegroups.php');
}	
				
?>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>

</body>
</html>
