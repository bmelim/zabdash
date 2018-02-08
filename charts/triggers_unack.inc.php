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
		<table id='triggersUnack' class='box table table-striped table-hover table-condensed' border='0' style='background:#fff;'>
			<thead>
				<tr>
					<th style='text-align:center; width:15%;'>". _('Last change')."</th>
					<th style='text-align:center;'>". _('Severity')."</th>
					<th style='text-align:center;'>". _('Status')."</th>				
					<th style='text-align:center;'>". _('Host')."</th>
					<th style='text-align:center;'>". _('Description')."</th>
				</tr>								
			</thead>
		<tbody> ";
	
	
 foreach($triggerUnack as $tu) {
 	
  	if($tu->value == 0) { $priority = 9; $statColor = '#34AA63';} 	
  	else { $priority = $tu->priority; $statColor = '#E33734'; } 	 
	    

	echo "<tr>";			            
		echo "<td style='text-align:center; vertical-align:middle !important;' data-order=".$tu->lastchange.">".from_epoch($tu->lastchange)."</td>";				            
		echo "<td style='text-align:left; vertical-align: middle !important;'>
					<div class='hostdiv nok". $priority ." hostevent trig_radius truncate' style='height:21px !important; margin-top:0px; !important;' onclick=\"window.open('/zabbix/tr_status.php?filter_set=1&hostid=". $tu->hosts[0]->hostid ."&show_triggers=1')\">
						<p class='severity' style='margin-top: -2px;'>". _(get_severity($tu->priority)) ."</p>									
					</div>
				</td>";				            
		echo "<td style='text-align:center; vertical-align: middle !important; color:".$statColor." !important;'>"._(set_status($tu->value))."</td>";				            
		echo "<td style='text-align:left; vertical-align: middle !important;'><a href='../zabdash/host_detail.php?hostid=".$tu->hosts[0]->hostid."' target='_blank'>". get_hostname($tu->hosts[0]->hostid)."</a></td>";
		echo "<td style='text-align:left; vertical-align: middle !important;'>".$tu->description."</td>";				            
	echo "</tr>";			            
		
 }

echo "</tbody>
			</table>\n";		
 
//echo "conta: ".count($triggerUnack); 

?>