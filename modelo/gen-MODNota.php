<?php
/**
*@package pXP
*@file gen-MODNota.php
*@author  (ada.torrico)
*@date 18-11-2014 19:30:03
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODNota extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarNota(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='fac.ft_nota_sel';
		$this->transaccion='FAC_NOT_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_nota','int4');
		$this->captura('id_factura','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_moneda','int4');
		$this->captura('estacion','varchar');
		$this->captura('fecha','date');
		$this->captura('excento','numeric');
		$this->captura('total_devuelto','numeric');
		$this->captura('tcambio','numeric');
		$this->captura('id_liquidacion','varchar');
		$this->captura('nit','varchar');
		$this->captura('estado','varchar');
		$this->captura('credfis','numeric');
		$this->captura('nro_liquidacion','varchar');
		$this->captura('monto_total','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('nro_nota','varchar');
		$this->captura('razon','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarNota(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_ime';
		$this->transaccion='FAC_NOT_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('estacion','estacion','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('excento','excento','numeric');
		$this->setParametro('total_devuelto','total_devuelto','numeric');
		$this->setParametro('tcambio','tcambio','numeric');
		$this->setParametro('id_liquidacion','id_liquidacion','varchar');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('credfis','credfis','numeric');
		$this->setParametro('nro_liquidacion','nro_liquidacion','varchar');
		$this->setParametro('monto_total','monto_total','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_nota','nro_nota','varchar');
		$this->setParametro('razon','razon','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarNota(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_ime';
		$this->transaccion='FAC_NOT_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_nota','id_nota','int4');
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('estacion','estacion','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('excento','excento','numeric');
		$this->setParametro('total_devuelto','total_devuelto','numeric');
		$this->setParametro('tcambio','tcambio','numeric');
		$this->setParametro('id_liquidacion','id_liquidacion','varchar');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('credfis','credfis','numeric');
		$this->setParametro('nro_liquidacion','nro_liquidacion','varchar');
		$this->setParametro('monto_total','monto_total','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_nota','nro_nota','varchar');
		$this->setParametro('razon','razon','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarNota(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_nota_ime';
		$this->transaccion='FAC_NOT_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_nota','id_nota','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>