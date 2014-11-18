CREATE OR REPLACE FUNCTION "fac"."ft_dosificacion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_dosificacion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'fac.tdosificacion'
 AUTOR: 		 (ada.torrico)
 FECHA:	        18-11-2014 19:17:08
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_dosificacion	integer;
			    
BEGIN

    v_nombre_funcion = 'fac.ft_dosificacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_DOSI_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:17:08
	***********************************/

	if(p_transaccion='FAC_DOSI_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into fac.tdosificacion(
			id_sucursal,
			id_activida_economica,
			notificado,
			llave,
			estado_reg,
			nro_tramite,
			tipo_autoimpresor,
			nroaut,
			final,
			estacion,
			inicial,
			tipo,
			glosa_consumidor,
			glosa_impuestos,
			fecha_dosificacion,
			id_lugar_pais,
			autoimpresor,
			nombre_sisfac,
			fecha_inicio_emi,
			nro_siguiente,
			nro_resolucion,
			glosa_empresa,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_sucursal,
			v_parametros.id_activida_economica,
			v_parametros.notificado,
			v_parametros.llave,
			'activo',
			v_parametros.nro_tramite,
			v_parametros.tipo_autoimpresor,
			v_parametros.nroaut,
			v_parametros.final,
			v_parametros.estacion,
			v_parametros.inicial,
			v_parametros.tipo,
			v_parametros.glosa_consumidor,
			v_parametros.glosa_impuestos,
			v_parametros.fecha_dosificacion,
			v_parametros.id_lugar_pais,
			v_parametros.autoimpresor,
			v_parametros.nombre_sisfac,
			v_parametros.fecha_inicio_emi,
			v_parametros.nro_siguiente,
			v_parametros.nro_resolucion,
			v_parametros.glosa_empresa,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_dosificacion into v_id_dosificacion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dosificacion almacenado(a) con exito (id_dosificacion'||v_id_dosificacion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_id_dosificacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DOSI_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:17:08
	***********************************/

	elsif(p_transaccion='FAC_DOSI_MOD')then

		begin
			--Sentencia de la modificacion
			update fac.tdosificacion set
			id_sucursal = v_parametros.id_sucursal,
			id_activida_economica = v_parametros.id_activida_economica,
			notificado = v_parametros.notificado,
			llave = v_parametros.llave,
			nro_tramite = v_parametros.nro_tramite,
			tipo_autoimpresor = v_parametros.tipo_autoimpresor,
			nroaut = v_parametros.nroaut,
			final = v_parametros.final,
			estacion = v_parametros.estacion,
			inicial = v_parametros.inicial,
			tipo = v_parametros.tipo,
			glosa_consumidor = v_parametros.glosa_consumidor,
			glosa_impuestos = v_parametros.glosa_impuestos,
			fecha_dosificacion = v_parametros.fecha_dosificacion,
			id_lugar_pais = v_parametros.id_lugar_pais,
			autoimpresor = v_parametros.autoimpresor,
			nombre_sisfac = v_parametros.nombre_sisfac,
			fecha_inicio_emi = v_parametros.fecha_inicio_emi,
			nro_siguiente = v_parametros.nro_siguiente,
			nro_resolucion = v_parametros.nro_resolucion,
			glosa_empresa = v_parametros.glosa_empresa,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_dosificacion=v_parametros.id_dosificacion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dosificacion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_parametros.id_dosificacion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DOSI_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:17:08
	***********************************/

	elsif(p_transaccion='FAC_DOSI_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from fac.tdosificacion
            where id_dosificacion=v_parametros.id_dosificacion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dosificacion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_parametros.id_dosificacion::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "fac"."ft_dosificacion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
