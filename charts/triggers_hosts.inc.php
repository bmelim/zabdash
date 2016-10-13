<?php

$triggers = $api->triggerGet(array(
	'output' => 'extend',
	/*'hostids' => $hostid,*/
	'sortfield' => 'priority',
	'sortorder' => 'DESC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active
	/*'withUnacknowledgedEvents' => '1',*/ 
	/*'expandDescription' => '1',*/
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
		'expandDescription' => '1',
		'selectHosts' => 1							
	));	

	if($hostid <> '') {
		$hosts[get_hostname($hostid)] = count($triggerHost);					
	}				
}

arsort($hosts);

$hosts = array_slice($hosts,0,10);

$names2 = array_keys($hosts) ;
$names1 = implode("','",$names2);
$names = "'$names1'";
//$soma3 = array_sum($hosts);

$values1 = array_values($hosts) ;


foreach($values1 as $v) {
	$values2[] = '{y:'.$v.', url: \'../tr_status.php\'}' ;
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

//print_r($hosts); 

?>