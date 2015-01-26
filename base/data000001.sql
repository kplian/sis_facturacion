----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------

select pxp.f_insert_tgui ('SISTEMA DE DEBITO CREDITO', '', 'DECR', 'si', 1, '', 1, '', '', 'DECR');
select pxp.f_insert_tgui ('Devoluciones', 'Devoluciones', 'DEVO', 'si', 1, 'sis_facturacion/vista/factura/formulario_notas.php', 2, '', 'FormNota', 'DECR');
----------------------------------
--COPY LINES TO dependencies.sql FILE  
---------------------------------

select pxp.f_insert_testructura_gui ('DECR', 'SISTEMA');
select pxp.f_insert_testructura_gui ('DEVO', 'DECR');