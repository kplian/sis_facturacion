<?php
/**
*@package pXP
*@file gen-ACTFactura.php
*@author  (favio figueroa penarrieta)
*@date 18-11-2014 19:26:15
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLiquidevolu extends ACTbase{    
			
	function listarLiquidevolu(){
		
		
		$this->objParam->defecto('ordenacion','nroliqui');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		/*if($this->objParam->getParametro('id_sucursal')!=''){
			$this->objParam->addFiltro("factu.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	
		}*/
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarInformixLiquidevolu');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarInformixLiquidevolu($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	
			
}

?>