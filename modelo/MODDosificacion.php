<?php
/**
*@package pXP
*@file gen-MODDosificacion.php
*@author  (ada.torrico)
*@date 18-11-2014 19:17:08
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODDosificacion extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarDosificacion(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='fac.ft_dosificacion_sel';
		$this->transaccion='FAC_DOSI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_dosificacion','int4');
		$this->captura('id_sucursal','int4');
		$this->captura('id_activida_economica','int4');
		$this->captura('notificado','varchar');
		$this->captura('llave','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('nro_tramite','varchar');
		$this->captura('tipo_autoimpresor','varchar');
		$this->captura('nroaut','varchar');
		$this->captura('final','varchar');
		$this->captura('estacion','varchar');
		$this->captura('inicial','varchar');
		$this->captura('tipo','varchar');
		$this->captura('glosa_consumidor','varchar');
		$this->captura('glosa_impuestos','varchar');
		$this->captura('fecha_dosificacion','date');
		$this->captura('id_lugar_pais','int4');
		$this->captura('autoimpresor','varchar');
		$this->captura('nombre_sisfac','varchar');
		$this->captura('fecha_inicio_emi','date');
		$this->captura('nro_siguiente','varchar');
		$this->captura('nro_resolucion','varchar');
		$this->captura('glosa_empresa','varchar');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarDosificacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_dosificacion_ime';
		$this->transaccion='FAC_DOSI_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_activida_economica','id_activida_economica','int4');
		$this->setParametro('notificado','notificado','varchar');
		$this->setParametro('llave','llave','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('tipo_autoimpresor','tipo_autoimpresor','varchar');
		$this->setParametro('nroaut','nroaut','varchar');
		$this->setParametro('final','final','varchar');
		$this->setParametro('estacion','estacion','varchar');
		$this->setParametro('inicial','inicial','varchar');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('glosa_consumidor','glosa_consumidor','varchar');
		$this->setParametro('glosa_impuestos','glosa_impuestos','varchar');
		$this->setParametro('fecha_dosificacion','fecha_dosificacion','date');
		$this->setParametro('id_lugar_pais','id_lugar_pais','int4');
		$this->setParametro('autoimpresor','autoimpresor','varchar');
		$this->setParametro('nombre_sisfac','nombre_sisfac','varchar');
		$this->setParametro('fecha_inicio_emi','fecha_inicio_emi','date');
		$this->setParametro('nro_siguiente','nro_siguiente','varchar');
		$this->setParametro('nro_resolucion','nro_resolucion','varchar');
		$this->setParametro('glosa_empresa','glosa_empresa','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarDosificacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_dosificacion_ime';
		$this->transaccion='FAC_DOSI_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_dosificacion','id_dosificacion','int4');
		$this->setParametro('id_sucursal','id_sucursal','int4');
		$this->setParametro('id_activida_economica','id_activida_economica','int4');
		$this->setParametro('notificado','notificado','varchar');
		$this->setParametro('llave','llave','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('tipo_autoimpresor','tipo_autoimpresor','varchar');
		$this->setParametro('nroaut','nroaut','varchar');
		$this->setParametro('final','final','varchar');
		$this->setParametro('estacion','estacion','varchar');
		$this->setParametro('inicial','inicial','varchar');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('glosa_consumidor','glosa_consumidor','varchar');
		$this->setParametro('glosa_impuestos','glosa_impuestos','varchar');
		$this->setParametro('fecha_dosificacion','fecha_dosificacion','date');
		$this->setParametro('id_lugar_pais','id_lugar_pais','int4');
		$this->setParametro('autoimpresor','autoimpresor','varchar');
		$this->setParametro('nombre_sisfac','nombre_sisfac','varchar');
		$this->setParametro('fecha_inicio_emi','fecha_inicio_emi','date');
		$this->setParametro('nro_siguiente','nro_siguiente','varchar');
		$this->setParametro('nro_resolucion','nro_resolucion','varchar');
		$this->setParametro('glosa_empresa','glosa_empresa','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarDosificacion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='fac.ft_dosificacion_ime';
		$this->transaccion='FAC_DOSI_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_dosificacion','id_dosificacion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>