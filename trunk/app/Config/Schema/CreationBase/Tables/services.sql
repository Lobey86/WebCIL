--
-- Création de la table commentaires
--
CREATE TABLE services (
  id              SERIAL      NOT NULL PRIMARY KEY,
  libelle         VARCHAR(50) NOT NULL,
  organisation_id INTEGER     NOT NULL REFERENCES organisations (id),
  created         DATE,
  modified        DATE
);