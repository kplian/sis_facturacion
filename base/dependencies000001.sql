/***********************************I-DEP-JRR-FACTU-0-18/02/2014****************************************/
ALTER TABLE ONLY factu.tdosificacion ADD 
CONSTRAINT dosificacion_sucursal_fkey FOREIGN KEY (sucursal)
    REFERENCES public.sucursal(id_sucursal);
    
ALTER TABLE ONLY factu.tfactura ADD  
  CONSTRAINT factura_autorizado_fkey FOREIGN KEY (autorizado)
    REFERENCES public.persona(id_persona);
    
ALTER TABLE ONLY factu.tfactura ADD
  CONSTRAINT factura_dosificacion_fkey FOREIGN KEY (id_dosificacion)
    REFERENCES factu.tdosificacion(id_dosificacion);
    
ALTER TABLE ONLY factu.tfactura ADD
  CONSTRAINT factura_vendedor_fkey FOREIGN KEY (id_vendedor)
    REFERENCES segu.tpersona(id_persona);
 
/***********************************F-DEP-JRR-FACTU-0-18/02/2014****************************************/
