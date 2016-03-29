BEGIN;

--
-- Insertion de valeur dans la table users
--
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

--
-- Insertion de valeur dans la table liste_droits
--
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

--
-- Insertion de valeur dans la table etats
--
INSERT INTO etats (libelle, value) VALUES
('En cours de rédaction', 1),
('En cours de validation', 2),
('Validée', 3),
('Refusée', 4),
('Validée par le CIL', 5),
('Demande d avis', 6),
('Archivée', 7);

--
-- Insertion de valeur dans la table admins
--
INSERT INTO admins (user_id, created, modified) values(1, NOW(), NOW());

COMMIT;