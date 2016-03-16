--
-- Création de la table valeurs
--
CREATE TABLE valeurs (
  id SERIAL NOT NULL PRIMARY KEY,
  fiche_id INTEGER NOT NULL REFERENCES fiches(id),
  valeur TEXT NOT NULL,
  created DATE,
  modified DATE,
  champ_name VARCHAR(100) NOT NULL
);