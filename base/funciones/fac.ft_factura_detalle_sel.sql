CREATE OR REPLACE FUNCTION fac.ft_nota_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
 	#AUTOR:		favio figueroa
 	#FECHA:		11-02-2015 11:30:03
	***********************************/

	if(p_transaccion='FAC_NOT_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
                  no.id_nota,
                  no.id_factura,
                  no.id_sucursal,
                  no.id_moneda,
                  no.estacion,
                  no.fecha,
                  no.excento,
                  no.total_devuelto,
                  no.tcambio,
                  no.id_liquidacion,
                  no.nit,
                  no.estado,
                  no.credfis,
                  no.nro_liquidacion,
                  no.monto_total,
                  no.estado_reg,
                  no.nro_nota,
                  no.razon,
                  no.id_usuario_ai,
                  no.usuario_ai,
                  no.fecha_reg,
                  no.id_usuario_reg,
                  no.fecha_mod,
                  no.id_usuario_mod,
                  usu1.cuenta as usr_reg,
                  usu2.cuenta as usr_mod
                from fac.tnota no
                inner join segu.tusuario usu1 on usu1.id_usuario = no.id_usuario_reg
                left join segu.tusuario usu2 on usu2.id_usuario = no.id_usuario_mod
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
					    from fac.tnota no
					    inner join segu.tusuario usu1 on usu1.id_usuario = no.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = no.id_usuario_mod
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