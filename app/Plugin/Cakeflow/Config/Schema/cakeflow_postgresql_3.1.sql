--
-- Création des circuits
--
CREATE SEQUENCE wkf_circuits_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_circuits (
  id integer DEFAULT nextval('wkf_circuits_id_seq'::regclass) NOT NULL,
  nom character varying(250) NOT NULL,
  description text,
  actif boolean DEFAULT true NOT NULL,
  defaut boolean DEFAULT false NOT NULL,
  created_user_id integer,
  modified_user_id integer,
  created timestamp without time zone,
  modified timestamp without time zone
);

--
-- Création des compositions
--
CREATE SEQUENCE wkf_compositions_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_compositions (
  id integer DEFAULT nextval('wkf_compositions_id_seq'::regclass) NOT NULL,
  etape_id integer NOT NULL,
  type_validation character varying(1) NOT NULL,
  trigger_id integer,
  soustype integer DEFAULT NULL,
  type_composition VARCHAR(20) DEFAULT 'USER',
  created_user_id integer,
  modified_user_id integer,
  created timestamp without time zone,
  modified timestamp without time zone
);

--
-- Création des étapes
--
CREATE SEQUENCE wkf_etapes_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_etapes (
  id integer DEFAULT nextval('wkf_etapes_id_seq'::regclass) NOT NULL,
  circuit_id integer NOT NULL,
  nom character varying(250) NOT NULL,
  description text,
  type integer NOT NULL,
  soustype integer DEFAULT NULL,
  ordre integer NOT NULL,
  created_user_id integer NOT NULL,
  modified_user_id integer,
  created timestamp without time zone,
  modified timestamp without time zone
);

--
-- Création des signatures
--
CREATE SEQUENCE wkf_signatures_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_signatures (
  id integer DEFAULT nextval('wkf_signatures_id_seq'::regclass) NOT NULL,
  type_signature character varying(100) NOT NULL,
  signature text NOT NULL
);

--
-- Création des traitements
--
CREATE SEQUENCE wkf_traitements_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_traitements (
  id integer DEFAULT nextval('wkf_traitements_id_seq'::regclass) NOT NULL,
  circuit_id integer NOT NULL,
  target_id integer NOT NULL,
  numero_traitement integer DEFAULT 1 NOT NULL,
  treated_orig smallint DEFAULT 0 NOT NULL,
  created_user_id integer,
  modified_user_id integer,
  created timestamp without time zone,
  modified timestamp without time zone,
  treated boolean
);

--
-- Création des visas
--
CREATE SEQUENCE wkf_visas_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;
CREATE TABLE wkf_visas (
  id integer DEFAULT nextval('wkf_visas_id_seq'::regclass) NOT NULL,
  traitement_id integer NOT NULL,
  trigger_id integer NOT NULL,
  signature_id integer,
  etape_nom character varying(250),
  etape_type integer NOT NULL,
  action character varying(2) NOT NULL,
  commentaire TEXT,
  date timestamp without time zone,
  numero_traitement integer NOT NULL,
  type_validation character varying(1) NOT NULL
);

--
-- Ajout des contraintes de clé primaire
--
ALTER TABLE ONLY wkf_circuits
ADD CONSTRAINT wkf_circuits_pkey PRIMARY KEY (id);


ALTER TABLE ONLY wkf_compositions
ADD CONSTRAINT wkf_compositions_pkey PRIMARY KEY (id);


ALTER TABLE ONLY wkf_etapes
ADD CONSTRAINT wkf_etapes_pkey PRIMARY KEY (id);


ALTER TABLE ONLY wkf_signatures
ADD CONSTRAINT wkf_signatures_pkey PRIMARY KEY (id);


ALTER TABLE ONLY wkf_traitements
ADD CONSTRAINT wkf_traitements_pkey PRIMARY KEY (id);


ALTER TABLE ONLY wkf_visas
ADD CONSTRAINT wkf_visas_pkey PRIMARY KEY (id);

--
-- Ajout des indexes
--
CREATE INDEX circuit_id       ON wkf_etapes       USING btree (circuit_id);
CREATE INDEX etape_id         ON wkf_compositions USING btree (etape_id);
CREATE INDEX modified_user_id ON wkf_circuits     USING btree (modified_user_id);
CREATE INDEX nom              ON wkf_etapes       USING btree (nom);
CREATE INDEX target           ON wkf_traitements  USING btree (target_id);
CREATE INDEX created_user_id  ON wkf_circuits     USING btree (created_user_id);

ALTER TABLE wkf_visas ADD COLUMN etape_id INT REFERENCES wkf_etapes(id) DEFAULT NULL;
ALTER TABLE wkf_signatures ADD COLUMN visa_id INT REFERENCES wkf_visas(id);
ALTER TABLE wkf_etapes ADD COLUMN cpt_retard INT;
ALTER TABLE wkf_visas ADD COLUMN date_retard TIMESTAMP WITHOUT TIME ZONE;