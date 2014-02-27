
/***********************************I-SCP-JRR-FACTU-0-18/02/2014****************************************/
CREATE TABLE factu.tdosificacion (
  id_dosificacion INTEGER NOT NULL, 
  sucursal VARCHAR(10) NOT NULL, 
  autorizacion VARCHAR(15) NOT NULL, 
  llave TEXT NOT NULL, 
  inicio BIGINT NOT NULL, 
  numero_factura BIGINT NOT NULL, 
  fecha_limite DATE NOT NULL, 
  autoimpresor VARCHAR(7) DEFAULT 'SFC 001'::character varying NOT NULL, 
  estado VARCHAR(10) DEFAULT 'Activo'::character varying NOT NULL, 
  fin BIGINT DEFAULT 0, 
  sistema VARCHAR(20) DEFAULT 'Pasajes'::character varying, 
  CONSTRAINT dosificacion_pkey PRIMARY KEY(id_dosificacion)
  
) WITHOUT OIDS;

CREATE TABLE factu.tfactura (
  id_factura SERIAL NOT NULL, 
  id_vendedor INTEGER NOT NULL, 
  id_dosificacion INTEGER NOT NULL, 
  nit VARCHAR(15) NOT NULL, 
  fecha DATE NOT NULL, 
  nombre VARCHAR(50) NOT NULL, 
  monto NUMERIC(7,2) NOT NULL, 
  numero_factura BIGINT NOT NULL, 
  texto_factura TEXT, 
  codigo_control VARCHAR(15), 
  fecha_limite DATE NOT NULL, 
  tipo VARCHAR(15) NOT NULL, 
  autorizado VARCHAR(10), 
  impresion INTEGER DEFAULT 0, 
  estado VARCHAR(10) DEFAULT 'Activo'::character varying NOT NULL, 
  CONSTRAINT factura_codigo_control_key UNIQUE(codigo_control), 
  CONSTRAINT factura_dosificacion_key UNIQUE(id_dosificacion, numero_factura), 
  CONSTRAINT factura_pkey PRIMARY KEY(id_factura)
) WITHOUT OIDS;


  
/***********************************F-SCP-JRR-FACTU-0-18/02/2014****************************************/
