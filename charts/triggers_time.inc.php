<?php


$triggersUn = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'lastchange',
	'sortorder' => 'ASC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active	
	'withUnacknowledgedEvents' => '1'		
			
));	


$triggersAc = $api->triggerGet(array(
	'output' => 'extend',	
	'sortfield' => 'lastchange',
	'sortorder' => 'ASC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active	
	/*'withAcknowledgedEvents' => '1'*/					
));	

//Unack
foreach($triggersUn as $t) {    			           
	$unack[] = from_epoch_date($t->lastchange);			            		
 }

$contaUn = array_count_values($unack);

foreach($contaUn AS $numero => $vezes) {
	$datesUn[$numero] = $vezes;
}


//Ack
foreach($triggersAc as $t) {    			           
	$ack[] = from_epoch_date($t->lastchange);			            		
 }

$contaAc = array_count_values($ack);

foreach($contaAc AS $numero => $vezes) {
	$datesAc[$numero] = $vezes;
}

$datasUn = array_slice($datesUn,-7,7,true);
$datasAc = array_slice($datesAc,-7,7,true);

$names2 = array_keys($datasUn) ;
$names1 = implode("','",$names2);
$names = "'$names1'";

$valUn2 = array_values($datasUn);
$valAc2 = array_values($datasAc);

for($i=0; $i < count($valUn2); $i++) {

	if($valUn2[$i] == $valAc2[$i]) {			
		$valDiff[] = 0;
	}
	else {
		$valDiff[] = ($valAc2[$i] - $valUn2[$i]); 
	}
}	


$valuesUn = implode(',',$valUn2);
$valuesAc = implode(',',$valDiff);

echo "
<script type='text/javascript'>

$(function () {

    Highcharts.setOptions({
        colors: ['#058DC7', '#50B432','#FF8E00','#7CB5EC']
    });	
	
        $('#triggers_time').highcharts({
            chart: {
                type: 'column',
                height: 300
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
                 text: 'Triggers'
             },
             stackLabels: {
             enabled: true,
             style: {
                 fontWeight: 'bold',
                 color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
             }
            }
            },
          legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: -10,
            floating: true,
            backgroundColor:'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
         tooltip: {
                valueSuffix: ' '
            },
            plotOptions: {
                column: {
                	  stacking:'normal',
                    dataLabels: {
                        enabled: true                                                
                    },
                     borderWidth: 1,
                		borderColor: 'white',
                		shadow:true,           
                		showInLegend: true
                }
            },
            series: [{
                name: 'Unacked',
                data: [$valuesUn]
                },{
                name: 'Acked',
                data: [$valuesAc]                                   
            }]
        });
    });

</script>";

?>