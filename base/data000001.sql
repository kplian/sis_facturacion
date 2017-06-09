<<<<<<< HEAD

/***********************************I-DAT-JRR-FACTU-0-15/02/2014*****************************************/

=======
>>>>>>> f5072c9b1abd2541f6bbac60719b155591a69bdb
----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------

<<<<<<< HEAD

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'FACTU', E'Sistema de Facturación', E'2014-02-21', E'FAC', E'activo', E'facturacion', NULL);

select pxp.f_insert_tgui ('FACTURACION', '', 'FACTU', 'si',1 , '', 1, '', '', 'FACTU');


/***********************************F-DAT-JRR-FACTU-0-15/02/2014*****************************************/
=======
select pxp.f_insert_tgui ('SISTEMA DE DEBITO CREDITO', '', 'DECR', 'si', 1, '', 1, '', '', 'DECR');
select pxp.f_insert_tgui ('Devoluciones', 'Devoluciones', 'DEVO', 'si', 1, 'sis_facturacion/vista/factura/formulario_notas.php', 2, '', 'FormNota', 'DECR');
select pxp.f_insert_tgui ('Ver Nota', 'notas y detalles de las devoluciones', 'NOTDE', 'si', 2, 'sis_facturacion/vista/nota/Nota.php', 2, '', 'Nota', 'DECR');
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('DECR', 'SISTEMA');
select pxp.f_insert_testructura_gui ('DEVO', 'DECR');
select pxp.f_insert_testructura_gui ('NOTDE', 'DECR');

INSERT INTO fac.tdosificacion ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_dosificacion", "id_lugar_pais", "estacion", "tipo", "id_sucursal", "tipo_autoimpresor", "autoimpresor", "nroaut", "inicial", "final", "llave", "fecha_dosificacion", "nro_tramite", "nombre_sisfac", "fecha_inicio_emi", "notificado", "id_activida_economica", "glosa_impuestos", "glosa_consumidor", "glosa_empresa", "nro_resolucion", "fecha_limite", "nro_siguiente")
VALUES (149, 149, E'2015-12-01 00:00:00', E'2016-05-01 00:00:00', E'activo', NULL, NULL, 7, 1, E'cbba', E'1', 1, E'SFC', E'2', E'3904001069175', E'1', E'150', E'SQ@Aa6vS%ML8%iEP*b#xh@_zpCZKi)Q6m5N(B(MIK(#K7nyU3\\\\mPDdj$(A[EaH6#', E'2016-01-01', E'3901960399', E'SISTEMAFACTURACIONBOA', E'2015-01-01', E'si', 1, E'a', E'a', E'a', E'123', E'2016-01-01', 187);
>>>>>>> f5072c9b1abd2541f6bbac60719b155591a69bdb
