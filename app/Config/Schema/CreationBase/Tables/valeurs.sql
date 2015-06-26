--
-- Création de la table users
--

CREATE TABLE valeurs (
  id SERIAL NOT NULL PRIMARY KEY,
  champ_id INTEGER NOT NULL REFERENCES champs(id),
  fiche_id INTEGER NOT NULL REFERENCES fiches(id),
  valeur TEXT NOT NULL,
  created DATE,
  modified DATE
);