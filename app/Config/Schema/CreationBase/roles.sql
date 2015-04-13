--
-- Création de la table roles
--

CREATE TABLE roles (
  id SERIAL NOT NULL PRIMARY KEY,
  libelle VARCHAR(50),
  organisation_id INTEGER NOT NULL REFERENCES organisations(id),
   created DATE,
   modified DATE