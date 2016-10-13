<?php

require_once '../../include/config.inc.php';
require_once '../../include/hosts.inc.php';
require_once '../../include/actions.inc.php';
require_once '../../include/items.inc.php';

include('../config.php');
include('../inc/functions.inc.php');

?>

<html> 
<head>
<title>Zabdash - <?php echo __('Hosts Map'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta http-equiv="refresh" content= "180"/> 

<link rel="icon" href="../favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<script src="../js/jquery.min.js" type="text/javascript" ></script>

<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript" ></script>  
<link href="css/map.css" rel="stylesheet" type="text/css" />
<script src="./js/markerclusterer.js" type="text/javascript" ></script>
<link href="css/google_api.css" rel="stylesheet" type="text/css" />   

<!--  
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<script src="../js/bootstrap.min.js" type="text/javascript" ></script> 
-->  

</head>


<!-- google maps - by Stevenes Donato -->

<script type="text/javascript">

var markers=[];	                 
var locations = [
<?php

$icon_red = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|FF0000|14|_|";
$icon_green = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|43B53C|14|_|";

$dbLoc = DBselect( 'SELECT hi.hostid, hi.name, hi.location, hi.location_lat AS lat, hi.location_lon AS lon , h.snmp_disable_until AS sd
							FROM host_inventory hi, hosts h 
							WHERE hi.location_lat <> 0 
							AND hi.hostid = h.hostid
							ORDER BY name ASC');

while ($row = DBFetch($dbLoc)) {
	
  //$row['conta'] = count($row);
 
  $id = $row['hostid'];
  $title = $row['name'];  
  $url = "../../hosts.php?form=update&hostid=".$id;   	
  $host = "<a href=". $url ." target=_blank >" . $title . " (".$id.")</a>";  
  $status = $row['conta'];  
  $local = $row['location']; 
  $lat = $row['lat']; 
  $lon = $row['lon']; 
  $quant1 = $row['sd'];   
  //$num_up = $row['conta'];
  //$num_down = $row['conta'];   


if ($quant1 == 0) {
	$color = $icon_green.$quant."";
	$num_up = 0;	
	$num_down = 1;
	
}

else {
	$color = $icon_red.$quant."";
	//$color = $icon_green.$quant1."";
	$num_up = 1;	
	$num_down = 0;
}

echo "['$title', $lat, $lon, '$local', '$color', '$host', $id, $quant1, $num_up, $num_down, '$url'],";

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
        scaledSize: new google.maps.Size(36, 52) // pixels
    		},     
        host: locations[i][5],
        id: locations[i][6],
        quant: locations[i][7],
        //shadow:'https://chart.googleapis.com/chart?chst=d_map_pin_shadow'
        status: locations[i][7],
        num_up: locations[i][8],
        num_down: locations[i][9],
        url: locations[i][10]
        
      });

//marker animation
marker.setAnimation(google.maps.Animation.DROP);
      	
      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
          //infowindow.setContent('<b>'+locations[i][5] + '</b><br> <?php echo $state; ?>: ' + locations[i][7]);
          infowindow.setContent('<b>'+locations[i][5] + '</b><br>');
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
        url: image_path + 0 + image_ext,
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


<body onload="initialize();" style="background:#e5e5e5;">

	<div id='container-fluid' style="margin: 0px 0px 0px 1%;" > 
					
		<div id="map_canvas"></div>
				
	</div>
</body>
</html>
