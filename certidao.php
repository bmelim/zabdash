
<?php
require_once '../include/config.inc.php';
require_once '../include/hosts.inc.php';
require_once '../include/actions.inc.php';

include('config.php');

$zabUser = 'deare';
$zabPass = '.#deare#.';

require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;
$api = new ZabbixApi($zabURL.'api_jsonrpc.php', ''. $zabUser .'', ''. $zabPass .'');

if(isset($_REQUEST['hostid']) && $_REQUEST['hostid'] != '' && $_REQUEST['hostid'] != 0) {	
	$include = "1";
	$hostid = $_REQUEST['hostid'];	
	$time_init = $_REQUEST['init'];
	$time_rec = $_REQUEST['rec'];		
	$period = $_REQUEST['per']; 
}

switch (date("m")) {
    case "01": $mes = 'Janeiro'; break;
    case "02": $mes = 'Fevereiro'; break;
    case "03": $mes = 'Março'; break;
    case "04": $mes = 'Abril'; break;
    case "05": $mes = 'Maio'; break;
    case "06": $mes = 'Junho'; break;
    case "07": $mes = 'Julho'; break;
    case "08": $mes = 'Agosto'; break;
    case "09": $mes = 'Setembro'; break;
    case "10": $mes = 'Outubro'; break;
    case "11": $mes = 'Novembro'; break;
    case "12": $mes = 'Dezembro'; break;
    }


	$dbHosts = DBselect( 'SELECT name, status, available FROM hosts WHERE hostid='.$hostid);
	$host = DBFetch($dbHosts);	

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<meta http-equiv='refresh' content='600'>-->

<title>Zabdash</title>

<!-- Bootstrap -->
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script src="js/media/js/jquery.dataTables.min.js"></script>
<link href="js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
<script src="js/media/js/dataTables.bootstrap.js"></script>

</head>

<body style="background:#fff !important;">

<?php	

$content = '
<page backtop="5mm" backbottom="5mm" backleft="15mm" backright="10mm"> 
      <page_header> 
      </page_header>
      <page_footer align="center">
    		[[page_cu]]/[[page_nb]]
  		</page_footer>
  		
	<div class="col-md-12 col-sm-12" style="background:#fff !important; margin-top:30px; margin-bottom: 35px; float:none; margin-right:auto; margin-left:auto;">
		
		<div id="certidao" class="col-md-12 col-sm-12">
			<table class="align" width="600" border="0" style="font-family:Arial;font-size:14px;letter-spacing: 0.1em;text-align: justify;" >
				<tbody>
					<tr>
						<td width="140" style="text-align: center;"><img src="html2pdf/mpro.png" alt="logo" height="80" ></td>
						<td><p style="vertical-align:middle; text-align: center; font-size:16pt;">Ministério Público do Estado de Rondônia</p></td>
					</tr>
					<tr>
						<td colspan="2">
							<p>&nbsp;</p>
							<p style="text-align: center; margin-left:120px; margin-right:60px; font-size: 16pt;">Certidão</p>
							<p>&nbsp;</p>
							<p style="margin-left:100px; margin-right:60px; text-align: justify;" >
							 &nbsp;&nbsp;&nbsp;&nbsp;O Departamento de Redes e Comunicação de Dados certifica que consta em seus registros a paralisação do link de 
							 dados da promotoria de <b>'; 
		  $content .= $host['name']; $content .='</b> no seguinte intervalo <b><br>'; 
		  $content .= $time_init . ' a ' . $time_rec .' ('. time_ext($period) .')';	   
		  $content .= '</b>, motivo pelo qual ficou impossibilitado o registro de ponto. 
					      </p>
					      <p>&nbsp;</p>
					      <p style="text-align: right; margin-left:120px; margin-right:50px;"><br>Porto Velho, '; 
		  $content .= date('d'). " de ". $mes ." de ". date('Y') ." </p>";
		  $content .= '<p>&nbsp;</p>
					      <br>
					      <br>
					      <p style="text-align: center; margin-left:120px; margin-right:50px;"><img src="img/chefe_deare.png" width="220"/></p>				      				      
					      <p style="text-align: center; margin-left:120px; margin-right:50px;">Chefe do Departamento de Redes</p>
					      <p>&nbsp;</p>
					      <p>&nbsp;</p>
						</td>	
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	</page>';	


require_once('./html2pdf/html2pdf.class.php');

$filename = "certidao_ponto.pdf";

$html2pdf = new HTML2PDF('P', 'A4', 'en');
$html2pdf->writeHTML($content);

ob_end_clean();
$html2pdf->Output($filename,'D');
?>	
	
</body>
</html>
