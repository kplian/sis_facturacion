CREATE OR REPLACE FUNCTION "fac"."ft_nota_detalle_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_nota_detalle_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'fac.tnota_detalle'
 AUTOR: 		 (ada.torrico)
 FECHA:	        18-11-2014 19:32:09
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
	v_id_nota_detalle	integer;
			    
BEGIN

    v_nombre_funcion = 'fac.ft_nota_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_DENO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:32:09
	***********************************/

	if(p_transaccion='FAC_DENO_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into fac.tnota_detalle(
			id_factura_detalle,
			id_nota,
			estado_reg,
			importe,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_factura_detalle,
			v_parametros.id_nota,
			'activo',
			v_parametros.importe,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_nota_detalle into v_id_nota_detalle;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Nota almacenado(a) con exito (id_nota_detalle'||v_id_nota_detalle||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota_detalle',v_id_nota_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DENO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:32:09
	***********************************/

	elsif(p_transaccion='FAC_DENO_MOD')then

		begin
			--Sentencia de la modificacion
			update fac.tnota_detalle set
			id_factura_detalle = v_parametros.id_factura_detalle,
			id_nota = v_parametros.id_nota,
			importe = v_parametros.importe,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_nota_detalle=v_parametros.id_nota_detalle;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Nota modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota_detalle',v_parametros.id_nota_detalle::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DENO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:32:09
	***********************************/

	elsif(p_transaccion='FAC_DENO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from fac.tnota_detalle
            where id_nota_detalle=v_parametros.id_nota_detalle;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle Nota eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota_detalle',v_parametros.id_nota_detalle::varchar);
              
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
ALTER FUNCTION "fac"."ft_nota_detalle_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
