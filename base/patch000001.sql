/***********************************I-SCP-FFP-FAC-1-19/11/2014****************************************/


/***********************************F-SCP-FFP-FAC-1-19/11/2014****************************************/


/***********************************I-SCP-FFP-FAC-2-21/01/2015****************************************/

/***********************************F-SCP-FFP-FAC-2-21/01/2015****************************************/




/***********************************I-SCP-FFP-FAC-3-12/02/2015****************************************/

CREATE SCHEMA informix;

CREATE EXTENSION informix_fdw;

CREATE SERVER sai1
FOREIGN DATA WRAPPER informix_fdw
OPTIONS (informixserver 'sai1');


CREATE USER MAPPING FOR CURRENT_USER
SERVER sai1
OPTIONS (username 'conexinf', password 'conexinf123');

CREATE FOREIGN TABLE informix.liquidevolu (
  pais varchar(3),
  estacion varchar(3),
  docmnt varchar(6),
  nroliqui varchar(20),
  fecha date,
  estpago varchar(1),
  estado varchar(1),
  notaboa varchar(1)

) SERVER sai1

OPTIONS ( query 'SELECT pais,estacion,docmnt,nroliqui,fecha,estpago,estado,notaboa FROM liquidevolu',
database 'ingresos',
  informixdir '/opt/informix',
  client_locale 'en_US.utf8',
  informixserver 'sai1');

  CREATE FOREIGN TABLE informix.liquitra (

 	pais varchar(3),
	estacion varchar(3),
	docmnt varchar(6),
	nroliqui varchar(20),
	renglon integer,
	idtramo varchar(1),
	billcupon decimal(13),
	cupon integer,
	origen varchar(3),
	destino varchar(3),
	estado varchar(1)

) SERVER sai1

OPTIONS ( query 'SELECT
                pais,
                estacion,
                docmnt,
                nroliqui,
                renglon,
                idtramo,
                billcupon,
                cupon,
                origen,
                destino,
                estado
            FROM
                liquitra',
          database 'ingresos',
          informixdir '/opt/informix',
          client_locale 'en_US.utf8',
          informixserver 'sai1');

CREATE TABLE fac.tactividad_economica (
  id_usuario_reg INTEGER,
  id_usuario_mod INTEGER,
  fecha_reg TIMESTAMP WITHOUT TIME ZONE DEFAULT now(),
  fecha_mod TIMESTAMP WITHOUT TIME ZONE DEFAULT now(),
  estado_reg CHARACTER VARYING(10) DEFAULT 'activo'::character varying,
  id_usuario_ai INTEGER,
  usuario_ai CHARACTER VARYING(300),
  id_actividad_economica INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('fac.tactividad_economica_id_actividad_economica_seq'::regclass),
  nombre_actividad CHARACTER VARYING(50) NOT NULL,
  codigo_actividad CHARACTER VARYING(50) NOT NULL
);

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
  CONSTRAINT pk_tdosificacion__id_dosificacion PRIMARY KEY(id_dosificacion)
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
  CONSTRAINT pk_tfactura__id_factura PRIMARY KEY(id_factura)
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
  CONSTRAINT pk_tfactura_detalle__id_factura_detalle PRIMARY KEY(id_factura_detalle)
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
  nrofac BIGINT,
  nroaut BIGINT,
  reimpresion VARCHAR(100)[],
  CONSTRAINT pk_tnota__id_nota PRIMARY KEY(id_nota)
) INHERITS (pxp.tbase)
WITHOUT OIDS;


CREATE TABLE fac.tnota_detalle (
  id_nota_detalle SERIAL,
  id_factura_detalle INTEGER,
  id_nota INTEGER,
  importe NUMERIC(18,6),
  cantidad INTEGER,
  concepto VARCHAR(255),
  exento NUMERIC(18,6),
  total_devuelto NUMERIC(18,6),
  CONSTRAINT pk_tnota_detalle__id_nota_detalle PRIMARY KEY(id_nota_detalle)
) INHERITS (pxp.tbase)
WITHOUT OIDS;


/***********************************F-SCP-FFP-FAC-3-12/02/2015****************************************/




/***********************************I-SCP-FFP-FAC-4-20/02/2015****************************************/

ALTER TABLE fac.tnota ADD fecha_fac DATE NULL;

/***********************************F-SCP-FFP-FAC-4-20/02/2015****************************************/