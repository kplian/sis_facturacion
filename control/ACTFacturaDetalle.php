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
		
		
		if($this->objParam->getParametro('id_factura')!=''){
			
			$this->objParam->defecto('ordenacion','id_factura_detalle');

			$this->objParam->defecto('dir_ordenacion','asc');
		
			$this->objParam->addFiltro("defa.id_factura = ".$this->objParam->getParametro('id_factura'));	
			
			$this->objFunc=$this->create('MODFacturaDetalle');
			
			$this->res=$this->objFunc->listarFacturaDetalle($this->objParam);
		}
		else if($this->objParam->getParametro('nroliqui')!=''){
			
			$this->objParam->defecto('ordenacion','nroliqui');

			$this->objParam->defecto('dir_ordenacion','asc');
			
			
			$this->objParam->addFiltro("lite.nroliqui = ''".$this->objParam->getParametro('nroliqui')."''");
		
			
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->liquidevolu($this->objParam);
			
			
			
		}
		
		else if($this->objParam->getParametro('nrofac')!='' && $this->objParam->getParametro('nroaut')!=''){
			
			
			
			$this->objFunc=$this->create('MODLiquidevolu');
			
			$this->res=$this->objFunc->conceptosComputarizada($this->objParam);
			
			//$this->res->imprimirRespuesta($this->res->generarJson());
			
			
			
		}
		/*else if($this->objParam->getParametro('pop')!=''){
				
			if($this->objParam->getParametro('billete')!=''){
				
				$this->objFunc=$this->create('MODLiquidevolu');
				$this->res=$this->objFunc->listarBoletosExistente($this->objParam);
			}
			else if ($this->objParam->getParametro('factura')!='') {
				
			}
		}*/
		
		
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