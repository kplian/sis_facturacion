CREATE OR REPLACE FUNCTION "fac"."ft_nota_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_nota_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'fac.tnota'
 AUTOR: 		 (ada.torrico)
 FECHA:	        18-11-2014 19:30:03
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
	v_id_nota	integer;
			    
BEGIN

    v_nombre_funcion = 'fac.ft_nota_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_NOT_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:30:03
	***********************************/

	if(p_transaccion='FAC_NOT_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into fac.tnota(
			id_factura,
			id_sucursal,
			id_moneda,
			estacion,
			fecha,
			excento,
			total_devuelto,
			tcambio,
			id_liquidacion,
			nit,
			estado,
			credfis,
			nro_liquidacion,
			monto_total,
			estado_reg,
			nro_nota,
			razon,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_factura,
			v_parametros.id_sucursal,
			v_parametros.id_moneda,
			v_parametros.estacion,
			v_parametros.fecha,
			v_parametros.excento,
			v_parametros.total_devuelto,
			v_parametros.tcambio,
			v_parametros.id_liquidacion,
			v_parametros.nit,
			v_parametros.estado,
			v_parametros.credfis,
			v_parametros.nro_liquidacion,
			v_parametros.monto_total,
			'activo',
			v_parametros.nro_nota,
			v_parametros.razon,
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_nota into v_id_nota;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Notas almacenado(a) con exito (id_nota'||v_id_nota||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota',v_id_nota::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'FAC_NOT_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:30:03
	***********************************/

	elsif(p_transaccion='FAC_NOT_MOD')then

		begin
			--Sentencia de la modificacion
			update fac.tnota set
			id_factura = v_parametros.id_factura,
			id_sucursal = v_parametros.id_sucursal,
			id_moneda = v_parametros.id_moneda,
			estacion = v_parametros.estacion,
			fecha = v_parametros.fecha,
			excento = v_parametros.excento,
			total_devuelto = v_parametros.total_devuelto,
			tcambio = v_parametros.tcambio,
			id_liquidacion = v_parametros.id_liquidacion,
			nit = v_parametros.nit,
			estado = v_parametros.estado,
			credfis = v_parametros.credfis,
			nro_liquidacion = v_parametros.nro_liquidacion,
			monto_total = v_parametros.monto_total,
			nro_nota = v_parametros.nro_nota,
			razon = v_parametros.razon,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_nota=v_parametros.id_nota;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Notas modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota',v_parametros.id_nota::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_NOT_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:30:03
	***********************************/

	elsif(p_transaccion='FAC_NOT_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from fac.tnota
            where id_nota=v_parametros.id_nota;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Notas eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_nota',v_parametros.id_nota::varchar);
              
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
ALTER FUNCTION "fac"."ft_nota_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
