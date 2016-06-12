CREATE OR REPLACE FUNCTION fac.ft_factura_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Factura
 FUNCION: 		fac.ft_factura_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'fac.tfactura'
 AUTOR: 		 (ada.torrico)
 FECHA:	        18-11-2014 19:26:15
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
	v_id_factura	integer;

BEGIN

    v_nombre_funcion = 'fac.ft_factura_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'FAC_FACTU_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		ada.torrico
 	#FECHA:		18-11-2014 19:26:15
	***********************************/

	if(p_transaccion='FAC_FACTU_INS')then

        begin
        	--Sentencia de la insercion
        	insert into fac.tfactura(
			id_agencia,
			id_sucursal,
			id_actividad_economica,
			id_moneda,
            id_dosificacion,
			nit,
			por_comis,
			tcambio,
			importe_comis,
			codigo_control,
			nro_factura,
			contabilizado,
			fecha,
			observacion,
			renglon,
			monto,
			estado_reg,
			commission,
			razon,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_agencia,
			v_parametros.id_sucursal,
			v_parametros.id_actividad_economica,
			v_parametros.id_moneda,
            v_parametros.id_dosificacion,
			v_parametros.nit,
			v_parametros.por_comis,
			v_parametros.tcambio,
			v_parametros.importe_comis,
			v_parametros.codigo_control,
			v_parametros.nro_factura,
			v_parametros.contabilizado,
			v_parametros.fecha,
			v_parametros.observacion,
			v_parametros.renglon,
			v_parametros.monto,
			'activo',
			v_parametros.commission,
			v_parametros.razon,
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null



			)RETURNING id_factura into v_id_factura;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Factura almacenado(a) con exito (id_factura'||v_id_factura||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura',v_id_factura::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'FAC_FACTU_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		ada.torrico
 	#FECHA:		18-11-2014 19:26:15
	***********************************/

	elsif(p_transaccion='FAC_FACTU_MOD')then

		begin
			--Sentencia de la modificacion
			update fac.tfactura set
			id_agencia = v_parametros.id_agencia,
			id_sucursal = v_parametros.id_sucursal,
			id_actividad_economica = v_parametros.id_actividad_economica,
			id_moneda = v_parametros.id_moneda,
            id_dosificacion = v_parametros.id_dosificacion,
			nit = v_parametros.nit,
			por_comis = v_parametros.por_comis,
			tcambio = v_parametros.tcambio,
			importe_comis = v_parametros.importe_comis,
			codigo_control = v_parametros.codigo_control,
			nro_factura = v_parametros.nro_factura,
			contabilizado = v_parametros.contabilizado,
			fecha = v_parametros.fecha,
			observacion = v_parametros.observacion,
			renglon = v_parametros.renglon,
			monto = v_parametros.monto,
			commission = v_parametros.commission,
			razon = v_parametros.razon,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_factura=v_parametros.id_factura;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Factura modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura',v_parametros.id_factura::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'FAC_FACTU_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		ada.torrico
 	#FECHA:		18-11-2014 19:26:15
	***********************************/

	elsif(p_transaccion='FAC_FACTU_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from fac.tfactura
            where id_factura=v_parametros.id_factura;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Factura eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura',v_parametros.id_factura::varchar);

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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;