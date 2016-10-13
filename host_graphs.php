<?php

require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';
require_once '../include/items.inc.php';

include('config.php');
include('inc/functions.inc.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$hostid = $_REQUEST['hostid'];

//Translate
$labels = include_once 'locales/pt.php';

if(isset($hostid)) {
	
	$period = $_REQUEST['period'];
	if(isset($period)) {
		$period = $_REQUEST['period'];
	}
	else {
		$period = 3600;
	}
	
  
// get all graphs
 $graphs = $api->graphGet(array(
     'output' => 'extend',
     'hostids' => $hostid,     
     'sortfield' => 'name'
 ));
}

else {
	echo '<script type="text/javascript">';
		echo 'history.back();';
	echo '</script>';
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

<title>Zabbix Host Graphs</title>

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>
</head>
<body>

<div class="row col-md-11 col-sm-11" style="margin-top:35px; margin-bottom: 80px; float:none; margin-right:auto; margin-left:auto; text-align:center;">
	
	<h3 style="color:#000 !important; margin-top:0px; margin-bottom: 20px;"> <?php echo get_hostname($hostid);  ?> </h3>	

	<div class="row">
		<?php	
			foreach($graphs as $g) {
			
			echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb' style='padding: 6px !important; margin-bottom:0px; height:165px; '>";
				echo '<a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="" data-caption="" data-image="../chart2.php?graphid='.$g->graphid.'&period='.$period.'&height=300" alt="" data-target="#image-gallery">';
					echo '<img class="img-responsive ximg-thumbnail ximg-rounded" src="../chart2.php?graphid='.$g->graphid.'&period='.$period.'&height=280" />';
				echo '</a>';
			echo "</div>\n";
			
			}
		?>	
	</div>
	
</div>

<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
        <div class="modal-content" style="width:800px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="image-gallery-title"></h4>
            </div>
            <div class="modal-body">
                <img id="image-gallery-image" class="img-responsive" src="">
            </div>
            <div class="modal-footer">

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="show-previous-image">Previous</button>
                </div>

                <div class="col-md-8 text-justify" id="image-gallery-caption">                    
                </div>

                <div class="col-md-2">
                    <button type="button" id="show-next-image" class="btn btn-default">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>