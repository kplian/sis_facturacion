CREATE OR REPLACE FUNCTION "fac"."ft_factura_detalle_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_factura_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'fac.tfactura_detalle'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'fac.ft_factura_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_DEFA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:28:06
	***********************************/

	if(p_transaccion='FAC_DEFA_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						defa.id_factura_detalle,
						defa.id_factura,
						defa.id_concepto_ingas,
						defa.precio_unitario,
						defa.concepto,
						defa.tipo_concepto,
						defa.renglon,
						defa.importe,
						defa.estado_reg,
						defa.cantidad,
						defa.id_usuario_ai,
						defa.fecha_reg,
						defa.usuario_ai,
						defa.id_usuario_reg,
						defa.fecha_mod,
						defa.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from fac.tfactura_detalle defa
						inner join segu.tusuario usu1 on usu1.id_usuario = defa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = defa.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_DEFA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:28:06
	***********************************/

	elsif(p_transaccion='FAC_DEFA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_factura_detalle)
					    from fac.tfactura_detalle defa
					    inner join segu.tusuario usu1 on usu1.id_usuario = defa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = defa.id_usuario_mod
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
ALTER FUNCTION "fac"."ft_factura_detalle_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
