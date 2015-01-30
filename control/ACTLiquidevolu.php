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
		
		$this->objParam->addFiltro("li.estado=''1''"); //que no este pagada
		$this->objParam->addFiltro("li.estpago=''N''"); // que no este anulada
		
		/*if($this->objParam->getParametro('id_sucursal')!=''){
			$this->objParam->addFiltro("factu.id_sucursal = ".$this->objParam->getParametro('id_sucursal'));	
		}*/
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarLiquidevolu');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarLiquidevolu($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	function listarLiquitra(){
		
	
		$this->objParam->defecto('ordenacion','renglon');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		if($this->objParam->getParametro('nroliqui')!=''){
			$this->objParam->addFiltro("lite.nroliqui = ".$this->objParam->getParametro('nroliqui'));	
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarLiquitra');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarLiquitra($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	
	
	function listarBoletos(){
		
	
		$this->objParam->defecto('ordenacion','bo.billete');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		$this->objParam->addFiltro("bo.estado = ''1''");
		
		if($this->objParam->getParametro('billete')!=''){
			$this->objParam->addFiltro("bo.billete = ".$this->objParam->getParametro('billete'));	
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarBoletos');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarBoletos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	
	
	//funcion para ver si esta cambinada con otro boleto
	function listarBoletoEx(){
		
		
		$this->objParam->defecto('ordenacion','billete');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		$this->objParam->addFiltro("ex.fecproc >= ''2014-01-01''"); //que no este pagada
		
		
		
		if($this->objParam->getParametro('billete')!=''){
			$this->objParam->addFiltro("ex.billete = ".$this->objParam->getParametro('billete'));	
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarLiquitra');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarBoletoEx($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	
	
	function listarBoletoOri(){
		
		
		$this->objParam->defecto('ordenacion','ori.billete');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		//$this->objParam->addFiltro("ori.fecha >= ''2014-01-01''"); //que no este pagada
		
		
		
		/*if($this->objParam->getParametro('billete')!=''){
			$this->objParam->addFiltro("ori.billete = ".$this->objParam->getParametro('billete'));	
		}*/
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarLiquitra');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarBoletoOri($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
		
		
	}
	
	
	function listarBoletosExistente(){
		
	
		$this->objParam->defecto('ordenacion','bo.billete');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		$this->objParam->addFiltro("bo.estado = ''1''");
		
		if($this->objParam->getParametro('billete')!=''){
			$this->objParam->addFiltro("bo.billete = ".$this->objParam->getParametro('billete'));	
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODLiquidevolu','listarBoletos');
		} else{
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->listarBoletosExistente($this->objParam);
		}
		
		//$this->res->imprimirRespuesta($this->res->generarJson());
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	
	function listarFacturaDevolucion(){
		
		$this->objFunc=$this->create('MODLiquidevolu');
			
		$this->res=$this->objFunc->listarFacturaDevolucion($this->objParam);
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
				
	
			
}

?>