/***********************************I-SCP-FFP-FAC-1-19/11/2014****************************************/

CREATE TABLE fac.tactividad_economica (
  id_actividad_economica SERIAL,
  nombre_actividad VARCHAR(50) NOT NULL,
  codigo_actividad VARCHAR(50) NOT NULL,
  CONSTRAINT pk_tactividad_economica__id_actividad_economica PRIMARY KEY(id_actividad_economica)
) INHERITS (pxp.tbase)
WITHOUT OIDS;


/***********************************F-SCP-FFP-FAC-1-19/11/2014****************************************/



