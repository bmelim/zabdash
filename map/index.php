<?php

require_once '../../include/config.inc.php';
require_once '../../include/hosts.inc.php';
require_once '../../include/actions.inc.php';
require_once '../../include/items.inc.php';
include('../config.php');

require_once '../lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

if(isset($_GET['off'])) {
	$off = $_GET['off'];
}

?>

<html> 
<head>
<title>Zabdash - <?php echo _('Hosts Map'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />

<link rel="icon" href="../img/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<script src="../js/jquery.min.js" type="text/javascript" ></script>

<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript" ></script>  
<link href="css/map.css" rel="stylesheet" type="text/css" />
<script src="./js/markerclusterer.js" type="text/javascript" ></script>
<!--<script src="../js/reload.js" type="text/javascript" ></script>-->
<script src="../js/reload_param.js" type="text/javascript" ></script>
<link href="css/google_api.css" rel="stylesheet" type="text/css" />   
 
</head>

<!-- google maps - by Stevenes Donato -->

<script type="text/javascript">

var markers=[];	                 
var locations = [

<?php

$icon_red = "./images/red-marker.png";
$icon_green = "./images/green-marker.png";

$dbLoc = DBselect( 'SELECT hi.hostid, h.host, hi.name, hi.location, hi.location_lat AS lat, hi.location_lon AS lon , h.snmp_disable_until AS sd, h.status, h.flags, h.description							
							FROM host_inventory hi, hosts h 
							WHERE hi.location_lat <> 0 
							AND hi.hostid = h.hostid
							ORDER BY name ASC');


while ($row = DBFetch($dbLoc)) {

  $id = $row['hostid'];
  $title = $row['host'];  
  $url = "../../tr_status.php?form=update&hostid=".$id;   	
  $host = "<a href=". $url ." target=_blank >" . $title . " (".$id.")</a>";  
  $status = $row['conta'];  
  $local = $row['location']; 
  $lat = $row['lat']; 
  $lon = $row['lon']; 
  $quant1 = $row['sd'];     
  $desc = str_replace(["\r\n", "\r", "\n"], "<br/>",$row['description']);	
  

if($row['status'] == 0 && $row['flags'] == 0) {	

	if ($quant1 != 0) {
		//$color = $icon_red.$quant."";
		$color = "./images/red-marker.png";				
		$num_up = 0;	
		$num_down = 1;	
		$conta[] = $id;		
	}
	
	if ($quant1 == 0) {
	
		$trigger = $api->triggerGet(array(
			'output' => 'extend',
			'hostids' => $id,
			'sortfield' => 'priority',
			'sortorder' => 'DESC',
			'only_true' => '1',
			'active' => '1', 
			'withUnacknowledgedEvents' => '1'				
		));
	
		if ($trigger) {
	
			// Highest Priority error
			//$prio = $trigger[0]->priority;				
			if($trigger[0]->value == 0) { $prio = 9;} 	
	  		else { $prio = $trigger[0]->priority;} 			
			$color = "./images/prio".$prio.".png";	
			$num_up = 1;	
			$num_down = 0;						
		}
		
		else {	
			$color = "./images/green-marker.png";							
			$num_up = 1;	
			$num_down = 0;
		}
		
	}
}	

echo "['$title', $lat, $lon, '$local', '$color', '$host', $id, $quant1, $num_up, $num_down, '$url', '$desc'],";

$contaRed += $num_down;
$showAlert += $id;

}
?>
    ];
    
function initialize() {
   
var mapOptions = {
	mapTypeId: google.maps.MapTypeId.ROADMAP
	//mapTypeId: google.maps.MapTypeId.HYBRID
	//zoom:9,
	//center: new google.maps.LatLng(40,-3)
	};
	
    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    var infowindow = new google.maps.InfoWindow();
    var marker, i;

    for (i = 0; i < locations.length; i++) {  

// avoid markers with same location
	 var min = .999999;
	 var max = 1.000001;    
  	 var offsetLat = locations[i][1] * (Math.random() * (max - min) + min);
    var offsetLng = locations[i][2] * (Math.random() * (max - min) + min);      
    
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(offsetLat, offsetLng),
        map: map,
		  title: locations[i][0],	        
        icon: {
        url: locations[i][4],
        //scaledSize: new google.maps.Size(32, 48) // pixels
    		},     
        host: locations[i][5],
        id: locations[i][6],
        quant: locations[i][7],
        //shadow:'https://chart.googleapis.com/chart?chst=d_map_pin_shadow'
        status: locations[i][7],
        num_up: locations[i][8],
        num_down: locations[i][9],
        url: locations[i][10],        
        infos: locations[i][11]        
      });
     
		
		//marker animation
		marker.setAnimation(google.maps.Animation.DROP);
      	
      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
        	        	
        	//var infos1 = infos.replace(/(\r|\n)/g, '<br />');
          //infowindow.setContent('<b>'+locations[i][5] + '</b><br> <?php echo $state; ?>: ' + locations[i][7]);
          infowindow.setContent('<b>' + locations[i][5] + '</b><br>' + locations[i][11]);
          infowindow.open(map, marker);
        }
      })(marker, i)); 

	// close infowindow when zoom change 
	google.maps.event.addListener(map, 'zoom_changed', function() { infowindow.close() }); 
	            
	markers.push(marker)			
	}
	
	//center map
	    var bounds = new google.maps.LatLngBounds();
	    for (i = 0; i < locations.length; i++) {    
	    bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
	 }
	 map.fitBounds(bounds);


// Define the marker clusterer color

 var styles = [];
   for (var i = 0; i < 4; i++) {
      image_path = "./images/";
      image_ext = ".png";
      styles.push({
        url: image_path + i + image_ext,
        height: 52,
        width: 53
      });
    } 
 
        var mcOptions = { 
        zoomOnClick: true,
        gridSize:30,
        minimumClusterSize: 4,
        styles: styles,  
        maxZoom: 15 
         }
     
	//criar cluster
	var markerClusterer = new MarkerClusterer(map, markers, mcOptions);	
	
	var iconCalculator = function(markers, numStyles) {
      var total_up = 0;
      var total_down = 0;
      for (var i = 0; i < markers.length; i++) {
        total_up += markers[i].num_up;
        total_down += markers[i].num_down;
      }

      var ratio_up = total_up / (total_up + total_down);

      //The map clusterer really does seem to use index-1... 
  		  index_ = 1;
  		
      if (ratio_up < 0.9999) {
        index_ = 4; // Could be 2, and then more code to use all 4 images
      }				

      return {
        text: (total_up + total_down),         
        index: index_
      };
    }

    markerClusterer.setCalculator(iconCalculator);	
			
	// Listen for a cluster to be clicked 
	google.maps.event.addListener(markerClusterer, 'mouseover', function(cluster) {
    var content = '';

    // Convert lat/long from cluster object to a usable MVCObject
    var info = new google.maps.MVCObject;
    info.set('position', cluster.center_);

    //----
    //Get markers
    var markers = cluster.getMarkers();
	 var titles = "";
	  
    //Get all the titles
    for(var i = 0; i < markers.length; i++) {
    	
    	if (markers[i].status == 0) 
    	{
    		titles += <?php echo '"<a href='. $url .' target=_blank style=color:#43B53C; "+markers[i].host + "</a><br>"'; ?>;    			
	   }
	
	   if (markers[i].status != 0)
		{
    		titles += <?php echo '"<a href='. $url .' target=_blank style=color:#990000; "+markers[i].host + "</a><br>"'; ?>;	
	   }
   }

    var infowindow = new google.maps.InfoWindow();
    infowindow.close();
    infowindow.setContent(titles); //set infowindow content to titles    
    infowindow.open(map, info);

	 //close infowindow
	 google.maps.event.addListener(markerClusterer, 'mouseout', function() { infowindow.close() });

	 // close infowindow when zoom change
	 google.maps.event.addListener(map, 'zoom_changed', function() { infowindow.close() });

});

}
</script> 

<?php

	 //offline hosts 	 
	 
	 if($contaRed != 0) {
		$sound = "../sound/Alarm1.wav";	 	
	 }
	 else { 
	 	$sound = "../sound/no_sound.wav";
	 }	
	 	
	 $offAtual = count($conta);
?>


<script type="text/javascript">

function reloadPage() {
	
		$("#reload_page").click(function() {			
			window.location.href='index.php?off=<?php echo $offAtual; ?>';
		});
		
		
		var reloadTimer = function(flag, interval) {
		if (flag === true) {
			clearInterval(metric.reloadId);
			var counter = interval;
			$("#countDownTimer").text(interval);
	
			metric.reloadId = setInterval(function() {
				counter--;
				$("#countDownTimer").text(counter);
	
				if (counter === 0) {					
					//window.location.reload();
					window.location.href='index.php?off=<?php echo $offAtual; ?>';
					counter = interval;
				}
	
			}, 1000);
		} else {
			clearInterval(metric.reloadId);
			$("#countDownTimer").text("");
		}
   	};			


	$(function($){
		
		var inter = localStorage.getItem('relInt');
		document.getElementById('reload_selecter').value = inter;
		
		if (inter > 0) {
			$("#reload_page").attr({
				"disabled" : "disabled"
			});

			reloadTimer(true, inter);

		} else {
			$("#reload_page").removeAttr("disabled");

			reloadTimer(false);
		}							
});

		
		$(function($) {
			$('#reload_selecter').change(function() {
								
				var selectVal = $(this).val();
				
				localStorage.setItem('relInt',selectVal);
				var inter = localStorage.getItem('relInt');
				
				//window.location.reload();
				window.location.href='index.php?off=<?php echo $offAtual; ?>';								

				if (selectVal != 0) {
					$("#reload_page").attr({
						"disabled" : "disabled"
					});

					reloadTimer(true, selectVal);

				} else {
					$("#reload_page").removeAttr("disabled");

					reloadTimer(false);
					//window.location.reload();
					window.location.href='index.php?off=<?php echo $offAtual; ?>';
				}
			});
		});			

}		 	  

</script>

<?php
    
    //echo '<meta http-equiv="refresh" content="URL=\'./index.php?off='.$offAtual.'\'"/>'; 	 	 	 
	 
	 if($off > 0 && $offAtual > $off) {
		 echo '<!--[if IE]>';
		 echo '<embed src="'.$sound.'" autostart="true" width="0" height="0" type="application/x-mplayer2"></embed>';
		 echo "<![endif]-->\n";   
		 // Browser HTML5    
		 echo '<audio preload="auto" autoplay>';
		 echo '<source src="'.$sound.'" type="audio/ogg"><source src="'.$sound.'" type="audio/mpeg">';
		 echo "</audio>\n";
	 }
 
 ?>

	<body onload="initialize();reloadPage();" style="background:#e5e5e5;">
	
		<div id='container-fluid' class="col-md-12 col-sm-12"  style="margin-top: -50px; margin-bottom:2px;" > 
			<div style="margin-top:2px;margin-bottom:1px;">
			<div style="float:left;"><a href="<?php echo $zabURL; ?>" target="_blank"><img src="../img/zabbix.png" alt="Zabbix" style="height:28px;"></img></a></div> 	
		   <div class="" id="date" style="color:#000; float:right; "><?php echo date("d F Y", time())." - "; echo date("H:i:s", time()); ?></div>	    
			</div>
		</div>
		<?php
			if($showAlert == '' && $showAlert == 0) {
				echo '<div id="help" class="col-md-12 col-sm-12" style="display:block;">
							<div class="alert alert-danger" role="alert">
								<span>' .$labels['To view a host on the map enter your latitude and longitude in "Host inventory".'].'</span>
							</div>
						</div>';
			}										
		?>				
		<div id="map_canvas"></div>
		
		<!-- interval selector -->
		<div class="col-xs-3 col-sm-4 col-md-4 col-lg-1 form-group pull-right" style="float: right; width:125px; margin-top:15px;">
			<select id="reload_selecter" class="form-control pull-right">
				<option value="30">Default</option>						
				<option value="45">45s</option>			
				<option value="60">60s</option>
				<option value="90">90s</option>
				<option value="120">120s</option>
				<option value="180">180s</option>
				<option value="300">300s</option>
			</select>
		</div>	
		<div>
			<button id="reload_page" type="button" class="btn btn-default pull-right" style="margin-top:15px;">
				<i class="glyphicon glyphicon-refresh"></i><text id="countDownTimer"></text>
			</button>
		</div>		
		<!-- interval selector -->	
							
	</body>
</html>
