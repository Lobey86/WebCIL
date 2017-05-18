BEGIN;

--
-- Insertion de valeur dans la table users
--
INSERT INTO users(civilite, nom, prenom, username, password, email, createdby, created, modified) VALUES(
'M.',
'Admin',
'Super',
'superadmin',
'b89e933ad5926b14346d323e4cd17237385007ce',
'admin@test.fr',
NULL,
NOW(),
NOW()
);

--
-- Insertion de valeur dans la table liste_droits
--
INSERT INTO liste_droits (libelle, value, created, modified) VALUES
('Rédiger une fiche', 1, NOW(), NOW()),
('Valider une fiche', 2, NOW(), NOW()),
('Viser une fiche', 3, NOW(), NOW()),
('Consulter le registre', 4, NOW(), NOW()),
('Insérer une fiche dans le registre', 5, NOW(), NOW()),
('Modifier une fiche du registre', 6, NOW(), NOW()),
('Télécharger une fiche du registre', 7, NOW(), NOW()),
('Créer un utilisateur', 8, NOW(), NOW()),
('Modifier un utilisateur', 9, NOW(), NOW()),
('Supprimer un utilisateur', 10, NOW(), NOW()),
('Créer une organisation', 11, NOW(), NOW()),
('Modifier une organisation', 12, NOW(), NOW()),
('Créer un profil', 13, NOW(), NOW()),
('Modifier un profil', 14, NOW(), NOW()),
('Supprimer un profil', 15, NOW(), NOW());

--
-- Insertion de valeur dans la table etats
--
INSERT INTO etats (libelle, value, created, modified) VALUES
('En cours de rédaction', 1, NOW(), NOW()),
('En cours de validation', 2, NOW(), NOW()),
('Validée', 3, NOW(), NOW()),
('Refusée', 4, NOW(), NOW()),
('Validée par le CIL', 5, NOW(), NOW()),
('Demande d avis', 6, NOW(), NOW()),
('Archivée', 7, NOW(), NOW()),
('Replacer en rédaction', 8, NOW(), NOW()),
('Modification du traitement inséré au registre', 9, NOW(), NOW());

--
-- Insertion de valeur dans la table admins
--
INSERT INTO admins (user_id, created, modified) values(1, NOW(), NOW());

COMMIT;