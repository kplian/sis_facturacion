
/***********************************I-DAT-JRR-FACTU-0-15/02/2014*****************************************/

----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------


INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'FACTU', E'Sistema de Facturación', E'2014-02-21', E'FAC', E'activo', E'facturacion', NULL);

select pxp.f_insert_tgui ('FACTURACION', '', 'FACTU', 'si',1 , '', 1, '', '', 'FACTU');


/***********************************F-DAT-JRR-FACTU-0-15/02/2014*****************************************/
