<?php
/**
*@package pXP
*@file gen-MODNota.php
*@author  (favio figueroa peñarrieta)
*@date 18-11-2014 19:30:03
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODNota extends MODbase{
	var $cone;
	var $link;
	var $informix;
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);

		$this->cone = new conexion();
		$this->informix = $this->cone->conectarPDOInformix(); // conexion a informix
		$this->link = $this->cone->conectarpdo(); //conexion a pxp(postgres)
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

	function saveForm(){
		$items = json_decode($this->aParam->getParametro('newRecords'));
		//$liquidevolu = $this->aParam->getParametro('liquidevolu');
		try {
			$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->informix->beginTransaction();
		  	$this->link->beginTransaction();
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
						//funcion para sumar a la nota si esque tiene mas de un detalle
						$this->notaDetalleSuma($item,$nota);
					}
				}
				$i++;
			}//fin foreach
			$this->link->commit();
			$this->informix->commit();
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito de insercion de nota','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
			$this->respuesta->setTotal(1);
			$this->respuesta->setDatos($temp);
			return $this->respuesta;

		} catch (Exception $e) {
						
			$this->link->rollBack();
			$this->informix->rollBack();

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
		$nota_in = $this->insertarNotaInformix($id_nota,$dosificacion);
		return $id_nota;
	}
	
	function guardarNotaFactura($item){
		
		$dosificacion = $this->generarDosificacion();
		$codigo_control = $this->generarCodigoControl($item->nit,$item->total_devuelto,$dosificacion);
		$id_nota = $this->insertarNota($item,$codigo_control,$dosificacion);
		$nota_in = $this->insertarNotaInformix($id_nota,$dosificacion);
		return $id_nota;
	}
	
	
	function generarDosificacion(){
		$dosi = $this->link->prepare("select id_dosificacion,
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
		$fecha_now = new DateTime("now");
		$date = new DateTime('now');
		$id_dosi = $dosificacion[0]['id_dosificacion'];
		$func_cod_con = $this->link->prepare("select pxp.f_gen_cod_control(
										'".$dosificacion[0]['llave']."',
										'".$dosificacion[0]['nroaut']."','".$dosificacion[0]['nro_siguiente']."','".$nit."','".$date->format('Ymd')."','".$total_devuelto."')");
		$func_cod_con->execute();
		$codigo_control = $func_cod_con->fetchAll(PDO::FETCH_ASSOC);
		return $codigo_control;
	}
	
	
	function insertarNota($item,$codigo_control,$dosificacion){
		$credfis = $item->total_devuelto * 0.13;
		$id_dosi =$dosificacion[0]['id_dosificacion'];
		$stmt = $this->link->prepare("INSERT INTO fac.tnota
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
										  '".$item->nro_billete."',
										  '".$codigo_control[0]['f_gen_cod_control']."',
										  '".$dosificacion[0]['id_dosificacion']."',
										  '".$item->nrofac."',
										  '".$item->nroaut."'
										)RETURNING id_nota;");
		$dosi_up = $this->link->prepare("update fac.tdosificacion set nro_siguiente = (nro_siguiente + 1) where id_dosificacion = '$id_dosi'");
		$dosi_up->execute();
		$stmt->execute();
		$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results[0]['id_nota'];
	}
	
	
	function insertarNotaDetalle($item,$id_nota){
		$stmt2 = $this->link->prepare("INSERT INTO
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

	function notaDetalleSuma($item,$nota){
		$total = $item->monto_total + $item->exento;
		$stmt2 = $this->link->prepare("update fac.tnota
								set monto_total = monto_total + '$item->importe_devolver'
								, excento = excento + '$item->exento'
								, total_devuelto = total_devuelto + '$item->total_devuelto'
								 WHERE id_nota = '$nota'");
		$stmt2->execute();
	}

	function listarNotaCompleta($id_nota){
		$stmt2 = $this->link->prepare("select * from fac.tnota where id_nota = '$id_nota'");
		$stmt2->execute();
		$results=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	function insertarNotaInformix($id_nota,$dosificacion){
		$nota = $this->listarNotaCompleta($id_nota);
		$nroliqui = $this->objParam->getParametro('liquidevolu');
		$fecha_reg = $nota[0]['fecha_reg'];
		$date = new DateTime($fecha_reg);
		$sql_in = "INSERT INTO notaprueba
					(pais, estacion, puntoven, sucursal,
					 estado, billete, nrofac, nroaut,
					  fechafac, montofac, nronota, nroautnota,
					   fecha, tcambio, razon, nit,
					    nroliqui, moneda, monto, exento,
					     ptjiva, neto, credfis, notamancom,
					     codcontrol, observa, usuario, fechareg,
					     horareg, devuelto, saldo)
						VALUES
					('BO', 'CBB', '56999913', '0',
					 '1', '".$nota[0]['billete']."', '".$nota[0]['nrofac']."', '".$nota[0]['nroaut']."',
					   '".$nota[0]['fecha']."', '500', '".$nota[0]['nro_nota']."', '".$dosificacion[0]['nroaut']."',
					   '".$nota[0]['fecha']."', ".$nota[0]['tcambio'].", '".$nota[0]['razon']."', '".$nota[0]['nit']."',
					    '".$nroliqui."', 'BO', '".$nota[0]['monto_total']."', '".$nota[0]['excento']."',
					     '13.000', '".$nota[0]['total_devuelto']."', '".$nota[0]['credfis']."', 'MANUAL',
					      '".$nota[0]['codigo_control']."',  '".$nroliqui."', '".$nota[0]['id_usuario_reg']."', '".$date->format('Ymd')."',
					       '".$date->format('H:i:s')."', '0', '".$nota[0]['monto_total']."')";

		$info_nota_ins = $this->informix->prepare($sql_in);
		$info_nota_ins->execute();
		$results=$info_nota_ins->fetchAll(PDO::FETCH_ASSOC);
		return true;
	}
	
	function verSiExisteNota($nrofac,$nroaut){
		$stmt2 = $this->link->prepare("select count(*) as count
								 from fac.tnota where nrofac = '$nrofac' and nroaut = '$nroaut'");
		$stmt2->execute();
		$results=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		return $results[0]['count'];
	}
	function verDatosDeNota($nrofac,$nroaut){
		$stmt2 = $this->link->prepare("select id_nota
								 from fac.tnota where nrofac = '$nrofac' and nroaut = '$nroaut'");
		$stmt2->execute();
		$results=$stmt2->fetchAll(PDO::FETCH_ASSOC);
		return $results[0]['id_nota'];
	}

	function generarNota(){
		$items_notas = $this->aParam->getParametro('notas'); //llega los id notas
		$cadena_aux = "";
		if(count($items_notas) == 1){
			$cadena_aux .= "where nota.id_nota = '$items_notas'";
		}else {
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
			$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  	$this->link->beginTransaction();
			$stmt = $this->link->prepare("SELECT
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
				$this->link->commit();
				$this->respuesta=new Mensaje();
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				
				$this->respuesta->setDatos($results);
				
				$this->respuesta->getDatos();

				} catch (Exception $e) {			
				    $this->link->rollBack();
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
		$reim = $this->link->prepare("update fac.tnota set reimpresion = reimpresion  || '$arreglo_impresion' WHERE id_nota ='$id_nota'");

		$reim->execute();

		//$dosi_result = $reim->fetchAll(PDO::FETCH_ASSOC);
	}

	function anularNota(){

		$this->anularNotaPXP();

	}

	function anularNotaInformix(){
		try {
			$this->informix->beginTransaction();

		} catch (Exception $e) {

		}
	}


	function anularNotaPXP(){

		$nota = $this->aParam->getParametro('notas');
		$id_nota = $this->aParam->getParametro('id_nota'); //id nota para comparar con informix

		try {
			$this->link->beginTransaction();

			$sql = "UPDATE fac.tnota SET estado = 9, total_devuelto = 0
					,monto_total = 0, excento = 0, credfis = 0 WHERE id_nota ='$nota'";


			$sql_conceptos ="update fac.tnota_detalle set importe = 0, exento =0,total_devuelto=0
								where id_nota ='$nota'";
			//$sql_conceptos ="select * from fac.tnota_detalle where id_nota = '$id_nota'";

			$res = $this->link->prepare($sql);
			$res->execute();

			$res2 = $this->link->prepare($sql_conceptos);
			$res2->execute();

			//$results = $res2->fetchAll(PDO::FETCH_ASSOC);

			$this->link->commit();
			return true;



		} catch (Exception $e) {
			$this->link->rollBack();
		}

	}

}
?>