CREATE OR REPLACE FUNCTION "fac"."ft_factura_detalle_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_factura_detalle_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'fac.tfactura_detalle'
 AUTOR: 		 (ada.torrico)
 FECHA:	        18-11-2014 19:28:06
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
	v_id_factura_detalle	integer;
			    
BEGIN

    v_nombre_funcion = 'fac.ft_factura_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_DEFA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:28:06
	***********************************/

	if(p_transaccion='FAC_DEFA_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into fac.tfactura_detalle(
			id_factura,
			id_concepto_ingas,
			precio_unitario,
			concepto,
			tipo_concepto,
			renglon,
			importe,
			estado_reg,
			cantidad,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_factura,
			v_parametros.id_concepto_ingas,
			v_parametros.precio_unitario,
			v_parametros.concepto,
			v_parametros.tipo_concepto,
			v_parametros.renglon,
			v_parametros.importe,
			'activo',
			v_parametros.cantidad,
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_factura_detalle into v_id_factura_detalle;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Factura almacenado(a) con exito (id_factura_detalle'||v_id_factura_detalle||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura_detalle',v_id_factura_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DEFA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:28:06
	***********************************/

	elsif(p_transaccion='FAC_DEFA_MOD')then

		begin
			--Sentencia de la modificacion
			update fac.tfactura_detalle set
			id_factura = v_parametros.id_factura,
			id_concepto_ingas = v_parametros.id_concepto_ingas,
			precio_unitario = v_parametros.precio_unitario,
			concepto = v_parametros.concepto,
			tipo_concepto = v_parametros.tipo_concepto,
			renglon = v_parametros.renglon,
			importe = v_parametros.importe,
			cantidad = v_parametros.cantidad,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_factura_detalle=v_parametros.id_factura_detalle;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Factura modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura_detalle',v_parametros.id_factura_detalle::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DEFA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:28:06
	***********************************/

	elsif(p_transaccion='FAC_DEFA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from fac.tfactura_detalle
            where id_factura_detalle=v_parametros.id_factura_detalle;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Factura eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura_detalle',v_parametros.id_factura_detalle::varchar);
              
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
ALTER FUNCTION "fac"."ft_factura_detalle_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
