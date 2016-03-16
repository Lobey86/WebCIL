--
-- Création de la table historiques
--
CREATE TABLE historiques (
  id SERIAL NOT NULL PRIMARY KEY,
  content VARCHAR(300),
  fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
  created DATE,
  modified DATE
);