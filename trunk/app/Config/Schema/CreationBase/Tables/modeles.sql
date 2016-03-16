--
-- Création de la table champs
--
CREATE TABLE modeles (
  id SERIAL  NOT NULL PRIMARY KEY,
  formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE,
  fichier VARCHAR(300) NOT NULL,
  created  DATE,
  modified DATE
);