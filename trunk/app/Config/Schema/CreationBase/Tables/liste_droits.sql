--
-- Création de la table liste_droits
--
CREATE TABLE liste_droits (
  id SERIAL NOT NULL PRIMARY KEY,
  libelle VARCHAR(50),
  value INTEGER UNIQUE,
   created DATE,
   modified DATE
);

INSERT INTO liste_droits (libelle, value) VALUES
('Rédiger une fiche', 1),
('Valider une fiche', 2),
('Viser une fiche', 3),
('Consulter le registre', 4),
('Insérer une fiche dans le registre', 5),
('modifier une fiche du registre', 6),
('Télécharger une fiche du registre', 7),
('Créer un utilisateur', 8),
('Modifier un utilisateur', 9),
('Supprimer un utilisateur', 10),
('Créer une organisation', 11),
('Modifier une organisation', 12),
('Créer un profil', 13),
('Modifier un profil', 14),
('Supprimer un profil', 15);