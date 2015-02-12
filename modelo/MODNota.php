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
			
	/*function insertarNota(){
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
	}*/	
		
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
	
	
	
	
	function saveFormLiquidacionBoleto(){
		
		
		
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
		
        $cone = new conexion();
		$link = $cone->conectarpdo();
		
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$items = json_decode($this->aParam->getParametro('newRecords'));
		//$liquidevolu = $this->aParam->getParametro('liquidevolu');
		
		
		
		
		
		try {
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  	$link->beginTransaction();
			$informix->beginTransaction();	
			
			$i=0;
			foreach ($items as $item) {
				
				if($item->tipo == 'BOLETO'){
					
					
					$temp[] = $this->guardarNotaBoleto($item);
				
					
				}elseif($item->tipo == 'FACTURA'){
					
					if($this->verSiExisteNota($item->nrofac,$item->nroaut) == 0){
						
						//se crea una nota para esta fila de datos por que no existe en la base de datos
						$temp[] = $this->guardarNotaFactura($item);
						$nota = $this->verDatosDeNota($item->nrofac,$item->nroaut);
						$this->insertarNotaDetalle($item, $nota); //mandamos la fila y el id_nota
						
						
					}else{
						
						//exote en la base de datos asi solo se guarda como detalle del que existe
						$nota = $this->verDatosDeNota($item->nrofac,$item->nroaut);
						$this->insertarNotaDetalle($item, $nota); //mandamos la fila y el id_nota
						
						
					}
					
				}
				
				
				$i++;
				
				
				
			}//fin foreach
			
			
				$link->commit();
				$informix->commit();
				
				$this->respuesta=new Mensaje();
				
				
					
				$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
				$this->respuesta->setTotal(1);
				$this->respuesta->setDatos($temp);
				
				return $this->respuesta;
				
				

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
		}	//fin catch
		
    }

	function guardarNotaBoleto($item){
		
		$dosificacion = $this->generarDosificacion();
		
		$codigo_control = $this->generarCodigoControl($item->nit,$item->total_devuelto,$dosificacion);
		
		
		$id_nota = $this->insertarNota($item,$codigo_control,$dosificacion);	
		$this->insertarNotaDetalle($item,$id_nota);
		
		
		
		return $id_nota;
		
		
		
		
	}
	
	function guardarNotaFactura($item){
		
		$dosificacion = $this->generarDosificacion();
		
		$codigo_control = $this->generarCodigoControl($item->nit,$item->total_devuelto,$dosificacion);
		
		
		$id_nota = $this->insertarNota($item,$codigo_control,$dosificacion);	
		
		return $id_nota;
		
		
		
		
	}
	
	
	function generarDosificacion(){
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
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
		
		
		return $dosi_result;
		
	}
	
	function generarCodigoControl($nit,$total_devuelto,$dosificacion){
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		
		
		
		
		$fecha_now = new DateTime("now");
		
		$date = new DateTime('now');
	
		$id_dosi = $dosificacion[0]['id_dosificacion'];
		
		
		$func_cod_con = $link->prepare("select pxp.f_gen_cod_control(
										'".$dosificacion[0]['llave']."',
										'".$dosificacion[0]['nroaut']."','".$dosificacion[0]['nro_siguiente']."','".$nit."','".$date->format('Ymd')."','".$total_devuelto."')");
		$func_cod_con->execute();
			
		$codigo_control = $func_cod_con->fetchAll(PDO::FETCH_ASSOC);
		
		return $codigo_control;
	}
	
	
	function insertarNota($item,$codigo_control,$dosificacion){
			
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		$credfis = $item->total_devuelto * 0.13;
		$id_dosi =$dosificacion[0]['id_dosificacion'];
			
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
										  id_dosificacion,
										  nrofac,
										  nroaut
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
										  '1',
										  1,
										  '".$dosificacion[0]['nro_siguiente']."',
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
										  '".$dosificacion[0]['id_dosificacion']."',
										  '".$item->nrofac."',
										  '".$item->nroaut."'
										)RETURNING id_nota;");
										
	

		$dosi_up = $link->prepare("update fac.tdosificacion set nro_siguiente = (nro_siguiente + 1) where id_dosificacion = '$id_dosi'");
		
		
		$dosi_up->execute();
							
		$stmt->execute();
		
		$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $results[0]['id_nota'];
		
		
	}
	
	
	function insertarNotaDetalle($item,$id_nota){
				
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		$stmt2 = $link->prepare("INSERT INTO 
				  fac.tnota_detalle
				(
				  id_usuario_reg,
				  estado_reg,
				  id_nota,
				  importe,
				  cantidad,
				  concepto,
				  exento,
				  total_devuelto
				) 
				VALUES (
				  ". $_SESSION['ss_id_usuario'] .",
				  'activo',
				  '".$id_nota."',
				  '". $item->importe_devolver ."',
				  1,
				   '".$item->concepto."',
				   '". $item->exento ."',
				   '". $item->total_devuelto ."'
				);");
				
								
		$stmt2->execute();	
		
		
		
	}
	
	function verSiExisteNota($nrofac,$nroaut){
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		$stmt2 = $link->prepare("select count(*) as count
								 from fac.tnota where nrofac = '$nrofac' and nroaut = '$nroaut'");
								
		$stmt2->execute();	
		$results=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		return $results[0]['count'];
		
	}
	function verDatosDeNota($nrofac,$nroaut){
		
		$cone = new conexion();
		$link = $cone->conectarpdo();
		
		$stmt2 = $link->prepare("select id_nota
								 from fac.tnota where nrofac = '$nrofac' and nroaut = '$nroaut'");
								
		$stmt2->execute();	
		$results=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		
		return $results[0]['id_nota'];
	}
	
	
	
	
	
	
	

	function generarNota(){
		
		$cone=new conexion();		
		$link=$cone->conectarpdo();
		

		$items_notas = $this->aParam->getParametro('notas'); //llega los id notas

		
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
										  nota.nrofac as factura,
										  nota.nroaut as autorizacion,
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

	function reImpresion(){

		$id_nota = $this->aParam->getParametro('notas');
		$date = new DateTime('now');

		$arreglo_impresion = '{'.$_SESSION['ss_id_usuario'].', "'.$_SESSION['_NOM_USUARIO'].'", '.$date->format('Y-m-d H:i:s').'}';
		$cone = new conexion();
		$link = $cone->conectarpdo();

		$reim = $link->prepare("update fac.tnota set reimpresion = reimpresion  || '$arreglo_impresion' WHERE id_nota ='$id_nota'");

		$reim->execute();

		//$dosi_result = $reim->fetchAll(PDO::FETCH_ASSOC);

	}

	function anularNota(){

		$this->anularNotaPXP();

	}

	function anularNotaInformix(){
		$cone_in=new conexion();
		$informix=$cone_in->conectarPDOInformix();

		try {
			$informix->beginTransaction();

		} catch (Exception $e) {

		}
	}


	function anularNotaPXP(){

		$nota = $this->aParam->getParametro('notas');
		$id_nota = $this->aParam->getParametro('id_nota'); //id nota para comparar con informix

		$cone = new conexion();
		$link = $cone->conectarpdo();
		try {
			$link->beginTransaction();

			$sql = "UPDATE fac.tnota SET estado = 9, total_devuelto = 0
					,monto_total = 0, excento = 0, credfis = 0 WHERE id_nota ='$nota'";


			$sql_conceptos ="update fac.tnota_detalle set importe = 0, exento =0,total_devuelto=0
								where id_nota ='$nota'";
			//$sql_conceptos ="select * from fac.tnota_detalle where id_nota = '$id_nota'";

			$res = $link->prepare($sql);
			$res->execute();

			$res2 = $link->prepare($sql_conceptos);
			$res2->execute();

			//$results = $res2->fetchAll(PDO::FETCH_ASSOC);

			$link->commit();
			return true;



		} catch (Exception $e) {
			$link->rollBack();
		}

	}

}
?>