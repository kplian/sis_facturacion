CREATE OR REPLACE FUNCTION factu.ft_factura_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Facturación
 FUNCION: 		factu.ft_factura_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'factu.tfactura'
 AUTOR: 		 (admin)
 FECHA:	        28-02-2014 19:29:02
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
    v_id_vendedor	integer;
			    
BEGIN

    v_nombre_funcion = 'factu.ft_factura_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_FACT_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		28-02-2014 19:29:02
	***********************************/

	if(p_transaccion='FAC_FACT_INS')then
					
        begin
        	--obtener el id_persona a partir del id_usuario para guardarlo en el vendedor
            select id_persona into v_id_vendedor
            from segu.tusuario u
            where id_usuario = p_id_usuario;
        	--Sentencia de la insercion
        	insert into factu.tfactura(
			codigo_control,
			id_dosificacion,
			id_vendedor,
			fecha,
			fecha_limite,
			tipo,
			monto,
			texto_factura,
			nit,
			nombre,
			numero_factura,
            impresion,
            id_usuario_reg
          	) values(
			v_parametros.codigo_control,
			v_parametros.id_dosificacion,
			v_id_vendedor,
			now(),
			v_parametros.fecha_limite,
			v_parametros.tipo,
			v_parametros.monto,
			'finalizado',
			v_parametros.nit,
			v_parametros.nombre,
			v_parametros.numero_factura,
            1,
            p_id_usuario							
			)RETURNING id_factura into v_id_factura;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Factura almacenado(a) con exito (id_factura'||v_id_factura||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura',v_id_factura::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'FAC_FACT_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		28-02-2014 19:29:02
	***********************************/

	elsif(p_transaccion='FAC_FACT_MOD')then

		begin
			--Sentencia de la modificacion
			update factu.tfactura set
			codigo_control = v_parametros.codigo_control,
			id_dosificacion = v_parametros.id_dosificacion,
			autorizado = v_parametros.autorizado,
			id_dosificacion = v_parametros.id_dosificacion,
			id_vendedor = v_parametros.id_vendedor,
			estado = v_parametros.estado,
			fecha = v_parametros.fecha,
			fecha_limite = v_parametros.fecha_limite,
			tipo = v_parametros.tipo,
			impresion = v_parametros.impresion,
			monto = v_parametros.monto,
			texto_factura = v_parametros.texto_factura,
			nit = v_parametros.nit,
			nombre = v_parametros.nombre,
			numero_factura = v_parametros.numero_factura
			where id_factura=v_parametros.id_factura;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Factura modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_factura',v_parametros.id_factura::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_FACT_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		28-02-2014 19:29:02
	***********************************/

	elsif(p_transaccion='FAC_FACT_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from factu.tfactura
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