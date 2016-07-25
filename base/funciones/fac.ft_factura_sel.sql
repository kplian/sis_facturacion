CREATE OR REPLACE FUNCTION fac.ft_factura_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_factura_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'fac.tfactura'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'fac.ft_factura_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_FACTU_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:26:15
	***********************************/

	if(p_transaccion='FAC_FACTU_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						factu.id_factura,
						factu.id_agencia,
						factu.id_sucursal,
						factu.id_actividad_economica,
						factu.id_moneda,
                        factu.id_dosificacion,
						factu.nit,
						factu.por_comis,
						factu.tcambio,
						factu.importe_comis,
						factu.codigo_control,
						factu.nro_factura,
						factu.contabilizado,
						factu.fecha,
						factu.observacion,
						factu.renglon,
						factu.monto,
						factu.estado_reg,
						factu.commission,
						factu.razon,
						factu.id_usuario_ai,
						factu.fecha_reg,
						factu.usuario_ai,
						factu.id_usuario_reg,
						factu.fecha_mod,
						factu.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        su.estacion as desc_sucursal,
                        age.codigo as desc_agencia,
                        ae.nombre_actividad as desc_actividad,
                        mo.moneda as desc_moneda,
                        dosi.nroaut as desc_numero_dosificacion
						from fac.tfactura factu
						inner join segu.tusuario usu1 on usu1.id_usuario = factu.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = factu.id_usuario_mod
                        inner join ven.tsucursal su on su.id_sucursal = factu.id_sucursal
                        inner join ven.tagencia age on age.id_agencia = factu.id_agencia
                        inner join fac.tactividad_economica ae on ae.id_actividad_economica = factu.id_actividad_economica
                        inner join param.tmoneda mo on mo.id_moneda = factu.id_moneda
                        left join fac.tdosificacion dosi on dosi.id_dosificacion = factu.id_dosificacion
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_FACTU_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:26:15
	***********************************/

	elsif(p_transaccion='FAC_FACTU_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_factura)
					    from fac.tfactura factu
					    inner join segu.tusuario usu1 on usu1.id_usuario = factu.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = factu.id_usuario_mod
                        inner join ven.tsucursal su on su.id_sucursal = factu.id_sucursal
                        inner join ven.tagencia age on age.id_agencia = factu.id_agencia
                        inner join fac.tactividad_economica ae on ae.id_actividad_economica = factu.id_actividad_economica
                        inner join param.tmoneda mo on mo.id_moneda = factu.id_moneda
                        left join fac.tdosificacion dosi on dosi.id_dosificacion = factu.id_dosificacion
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
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