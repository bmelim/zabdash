<?php

require_once '../include/config.inc.php';
require_once('config.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

//If already auth go to zabdash.php
if($_COOKIE["zabdash_session"]) {
	header("location:zabdash.php");
}

else {
	
$username = $_POST['inputUser'];
$password = $_POST['inputPasswd'];

if(isset($username) && isset($password)) {
	
	try
	{
		$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $username .'', ''. $password .'');
			
		$login = $api->userLogin(array(
			'output' => 'extend',	
			'user' => $username,
			'password' => $password						
		));
	}
	catch(Exception $e)
	{
	    // Exception in ZabbixApi catched
	    //echo $e->getMessage();
	}
}

//If login successful 	
if (strlen($login) === 32) {
	setcookie('zabdash_session', $login, time() + (86400 * 7), "/");
	header("location:zabdash.php");
}

?>

<html>
<head>
    <title>ZabDash - Login </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">           

    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet">        		   
    <link rel="stylesheet" type="text/css" href="css/index.css">
    
     <!-- this page specific styles 
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" /> -->    

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>

<body class="skin-blue sidebar-mini login-page">

<div id="top_login" class="col-md-12 col-sm-12 col-xs-12">
	<div class="login-box">

		<div class="login-box-body">
			<div class="login-logo">
				<b>ZabDash</b>
			</div>
			<form id="form" name="form" method="post" action="">
				<div class="form-group">
					<input id="user" name="inputUser" class="form-control" required placeholder="Zabbix User" />
					<span class="form-control-feedback"></span>
				</div>
	
				<div class="form-group">
					<input id="passwd" name="inputPasswd" type="password" class="form-control" required placeholder="Password" />
					<span class="form-control-feedback"></span>
				</div>
	
				<div class="row" style="">
					<div class="col-md-12"></div>
					<div class="col-md-12">
						<button id="submit_login" type="submit" name="submit" class="btn btn-primary btn-flat" onclick="javascript:this.form.submit();">
							<?php echo _('Sign in'); ?>
						</button>
					</div>
					<div class="col-xs-4"></div>
				</div>
			</form>
		</div>

	</div>
	<!-- /.login-box -->
</div>
</body>
</html>	

<?php
}
?>
	