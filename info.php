<?php

require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');
            						                         	            
?>

<html>
  <head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Zabbix - Dashboard - Info</title>
  <link rel="icon" href="img/dash.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />
  <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />      
	<style type="text/css">
		a:link, a:focus, a:visited, a:hover { color: #3575AC !important;}
	</style>    
    
  </head>
<body style="background-color: #fff; background:url('img/bg.jpg'); height:800px;">

<div id="content" class="col-md-12" >
	  
	<div class="well info_box col-md-6" style="opacity: 0.9; height:460px; margin:auto; margin-top:100px; margin-bottom: 400px; float:none; text-align:center; font-size:14pt;">    
	    <br>
	    <span style="font-weight: bold;">Zabdash - Zabbix Dashboard</span><p>
	    <br>
		 <?php echo $labels['Version']." ". $version; ?><br>
	    <br><p>
	    <?php echo $labels['Developed by']; ?>:
	    <br><br>
	    <b>Stevenes Donato
	    <br>
	     <a href="mailto:stevenesdonato@gmail.com"> stevenesdonato@gmail.com </b> </a>
	    <br>
	    <br>
	    <a href="https://sourceforge.net/projects/zabdash" target="_blank" >https://sourceforge.net/projects/zabdash</a>
	    <br><p></p>
	    
	    <div id="donate" style="margin-top:25px; margin-left:0px;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="3SN6KVC4JSB98">
			<input type="image" src="./img/paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="./img/paypal.png" width="1" height="1">
			</form>
		 </div>	          
	</div>
</div>
</body>
</html>
