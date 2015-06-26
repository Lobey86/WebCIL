--
-- Création de la table files
--

CREATE TABLE files (
  id SERIAL NOT NULL PRIMARY KEY,
  nom VARCHAR(50),
  url VARCHAR(100),
  fiche_id INTEGER NOT NULL REFERENCES fiches(id),
   created DATE,
   modified DATE
);