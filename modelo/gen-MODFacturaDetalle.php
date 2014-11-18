<?php
/**
*@package pXP
*@file gen-MODFacturaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:28:06
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFacturaDetalle extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='fac.ft_factura_detalle_sel';
		$this->transaccion='FAC_DEFA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_factura_detalle','int4');
		$this->captura('id_factura','int4');
		$this->captura('id_concepto_ingas','int4');
		$this->captura('precio_unitario','varchar');
		$this->captura('concepto','varchar');
		$this->captura('tipo_concepto','varchar');
		$this->captura('renglon','varchar');
		$this->captura('importe','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('cantidad','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_detalle_ime';
		$this->transaccion='FAC_DEFA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
		$this->setParametro('precio_unitario','precio_unitario','varchar');
		$this->setParametro('concepto','concepto','varchar');
		$this->setParametro('tipo_concepto','tipo_concepto','varchar');
		$this->setParametro('renglon','renglon','varchar');
		$this->setParametro('importe','importe','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('cantidad','cantidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_detalle_ime';
		$this->transaccion='FAC_DEFA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura_detalle','id_factura_detalle','int4');
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
		$this->setParametro('precio_unitario','precio_unitario','varchar');
		$this->setParametro('concepto','concepto','varchar');
		$this->setParametro('tipo_concepto','tipo_concepto','varchar');
		$this->setParametro('renglon','renglon','varchar');
		$this->setParametro('importe','importe','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('cantidad','cantidad','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFacturaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_detalle_ime';
		$this->transaccion='FAC_DEFA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura_detalle','id_factura_detalle','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>