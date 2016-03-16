--
-- Création de la table fg_formulaires
--
CREATE TABLE fg_formulaires (
  id SERIAL  NOT NULL PRIMARY KEY,
  organisations_id  INTEGER NOT NULL REFERENCES organisations (id) ON DELETE CASCADE,
  libelle VARCHAR(50) NOT NULL,
  active BOOL NOT NULL,
  created  DATE,
  modified DATE,
  description TEXT
);