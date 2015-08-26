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

	function verTipoDevolucion($nroliqui){
		
		//$nroliqui = $this->aParam->getParametro('nroliqui');
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$sql = "select doc.nroaut,doc.nrofac,doc.iddoc from liquidoc doc
				where doc.nroliqui = '$nroliqui'";
				
		$result = $informix->prepare($sql);
						
		$result->execute();
		
			
		$dosi_result = $result->fetchAll(PDO::FETCH_ASSOC);
		
	
		return $dosi_result;

		
	}
	
	function liquidevolu(){
		
		
		$nroliqui = $this->aParam->getParametro('nroliqui');
		
		$documento = $this->verTipoDevolucion($nroliqui);
		
		
		
		if(trim($documento[0]['IDDOC']) == 'BOL'){//CUANDO EL DOCUMENTO SEA BOLETO
		
			$datos = $this->liquiboletramos($nroliqui);
			
			
			
		}elseif(trim($documento[0]['IDDOC']) == 'FACCOM'){//DOCUMENTO FACTURA COMPOTARIZADA
		
			$nrofac = $documento[0]['NROFAC'];
			$nroaut = $documento[0]['NROAUT'];
			
			$this->objParam->addParametro('nrofac',$nrofac);
			$this->objParam->addParametro('nroaut',$nroaut);
			
			$datos = $this->conceptosComputarizada();
		
			
		}elseif(trim($documento[0]['IDDOC']) == 'FACMAN'){//DOCUMENTO FACTURA MANUAL
		
			echo "manual";
			exit;
		}
		
		$this->respuesta=new Mensaje();
				
		$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
		$this->respuesta->setTotal(1);
		$this->respuesta->setDatos($datos);
			
		
		
		return $this->respuesta;
		
		
	}
	
	function liquiboletramos($nroliqui){
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql = "select
            			lite.pais,
                          lite.estacion,
                          lite.docmnt,
                          lite.nroliqui,
                          lite.nroliqui as concepto,
                          lite.renglon as cantidad,
                          lite.idtramo,
                          lite.billcupon,
                          lite.cupon,
                          lite.origen,
                          lite.destino,
                          lite.estado,
                        lite.billcupon,
                         lite.origen,
                        lite.destino,
                        factu.nit,
                         factu.razon,
                         factu.monto,
                         factu.exento,
                         factu.fecha as fecha_fac,
                         factu.nroaut,
                         factu.nrofac
                         
                         FROM liquitra lite
                          left join facturas factu on factu.billete = lite.billcupon
				        where lite.nroliqui = '$nroliqui' and lite.idtramo = 'D'";
				
		$result = $informix->prepare($sql);
						
		$result->execute();
		
			
		$fetch_result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		$i = 0;
		
		$concepto = "";
		$concepto = $fetch_result[0]['billcupon'];
		
		foreach ($fetch_result as $item) {
			if($i == 0){
				$concepto .= "/".$item["origen"]."/".$item["destino"];
			}else{
				$concepto .= "/".$item["destino"];
			}
			$i++;
			
		}
		
		$listar_cupones_originales = $this->listarCuponesOriginales($fetch_result[0]['billcupon']);
		
	
		//envia el billete el concepto con lo que se esta devolviendo y los conceptos originales
		$datos_del_boleto = $this->listarBoletos($fetch_result[0]['billcupon'],$concepto,$listar_cupones_originales);
		
		if(trim($datos_del_boleto[0]['moneda']) != 'BOB'){
					
			$conversion = $this->monedaConvercion($datos_del_boleto[0]['moneda'], $datos_del_boleto[0]['importe_original'], $datos_del_boleto[0]['pais'],$datos_del_boleto[0]['fecha']);
			
			$datos_del_boleto[0]['importe_original'] = "$conversion";
			$datos_del_boleto[0]['precio_unitario'] = "$conversion";
			//$datos_del_boleto[0]['total_devuelto'] = "$conversion";
			$datos_del_boleto[0]['importe_devolver'] = "$conversion";
		
		}
		
		$datos_del_boleto[0]['fecha_fac'] = date("d-m-Y", strtotime($datos_del_boleto[0]['fecha_fac']));
		
		
		
		
		return $datos_del_boleto;
		
		
	}
	
	function monedaConvercion($moneda,$importe,$pais,$fecha_boleto){
				
			
		$fecha = date("d-m-Y", strtotime($fecha_boleto));
		
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql = "select * from cambio where pais = '$pais' and fecha = '$fecha'";
		
		
									
		$prepare = $informix->prepare($sql);
		$prepare->execute();
		
		
		// obtengo los datos de razon social y nit
		$resultado = $prepare->fetchAll(PDO::FETCH_ASSOC); 
					
		return $importe*$resultado[0]['tcambio'];
	
	}
	
	function listarCuponesOriginales($billete){
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql = "select * from cupon where billete = '$billete' and bill_cupon = '$billete'";
			
		$prepare = $informix->prepare($sql);
		$prepare->execute();
		
		// obtengo los datos de razon social y nit
		$resultado = $prepare->fetchAll(PDO::FETCH_ASSOC); 
		
		foreach ($resultado as $item) {
			if($i == 0){
				$billete .= "/".$item["origen"]."/".$item["destino"];
			}else{
				$billete .= "/".$item["destino"];
			}
			$i++;
			
		}
		
		return $billete;	
	}
	
	
	function listarBoletos($billete,$concepto,$concepto_original){

		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_facturas = "select bo.pais,bo.billete as billcupon,
										bo.billete as nro_billete,
				                        bo.fecha,
				                        bo.tcambio,
				                        bo.pasajero,
				                        bo.moneda,
				                        bo.importe as importe_original,
				                        bo.estado,
				                        factu.nit,
				                        factu.nit as nro_nit,
				                         factu.razon,
				                         factu.monto,
				                         factu.exento,
				                         factu.fecha as fecha_fac,
				                         '$concepto' as concepto,
				                         '$concepto_original' as concepto_original,
				                         bo.importe as precio_unitario,
				                         (factu.monto - factu.exento) as total_devuelto,
				                         bo.importe as importe_devolver,
				                         bo.billete as nro_fac,
				                         '1' as nro_aut,
				                         
				                         'BOLETO' as tipo
				                          
									from boletos bo
									inner join facturas factu on factu.billete = bo.billete 
									where bo.billete = '$billete' ";
									
		$prepare_facturas = $informix->prepare($sql_facturas);
		$prepare_facturas->execute();
		
		
		
		// obtengo los datos de razon social y nit
		$resultado_factura = $prepare_facturas->fetchAll(PDO::FETCH_ASSOC); 
		
		/*var_dump($resultado_factura);
		exit;*/
				
		return $resultado_factura;	
		
	}
	
	function listarLiquitra(){
		
		
		$cone_in=new conexion();		
		$informix=$cone_in->conectarPDOInformix();
		
		
		$nroliqui = $this->aParam->getParametro('nroliqui');
		
		
		
		
		
		
		
		try {
			
		  	$informix->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			
		  
			$informix->beginTransaction();
			
			$sql = "select
            			lite.pais,
                          lite.estacion,
                          lite.docmnt,
                          lite.nroliqui,
                          lite.nroliqui as concepto,
                          lite.renglon as cantidad,
                          lite.idtramo,
                          lite.billcupon,
                          lite.cupon,
                          lite.origen,
                          lite.destino,
                          lite.estado,
                        lite.billcupon,
                         lite.origen,
                        lite.destino
                         
                         FROM liquitra lite
				        where lite.nroliqui = '$nroliqui'";

				$result = $informix->prepare($sql);
						
				$result->execute();
				
					
				$dosi_result = $result->fetchAll(PDO::FETCH_ASSOC);
				$informix->commit();
				
				//var_dump($dosi_result);
				
				$this->respuesta=new Mensaje();
				
				$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				
				$this->respuesta->setDatos($dosi_result);
				
				
				$this->respuesta->getDatos();
				
				
		
		} catch (Exception $e) {			
		   
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
	
	
	function listarInformixLiquidevolu(){
    
        //Definicion de variables para ejecucion del procedimiento
        
        $this->procedimiento='informix.f_liquidevolu_sel';// nombre procedimiento almacenado
        $this->transaccion='INFORMIX_LIQUIDEVOLU_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);
        
        $this->setTipoRetorno('record');
        
      
        $this->captura('pais','varchar');
		$this->captura('estacion','varchar');
		$this->captura('docmnt','varchar');
		$this->captura('nroliqui','varchar');
		
		
        
        
        $this->armarConsulta();
        
       
        $this->ejecutarConsulta();
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
		
		
		
		if($this->datosNoPermitidosBoleto()){
			
		
	
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
					
					$concepto_original = $this->listarCuponesOriginales($billete);
					
					
					$sql_facturas = "select bo.billete,
					                        bo.fecha,
					                        bo.tcambio,
					                        bo.pasajero,
					                        bo.moneda,
					                        bo.importe,
					                        bo.estado,
					                        bo.billete as nrofac,
					                        '1' as nroaut,
					                        factu.nit,
					                        factu.nit as nro_nit,
					                         factu.razon,
					                         factu.monto,
					                         factu.exento,
					                         factu.fecha as fecha_fac,
					                         'BOLETO' as tipo,
					                         '$concepto_original' as concepto_original
										from boletos bo
										inner join facturas factu on factu.billete = bo.billete 
										where bo.billete = '$billete' ";
										
									
										
					$prepare_facturas = $link->prepare($sql_facturas);
					$prepare_facturas->execute();
					
					// obtengo los datos de razon social y nit
					$resultado_factura = $prepare_facturas->fetchAll(PDO::FETCH_ASSOC); 	
					
					
					
					$this->respuesta=new Mensaje();
					
					$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
					$this->respuesta->setTotal(1);
					$this->respuesta->setDatos($resultado_factura);
						
					
					
					
				}else{
						$sql = "select nronota from notacrdb where billete = '$billete'";
						$prepare = $link->prepare($sql);
						$prepare->execute();
						$resultado= $prepare->fetchAll(PDO::FETCH_ASSOC); 	
						
						
					$this->respuesta=new Mensaje();
					
					$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
					$this->respuesta->setTotal($resultado[0]['NRONOTA']);
					$this->respuesta->setDatos("PERTENECE A UNA NOTA");
				
				}
			}else{
				$this->respuesta=new Mensaje();
					
				$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
				$this->respuesta->setTotal(1);
				$this->respuesta->setDatos("PERTENECE A UNA LIQUIDACION");
			}
			
		}else{
			
			$this->respuesta=new Mensaje();
					
			$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
			$this->respuesta->setTotal(1);
			$this->respuesta->setDatos("DUPLICADO");	
				
			
		}
	
		
		
		return $this->respuesta;
		
	}
	
	function datosNoPermitidosBoleto(){
		$datos_no_permitidos = json_decode($this->aParam->getParametro('datos_no_permitidos'));
		$billete = $this->aParam->getParametro('billete');
		
		
		$resp = TRUE;
		
		
		foreach ($datos_no_permitidos as $item) {
			
			if($item->tipo == 'BOLETO'){
				
				if($item->billete == $billete){
					$resp = FALSE;
				}
			}
			
		}
		
		return $resp;
		
		
		
	}
	
	
	function datosNoPermitidos(){
		$datos_no_permitidos = json_decode($this->aParam->getParametro('datos_no_permitidos'));
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		
		
		$resp = TRUE;
		
		foreach ($datos_no_permitidos as $item) {
			
			if($item->tipo == 'FACTURA'){
				
				if($item->nrofac == $nrofac && $item->nroaut == $nroaut){
					$resp = FALSE;
				}
			}
			
		}
		
		return $resp;
		
		
		
	}
	
	function listarFacturaDevolucion(){
			
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		
		$cone=new conexion();		
		$link=$cone->conectarPDOInformix();
		
		
		$this->respuesta=new Mensaje();
		
		if($this->datosNoPermitidos()){
			
			$sql= "select count(*) as count from notacrdb where nroaut = '$nroaut' and nrofac = '$nrofac' ";
			
			$stmt = $link->prepare($sql);
			$stmt->execute();
			$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
			if($results[0]['COUNT'] == 0){	
	
				if($this->siExisteComputarizada()){//facturas computarizadas
					//echo "existe";
					$tipo_de_factura = $this->facturaComputarizada();
					$conceptos = $this->conceptosComputarizada();
					
					
				}elseif($this->siExisteManual()){//facturas manuales
					
					$monto = 0;
					$tipo_de_factura = $this->facturaManual();
					$conceptos = $this->ConceptosManual();
					
					$monto = $monto + $manual[0]['monto'];
					
					if($this->siExisteFacturas()){
						
						$facturas = $this->facturaFacturas();
						$monto = $monto + $facturas[0]['monto'];
						$tipo_de_factura[0]['razon'] = $facturas[0]['razon'];
						$tipo_de_factura[0]['nit'] = $facturas[0]['nit'];
						
						foreach ($conceptos as $item) {
							$conceptos[0]['razon'] = $facturas[0]['razon'];
							$conceptos[0]['nit'] = $facturas[0]['nit'];
						}
						
						
					}
					
					
					
				}elseif($this->siExisteManual() == false){ //facturas de la tabla facturas
					
					
					if($this->siExisteFacturas()){
						
						$tipo_de_factura = $this->facturaFacturas();
						$conceptos = $this->ConceptosFacturas();
						
					}
					
					
					
					
				}
				
				$i = 0;
				
				foreach ($conceptos as $item) {
					
					
					if($item['moneda'] != 'BOB'){
						
						$conversion = $this->monedaConvercion($item['moneda'],$item['precio_original'],$item['pais'],$item['fecha']);
						
						$conversion[$i]['importe_original'] = "$conversion";
						$conversion[$i]['precio_unitario'] = "$conversion";
						
					
					}
					
					$i++;
				}
				
				
				
				
						
				$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
				$this->respuesta->setTotal(1);
				$this->respuesta->setDatos($tipo_de_factura);
				
				
				$this->respuesta->extraData	= $conceptos;
			
				}else{
					$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
					$this->respuesta->setTotal(1);
					$this->respuesta->setDatos("esta factura ya se devolvio");
				}
		
		
		
		}else{
			
			$this->respuesta->setMensaje('EXITO',$this->nombre_archivo,'La consulta se ejecuto con exito','La consulta se ejecuto con exito','base','no tiene','no tiene','SEL','$this->consulta','no tiene');
			$this->respuesta->setTotal(1);
			$this->respuesta->setDatos("DUPLICADO");
		
			
			
		}
		
		return $this->respuesta;
		
				
		
	}
	
	function siExisteComputarizada(){
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT  COUNT(*) as count from factucom where nrofac = '$nrofac' and nroaut = '$nroaut'";
		
								
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		if($resultado_fac[0]['count'] > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function facturaComputarizada(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT * from factucom where nrofac = '$nrofac' and nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		return $resultado_fac;
		
	}
	
	
	
	function conceptosComputarizada(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql = "select con.concepto,
						con.concepto as concepto_original,
						faco.pais,
						faco.estacion,
						faco.nroaut,
						faco.nrofac,
						faco.cantidad,
						faco.preciounit as precio_unitario,
						faco.importe as importe_original,
						fac.razon,
						fac.nit,
						fac.moneda,
						fac.tcambio,
						fac.fecha,
						fac.fecha as fecha_fac,
						fac.nit as nro_nit,
						fac.nroaut as nro_aut,
						fac.nrofac as nro_fac,
						'FACTURA' as tipo
						 from factucomcon faco
						 inner join concefaccom con on con.tipocon = faco.tipocon
						 inner join factucom fac on fac.nroaut = faco.nroaut
						 where con.nroconce = faco.nroconce 
						 and fac.nrofac = faco.nrofac
						 and faco.nrofac = '$nrofac' and faco.nroaut = '$nroaut'";
						 
						 
		$prepare_con = $informix->prepare($sql);
		$prepare_con->execute();
		
	
		$resultado_con = $prepare_con->fetchAll(PDO::FETCH_ASSOC); 
		
		
	
		
		
		return $resultado_con;
		
	}


	function siExisteManual(){
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT  COUNT(*) as count from factuman where nrofac = '$nrofac' and nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		if($resultado_fac[0]['count'] > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function siExisteFacturas(){
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT COUNT(*) as count from facturas where nrofac = '$nrofac' and nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		if($resultado_fac[0]['count'] > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	
	function facturaManual(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT * from factuman where nrofac = '$nrofac' and nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		return $resultado_fac;
		
	}
	
	function ConceptosManual(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		
		
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = "select
					con.concepto,
					faco.pais,
					faco.estacion,
					faco.importe as importe_original,
					fac.razon,
					fac.nit,
					fac.monto as importe_original,
					fac.monto,
					fac.nrofac,
					fac.nroaut,
					fac.moneda,
					fac.tcambio,
					fac.fecha,
					fac.fecha as fecha_fac
					 from factumancon faco
					 inner join concefac con on con.tipocon = faco.tipdoc
					 inner join factuman fac on fac.nroaut = faco.nroaut
					 where con.nroconce = faco.nroconce 
					 and fac.nrofac = faco.nrofac
					 and faco.nrofac = '$nrofac' and faco.nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		
		return $resultado_fac;
		
	}
	
	function facturaFacturas(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		
		
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = " SELECT * from facturas where nrofac = '$nrofac' and nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		return $resultado_fac;
		
	}
	
	function ConceptosFacturas(){
		
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nroaut = $this->aParam->getParametro('nroaut');
		$cone_in=new conexion();	
		$informix=$cone_in->conectarPDOInformix();
		
		$informix->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		
		$sql_fac = "select
					con.concepto,
					faco.pais,
					faco.estacion,
					faco.importe as importe_original,
					fac.razon,
					fac.nit,
					fac.monto as importe_original,
					fac.monto,
					fac.nrofac,
					fac.nroaut,
					fac.moneda,
					fac.tcambio,
					fac.fecha,
					fac.fecha as fecha_fac
					 from factumancon faco
					 inner join concefac con on con.tipocon = faco.tipdoc
					 inner join facturas fac on fac.nroaut = faco.nroaut
					 where con.nroconce = faco.nroconce 
					 and fac.nrofac = faco.nrofac
					 and faco.nrofac = '$nrofac' and faco.nroaut = '$nroaut'";
									
		$prepare_fac = $informix->prepare($sql_fac);
		$prepare_fac->execute();
		
	
		$resultado_fac = $prepare_fac->fetchAll(PDO::FETCH_ASSOC); 
		
		
		return $resultado_fac;
		
	}
	
	function listarBoletosConTramos($boleto){
		
		
		$listar_cupones_originales = $this->listarCuponesOriginales($boleto);
		$datos_del_boleto = $this->listarBoletos($boleto,$listar_cupones_originales,$listar_cupones_originales);
		return $datos_del_boleto;
	}
	
	function listarFacturaConceptosOriginales(){
		
		$nrofac = $this->aParam->getParametro('nrofac');
		$nrofac = $this->aParam->getParametro('nroaut');
		
	
		if($this->siExisteComputarizada()){//facturas computarizadas
			//echo "existe";
			$tipo_de_factura = $this->facturaComputarizada();
			$conceptos = $this->conceptosComputarizada();
			
			
		}elseif($this->siExisteManual()){//facturas manuales
			
			$monto = 0;
			$tipo_de_factura = $this->facturaManual();
			$conceptos = $this->ConceptosManual();
			
			$monto = $monto + $manual[0]['monto'];
			
			if($this->siExisteFacturas()){
				
				$facturas = $this->facturaFacturas();
				$monto = $monto + $facturas[0]['monto'];
				$tipo_de_factura[0]['razon'] = $facturas[0]['razon'];
				$tipo_de_factura[0]['nit'] = $facturas[0]['nit'];
				
				foreach ($conceptos as $item) {
					$conceptos[0]['razon'] = $facturas[0]['razon'];
					$conceptos[0]['nit'] = $facturas[0]['nit'];
				}
				
				
			}
			
			
			
		}elseif($this->siExisteManual() == false){ //facturas de la tabla facturas
			
			
			if($this->siExisteFacturas()){
				
				$tipo_de_factura = $this->facturaFacturas();
				$conceptos = $this->ConceptosFacturas();
				
			}
			
			
			
			
		}
		
		$i = 0;
		
		foreach ($conceptos as $item) {
			
			
			
			if($item['moneda'] != 'BOB'){
				
				$conversion = $this->monedaConvercion($item['moneda'],$item['precio_original'],$item['pais'],$item['fecha']);
				
				$conversion[$i]['importe_original'] = "$conversion";
				$conversion[$i]['precio_unitario'] = "$conversion";
				
			
			}
			
			$i++;
		}
		
		
		return $conceptos;
		
	
		
	}
	
	
	
	
	
			
	
			
}
?>