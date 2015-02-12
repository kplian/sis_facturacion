/***********************************I-DEP-FFP-FAC-1-19/11/2014****************************************/



/***********************************F-DEP-FFP-FAC-1-19/11/2014****************************************/



/***********************************I-DEP-FFP-FAC-2-25/11/2014****************************************/

/***********************************F-DEP-FFP-FAC-2-25/11/2014****************************************/


/***********************************I-DEP-FFP-FAC-3-12/02/2015****************************************/

--docificacion
ALTER TABLE fac.tdosificacion
  ADD CONSTRAINT fk_tdosificacion__id_activida_economica FOREIGN KEY (id_activida_economica)
    REFERENCES fac.tactividad_economica(id_actividad_economica)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

/*
ALTER TABLE fac.tdosificacion
  ADD CONSTRAINT fk_tdosificacion__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
*/

--factura
ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tfactura__id_actividad_economica FOREIGN KEY (id_actividad_economica)
    REFERENCES fac.tactividad_economica(id_actividad_economica)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

/*ALTER TABLE fac.tfactura
  ADD  CONSTRAINT fk_tfactura__id_agencia FOREIGN KEY (id_agencia)
    REFERENCES ven.tagencia(id_agencia)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
*/

 ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tfactura__id_moneda FOREIGN KEY (id_moneda)
    REFERENCES param.tmoneda(id_moneda)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

 /*ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tfactura__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
*/


--factura detalle

 ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tfactura_detalle__id_concepto_ingas FOREIGN KEY (id_concepto_ingas)
    REFERENCES param.tconcepto_ingas(id_concepto_ingas)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

   ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tfactura_detalle__id_factura FOREIGN KEY (id_factura)
    REFERENCES fac.tfactura(id_factura)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

--nota

ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tnota__id_factura FOREIGN KEY (id_factura)
    REFERENCES fac.tfactura(id_factura)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

  ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tnota__id_moneda FOREIGN KEY (id_moneda)
    REFERENCES param.tmoneda(id_moneda)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

  /*ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tnota__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
*/

--nota detalle

ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tnota_detalle__id_factura_detalle FOREIGN KEY (id_factura_detalle)
    REFERENCES fac.tfactura_detalle(id_factura_detalle)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
ALTER TABLE fac.tfactura
  ADD CONSTRAINT fk_tnota_detalle__id_nota FOREIGN KEY (id_nota)
    REFERENCES fac.tnota(id_nota)
    MATCH PARTIAL
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;



/***********************************F-DEP-FFP-FAC-3-12/02/2015****************************************/