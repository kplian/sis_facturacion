CREATE OR REPLACE FUNCTION fac.impuestos_pxp (
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		fac
 FUNCION: 		fac.impuestos_pxp
 DESCRIPCION:   Genera codigo de control hacer la prueba y comparar con los datos que te da impuestos
 AUTOR: 		 (favio figueroa)
 FECHA:	       08/09/2015

****************************************************************************/
DECLARE
  v_registros record;
  v_codigo_control text;
  v_fecha varchar(255);
BEGIN

  FOR v_registros in (
  SELECT *
  FROM fac.tcasosprueba)
  LOOP

    v_fecha = split_part(v_registros.fecha,'/',1)||split_part(v_registros.fecha,'/',2)||split_part(v_registros.fecha,'/',3);
   
    v_codigo_control = pxp.f_gen_cod_control(
      ''|| v_registros.llave ||'',
      ''|| v_registros.autorizacion ||'',
       ''|| v_registros.factura ||'',
        ''|| v_registros.nit ||'',
         ''|| v_fecha ||'',
          round(v_registros.monto,0));
           
      update fac.tcasosprueba set codigo_control_pxp = v_codigo_control
      where id_caso_prueba = v_registros.id_caso_prueba;
      
      if(v_codigo_control = v_registros.codigo_de_control_impuestos)
      then
       update fac.tcasosprueba set validacion = 'verdadera'
        where id_caso_prueba = v_registros.id_caso_prueba;
     
      end if;
      
      

  END LOOP;
  RETURN true;
  END
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;