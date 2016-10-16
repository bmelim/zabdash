<?php

	$triggerUnack = $api->triggerGet(array(
		'output' => 'extend',
		/*'hostids' => $hostid,*/
		'sortfield' => 'lastchange',
		'sortorder' => 'DESC',
		'only_true' => '1',
		'active' => '1', // include trigger state active not active
		'withUnacknowledgedEvents' => '1', 
		'expandDescription' => '1',
		'selectHosts' => 1								
	));	
	

echo "			
	<!--<div id='triggers_Unack' class='align col-md-12 col-sm-12' style='margin-bottom: 30px;'>-->
		<table id='triggersUnack' class='box table table-striped table-hover table-condensed' border='0' style='background:#fff;'>
		<thead>
			<tr>
				<th style='text-align:center; width:15%;'>Lastchange</th>
				<th style='text-align:center;'>Severity</th>
				<th style='text-align:center;'>Host</th>
				<th style='text-align:center;'>Description</th>
			</tr>								
		</thead>
		<tbody> ";
	
	
 foreach($triggerUnack as $tu) {    

	echo "<tr>";			            
		echo "<td style='text-align:center; vertical-align:middle !important;' data-order=".$tu->lastchange.">".from_epoch($tu->lastchange)."</td>";				            
		//echo $t->triggerid.",";				            			            
		//echo "<td style='text-align:center;'>".$t->priority."</td>";
		echo "<td style='text-align:left; vertical-align: middle !important;'>
					<div class='hostdiv nok". $tu->priority ." hostevent trig_radius' style='height:21px !important; margin-top:0px; !important;' onclick=\"window.open('/zabbix/tr_status.php?filter_set=1&hostid=". $tu->hosts[0]->hostid ."&show_triggers=1')\">
					<p class='severity' style='margin-top: -2px;'>". get_severity($tu->priority) ."</p>									
					</div>
				</td>";				            
		echo "<td style='text-align:left; vertical-align: middle !important;'>". get_hostname($tu->hosts[0]->hostid)."</td>";				            
		echo "<td style='text-align:left; vertical-align: middle !important;'>".$tu->description."</td>";				            
	echo "</tr>";			            
		
 }

echo "</tbody>
			</table>\n";		
 
//echo "conta: ".count($triggerUnack); 

?>