
--
-- Création de la table roles_droits
--

CREATE TABLE droits (
  id SERIAL NOT NULL PRIMARY KEY,
  role_id INTEGER NOT NULL REFERENCES roles(id),
  liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id)
);