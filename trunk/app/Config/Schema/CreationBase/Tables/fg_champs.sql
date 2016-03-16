--
-- Création de la table champs
--
CREATE TABLE fg_champs (
  id SERIAL  NOT NULL PRIMARY KEY,
  formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE,
  type VARCHAR(25) NOT NULL,
  ligne INTEGER NOT NULL,
  colonne INTEGER NOT NULL,
  details TEXT NOT NULL,
  created  DATE,
  modified DATE
);