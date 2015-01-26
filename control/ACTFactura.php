<?php
/**
*@package pXP
*@file gen-ACTFactura.php
*@author  (ada.torrico)
*@date 18-11-2014 19:26:15
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFactura extends ACTbase{    
			
	function listarFactura(){
		$this->objParam->defecto('ordenacion','id_factura');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		if($this->objParam->getParametro('id_sucursal')!=''){
			$this->objParam->addFiltro("factu.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFactura','listarFactura');
		} else{
			$this->objFunc=$this->create('MODFactura');
			
			$this->res=$this->objFunc->listarFactura($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFactura(){
		$this->objFunc=$this->create('MODFactura');	
		if($this->objParam->insertar('id_factura')){
			$this->res=$this->objFunc->insertarFactura($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFactura($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFactura(){
			$this->objFunc=$this->create('MODFactura');	
		$this->res=$this->objFunc->eliminarFactura($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>