<?php
/**
*@package pXP
*@file gen-ACTNota.php
*@author  (ada.torrico)
*@date 18-11-2014 19:30:03
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/numLetra.php');
require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class ACTNota extends ACTbase{

	function listarNota(){
		$this->objParam->defecto('ordenacion','id_nota');

		$this->objParam->defecto('dir_ordenacion','asc');


		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODNota','listarNota');
		} else{
			$this->objFunc=$this->create('MODNota');
			
			$this->res=$this->objFunc->listarNota($this->objParam);




		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarNota(){
		$this->objFunc=$this->create('MODNota');	
		if($this->objParam->insertar('id_nota')){
			$this->res=$this->objFunc->insertarNota($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarNota($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarNota(){
			$this->objFunc=$this->create('MODNota');	
		$this->res=$this->objFunc->eliminarNota($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function saveForm(){
		
		
		$this->objFunc=$this->create('MODNota');  
		
		
		$this->res=$this->objFunc->saveForm($this->objParam);
		
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
	
	
	function generarNota(){



		if($this->objParam->getParametro('reimpresion') != ''){
			$this->reImpresion();
		}

		$this->objParam->defecto('ordenacion','nro_nota');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		$this->objFunc=$this->create('MODNota');
		
		$this->res=$this->objFunc->generarNota($this->objParam);


		/*if($this->res->getTipo()!='EXITO'){
			
			$this->res->imprimirRespuesta($this->res->generarJson());
			exit;
		}*/
		
		$notas = $this->res->getDatos();
		
		
		
		$html = "";
		$i = 0;
		
		$V = new EnLetras();
		
		
		
		
		
		foreach ($notas as $item) {
			/* Cadena para qr |nit emisor|razon social emisor|Número de Factura |Número de Autorización|
			|Fecha de emisión|Importe de la compra |Código de Contro|Fecha Límite de Emisión| NIT comprador | razon comprador| */
			//$cadena_qr = "|154422029|BOLIVIANA DE AVIACION|123|123|28/12/2014|67.10|73-65-52-A5-FE|29/12/2014"; 
			$cadena_qr = '|154422029|BOLIVIANA DE AVIACION|123|123|02/10/2014|'.$item['total_devuelto'].'|'.$item['codigo_control'].'| '.$item['nit'].' | '.trim($item['razon']).'|';
			$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,H');
			
			$this->objParam->parametros_consulta['filtro'] = ' 0 = 0 ';
			$this->objParam->addFiltro("deno.id_nota = ". $item['id_nota']);
			
			//obtenemos conceptos originales de esta factura o boleto
			
			if($item['autorizacion'] == 1){ //esta autorizacion es de la nota ya sea de factura o boleto
				
				
				$original = $this->listarBoletosOriginales($item['factura']);
				
			}else{
				
				$original = $this->listarFacturaOriginales($item['factura'],$item['autorizacion']);
				
				
				
			}


				
			//listamos detalle de la nota
			$this->objFunc2=$this->create('MODNotaDetalle');	
			$this->res2=$this->objFunc2->listarNotaDetalle($this->objParam);
			
			if($this->res2->getTipo()!='EXITO'){
			
				$this->res2->imprimirRespuesta($this->res2->generarJson());
				exit;
			}
			
			$detalles = $this->res2->getDatos();
			
			
			
			setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
			

			
			
			$html.='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>devoluciones</title>
						<meta name="author" content="favio figueroa">
						    
					
					  <link rel="stylesheet" href="../../../sis_facturacion/control/print.css" type="text/css" media="print" charset="utf-8">
					  
					</head>
					<body >
					<center>
					<p text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;BOLIVIANA DE AVIACION BOA</br>
					    &nbsp;&nbsp;Av. SIMON LOPEZ No 1582 <br />
					    &nbsp;&nbsp;ZONA CONDEBAMBA <br />
					    TELF. 4122438-4140873 <br />
					    &nbsp;&nbsp;COCHABAMBA-BOLIVIA <br />
					</p>
					</center>
					<hr />
					<p style="text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;NOTA DE CREDITO-DEBITO
					</p>
					<hr />
					<p style="text-align: center;">
					    NIT: 154422029 <br/>
					    N&#176; NOTA FISCAL: '.$item['nro_nota'].'<br>
					    N&#176; AUTORIZACION: '.$item['nroaut'].'<br />
					    ORIGINAL<br />
					    Transporte Regular de Pasajeros y Carga por Via Aerea
					</p>
					<hr/>';
					if($item['estado'] == 9){
					    $html.='<div style="text-align: center; width:350px; font-size: 30pt; ">
					
								        N/C ANULADA<br />
					
								      <hr/>
					
					
								    </div>';
					}
					$html.='<p>
					    Cochabamba '.strftime("%d de %B de %Y", strtotime($item['fecha'])).'<br/>
					    Senor(es): '.trim($item['razon']).'<br/>
					    NIT/CI: '.$item['nit'].'
					</p>
					<hr/>
					<p style="text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;DATOS DE LA TRANSACCION ORIGINAL
					</p>
					<hr/>
					<p>
					    FACTURA: '.$item['factura'].' <br/>
					    AUTORIZACION : '.$item['autorizacion'].'<br>
					    FECHA DE EMISION: '.$item['fecha_fac'].'
					</p>
					
					<table>
					    <!-- <caption>Lorem ipsum dolor sit amet</caption> -->
					
					
					<thead>
					
						<tr><th>Cant</th><th>Concepto</th><th>PU</th><th>SubTotal</th></tr>
					</thead>
					<tbody>';
					$total_original = 0;
					
					foreach ($original as $item_detalle) {
					    $html .= '<tr>
							<td>1</td>
							<td>'.str_replace( "/", " / ", $item_detalle['concepto'] ).'</td>
							<td>'.number_format($item_detalle['importe_original'], 2, '.', '').'</td>
							<td align="center">'.number_format($item_detalle['importe_original'], 2, '.', '').'</td>
							</tr>';
					    $total_original = $total_original + $item_detalle['importe_original'];
					
					}
					
					$html.='</tbody>
					    <tfoot>
					    <tr><td colspan="4" align="right">Total Bs. '.number_format($total_original, 2, '.', '').' &nbsp;&nbsp;&nbsp;</td></tr>
					    </tfoot>
					</table>
					<hr/>
					<p style="text-align: center;">
					    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLE DE LA DEVOLUCION O &nbsp;&nbsp;RESCICION DEL SERVICIO
					</p>
					<hr/>
					<table>
					    <!-- <caption>Lorem ipsum dolor sit amet</caption> -->
					    <thead>
					
					    <tr><th>Cant</th><th>Concepto</th><th>PU</th><th>SubTotal</th></tr>
					    </thead>
					    <tbody>';
					
					    $exento_total = 0;
					    $importe_total = 0;
					    foreach ($detalles as $item_detalle) {
					
					    $exento_total = $exento_total + $item_detalle['exento'];
					    $importe_total = $importe_total + $item_detalle['importe'];
					
					    $html .= '<tr>
							<td>1</td>
							<td>'.str_replace( "/", " / ", $item_detalle['concepto'] ).'</td>
							<td>'.number_format($item_detalle['importe'], 2, '.', '').'</td>
							<td align="center">'.number_format($item_detalle['importe'], 2, '.', '').'</td>
							</tr>';
					    }
					    $total_devolver = $importe_total - $exento_total;
					
					    $html.='</tbody>
					    <tfoot>
					    <tr><td colspan="4" align="right">Total Bs. '.number_format($total_original, 2, '.', '').' &nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
					    <tr><td colspan="3" align="left">MENOS: Importes Exentos :</td><td colspan="1" align="right"> '.number_format($exento_total, 2, '.', '').' &nbsp;</td></tr>
					    <tr><td colspan="4" align="right">Importe Total Devuelto: '.number_format($total_devolver, 2, '.', '').' &nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
					    </tfoot>
					</table>
					
					<hr/>
					<p>
					    Son: '.$V->ValorEnLetras(number_format($total_devolver, 2, '.', ''),"").' <br/>
					    Monto efectivo del Crédito o Débito <br/>
					    (13% del Importe total Devuelto) '.number_format($item['credfis'], 2, '.', '').'
					</p>
					<hr/>
					<p>
					    Codigo de Control: '.$item['codigo_control'].' <br/>
					    Fecha Limite de Emision: '.$item['fecha_limite'].' <br/>
					    OBS: '.$item['nro_liquidacion'].' <br/>
					</p>
					<hr/>
					<!--<div align="center">
								    '.$barcodeobj->getBarcodeHTML(3, 3, 'black').'
								    </div>-->
					<hr/>
					<p style="text-align: center">
					
					    "La reproduccion total <br/>o parcial y/o el uso no autorizado<br/> de esta Nota
					    Fiscal, <br/>constituye un delito a <br/>ser sancionado conforme a Ley"
					
					    <!--ESTA FACTURA CONTRIBUYE<br/> AL DESARROLLO
					    DEL PAIS. EL USO ILICITO<br/> DE ESTA SERA
					    SANCIONADO DE ACUERDO A LEY.<br />-->
					
					</p>
					<p>GRACIAS POR SU PREFERENCIA !
					    <br/> www.boa.bo</p>
					    
						<script language="VBScript">
Sub Print()
       OLECMDID_PRINT = 6
       OLECMDEXECOPT_DONTPROMPTUSER = 2
       OLECMDEXECOPT_PROMPTUSER = 1
       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
End Sub
document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
</script>

<script type="text/javascript"> 
window.onload=function(){self.print();} 
</script> 
					
					</body>
					</html>';

	
		
			$temp[] = $html;
			$i++;
		}
		
		
		$this->res->setDatos($temp);
		$this->res->imprimirRespuesta($this->res->generarJson());		
		
	}

	function listarBoletosOriginales($billete){
		
		
	
		$this->objFunc=$this->create('MODLiquidevolu');	
		$ori =$this->objFunc->listarBoletosConTramos($billete);
		
		return $ori;
	}
	
	function listarFacturaOriginales($factura,$autorizacion){
		
		
		$this->objParam->addParametro('nrofac',$factura);
		$this->objParam->addParametro('nroaut',$autorizacion);
		
		
		$this->objFunc2=$this->create('MODLiquidevolu');	
		$ori =$this->objFunc2->listarFacturaConceptosOriginales($this->objParam);
		return $ori;
	}

	function reImpresion(){

		$this->objFunc2=$this->create('MODNota');

		$re =$this->objFunc2->reImpresion($this->objParam);
		return $re;

	}



	function anularNota(){

		$this->objFunc=$this->create('MODNota');

		$re =$this->objFunc->anularNota($this->objParam);
		$this->generarNota();

	}

	function crearReporteNotas(){
		
		
		
	}
			
}

?>