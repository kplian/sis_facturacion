CREATE OR REPLACE FUNCTION fac.f_dosi_siguiente (
json json
 
)
RETURNS text AS
$body$
/**************************************************************************
 SISTEMA:		PXP
 FUNCION: 		ven.f_dosi_siguiente
 DESCRIPCION:   Genera codigo de control para los datos recibidos de acuerdo a algoritmo 
 definido por impuestos nacionales en Bolivia
 AUTOR: 		 (jrivera)
 FECHA:	        10-11-2015 14:58:35
 COMENTARIOS:	La fecha_emision debe estar en formato 'YYYYMMDD' y el monto_facturado debe
 				estar redondeado sin decimales
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/


DECLARE

    v_resp		            varchar;
	v_nombre_funcion        text;
    v_id_dosi_correlativo  integer;
    v_nro_siguiente integer;

BEGIN
	v_nombre_funcion = 'ven.f_dosi_siguiente';
    
      IF EXISTS (SELECT 0 FROM fac.tdosi_correlativo where id_dosificacion = cast(json->>'id_dosificacion' as int) )
    THEN
      --stuff here
     
      
        select nro_siguiente into v_nro_siguiente from fac.tdosi_correlativo where id_dosificacion = cast(json->>'id_dosificacion' as int);

      ELSE
      
      insert into fac.tdosi_correlativo(
          
          id_dosificacion,
          nro_actual,
          nro_siguiente,
          
          estado_reg,
          id_usuario_ai,
          id_usuario_reg
      
        ) values(
         
          cast(json->>'id_dosificacion' as int),
         cast(json->>'inicial' as int),
          cast(json->>'inicial' as int),
      
          'activo',
          1,
          null
        
        )RETURNING nro_siguiente into v_nro_siguiente;
        
    END IF;

	--raise exception '%',json->>'ESTACION';
	
     return v_nro_siguiente;
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