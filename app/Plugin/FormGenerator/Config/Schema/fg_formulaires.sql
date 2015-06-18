--
-- Création de la table formulaires
--

CREATE TABLE fg_formulaires (
  id              SERIAL       NOT NULL PRIMARY KEY,
  organisation_id INTEGER      NOT NULL REFERENCES organisations (id),
  nom             VARCHAR(100) NOT NULL,
  description     TEXT,
  active          BOOLEAN      NOT NULL,
  created         DATE,
  modified        DATE
);