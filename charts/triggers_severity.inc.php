<?php

$triggersPr = $api->triggerGet(array(
	'output' => 'extend',
	/*'hostids' => $hostid,*/
	'sortfield' => 'priority',
	'sortorder' => 'DESC',
	'only_true' => '1',
	'active' => '1', // include trigger state active not active
	/*'withUnacknowledgedEvents' => '1', */			
));	

foreach($triggersPr as $t) {    			           
	$valores[] = $t->priority;			            		
 }

$contagem = array_count_values($valores);

foreach($contagem AS $numero => $vezes) {
	$priori[get_severity($numero)] = $vezes;
}

$conta = count($valores);

$severity = array('Disaster' => 0,'High' => 0,'Average' => 0,'Warning' => 0,'Information' => 0,'Not Classified' => 0);
$colors = array('Disaster' => '#B10505','High' => '#E97659','Average' => '#FFA059','Warning' => '#FFC859','Information' => '#7499FF','Not Classified' => '#CECECE');

krsort($priori);

$arrDiff = array_diff_key($severity,$priori);

if(count($arrDiff) != '') {
	$priority = $priori;
}

else {
	$priority = array_merge($priori, $arrDiff);
}

krsort($priority);

$names = array_keys($priority);
$values = array_values($priority);

$arrCor = array_intersect_key($colors,$priori);

krsort($arrCor);

$colorsCod = array_values($arrCor);

echo "
<script type='text/javascript'>

$(function () {
    Morris.Donut({
      element: 'severity',
      data: [";
		for($i=0; $i < count($names); $i++) {      
			echo "{value: ". percent($values[$i],$conta).", label: '". _($names[$i])."'},";
		}
		        
		echo "],
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: [";
		for($i=0; $i < count($colorsCod); $i++) {      
			echo "'".$colorsCod[$i]."',";
		}    
      echo "],
      formatter: function (x) { return x + '%'}
    });
  });

</script>";

//disaster #B10505 
//high    #E97659
//average #FFA059
//warn #FFC859
//info #59DB8F
//ok #4BAC64

?>


    
