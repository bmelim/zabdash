<?php


$triggersPr = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'lastchange',
	'sortorder' => 'ASC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active					
));	

foreach($triggersPr as $t) {    			           
	$valores[] = from_epoch_date($t->lastchange);			            		
 }

$contagem = array_count_values($valores);

foreach($contagem AS $numero => $vezes) {
	$dates[$numero] = $vezes;
}

$conta = count($dates);

$datas = array_slice($dates,-7,7,true);

$names2 = array_keys($datas) ;
$names1 = implode("','",$names2);
$names = "'$names1'";

$values2 = array_values($datas) ;
$values = implode(',',$values2);

echo "
<script type='text/javascript'>

$(function () {
        $('#triggers_time').highcharts({
            chart: {
                type: 'column',
                height: 350
            },
            title: {
                text: ''
            },
           
            xAxis: {
                categories: [$names],
                labels: {                    
                    align: 'center',
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
                column: {
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
                    //events: {
                       // click: function () {
                            //location.href = this.options.url;
                           // window.open(this.options.url);
                        //}
                    //}
                }    
            }]
        });
    });

</script>";

//print_r($hosts); 

?>