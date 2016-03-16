--
-- Création de la table droits
--
CREATE TABLE droits (
  id SERIAL NOT NULL PRIMARY KEY,
  organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE,
  liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id),
  created DATE,
  modified DATE
);