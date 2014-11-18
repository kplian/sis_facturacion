<?php
/**
*@package pXP
*@file gen-ACTNota.php
*@author  (ada.torrico)
*@date 18-11-2014 19:30:03
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

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
			
}

?>