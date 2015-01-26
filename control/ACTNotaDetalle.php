<?php
/**
*@package pXP
*@file gen-ACTNotaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:32:09
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTNotaDetalle extends ACTbase{    
			
	function listarNotaDetalle(){
		$this->objParam->defecto('ordenacion','id_nota_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODNotaDetalle','listarNotaDetalle');
		} else{
			$this->objFunc=$this->create('MODNotaDetalle');
			
			$this->res=$this->objFunc->listarNotaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarNotaDetalle(){
		$this->objFunc=$this->create('MODNotaDetalle');	
		if($this->objParam->insertar('id_nota_detalle')){
			$this->res=$this->objFunc->insertarNotaDetalle($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarNotaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarNotaDetalle(){
			$this->objFunc=$this->create('MODNotaDetalle');	
		$this->res=$this->objFunc->eliminarNotaDetalle($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>