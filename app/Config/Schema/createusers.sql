
--
-- Création de la table users
--

CREATE TABLE users (
  id SERIAL NOT NULL PRIMARY KEY,
  nom VARCHAR(50),
  prenom VARCHAR(50),
  username VARCHAR(50),
  password VARCHAR(100),
  email VARCHAR(100),
  createdby INT,
  created TIMESTAMP WITHOUT TIME ZONE,
  modified TIMESTAMP WITHOUT TIME ZONE
);

INSERT INTO users(nom, prenom, username, password, email, createdby, created, modified) VALUES(
'Admin',
'Super',
'superadmin',
'84dedcb691046009c3ff23464fa6366b41ce6e34',
'',
'1',
NOW(),
NOW()
);