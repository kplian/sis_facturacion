<?php
/**
*@package pXP
*@file gen-ACTFacturaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:28:06
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFacturaDetalle extends ACTbase{    
			
	function listarFacturaDetalle(){
		$this->objParam->defecto('ordenacion','id_factura_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFacturaDetalle','listarFacturaDetalle');
		} else{
			$this->objFunc=$this->create('MODFacturaDetalle');
			
			$this->res=$this->objFunc->listarFacturaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFacturaDetalle(){
		$this->objFunc=$this->create('MODFacturaDetalle');	
		if($this->objParam->insertar('id_factura_detalle')){
			$this->res=$this->objFunc->insertarFacturaDetalle($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFacturaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFacturaDetalle(){
			$this->objFunc=$this->create('MODFacturaDetalle');	
		$this->res=$this->objFunc->eliminarFacturaDetalle($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>