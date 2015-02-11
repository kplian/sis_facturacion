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
			//$cadena_qr = "|154422029|BOLIVIANA DE AVIACIÓN|123|123|28/12/2014|67.10|73-65-52-A5-FE|29/12/2014"; 
			$cadena_qr = '|154422029|BOLIVIANA DE AVIACIÓN|123|123|02/10/2014|'.$item['total_devuelto'].'|'.$item['codigo_control'].'| '.$item['nit'].' | '.trim($item['razon']).'|';
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
			
			
			
		
			//var_dump($item['nro_nota']);
			
			
			
			$html ='<!doctype html>
					<html>
					<link media="print" rel="stylesheet" type="text/css" />
					<head>
					<meta charset="utf-8">
					<title>Liquidacion</title>
					
					    <style type="text/css" media="print">
					        @page{
					            margin: 0;
					        }
					    </style>
					
					<style>
					
					        @page { size:76mm 297mm ;}
					
					
					        @media print {
					            @page { size:76mm 297mm ;}
					            body{
					                font-size: 12pt;
					            }
					
					            body{
					                font-size:12pt;
					                font-family:Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Courier New", monospace;
					            }
					            .line{
					                border-bottom:1px dotted black;
					                position:relative;
					
					            }
					
					            .line span{
					                display:inline-block;
					                position:relative;
					                background:white;
					                bottom:-2px;
					                height:100%;
					                padding:0 5px;
					            }
					            .line .price{
					                position:absolute;
					                right:0;
					            }​
					
					        }
					
					
					
					    </style>
					</head>
					
					<body onLoad="window.print();">
					
					<div  style="text-align: center;width:350px;">
					    BOLIVIANA DE AVIACION - BoA</br>
					    CALLE NATANIEL AGUIRRE ESQUINA </br>JORDAN NRO 202 EDIF EX.BBA PISO 1</br>
					    TELEF 4141956-4140873</br>
					    COCHABAMBA - BOLIVIA</br>
					</div>
					
					<div style="text-align: center; width:350px; ">
					
					    <div class="line"></div>
					    <br />
					    NOTA
					    DE CREDITO - DEBITO <br />
					    <br />
					    <div class="line"></div>
					
					    NIT: 154422029 <br>
					    N.NOTA FISCAL N: 121815 <br>
					    AUTORIZACION N: 3901021174745
					    <div class="line"></div>
					</div>
					
					
					<div style="text-align: center; width:350px; ">
					    <table width="100%" border="0">
					    
							 <tr>
					            <td colspan="4" align="left">Cochabamba '.strftime("%d de %B de %Y", strtotime($item['fecha'])).'</td>
					
					        </tr>
					
					        <tr>
					            <td colspan="4" align="left">Señor(es):'.trim($item['razon']).'</td>
					
					        </tr>
					
					        <tr>
					            <td colspan="4" align="left">NIT/CI:'.$item['nit'].'</td>
					
					        </tr>
					        
							<tr>
					            <td colspan="4" class="line"></td>
					        </tr>
        
					        <tr>
					            <td colspan="4"><div align="center">DATOS DE LA TRANSACCION ORIGINAL</div></td>
					        </tr>
					        <tr>
					            <td align="left">N.Factura:</td>
					            <td align="left">'.$item['nro_nota'].'</td>
					            <td></td>
					            <td></td>
					        </tr>
					
					        <tr>
					            <td align="left">Autorizacion:</td>
					            <td align="left">'.$item['nroaut'].'</td>
					            <td>&nbsp;</td>
					            <td>&nbsp;</td>
					        </tr>
					        <tr>
					            <td align="left">Fecha:</td>
					            <td align="left">30/11/2014</td>
					            <td>&nbsp;</td>
					            <td>&nbsp;</td>
					        </tr>
					
					        
					        
							</table>
					
					    <hr class="line" />
					
					    <table width="100%" border="0">
					
					        <tr>
					            <td>CANT</td>
					            <td>CONCEPTO</td>
					            <td>P.U</td>
					            <td>IMPORTE</td>
					        </tr>
					        <tr>
					            <td colspan="4" class="line"></td>
					        </tr>';
							$total_original = 0;
							
					         foreach ($original as $item_detalle) {
						$html.='<tr>
					            <td>1</td>
					            <td>'.$item_detalle['concepto'].'</td>
					            <td>'.number_format($item_detalle['importe_original'], 2, '.', '').'</td>
					            <td>'.number_format($item_detalle['importe_original'], 2, '.', '').'</td>
					        </tr>';
							$total_original = $total_original + $item_detalle['monto'];
					        }


					
					      
	        	    $html.= '<tr>
					            <td colspan="4" class="line"></td>
					        </tr>
					        <tr>
					            <td colspan="2" align="right">Total General:</td>
					            <td colspan="2" align="center">'.number_format($total_original, 2, '.', '').'</td>
					        </tr>
					    </table>
					
					    <hr class="line" />
					
					    <table width="100%" border="0">
					        <tr>
					            <td colspan="4"><div align="center">DETALLE DE LA DEVOLUCINO O <br /> RESCICION DEL SERVICIO</div></td>
					        </tr>
					
					
					
					        <tr>
					            <td colspan="4" class="line"></td>
					        </tr>
					
					        <tr>
					            <td>CANT</td>
					            <td>CONCEPTO</td>
					            <td>P/UNIT</td>
					            <td>IMPORTE</td>
					            
					        </tr>
					        <tr>
					            <td colspan="4" class="line"></td>
					        </tr>';
					      $exento_total = 0;
						  $importe_total = 0;
						   foreach ($detalles as $item_detalle) {
						   	
						   	$exento_total = $exento_total + $item_detalle['exento'];
							$importe_total = $importe_total + $item_detalle['importe'];
							
						$html.='<tr>
								<td>1</td>
					            <td>'.$item_detalle['concepto'].'</td>
					            <td>'.number_format($item_detalle['importe'], 2, '.', '').'</td>
					            <td>'.number_format($item_detalle['importe'], 2, '.', '').'</td>
					        </tr>';
					        }
							$total_devolver = $importe_total - $exento_total;
					
					       $html.= '<tr>
					            <td colspan="4" class="line"></td>
					        </tr>
					
					        <tr>
					            <td colspan="2" align="right">Total:</td>
					            <td colspan="2" align="center">'.number_format($importe_total, 2, '.', '').'</td>
					        </tr>
					        <tr>
					            <td colspan="2" align="right">Menos: Importes Exentos Bs:</td>
					            <td colspan="2" align="center">'.number_format($exento_total, 2, '.', '').'</td>
					        </tr>
					        <tr>
					            <td colspan="2" align="right">Importe Total Devuelto</td>
					            <td colspan="2" align="center">'.number_format($total_devolver, 2, '.', '').'</td>
					        </tr>
					    </table>
					
					    <hr class="line"/>
					
					    <table width="100%" border="0">
					        <tr>
					            <td colspan="4" align="left">Monto efectivo del Credito</td>
					        </tr>
					
					
					        <tr>
					            <td colspan="3" align="left">(13% del Imp.Total Devuelto)</td>
					            <td colspan="1" align="right">'.number_format($item['credfis'], 2, '.', '').'</td>
					        </tr>
					
					        <tr>
					            <td colspan="4" class="line"></td>
					        </tr>
					
					
					        <tr>
					            <td colspan="4" align="left">Son: '.$V->ValorEnLetras(number_format($item['total_devuelto'], 2, '.', ''),"").'</td>
					        </tr>
					        <tr>
					            <td colspan="4" align="left">Codigo de Control: '.$item['codigo_control'].'</td>
					        </tr>
					        <tr>
					            <td colspan="4" align="left">Fecha Limite de Emision: '.$item['fecha_limite'].'</td>
					        </tr>

					         <tr>
					            <td colspan="4" align="left">OBS: '.$item['nro_liquidacion'].'</td>
					        </tr>
					        
							<tr>
					            <td colspan="4" align="center">'.$barcodeobj->getBarcodeHTML(3, 3, 'black').'</td>
					        </tr>
					        
							
					
					
					        <tr style="margin-top:10px;">
					            <td colspan="4" align="left">ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY”</td>
					        </tr>
					
					
					        <tr style="margin-top:10px;">
					            <td colspan="4" align="left">Ley N.453 "Tienes derecho a un trto equitativo sin discriminacion en la oferta de servicios"</td>
					        </tr>
					
					
					    </table>
					</div>
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

	function crearReporteNotas(){
		
		
		
	}
			
}

?>