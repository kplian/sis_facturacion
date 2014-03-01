CREATE OR REPLACE FUNCTION "factu"."ft_factura_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Facturación
 FUNCION: 		factu.ft_factura_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'factu.tfactura'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'factu.ft_factura_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_FACT_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		28-02-2014 19:29:02
	***********************************/

	if(p_transaccion='FAC_FACT_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						fact.id_factura,
						fact.codigo_control,
						fact.id_dosificacion,
						fact.autorizado,
						fact.id_dosificacion,
						fact.id_vendedor,
						fact.estado,
						fact.fecha,
						fact.fecha_limite,
						fact.tipo,
						fact.impresion,
						fact.monto,
						fact.texto_factura,
						fact.nit,
						fact.nombre,
						fact.numero_factura,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from factu.tfactura fact
						inner join segu.tusuario usu1 on usu1.id_usuario = fact.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = fact.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_FACT_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		28-02-2014 19:29:02
	***********************************/

	elsif(p_transaccion='FAC_FACT_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_factura)
					    from factu.tfactura fact
					    inner join segu.tusuario usu1 on usu1.id_usuario = fact.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = fact.id_usuario_mod
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "factu"."ft_factura_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
