--
-- Création de la table de jointure Users Organisations
--

CREATE TABLE organisations_users (
  id SERIAL PRIMARY KEY NOT NULL,
  user_id INTEGER NOT NULL REFERENCES users(id),
  organisation_id INTEGER NOT NULL REFERENCES organisations(id),
   created DATE,
   modified DATE
);