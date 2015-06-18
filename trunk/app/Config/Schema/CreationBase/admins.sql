--
-- Création de la table admins
--

CREATE TABLE champs (
  id SERIAL  NOT NULL PRIMARY KEY,
  formulaires_id  INTEGER NOT NULL REFERENCES formulaires (id) ON DELETE CASCADE,
  type VARCHAR(25) NOT NULL,
  ligne INTEGER NOT NULL,
  colonne INTEGER NOT NULL,
  details TEXT NOT NULL,
  created  DATE,
  modified DATE
);

INSERT INTO admins (user_id, created, modified) values(1, NOW(), NOW());