--
-- Création de la table fiches
--

CREATE TABLE fiches
(
  id serial NOT NULL,
  user_id integer,
  created date,
  modified date,
  form_id integer NOT NULL,
  organisation_id integer,
  numero character varying(200),
  CONSTRAINT fiches_pkey PRIMARY KEY (id)
)