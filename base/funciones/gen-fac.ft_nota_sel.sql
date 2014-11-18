CREATE OR REPLACE FUNCTION "fac"."ft_nota_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Factura 
 FUNCION: 		fac.ft_nota_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'fac.tnota'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'fac.ft_nota_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FAC_NOT_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:30:03
	***********************************/

	if(p_transaccion='FAC_NOT_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						not.id_nota,
						not.id_factura,
						not.id_sucursal,
						not.id_moneda,
						not.estacion,
						not.fecha,
						not.excento,
						not.total_devuelto,
						not.tcambio,
						not.id_liquidacion,
						not.nit,
						not.estado,
						not.credfis,
						not.nro_liquidacion,
						not.monto_total,
						not.estado_reg,
						not.nro_nota,
						not.razon,
						not.id_usuario_ai,
						not.usuario_ai,
						not.fecha_reg,
						not.id_usuario_reg,
						not.fecha_mod,
						not.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from fac.tnota not
						inner join segu.tusuario usu1 on usu1.id_usuario = not.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = not.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'FAC_NOT_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		ada.torrico	
 	#FECHA:		18-11-2014 19:30:03
	***********************************/

	elsif(p_transaccion='FAC_NOT_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_nota)
					    from fac.tnota not
					    inner join segu.tusuario usu1 on usu1.id_usuario = not.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = not.id_usuario_mod
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
ALTER FUNCTION "fac"."ft_nota_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
