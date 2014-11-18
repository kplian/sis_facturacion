<?php
/**
*@package pXP
*@file gen-MODFactura.php
*@author  (ada.torrico)
*@date 18-11-2014 19:26:15
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFactura extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFactura(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='fac.ft_factura_sel';
		$this->transaccion='FAC_FACTU_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_factura','int4');
		$this->captura('id_agencia','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_actividad_economica','int4');
		$this->captura('id_moneda','int4');
		$this->captura('nit','varchar');
		$this->captura('por_comis','numeric');
		$this->captura('tcambio','numeric');
		$this->captura('importe_comis','numeric');
		$this->captura('codigo_control','varchar');
		$this->captura('nro_factura','varchar');
		$this->captura('contabilizado','varchar');
		$this->captura('fecha','date');
		$this->captura('observacion','varchar');
		$this->captura('renglon','varchar');
		$this->captura('monto','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('comision','numeric');
		$this->captura('razon','varchar');
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
			
	function insertarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_ime';
		$this->transaccion='FAC_FACTU_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_agencia','id_agencia','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('por_comis','por_comis','numeric');
		$this->setParametro('tcambio','tcambio','numeric');
		$this->setParametro('importe_comis','importe_comis','numeric');
		$this->setParametro('codigo_control','codigo_control','varchar');
		$this->setParametro('nro_factura','nro_factura','varchar');
		$this->setParametro('contabilizado','contabilizado','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('observacion','observacion','varchar');
		$this->setParametro('renglon','renglon','varchar');
		$this->setParametro('monto','monto','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('comision','comision','numeric');
		$this->setParametro('razon','razon','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_ime';
		$this->transaccion='FAC_FACTU_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_factura','id_factura','int4');
		$this->setParametro('id_agencia','id_agencia','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_actividad_economica','id_actividad_economica','int4');
		$this->setParametro('id_moneda','id_moneda','int4');
		$this->setParametro('nit','nit','varchar');
		$this->setParametro('por_comis','por_comis','numeric');
		$this->setParametro('tcambio','tcambio','numeric');
		$this->setParametro('importe_comis','importe_comis','numeric');
		$this->setParametro('codigo_control','codigo_control','varchar');
		$this->setParametro('nro_factura','nro_factura','varchar');
		$this->setParametro('contabilizado','contabilizado','varchar');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('observacion','observacion','varchar');
		$this->setParametro('renglon','renglon','varchar');
		$this->setParametro('monto','monto','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('comision','comision','numeric');
		$this->setParametro('razon','razon','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFactura(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_factura_ime';
		$this->transaccion='FAC_FACTU_ELI';
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