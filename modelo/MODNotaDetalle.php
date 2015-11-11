<?php
/**
*@package pXP
*@file gen-MODNotaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:32:09
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODNotaDetalle extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarNotaDetalle(){
		
		
		
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='fac.ft_nota_detalle_sel';
		$this->transaccion='FAC_DENO_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
				
		//Definicion de la lista del resultado del query
		$this->captura('id_nota_detalle','int4');
		$this->captura('id_factura_detalle','int4');
		$this->captura('id_nota','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('importe','numeric');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('cantidad','int4');
		
		$this->captura('concepto','varchar');
		
		$this->captura('exento','numeric');
		$this->captura('total_devuelto','numeric');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		

		
		//Ejecuta la instruccion
		$this->armarConsulta();
		
		
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarNotaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_detalle_ime';
		$this->transaccion='FAC_DENO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura_detalle','id_factura_detalle','int4');
		$this->setParametro('id_nota','id_nota','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('importe','importe','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarNotaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_detalle_ime';
		$this->transaccion='FAC_DENO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_nota_detalle','id_nota_detalle','int4');
		$this->setParametro('id_factura_detalle','id_factura_detalle','int4');
		$this->setParametro('id_nota','id_nota','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('importe','importe','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarNotaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_detalle_ime';
		$this->transaccion='FAC_DENO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_nota_detalle','id_nota_detalle','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>