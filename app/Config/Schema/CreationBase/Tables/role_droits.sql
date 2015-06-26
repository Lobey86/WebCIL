
--
-- Création de la table role_droits
--

CREATE TABLE role_droits(
  id SERIAL NOT NULL PRIMARY KEY,
  role_id INTEGER NOT NULL REFERENCES roles(id),
  liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id),
   created DATE,
   modified DATE
);