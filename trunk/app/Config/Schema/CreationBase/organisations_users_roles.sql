--
-- Création de la table de jointure Users Organisations
--

CREATE TABLE organisation_user_roles (
  id SERIAL PRIMARY KEY NOT NULL,
  organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE,
  role_id INTEGER NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
   created DATE,
   modified DATE
);