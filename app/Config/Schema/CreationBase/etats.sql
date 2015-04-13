
--
-- Création de la table etats
--

CREATE TABLE etats (
  id SERIAL NOT NULL PRIMARY KEY,
  libelle VARCHAR(50),
  value INT,
     created DATE,
   modified DATE
);

INSERT INTO etats (libelle, value) VALUES
('En cours de rédaction', 1),
('En cours de validation', 2),
('Validée', 3),
('Refusée', 4),
('Validée par le CIL', 5),
('Demande d\'avis', 6),
('Archivée', 7);