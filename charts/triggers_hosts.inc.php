<?php

$triggers = $api->triggerGet(array(
	'output' => 'extend',
	/*'hostids' => $hostid,*/
	'sortfield' => 'priority',
	'sortorder' => 'DESC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active
	/*'withUnacknowledgedEvents' => '1',*/ 	
	'selectHosts' => 1					
));	

$conta = count($triggers);

for($i=0; $i <= $conta; $i++) {
 	    
	$hostid = $triggers[$i]->hosts[0]->hostid;	
 
	$triggerHost = $api->triggerGet(array(
		'output' => 'extend',
		'hostids' => $hostid,
		'sortfield' => 'priority',
		'sortorder' => 'DESC',
		'only_true' => '1',
		'active' => '1',		
		//'expandDescription' => '1',
		'selectHosts' => 1							
	));	

	if($hostid <> '') {
		$hosts[get_hostname($hostid)] = count($triggerHost);					
<<<<<<< HEAD
=======
		$hosts_ids[$hostid] = count($triggerHost);					
>>>>>>> 1.1.2
	}				
}

arsort($hosts);
<<<<<<< HEAD

$hosts = array_slice($hosts,0,10);

$names2 = array_keys($hosts) ;
$names1 = implode("','",$names2);
$names = "'$names1'";

$values1 = array_values($hosts) ;


foreach($values1 as $v) {
	$values2[] = '{y:'.$v.', url: \'../tr_status.php\'}' ;
=======
arsort($hosts_ids);

$hosts = array_slice($hosts,0,10);

$hosts_ids2 = array_keys($hosts_ids);
$hosts_ids = array_slice($hosts_ids2,0,10);

$names2 = array_keys($hosts);
$names1 = implode("','",$names2);
$names = "'$names1'";


$values1 = array_values($hosts);
$ids = $hosts_ids;

/*var_dump($hosts_ids);
var_dump($hosts);
var_dump($ids2);*/


foreach ($values1 as $key => $v) {
    $values2[] = '{y:'.$v.', url: \'../zabbix.php?action=problem.view&page=1&filter_show=1&filter_application=&filter_name=&filter_severity=0&filter_inventory[0][field]=type&filter_inventory[0][value]=&filter_evaltype=0&filter_tags[0][tag]=&filter_tags[0][operator]=0&filter_tags[0][value]=&filter_show_tags=3&filter_tag_name_format=0&filter_tag_priority=&filter_show_timeline=1&filter_set=1&filter_hostids[]='.$ids[$key].'\'}' ;
>>>>>>> 1.1.2
}

$values = implode(',',$values2);

echo "
<script type='text/javascript'>

$(function () {
        $('#graflinhas1').highcharts({
            chart: {
                type: 'bar',
                height: 400
            },
            title: {
                text: ''
            },
           
            xAxis: {
                categories: [$names],
                labels: {                    
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }, 
                    overflow: 'justify'                
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
         tooltip: {
                valueSuffix: ' '
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true                                                
                    },
                     borderWidth: 1,
                		borderColor: 'white',
                		shadow:true,           
                		showInLegend: false
                }
            },
            series: [{
                name: 'Triggers',
                data: [$values],
                dataLabels: {
                    enabled: true,                                       
                    style: {
                        fontSize: '12px',                        
                    }
                },
                point: {
                    events: {
                        click: function () {
                            //location.href = this.options.url;
                            window.open(this.options.url);
                        }
                    }
                }    
            }]
        });
    });

</script>";

?>