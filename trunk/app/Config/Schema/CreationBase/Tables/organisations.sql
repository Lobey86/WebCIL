--
-- Création de la table organisations
--
CREATE TABLE organisations (
  id SERIAL NOT NULL PRIMARY KEY,
  raisonsociale VARCHAR(75),
  telephone VARCHAR(15),
  fax VARCHAR(15),
  adresse TEXT,
  email VARCHAR(75),
  sigle VARCHAR(100),
  siret VARCHAR(14),
  ape VARCHAR(5),
  logo TEXT,
  cil INT DEFAULT NULL REFERENCES users(id),
  created DATE,
  modified DATE
);