CREATE TABLE fg_champs (
  id            SERIAL      NOT NULL PRIMARY KEY,
  formulaire_id INTEGER     NOT NULL REFERENCES fg_formulaires (id),
  name          VARCHAR(25) NOT NULL,
  obligatoire   BOOLEAN     NOT NULL,
  defaut        VARCHAR(50) NOT NULL,
  label         VARCHAR(50) NOT NULL,
  placeholder   VARCHAR(50) NOT NULL,
  details       TEXT        NOT NULL,
  created       DATE,
  modified      DATE
);