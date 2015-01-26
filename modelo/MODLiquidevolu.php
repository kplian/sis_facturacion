<?php
/**
*@package pXP
*@file gen-MODFactura.php
*@author  (favio figueroa)
*@date 18-11-2014 19:26:15
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODLiquidevolu extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarLiquidevolu(){
		
		

		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='informix.ft_liquidevolu_sel';
		$this->transaccion='FAC_LIQUI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//Definicion de la lista del resultado del query
		

	
		$this->captura('pais','varchar');
		$this->captura('estacion','varchar');
		$this->captura('docmnt','varchar');
		$this->captura('nroliqui','varchar');
		$this->captura('fecha','date');
		$this->captura('estpago','varchar');
		$this->captura('estado','varchar');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	
	
	function listarLiquitra(){
		
		
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='informix.ft_liquitra_sel';
		$this->transaccion='FAC_LITRA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//Definicion de la lista del resultado del query
		
		/*$this->arreglo = array_merge ($this->arreglo,array(
									"sort"=>'nroliqui' 
									 ));*/
		

		$this->captura('pais','varchar');
		$this->captura('estacion','varchar');
		$this->captura('docmnt','varchar');
		$this->captura('nroliqui','varchar');
		$this->captura('cantidad','int4');
		$this->captura('idtramo','varchar');
		$this->captura('billcupon','numeric');
		$this->captura('cupon','int4');
		$this->captura('origen','varchar');
		$this->captura('destino','varchar');
		$this->captura('estado','varchar');
		$this->captura('concepto','text');
		
		
		
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	
	function listarInformixLiquidevolu(){
    
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='informix.f_liquidevolu_sel';// nombre procedimiento almacenado
        $this->transaccion='INFORMIX_LIQUIDEVOLU_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);
        
        $this->setTipoRetorno('record');
        
        //$this->setParametro('id_proceso_wf','id_proceso_wf','integer');
        
       //Definicion de la lista del resultado del query
        $this->captura('pais','varchar');
		$this->captura('estacion','varchar');
		$this->captura('docmnt','varchar');
		$this->captura('nroliqui','varchar');
		
		
        
        
        //$this->captura('id_estructura_uo','integer');
        //Ejecuta la funcion
        $this->armarConsulta();
        
        //echo $this->getConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;

    }
	
	
	
	
	function listarBoletos(){
		
		

		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='informix.ft_boletos_sel';
		$this->transaccion='FAC_BOLE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//Definicion de la lista del resultado del query
		

	
		$this->captura('billete','numeric');
		$this->captura('fecha','date');
		$this->captura('tcambio','numeric');
		$this->captura('pasajero','varchar');
		$this->captura('moneda','varchar');
		$this->captura('importe','numeric');
		$this->captura('estado','varchar');
		
		$this->captura('nit','numeric');
		$this->captura('razon','varchar');
		$this->captura('monto','numeric');
		$this->captura('exento','numeric');
		$this->captura('fecha_fac','date');
		
		$this->captura('nroaut','numeric');
		$this->captura('nrofac','numeric');
		
	
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}


	function listarBoletoEx(){
		
		
		
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='informix.ft_boletoex_sel';
		$this->transaccion='FAC_BOLEX_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//Definicion de la lista del resultado del query
		

	
		$this->captura('pais','varchar');
		$this->captura('estacion','varchar');
		$this->captura('tipdoc','varchar');
		$this->captura('billete','numeric');
		
		$this->captura('fareit04','varchar');
		$this->captura('totlit04','varchar');
		$this->captura('impit04','varchar');
		$this->captura('impit05','varchar');
		$this->captura('comiit05','varchar');
		
		$this->captura('fecproc','date');
		
		
	
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	
	function listarBoletoOri(){
		
		
		
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='informix.ft_boletoori_sel';
		$this->transaccion='FAC_BOLEORI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
		//Definicion de la lista del resultado del query
		
		
		$this->setParametro('billete','billete','numeric');
		
		$this->arreglo = array("fecha" => "'2014-01-01'");
		$this->setParametro('fecha','fecha','date');
	
		$this->captura('bolori','numeric');
		$this->captura('billete','numeric');
		$this->captura('fecha','date');
		$this->captura('importe','varchar');
		
		
		
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		
		
		if($this->respuesta->datos[0]['bolori'] != NULL || $this->respuesta->datos[0]['bolori']=""){
			
		
			if($this->respuesta->getTipo()=='ERROR'){
				return $this->respuesta;
			}
			else {
				
			  	$this->procedimiento='informix.ft_boletoori_sel';
				$this->transaccion='FAC_BOLEORIS_SEL';
				$this->tipo_procedimiento='SEL';//tipo de transaccion
				$this->setCount(false);
				
				
				
				$this->arreglo = array("bolori" => $this->respuesta->datos[0]['bolori']);	
				
				//while(count($this->arreglo))array_pop($this->arreglo);
			
				$this->setParametro('bolori','bolori','numeric');
				
				
				$this->resetCaptura();
				$this->addConsulta();
				
				
				$this->captura('bolori','numeric');
				
				$this->captura('fecha_ori','date');
				$this->captura('nit','numeric');
				$this->captura('razon','varchar');
				$this->captura('monto','numeric');
				$this->captura('exento','numeric');
				$this->captura('moneda','varchar');
				$this->captura('tcambio','numeric');
				$this->captura('fecha_fac','date');
				
				
				
				$this->armarConsulta();
				$consulta=$this->getConsulta();
						
		  		
		  		while(count($this->respuesta->datos))array_pop($this->respuesta->datos);//limpia el array de datos
				
				$this->ejecutarConsulta($this->respuesta);
			}
		}
		
		//Devuelve la respuesta
		
		
		return $this->respuesta;
	}



	function listarBoletosExistente(){
		
	
		
		$cone=new conexion();		
		$link=$cone->conectarPDOInformix();
		
		
		
	
		$billete = $this->aParam->getParametro('billete');
		$sql_doc = "select count(*) as count from liquidoc where billete = '$billete' ";
		
		$stmt = $link->prepare($sql_doc);
		$stmt->execute();
		$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		if($results[0]['COUNT'] == 0){
			
			$sql_nota = "select count(*) as count from notacrdb where billete = '$billete' ";
			$statement2=$link->prepare($sql_nota);
			$statement2->execute();
			$results2=$statement2->fetchAll(PDO::FETCH_ASSOC);
			
			if($results2[0]['COUNT'] == 0){
				
				//Definicion de variables para ejecucion del procedimientp
				$this->procedimiento='informix.ft_boletos_sel';
				$this->transaccion='FAC_BOLE_SEL';
				$this->tipo_procedimiento='SEL';//tipo de transaccion
				//Definicion de la lista del resultado del query
				
		
			
				$this->captura('billete','numeric');
				$this->captura('fecha','date');
				$this->captura('tcambio','numeric');
				$this->captura('pasajero','varchar');
				$this->captura('moneda','varchar');
				$this->captura('importe','numeric');
				$this->captura('estado','varchar');
				
				$this->captura('nit','numeric');
				$this->captura('razon','varchar');
				$this->captura('monto','numeric');
				$this->captura('exento','numeric');
				$this->captura('fecha_fac','date');
		
				$this->captura('nroaut','numeric');
				$this->captura('nrofac','numeric');
						
				
			
				
				//Ejecuta la instruccion
				$this->armarConsulta();
				$this->ejecutarConsulta();
				
				//Devuelve la respuesta
				return $this->respuesta;
				
			}
		}
	
		
		
		
		
	}
	
	
	
			
	
			
}
?>