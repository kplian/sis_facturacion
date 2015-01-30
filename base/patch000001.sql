/***********************************I-SCP-FFP-FAC-1-19/11/2014****************************************/

CREATE TABLE fac.tactividad_economica (
  id_actividad_economica SERIAL,
  nombre_actividad VARCHAR(50) NOT NULL,
  codigo_actividad VARCHAR(50) NOT NULL,
  CONSTRAINT pk_tactividad_economica__id_actividad_economica PRIMARY KEY(id_actividad_economica)
) INHERITS (pxp.tbase)
WITHOUT OIDS;


/***********************************F-SCP-FFP-FAC-1-19/11/2014****************************************/


/***********************************I-SCP-FFP-FAC-2-21/01/2015****************************************/



CREATE TABLE fac.tactividad_economica (
  id_actividad_economica SERIAL, 
  nombre_actividad VARCHAR(50) NOT NULL, 
  codigo_actividad VARCHAR(50) NOT NULL, 
  CONSTRAINT pk_tactividad_economica__id_actividad_economica PRIMARY KEY(id_actividad_economica)
) INHERITS (pxp.tbase)
WITHOUT OIDS;


CREATE TABLE fac.tdosificacion (
  id_dosificacion SERIAL, 
  id_lugar_pais INTEGER NOT NULL, 
  estacion VARCHAR(50) NOT NULL, 
  tipo VARCHAR(50) NOT NULL, 
  id_sucursal INTEGER NOT NULL, 
  tipo_autoimpresor VARCHAR(150) NOT NULL, 
  autoimpresor VARCHAR(150) NOT NULL, 
  nroaut VARCHAR(150) NOT NULL, 
  inicial VARCHAR(150) NOT NULL, 
  final VARCHAR(150) NOT NULL, 
  llave VARCHAR(150) NOT NULL, 
  fecha_dosificacion DATE NOT NULL, 
  nro_tramite VARCHAR(150) NOT NULL, 
  nombre_sisfac VARCHAR(150) NOT NULL, 
  fecha_inicio_emi DATE NOT NULL, 
  notificado VARCHAR(150) NOT NULL, 
  id_activida_economica INTEGER NOT NULL, 
  glosa_impuestos VARCHAR(150) NOT NULL, 
  glosa_consumidor VARCHAR(150) NOT NULL, 
  glosa_empresa VARCHAR(150) NOT NULL, 
  nro_resolucion VARCHAR(150) NOT NULL, 
  fecha_limite DATE, 
  nro_siguiente INTEGER, 
  CONSTRAINT pk_tdosificacion__id_dosificacion PRIMARY KEY(id_dosificacion), 
  CONSTRAINT fk_tdosificacion__id_activida_economica FOREIGN KEY (id_activida_economica)
    REFERENCES fac.tactividad_economica(id_actividad_economica),
   
  CONSTRAINT fk_tdosificacion__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
  
) INHERITS (pxp.tbase)
WITHOUT OIDS;


CREATE TABLE fac.tfactura (
  id_factura SERIAL, 
  id_agencia INTEGER NOT NULL, 
  id_sucursal INTEGER NOT NULL, 
  nro_factura VARCHAR(50) NOT NULL, 
  fecha DATE NOT NULL, 
  razon VARCHAR(150) NOT NULL, 
  nit VARCHAR(150) NOT NULL, 
  monto VARCHAR(150) NOT NULL, 
  tcambio NUMERIC(18,6) NOT NULL, 
  codigo_control VARCHAR(150) NOT NULL, 
  contabilizado VARCHAR(150) NOT NULL, 
  observacion VARCHAR(150) NOT NULL, 
  id_actividad_economica INTEGER NOT NULL, 
  comision NUMERIC(18,6) NOT NULL, 
  importe_comis NUMERIC(18,6) NOT NULL, 
  por_comis NUMERIC(18,6) NOT NULL, 
  renglon VARCHAR(150) NOT NULL, 
  id_moneda INTEGER NOT NULL, 
  id_dosificacion INTEGER, 
  CONSTRAINT pk_tfactura__id_factura PRIMARY KEY(id_factura), 
  CONSTRAINT fk_tfactura__id_actividad_economica FOREIGN KEY (id_actividad_economica)
    REFERENCES fac.tactividad_economica(id_actividad_economica),
   
  CONSTRAINT fk_tfactura__id_agencia FOREIGN KEY (id_agencia)
    REFERENCES ven.tagencia(id_agencia),
   
  CONSTRAINT fk_tfactura__id_moneda FOREIGN KEY (id_moneda)
    REFERENCES param.tmoneda(id_moneda),
   
  CONSTRAINT fk_tfactura__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
   
) INHERITS (pxp.tbase)
WITHOUT OIDS;





CREATE TABLE fac.tfactura_detalle (
  id_factura_detalle SERIAL, 
  id_factura INTEGER NOT NULL, 
  renglon VARCHAR(50) NOT NULL, 
  tipo_concepto VARCHAR(50) NOT NULL, 
  id_concepto_ingas INTEGER NOT NULL, 
  cantidad VARCHAR(50) NOT NULL, 
  precio_unitario VARCHAR(50) NOT NULL, 
  importe VARCHAR(50) NOT NULL, 
  concepto VARCHAR(50) NOT NULL, 
  CONSTRAINT pk_tfactura_detalle__id_factura_detalle PRIMARY KEY(id_factura_detalle), 
  CONSTRAINT fk_tfactura_detalle__id_concepto_ingas FOREIGN KEY (id_concepto_ingas)
    REFERENCES param.tconcepto_ingas(id_concepto_ingas), 
  CONSTRAINT fk_tfactura_detalle__id_factura FOREIGN KEY (id_factura)
    REFERENCES fac.tfactura(id_factura)
) INHERITS (pxp.tbase)
WITHOUT OIDS;



CREATE TABLE fac.tnota (
  id_nota SERIAL, 
  estacion VARCHAR(20), 
  id_sucursal INTEGER, 
  estado VARCHAR(50) NOT NULL, 
  id_factura INTEGER NOT NULL, 
  nro_nota VARCHAR(50) NOT NULL, 
  fecha DATE DEFAULT now() NOT NULL, 
  razon VARCHAR(50) NOT NULL, 
  tcambio NUMERIC(18,6) NOT NULL, 
  nit VARCHAR(50) NOT NULL, 
  id_liquidacion VARCHAR(50) NOT NULL, 
  nro_liquidacion VARCHAR(50) NOT NULL, 
  id_moneda INTEGER NOT NULL, 
  monto_total NUMERIC(18,6) NOT NULL, 
  excento NUMERIC(18,6) NOT NULL, 
  total_devuelto NUMERIC(18,6) NOT NULL, 
  credfis NUMERIC(18,6) NOT NULL, 
  billete VARCHAR(255), 
  codigo_control VARCHAR(255), 
  id_dosificacion INTEGER, 
  CONSTRAINT pk_tnota__id_nota PRIMARY KEY(id_nota), 
  CONSTRAINT fk_tnota__id_factura FOREIGN KEY (id_factura)
    REFERENCES fac.tfactura(id_factura),
  
  CONSTRAINT fk_tnota__id_moneda FOREIGN KEY (id_moneda)
    REFERENCES param.tmoneda(id_moneda),
   
  CONSTRAINT fk_tnota__id_sucursal FOREIGN KEY (id_sucursal)
    REFERENCES ven.tsucursal(id_sucursal)
   
) INHERITS (pxp.tbase)
WITHOUT OIDS;



CREATE TABLE fac.tnota_detalle (
  id_nota_detalle SERIAL, 
  id_factura_detalle INTEGER, 
  id_nota INTEGER, 
  importe NUMERIC(18,6), 
  cantidad INTEGER, 
  concepto VARCHAR(255), 
  CONSTRAINT pk_tnota_detalle__id_nota_detalle PRIMARY KEY(id_nota_detalle), 
  CONSTRAINT fk_tnota_detalle__id_factura_detalle FOREIGN KEY (id_factura_detalle)
    REFERENCES fac.tfactura_detalle(id_factura_detalle),
   
  CONSTRAINT fk_tnota_detalle__id_nota FOREIGN KEY (id_nota)
    REFERENCES fac.tnota(id_nota)

) INHERITS (pxp.tbase)
WITHOUT OIDS;



/***********************************F-SCP-FFP-FAC-2-21/01/2015****************************************/







