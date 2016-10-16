<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');
//include('inc/functions.inc.php');

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

<title>Zabbix - Groups</title>

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<body>

<div class="row col-md-12 col-sm-12" style="margin-top:40px; margin-bottom: 0px; float:none; margin-right:auto; margin-left:auto; text-align:center;">	
</div>	

<?php
				
	$md = 10;		
	
	$dbgroupsCount = DBselect( 'SELECT COUNT(groupid) AS hc FROM groups');
	$groupsCount = DBFetch($dbgroupsCount);	
	
	$dbGroups = DBselect( 'SELECT groupid, name FROM groups ORDER BY name ASC'	);
	
	echo "			
		<div class='align col-md-".$md." col-sm-".$md."' >
			<table id='hosts' class='box table table-striped table-hover' border='0' width='50%'>
			<thead>
				<tr>
					<th width='8px'></th>
					<th style='text-align:center;'>Groups (".$groupsCount['hc'].")</th>
					<th style='text-align:center;'>Hosts</th>										
				</tr>								
			</thead> ";
	
	
	while ($groups = DBFetch($dbGroups)) {						 	
			
		$dbHosts = DBSelect('SELECT COUNT(hostid) AS hosts FROM hosts_groups WHERE groupid ='.$groups['groupid']);
		$hosts = DBFetch($dbHosts);						
		
		echo "
				<tr>
					<td  style='background-color:".$cor.";' title='".$conn."'>
					</td>
					<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
						<a href='hosts_view.php?groupid=".$groups['groupid']."' target='_self' >".$groups['name']."</a>
					</td>
					<td style='text-align:center;'>
						".$hosts['hosts']."
					</td>					
				</tr>";								
	}

echo "	</table>						
		</div>\n";				
?>

</body>
</html>
