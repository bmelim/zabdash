<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';
require_once '../include/items.inc.php';

include('config.php');
//include('inc/functions.inc.php');

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

$hostid = $_REQUEST['hostid'];

if(isset($hostid)) {

//Get info from hostid	
include('host_info.php');
  
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Language" content="pt-br">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<meta http-equiv='refresh' content='120'>-->	
	<title>Zabbix Host Details</title>
	
	<!-- Bootstrap -->
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<link rel="stylesheet" type="text/css" href="css/sprite.css" />
	
	<link href="./inc/select2/select2.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="./inc/select2/select2.js" language="javascript"></script>

<script type="text/javascript">

    function getVal() {
    	
		var sel = document.getElementById("graphid");
		var width = document.getElementById("showgraph").offsetWidth;
		var div = document.getElementById("showgraph");
		var period = document.getElementById("period").value;
        
      if (sel.value != 0) {       	       	                
       	div.innerHTML = '<a href="../charts.php?form_refresh=1&fullscreen=0&groupid=0&hostid=<?php echo $hostid; ?>&graphid=' + sel.value + '" target="_blank"><img src="../chart2.php?graphid=' + sel.value + '&width=' + width + '&period=' + period + '" /></a>' ;              	     
      }  
       
      if (sel.value == 0) {       	       
       	div.innerHTML = '' ;       
      }  
    }
    
    function graphPage() {
    	
    	var period = document.getElementById("period").value;
    	
    	/*window.open('host_graphs.php?period='+period+'&hostid=<?php echo $hostid; ?>','_top');*/
    	window.location.href = 'host_graphs.php?period='+period+'&hostid=<?php echo $hostid; ?>';    	
    }

</script>

</head>

<body>

<div class="row col-md-12 col-sm-12" style="margin-top:40px; margin-bottom: 0px; float:none; margin-right:auto; margin-left:auto; text-align:center;"></div>	

<?php
				
	$dbHosts = DBselect( 'SELECT hostid, name, status, snmp_available AS sa, snmp_disable_until AS sd, flags FROM hosts WHERE hostid='.$hostid);
	$host = DBFetch($dbHosts);		
	
	echo "			
		<div id='host' class='align col-md-10 col-sm-10' >
			<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2;'>\n
			 ";
			
		if($host['sd'] <> 0) { $conn = "Offline"; $cor = "#E3573F"; } else { $conn = "Online"; $cor = "#4BAC64"; } 	
			
		$dbIP = DBSelect('SELECT DISTINCT ip FROM interface WHERE hostid ='.$host['hostid']);
		$IP = DBFetch($dbIP);
		
		$hostOS = getOS($host['hostid']);
		echo "
				<tr>
					<td style='background:".$cor."; width:12px;' title='".$conn."'></td>
					<td class='link2' style='vertical-align:middle; text-align:left; padding:5px;'>
						<a href='".$zabURL."tr_status.php?fullscreen=0&source=0&hostid=".$host['hostid']."' target='_blank' >".$host['name']."</a>
					</td>
					<td style='text-align:center;'>
						<img src='img/os/".$hostOS.".png' alt='' title='".$hostOS."'/>
					</td>
					<td style='text-align:center; vertical-align:middle; '>
						<i class='oicon-server' title='Hostname'></i> ".get_host_name($host['hostid'])."
					</td>
					<td style='text-align:center; vertical-align:middle; '>
						<i class='oicon-network-ethernet' title='IP'></i> ".$IP['ip']."
					</td>
					<td style='text-align:center; vertical-align:middle; '>
						<i class='oicon-time' title='Uptime'></i> ".time_ext($arrTime[0])."
					</td>
					<td style='text-align:center; vertical-align:middle'>
						". hostStatus($host['status']) ."
					</td>
				</tr>\n
			</table>";	
				
		//CPU Load
		echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2;'>
				<tr>					
					<td colspan='5' style='font-weight:bold;'><img src='img/icon/electronics-24.png' alt=''/> ".$labels['Processor']." </td>
				</tr>\n"; 										

		echo "<tr>
					<td colspan='4'> ".$labels['Average'].": ".$avgCPU."% </td>
				</tr>\n"; 
			
				for($i=0; $i<$cpuNum; $i++) {
		
					echo "<tr><td>".$labels['Processor']." ".($i + 1).": ".$arrCPU[$i]."% &nbsp;</td></tr>\n";
				}					
										

		//Memory		 
		echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2;'>
				<tr>\n";					
			echo "<td colspan='5' style='font-weight:bold;'><img src='img/icon/memory_slot-24.png' alt=''/>  ".$labels['Memory']." </td>
				</tr>\n"; 										

		echo "<tr>\n";
			echo "<td colspan='2' style='width:50%;'> ".$labels['Type']." </td>
					<td> ".$labels['Used']." </td>
					<td> ".$labels['Total']." </td>
					<td> % ".$labels['Used']." </td>
				</tr>\n"; 


		//print memory size					
		for($n=0;$n<count($arrSize);$n++) {
		
			$s = explode(",",$arrSize[$n]);
		
			if( stripos($s[0] , 'Memory') == true ) {					
				$arrSize2[] = $s[0].",".$s[1];	
			}
		}	
		
	
		for($n=0;$n<count($arrUsed);$n++) {
		
			$u = explode(",",$arrUsed2[$n]); 			
				
			if( stripos($u[0] , 'Memory') == true ) {					
				$arrUsed3[] = $u[0].",".$u[1];	
			}
		}
		
		for($i=0;$i < count($arrUsed2);$i++) {
	
		$s = explode(",",$arrSize2[$i]);
		$u = explode(",",$arrUsed3[$i]); 
			
		if( stripos($s[0] , 'Memory') == true ) {
			
			if($s[1] != 0) {
				$barra = round((100*$u[1])/$s[1],1);	
			}
			else { $barra = 0; }	
			
			$barraValue = $barra;
		
			// cor barra
			if($barra >= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; $barraValue = 100;}
			if($barra >= 80 and $barra <= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
			if($barra >= 61 and $barra <= 79) { $cor = "progress-bar-warning"; $perc_cor = "#fff"; }
			if($barra >= 26 and $barra <= 60) { $cor = " "; $perc_cor = "#fff"; }
			if($barra >= 0 and $barra <= 25) { $cor = "progress-bar-success"; $perc_cor = "#000";}	
			if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }			
					
									
			echo "<tr>";	
				echo "<td colspan='2'>". $s[0] ."</td>";
				echo "<td>". formatBytes($u[1],1) ."</td>";
				echo "<td width='15%'>". formatBytes($s[1],1) ."</td>";
				echo "<td width='15%' style='padding-right:15px; '>
							<div style='font-size:13px; position:absolute; vertical-align:middle; color:".$perc_cor.";'>&nbsp;".$barra."%</div>
							<div class='progress-bar ". $cor ." progress-bar ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='text-align:left; width: ".$barraValue."%;'>&nbsp; </div>
				   	</td>";
			echo "</tr>\n";						
			}
		}
					
		echo "</table>\n";
		
		
		//Disks				
		echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2;'>
				<tr>					
					<td colspan='5' style='font-weight:bold;'><img src='img/icon/HDD-24.png' alt=''/> ".$labels['Storage']." </td>
				</tr>\n"; 										

		echo "<tr>
					<td colspan='2' style='width:50%;'> ".$labels['Unity']." </td>
					<td> ".$labels['Used']." </td>
					<td> ".$labels['Total']." </td>
					<td> % ".$labels['Used']." </td>
				</tr>\n"; 		
		
		for($i=0;$i<count($arrUsed);$i++) {
		
			$s = explode(",",$arrSize[$i]);
			$u = explode(",",$arrUsed[$i]); 
				
			if( stripos($s[0] , 'Memory') != true ) {
				
				if($s[1] != 0) {
					$barra = round((100*$u[1])/$s[1],1);	
				}
				else { $barra = 0; }
				
				// cor barra disks
				if($barra >= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
				if($barra >= 80 and $barra <= 100) { $cor = "progress-bar-danger"; $perc_cor = "#fff"; }
				if($barra >= 61 and $barra <= 79) { $cor = "progress-bar-warning"; $perc_cor = "#fff"; }
				if($barra >= 26 and $barra <= 60) { $cor = " "; $perc_cor = "#fff"; }
				if($barra >= 0 and $barra <= 25) { $cor = "progress-bar-success"; $perc_cor = "#000";}	
				if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }			
														
				echo "<tr>";	
					echo "<td colspan='2'>". $s[0] ."</td>";
					echo "<td>". formatBytes($u[1],1) ."</td>";
					echo "<td width='15%'>". formatBytes($s[1],1) ."</td>";
					echo "<td width='15%' style='padding-right:15px; '>
								<div style='font-size:13px; position:absolute; vertical-align:middle; color:".$perc_cor.";'>&nbsp;".$barra."%</div>
								<div class='progress-bar ". $cor ." progress-bar ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='text-align:left; width: ".$barra."%;'>&nbsp; </div>
					      </td>";
				echo "</tr>\n";						
			}
		}
		
		echo "</table>\n";
			

		//Graphs				
		echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2; margin-bottom:20px;'>
				<tr>					
					<td colspan='5' style='font-weight:bold;'><img src='img/icon/graph.png' alt='' style='width:24px;' /> ".$labels['Charts']." </td>					
				</tr>\n"; 	
				
		echo "<tr>\n";	
			echo "<td>\n";		
				echo "<select id='graphid' name='graphid' onChange=\"getVal();\" style='width:97% !important; height: 27px;' autofocus data-placeholder=''>\n";
					echo "<option value='0'>------</option>\n";	
													
					foreach($graphs as $g) {
						echo "<option value='".$g->graphid."'>".$g->name."</option>\n";							
					}												
				echo "</select>\n";
				echo "</td>\n";
				
				echo "<td width='20%' >\n";	
				echo "<select id='period' name='period' onChange=\"getVal();\" style='height: 27px; width:75px;' autofocus >\n";
					echo "<option value='1800'> 30m </option>\n";	
					echo "<option value='3600' selected> 1h </option>\n";	
					echo "<option value='7200'> 2h </option>\n";	
					echo "<option value='21600'> 6h </option>\n";	
					echo "<option value='43200'> 12h </option>\n";	
					echo "<option value='86400'> 1d </option>\n";														
					echo "<option value='259200'> 3d </option>\n";														
					echo "<option value='604800'> 7d </option>\n";																			
				echo "</select>\n";
			echo "</td>\n";
			echo "<td width='20%'>\n";
				echo '<button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off" onclick="graphPage();"> Todos os gráficos </button>';																
			echo "</td>\n";																
		echo "</tr>\n";
		
		echo "<tr>\n";	
			echo "<td colspan='3'>\n";
				echo "<div class='col-md-10' id='showgraph' style='float:left; margin:auto;'></div>\n";
			echo "</td>\n";		
		echo "</tr>\n";		
		echo "</table>\n";	
		
		
		//Network				
		echo "<table class='box table table-striped table-hover table-condensed' border='0' width='50%' style='border:1px solid #f2f2f2; margin-bottom:80px;'>
					<tr>					
						<td colspan='4' style='font-weight:bold;'><img src='img/icon/ethernet_off-24.png' alt=''/> ".$labels['Network Interfaces']." </td>
					</tr>\n"; 										
	
			echo "<tr>
						<td colspan='2' style='width:65%;'> ".$labels['Name']."  </td>
						<td> In </td>
						<td> Out </td>				
					</tr>\n";
			
			//foreach($arrIfSize as $k){
			for($i=0;$i<count($arrIfUsed);$i++) {
			
				$s = explode(",",$arrIfSize[$i]);
				$u = explode(",",$arrIfUsed[$i]); 					
				
				echo "<tr>";
					echo "<td colspan='2' style='width:60%;'>".$s[0]."</td>
							<td>". formatBytes(($s[1] * 8),1)."</td>
							<td>". formatBytes(($u[1] * 8),1)."</td>";		
				echo "</tr>\n";
			}
		echo "</table>\n";
																						
		echo "</div>";
}

else {
	echo "<h3>No host selected!!</h3>";
}		

?>

<script type="text/javascript">
	$("#graphid").select2({
	  placeholder: "Selecione o gráfico",
	  allowClear: false	  
	});
	$("#period").select2();
</script>

</body>
</html>
