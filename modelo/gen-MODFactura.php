<?php
/**
*@package pXP
*@file gen-MODFactura.php
*@author  (admin)
*@date 28-02-2014 19:29:02
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFactura extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFactura(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='factu.ft_factura_sel';
		$this->transaccion='FAC_FACT_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_factura','int4');
		$this->captura('codigo_control','varchar');
		$this->captura('id_dosificacion','int4');
		$this->captura('autorizado','varchar');
		$this->captura('id_dosificacion','int4');
		$this->captura('id_vendedor','int4');
		$this->captura('estado','varchar');
		$this->captura('fecha','date');
		$this->captura('fecha_limite','date');
		$this->captura('tipo','varchar');
		$this->captura('impresion','int4');
		$this->captura('monto','numeric');
		$this->captura('texto_factura','text');
		$this->captura('nit','varchar');
		$this->captura('nombre','varchar');
		$this->captura('numero_factura','int8');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='factu.ft_factura_ime';
		$this->transaccion='FAC_FACT_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo_control','codigo_control','varchar');
		$this->setParametro('id_dosificacion','id_dosificacion','int4');
		$this->setParametro('autorizado','autorizado','varchar');
		$this->setParametro('id_dosificacion','id_dosificacion','int4');
		$this->setParametro('id_vendedor','id_vendedor','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('fecha_limite','fecha_limite','date');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('impresion','impresion','int4');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('texto_factura','texto_factura','text');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('numero_factura','numero_factura','int8');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='factu.ft_factura_ime';
		$this->transaccion='FAC_FACT_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('codigo_control','codigo_control','varchar');
		$this->setParametro('id_dosificacion','id_dosificacion','int4');
		$this->setParametro('autorizado','autorizado','varchar');
		$this->setParametro('id_dosificacion','id_dosificacion','int4');
		$this->setParametro('id_vendedor','id_vendedor','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('fecha_limite','fecha_limite','date');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('impresion','impresion','int4');
		$this->setParametro('monto','monto','numeric');
		$this->setParametro('texto_factura','texto_factura','text');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('numero_factura','numero_factura','int8');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='factu.ft_factura_ime';
		$this->transaccion='FAC_FACT_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura','id_factura','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>