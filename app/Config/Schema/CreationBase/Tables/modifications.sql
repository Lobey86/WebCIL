--
-- Création de la table champs
--
CREATE TABLE modifications (
  id SERIAL  NOT NULL PRIMARY KEY,
  fiches_id  INTEGER NOT NULL REFERENCES fiches (id) ON DELETE CASCADE,
  modif VARCHAR(300) NOT NULL,
  created  DATE,
  modified DATE
);