CREATE OR REPLACE FUNCTION fac.ft_dosificacion_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_dosificacion_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'fac.tdosificacion'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'fac.ft_dosificacion_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_DOSI_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:17:08
	***********************************/

	if(p_transaccion='FAC_DOSI_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						dosi.id_dosificacion,
						dosi.id_sucursal,
						dosi.id_activida_economica,
						dosi.notificado,
						dosi.llave,
						dosi.estado_reg,
						dosi.nro_tramite,
						dosi.tipo_autoimpresor,
						dosi.nroaut,
						dosi.final,
						dosi.estacion,
						dosi.inicial,
						dosi.tipo,
						dosi.glosa_consumidor,
						dosi.glosa_impuestos,
						dosi.fecha_dosificacion,
						dosi.id_lugar_pais,
						dosi.autoimpresor,
						dosi.nombre_sisfac,
						dosi.fecha_inicio_emi,
						dosi.nro_siguiente,
						dosi.nro_resolucion,
						dosi.glosa_empresa,
						dosi.usuario_ai,
						dosi.fecha_reg,
						dosi.id_usuario_reg,
						dosi.id_usuario_ai,
						dosi.id_usuario_mod,
						dosi.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        su.estacion as desc_sucursal,
                        ae.nombre_actividad as desc_actividad,
                        lu.nombre as desc_pais
						from fac.tdosificacion dosi
						inner join segu.tusuario usu1 on usu1.id_usuario = dosi.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = dosi.id_usuario_mod
                        inner join ven.tsucursal su on su.id_sucursal = dosi.id_sucursal
                        inner join fac.tactividad_economica ae on ae.id_actividad_economica = dosi.id_activida_economica
				        inner join param.tlugar lu on lu.id_lugar = dosi.id_lugar_pais
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DOSI_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:17:08
	***********************************/

	elsif(p_transaccion='FAC_DOSI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_dosificacion)
					    from fac.tdosificacion dosi
					    inner join segu.tusuario usu1 on usu1.id_usuario = dosi.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = dosi.id_usuario_mod
                        inner join ven.tsucursal su on su.id_sucursal = dosi.id_sucursal
                        inner join fac.tactividad_economica ae on ae.id_actividad_economica = dosi.id_activida_economica
					    inner join param.tlugar lu on lu.id_lugar = dosi.id_lugar_pais
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