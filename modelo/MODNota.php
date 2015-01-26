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

	private function montoTotal($items) {
		$res = 0;
		foreach ($items as $item) {
			$res += $item->importe;
		}
		if ($res == 0) {
			throw new Exception("El importe total no puede ser cero", 1);
		}
		return $res;
	}
	
	
	private function totalDevuelto($items) {
		$res = 0;
		foreach ($items as $item) {
			$res += $item->exento;
		}
		if ($res == 0) {
			throw new Exception("El importe total no puede ser cero", 1);
		}
		return $res;
	}
	
	
	
	
	function saveFormLiquidacion(){
		
		
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$items = json_decode($this->aParam->getParametro('newRecords'));
		
		
		
		$nroliqui = $this->aParam->getParametro('liquidevolu');
		$boleto = $this->aParam->getParametro('boleto');
		$pasajero = $this->aParam->getParametro('pasajero');
		$sucursal = $this->aParam->getParametro('sucursal_id');
		
		
		
		try {
			//obtener sucursal del usuario 
		  	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  	$link->beginTransaction();
			$informix->beginTransaction();
			
			
			
			
			
			//insertar nota de cada uno de las liquidaciones o boletos relacionados
			foreach ($items as $item) {
				
				
				$dosi = $link->prepare("select id_dosificacion,
										       nro_siguiente,
										       fecha_inicio_emi,
										       fecha_limite,
										       nroaut,
										       llave
										from fac.tdosificacion
										order by fecha_inicio_emi DESC
										limit 1 for update;");
				
				$dosi->execute();
					
				$dosi_result = $dosi->fetchAll(PDO::FETCH_ASSOC);
				$fecha_now = new DateTime("now");
				//var_dump($dosi_result[0]['fecha_limite']);
				/*if($dosi_result[0]['fecha_inicio_emi'] <= $fecha_now == true && $dosi_result[0]['fecha_limite'] > $fecha_now == true){
					
					var_dump("hola esta correcto");
				}*/
				
				//$func_cod_con = $link->prepare("select pxp.f_gen_cod_control('SQ@Aa6vS%ML8%iEP*b#xh@_zpCZKi)Q6m5N(B(MIK(#K7nyU3\mPDdj$(A[EaH6#','3904001069175','2110','5298636','20140506','522')");
				
				$date = new DateTime('now');
				//echo $date->format('Ymd'); 
				
				$id_dosi = $dosi_result[0]['id_dosificacion'];
				$credfis = $item->total_devuelto * 0.13;
				
				$func_cod_con = $link->prepare("select pxp.f_gen_cod_control(
												'".$dosi_result[0]['llave']."',
												'".$dosi_result[0]['nroaut']."','".$dosi_result[0]['nro_siguiente']."','".$item->nro_nit."','".$date->format('Ymd')."','".$item->total_devuelto."')");
				$func_cod_con->execute();
					
				$codigo_control = $func_cod_con->fetchAll(PDO::FETCH_ASSOC);
				
				//var_dump($codigo_control[0]['f_gen_cod_control']);
				
				$stmt = $link->prepare("INSERT INTO fac.tnota
													(
													
													  id_usuario_reg,
													  id_usuario_mod,
													  fecha_reg,
													  fecha_mod,
													  estado_reg,
													  id_usuario_ai,
													  usuario_ai,
													 
													  estacion,
													  id_sucursal,
													  estado,
													  id_factura,
													  nro_nota,
													  fecha,
													  razon,
													  tcambio,
													  nit,
													  id_liquidacion,
													  nro_liquidacion,
													  id_moneda,
													  monto_total,
													  excento,
													  total_devuelto,
													  credfis,
													  billete,
													  codigo_control,
													  id_dosificacion
													) 
						
													VALUES (
													
													  ". $_SESSION['ss_id_usuario'] .",
													  null,
													  now(),
													  null,
													  'activo',
													  ". $_SESSION['ss_id_usuario'] .",
													  null,
													
													  'estacion',
													  '1',
													  'estado',
													  1,
													  '".$dosi_result[0]['nro_siguiente']."',
													   now(),
													  '". $item->razon ."',
													  '6.9',
													  '". $item->nro_nit ."',
													  1,
													  '". $item->nroliqui ."',
													  1,
													  ". $item->importe_devolver .",
													   ". $item->exento .",
													   '". $item->total_devuelto ."',
													  ". $credfis .",
													  '".$item->concepto."',
													  '".$codigo_control[0]['f_gen_cod_control']."',
													  '".$id_dosi."'
													)RETURNING id_nota;");
													
				
				
				$dosi_up = $link->prepare("update fac.tdosificacion set nro_siguiente = (nro_siguiente + 1) where id_dosificacion = '$id_dosi'");
				
				
				$dosi_up->execute();
									
				$stmt->execute();
				
				$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				
				$stmt2 = $link->prepare("INSERT INTO 
							  fac.tnota_detalle
							(
							  id_usuario_reg,
							  estado_reg,
							  id_nota,
							  importe,
							  cantidad,
							  concepto
							) 
							VALUES (
							  ". $_SESSION['ss_id_usuario'] .",
							  'activo',
							  '".$results[0]['id_nota']."',
							  '". $item->total_devuelto ."',
							  1,
							   '".$item->concepto."'
							);");
							
											
				$stmt2->execute();
				
				$temp[] = $results[0]['id_nota'];
				
				
				
				/* informix insert nota*/
				$sql_in = "INSERT INTO ingresos:notaprueba
							(pais, estacion, puntoven, sucursal,
							 estado, billete, nrofac, nroaut,
							  nronota, nroautnota, fecha, tcambio, 
							  razon, nit, nroliqui, moneda, 
							  monto, exento, ptjiva, neto,
							   credfis, observa, usuario, fechareg,
							    horareg, devuelto, saldo)
						VALUES 
							('BO', 'CBB', '56999913', '0',
							 '1', '".$item->nro_billete."', '', '".$dosi_result[0]['nroaut']."',
							  '".$dosi_result[0]['nro_siguiente']."' , '390102293247', ".$date->format('Y-m-d')." , '6.960000', 
							  '".trim($item->razon)."' , '".$item->nro_nit."' , '". $item->nroliqui ."', 'BOB',
							   '".$item->total_devuelto."' , '". $item->exento ."', '13.000', '".$item->total_devuelto."',
							    ". $credfis .", 'F22102', 'rag', ".$date->format('Y-m-d')." ,
							     '15:18:17', '0.00', '".$item->total_devuelto ."');";
										
				$info_nota_ins = $informix->prepare($sql_in);
				
				
				$info_nota_ins->execute();
				
											
				/*fin informix*/
				
			}
				
				$link->commit();
				$informix->commit();
				
				$this->respuesta=new Mensaje();
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				
				$this->respuesta->setDatos($temp);
				
				$this->respuesta->getDatos();
				
				
		
				} catch (Exception $e) {			
				    $link->rollBack();
					$informix->rollBack();
					
				    $this->respuesta=new Mensaje();
					if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
						$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
					} else if ($e->getCode() == 2) {//es un error en bd de una consulta
						$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
					} else {//es un error lanzado con throw exception
						throw new Exception($e->getMessage(), 2);
					}
				}	
				
		return $this->respuesta;	
        
		
		
		
		
		
	}
	
	function saveForm(){
		
        //crear conexion pdo
        		
        $cone=new conexion();		
		$link=$cone->conectarpdo();
		//$factura_util = new UTILFacturacion();
		// decodificar los items
		
		$items = json_decode($this->aParam->getParametro('newRecords'));
		$total = $this->montoTotal($items); // obtiene el total de todos los detalles
		$exento_total = $this->totalDevuelto($items); // calcula el exento total de todos los detalles devueltos de la factura
		$total_devuelto = $total - $exento_total; // calcula el total a devolver
		$credfis = $total_devuelto * 0.13;
		
		$id_factura = $this->aParam->getParametro('id_factura');
		
		$datos = array();
		$hora = date("H:i:s");
		$hoy = date("Y-m-d");
		
		
		
		
		
		//var_dump($items);
		//exit;
		
		//echo $total;
		try {
			//obtener sucursal del usuario 
		  	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  	$link->beginTransaction();
			
			
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='fac.ft_nota_ime';
			$this->transaccion='FAC_NOT_INS';
			$this->tipo_procedimiento='IME';
			
			$this->arreglo = array_merge ($this->arreglo,array(
									"excento"=>$exento_total,
									 "total_devuelto" =>$total_devuelto,
									 "credfis"=>$credfis,
									 "monto_total"=>$total,
									 "nro_liquidacion"=>'1',
									 "estacion"=>'estacion',
									 "estado"=>'estado',
									 "estado_reg"=>'1',
									 "id_liquidacion"=>'1',
									 "nro_nota"=>'100'
									 ));
			 
			//Define los parametros para la funcion
			$this->setParametro('id_factura','id_factura','int4'); //tengo
			$this->setParametro('id_sucursal','sucursal_id','int4');//tengo
			$this->setParametro('id_moneda','id_moneda','int4');//tengo
			$this->setParametro('estacion','estacion','varchar');
			$this->setParametro('fecha','fecha','date');//tengo
			$this->setParametro('excento','excento','numeric');//tengo
			$this->setParametro('total_devuelto','total_devuelto','numeric');//tengo
			$this->setParametro('tcambio','tcambio','numeric');//tengo
			$this->setParametro('id_liquidacion','id_liquidacion','varchar');
			$this->setParametro('nit','nit','varchar');//tengo
			$this->setParametro('estado','estado','varchar');
			$this->setParametro('credfis','credfis','numeric');//tengo
			$this->setParametro('nro_liquidacion','nro_liquidacion','varchar');
			$this->setParametro('monto_total','monto_total','numeric');//tengo
			$this->setParametro('estado_reg','estado_reg','varchar');
			$this->setParametro('nro_nota','nro_nota','varchar');
			$this->setParametro('razon','razon','varchar');//tengo
			
			
			
	
			//Ejecuta la instruccion
			$this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			} else {				
				$id_nota = $resp_procedimiento['datos']['id_nota'];//aca recuperamos el id_nota
				$datos['id_nota'] = $id_nota;
				
				//$datos['guia'] = $resp_procedimiento['datos']['guia'];	
			} 
				//crear detalle-item directamente por consulta
				foreach ($items as $item) {
					$stmt = $link->prepare("INSERT INTO fac.tnota_detalle (
																			  id_usuario_reg,
																			  fecha_reg,
																			  estado_reg,
																			  id_factura_detalle,
																			  id_nota,
																			  importe
																			)  
																			VALUES (
																					 ". $_SESSION['ss_id_usuario'] .",
																					  now(),
																					  'activo',
																					  ". $id_factura .",
																					  " . $id_nota . ",
																					  ". $item->importe ."
																					  
																			);");
																			
																		
					$stmt->execute();
				}
				
				$link->commit();
				$this->respuesta=new Mensaje();
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				$this->respuesta->setDatos($datos);
				
				
		
				} catch (Exception $e) {			
				    $link->rollBack();
				    $this->respuesta=new Mensaje();
					if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
						$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
					} else if ($e->getCode() == 2) {//es un error en bd de una consulta
						$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
					} else {//es un error lanzado con throw exception
						throw new Exception($e->getMessage(), 2);
					}
				}	
				
		return $this->respuesta;	
        
    }

	function generarNota(){
		
		$cone=new conexion();		
		$link=$cone->conectarpdo();
		
		
		$items_notas = $this->aParam->getParametro('notas');
		
		
		$cadena_aux = "";
		if(count($items_notas) == 1){
			
			$cadena_aux .= "where nota.id_nota = '$items_notas'";
			
		}
		else
		{
			$coun = count($items_notas)-1;
			$cadena_aux .= "where nota.id_nota in (";
			for($i=0; $i<=$coun;$i++){
				
				if($i<$coun){
					$cadena_aux .= "'$items_notas[$i]',";
				}
				else{
					$cadena_aux .= "'$items_notas[$i]'";
				}
				
			
			}
			$cadena_aux .= ")";
			
			
		}
		
		
		
		
		try {
			//obtener sucursal del usuario 
		  	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  	$link->beginTransaction();
			
			
			
		
			
				$stmt = $link->prepare("SELECT 
										  nota.id_usuario_reg,
										  nota.id_usuario_mod,
										  nota.fecha_reg,
										  nota.fecha_mod,
										  nota.estado_reg,
										  nota.id_usuario_ai,
										  nota.usuario_ai,
										  nota.id_nota,
										  nota.estacion,
										  nota.id_sucursal,
										  nota.estado,
										  nota.id_factura,
										  nota.nro_nota,
										  to_char(nota.fecha,'DD-MM-YYYY') AS fecha,
										  nota.razon,
										  nota.tcambio,
										  nota.nit,
										  nota.id_liquidacion,
										  nota.nro_liquidacion,
										  nota.id_moneda,
										  nota.monto_total,
										  nota.excento,
										  nota.total_devuelto,
										  nota.credfis,
										  nota.billete,
										  nota.codigo_control,
										  nota.id_dosificacion,
										  dosi.nroaut,
										  to_char(dosi.fecha_limite,'DD-MM-YYYY') AS fecha_limite
										FROM 
										  fac.tnota nota
										  inner join fac.tdosificacion dosi on dosi.id_dosificacion = nota.id_dosificacion $cadena_aux");
																							
									
				$stmt->execute();
				
				$results=$stmt->fetchAll(PDO::FETCH_ASSOC);	
				
				
				
			
				
				$link->commit();
				$this->respuesta=new Mensaje();
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				
				$this->respuesta->setDatos($results);
				
				$this->respuesta->getDatos();
				
				
		
				} catch (Exception $e) {			
				    $link->rollBack();
				    $this->respuesta=new Mensaje();
					if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
						$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
					} else if ($e->getCode() == 2) {//es un error en bd de una consulta
						$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
					} else {//es un error lanzado con throw exception
						throw new Exception($e->getMessage(), 2);
					}
				}	
				
		return $this->respuesta;	
        
		
		
		
		
	}



	function saveFormBoletoManual(){
		
		
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$items = json_decode($this->aParam->getParametro('newRecords'));
		
		
		
		
		$factura = $this->aParam->getParametro('nro_factura');
		$nit = $this->aParam->getParametro('nit');
		$razon = $this->aParam->getParametro('razon');
		$fecha = $this->aParam->getParametro('fecha');
		$importe = $this->aParam->getParametro('importe');
		
		
		
		try {
			//obtener sucursal del usuario 
		  	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  	$link->beginTransaction();
			$informix->beginTransaction();
			
			
			
			
			
			//insertar nota de cada uno de las liquidaciones o boletos relacionados
			foreach ($items as $item) {
				
				
				$dosi = $link->prepare("select id_dosificacion,
										       nro_siguiente,
										       fecha_inicio_emi,
										       fecha_limite,
										       nroaut,
										       llave
										from fac.tdosificacion
										order by fecha_inicio_emi DESC
										limit 1 for update;");
				
				$dosi->execute();
					
				$dosi_result = $dosi->fetchAll(PDO::FETCH_ASSOC);
				$fecha_now = new DateTime("now");
				//var_dump($dosi_result[0]['fecha_limite']);
				/*if($dosi_result[0]['fecha_inicio_emi'] <= $fecha_now == true && $dosi_result[0]['fecha_limite'] > $fecha_now == true){
					
					var_dump("hola esta correcto");
				}*/
				
				//$func_cod_con = $link->prepare("select pxp.f_gen_cod_control('SQ@Aa6vS%ML8%iEP*b#xh@_zpCZKi)Q6m5N(B(MIK(#K7nyU3\mPDdj$(A[EaH6#','3904001069175','2110','5298636','20140506','522')");
				
				$date = new DateTime('now');
				//echo $date->format('Ymd'); 
				
				$id_dosi = $dosi_result[0]['id_dosificacion'];
				$credfis = $item->total_devuelto * 0.13;
				
				$func_cod_con = $link->prepare("select pxp.f_gen_cod_control(
												'".$dosi_result[0]['llave']."',
												'".$dosi_result[0]['nroaut']."','".$dosi_result[0]['nro_siguiente']."','".$nit."','".$date->format('Ymd')."','".$item->total_devuelto."')");
				$func_cod_con->execute();
					
				$codigo_control = $func_cod_con->fetchAll(PDO::FETCH_ASSOC);
				
				//var_dump($codigo_control[0]['f_gen_cod_control']);
				
				$stmt = $link->prepare("INSERT INTO fac.tnota
													(
													
													  id_usuario_reg,
													  id_usuario_mod,
													  fecha_reg,
													  fecha_mod,
													  estado_reg,
													  id_usuario_ai,
													  usuario_ai,
													 
													  estacion,
													  id_sucursal,
													  estado,
													  id_factura,
													  nro_nota,
													  fecha,
													  razon,
													  tcambio,
													  nit,
													  id_liquidacion,
													  nro_liquidacion,
													  id_moneda,
													  monto_total,
													  excento,
													  total_devuelto,
													  credfis,
													  billete,
													  codigo_control,
													  id_dosificacion
													) 
						
													VALUES (
													
													  ". $_SESSION['ss_id_usuario'] .",
													  null,
													  now(),
													  null,
													  'activo',
													  ". $_SESSION['ss_id_usuario'] .",
													  null,
													
													  'estacion',
													  '1',
													  'estado',
													  1,
													  '".$dosi_result[0]['nro_siguiente']."',
													   now(),
													  '". $razon ."',
													  '6.9',
													  '". $nit ."',
													  1,
													  ' ',
													  1,
													  ". $item->importe_devolver .",
													   ". $item->exento .",
													   '". $item->total_devuelto ."',
													  ". $credfis .",
													  '".$item->concepto."',
													  '".$codigo_control[0]['f_gen_cod_control']."',
													  '".$id_dosi."'
													)RETURNING id_nota;");
													
				
				
				$dosi_up = $link->prepare("update fac.tdosificacion set nro_siguiente = (nro_siguiente + 1) where id_dosificacion = '$id_dosi'");
				
				
				$dosi_up->execute();
									
				$stmt->execute();
				
				$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				
				$stmt2 = $link->prepare("INSERT INTO 
							  fac.tnota_detalle
							(
							  id_usuario_reg,
							  estado_reg,
							  id_nota,
							  importe,
							  cantidad,
							  concepto
							) 
							VALUES (
							  ". $_SESSION['ss_id_usuario'] .",
							  'activo',
							  '".$results[0]['id_nota']."',
							  '". $item->total_devuelto ."',
							  1,
							   '".$item->concepto."'
							);");
							
											
				$stmt2->execute();
				
				$temp[] = $results[0]['id_nota'];
				
				
				
				/* informix insert nota*/
				$sql_in = "INSERT INTO ingresos:notaprueba
							(pais, estacion, puntoven, sucursal,
							 estado, billete, nrofac, nroaut,
							  nronota, nroautnota, fecha, tcambio, 
							  razon, nit, nroliqui, moneda, 
							  monto, exento, ptjiva, neto,
							   credfis, observa, usuario, fechareg,
							    horareg, devuelto, saldo)
						VALUES 
							('BO', 'CBB', '56999913', '0',
							 '1', '".$item->nro_billete."', '', '".$dosi_result[0]['nroaut']."',
							  '".$dosi_result[0]['nro_siguiente']."' , '390102293247', ".$date->format('Y-m-d')." , '6.960000', 
							  '".trim($item->razon)."' , '".$item->nro_nit."' , '". $item->nroliqui ."', 'BOB',
							   '".$item->total_devuelto."' , '". $item->exento ."', '13.000', '".$item->total_devuelto."',
							    ". $credfis .", 'F22102', 'rag', ".$date->format('Y-m-d')." ,
							     '15:18:17', '0.00', '".$item->total_devuelto ."');";
										
				$info_nota_ins = $informix->prepare($sql_in);
				
				
				$info_nota_ins->execute();
				
											
				/*fin informix*/
				
			}
				
				$link->commit();
				$informix->commit();
				
				$this->respuesta=new Mensaje();
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				
				$this->respuesta->setDatos($temp);
				
				$this->respuesta->getDatos();
				
				
		
				} catch (Exception $e) {			
				    $link->rollBack();
					$informix->rollBack();
					
				    $this->respuesta=new Mensaje();
					if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
						$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
					} else if ($e->getCode() == 2) {//es un error en bd de una consulta
						$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
					} else {//es un error lanzado con throw exception
						throw new Exception($e->getMessage(), 2);
					}
				}	
				
		return $this->respuesta;	
        
		
		
		
		
		
	}
			
}
?>