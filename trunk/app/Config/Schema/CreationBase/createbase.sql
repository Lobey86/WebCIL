--
-- CREATION DES TABLES DE LA BASE DE DONNEES
--


--
-- Création de la table users
-- Insertion de valeur dans la table users
--
CREATE TABLE users (
    id SERIAL NOT NULL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    createdby INT,
    created DATE,
    modified DATE
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

--
-- Création de la table organisations
--
CREATE TABLE organisations (
    id SERIAL NOT NULL PRIMARY KEY,
    raisonsociale VARCHAR(75) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    fax VARCHAR(15),
    adresse TEXT NOT NULL,
    email VARCHAR(75) NOT NULL,
    sigle VARCHAR(100),
    siret VARCHAR(14) NOT NULL,
    ape VARCHAR(5) NOT NULL,
    logo TEXT,
    cil INT DEFAULT NULL FOREIGN KEY REFERENCES users(id),
    created DATE,
    modified DATE
);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisations_users (
    id SERIAL PRIMARY KEY NOT NULL,
    user_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id),
    organisation_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations(id),
    created DATE,
    modified DATE
);

--
-- Création de la table commentaires
--
CREATE TABLE services (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    organisation_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations(id),
    created DATE,
    modified DATE
);

--
-- Création de la table roles
--
CREATE TABLE roles (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50),
    organisation_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations(id),
    created DATE,
    modified DATE
);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_services (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations_users(id) ON DELETE CASCADE,
    service_id INTEGER NOT NULL FOREIGN KEY REFERENCES services(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_roles (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations_users(id) ON DELETE CASCADE,
    role_id INTEGER NOT NULL FOREIGN KEY REFERENCES roles(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

--
-- Création de la table liste_droits
-- Insertion de valeur dans la table liste_droits
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

--
-- Création de la table role_droits
--
CREATE TABLE role_droits(
    id SERIAL NOT NULL PRIMARY KEY,
    role_id INTEGER NOT NULL FOREIGN KEY REFERENCES roles(id),
    liste_droit_id INTEGER NOT NULL FOREIGN KEY REFERENCES liste_droits(id),
    created DATE,
    modified DATE
);

--
-- Création de la table admins
-- Insertion de valeur dans la table admins
--
CREATE TABLE admins
(
    id serial NOT NULL,
    user_id integer NOT NULL,
    created date,
    modified date,
    CONSTRAINT admins_pkey PRIMARY KEY (id),
    CONSTRAINT admins_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES users (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE CASCADE
);

INSERT INTO admins (user_id, created, modified) values(1, NOW(), NOW());

--
-- Création de la table fiches
--
CREATE TABLE fiches
(
    id serial NOT NULL,
    user_id integer,
    created date,
    modified date,
    form_id integer NOT NULL,
    organisation_id integer,
    numero character varying(200),
    CONSTRAINT fiches_pkey PRIMARY KEY (id)
);

--
-- Création de la table etats
-- Insertion de valeur dans la table etats
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
('Demande d avis', 6),
('Archivée', 7);

--
-- Création de la table etat_fiches
--
CREATE TABLE etat_fiches (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL FOREIGN KEY REFERENCES fiches(id) ON DELETE CASCADE,
    etat_id INTEGER NOT NULL FOREIGN KEY REFERENCES etats(id),
    user_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id) ON DELETE CASCADE,
    previous_user_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id),
    previous_etat_id INTEGER DEFAULT NULL,
    created DATE,
    modified DATE
);

--
-- Création de la table files
--
CREATE TABLE files (
    id SERIAL NOT NULL PRIMARY KEY,
    nom VARCHAR(50),
    url VARCHAR(100),
    fiche_id INTEGER NOT NULL FOREIGN KEY REFERENCES fiches(id),
    created DATE,
    modified DATE
);

--
-- Création de la table commentaires
--
CREATE TABLE commentaires (
    id SERIAL NOT NULL PRIMARY KEY,
    etat_fiches_id INTEGER NOT NULL FOREIGN KEY REFERENCES etat_fiches(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id),
    destinataire_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id),
    created DATE,
    modified DATE
);

--
-- Création de la table droits
--
CREATE TABLE droits (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL FOREIGN KEY REFERENCES organisations_users(id) ON DELETE CASCADE,
    liste_droit_id INTEGER NOT NULL FOREIGN KEY REFERENCES liste_droits(id),
    created DATE,
    modified DATE
);

--
-- Création de la table historiques
--
CREATE TABLE historiques (
    id SERIAL NOT NULL PRIMARY KEY,
    content VARCHAR(300),
    fiche_id INTEGER NOT NULL FOREIGN KEY REFERENCES fiches(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

--
-- Création de la table champs
--
CREATE TABLE modifications (
    id SERIAL  NOT NULL PRIMARY KEY,
    fiches_id  INTEGER NOT NULL FOREIGN KEY REFERENCES fiches (id) ON DELETE CASCADE,
    modif VARCHAR(300) NOT NULL,
    created  DATE,
    modified DATE
);

--
-- Création de la table commentaires
--
CREATE TABLE notifications (
    id SERIAL NOT NULL PRIMARY KEY,
    user_id INTEGER NOT NULL FOREIGN KEY REFERENCES users(id),
    content INTEGER NOT NULL,
    fiche_id INTEGER NOT NULL FOREIGN KEY REFERENCES fiches(id) ON DELETE CASCADE,
    vu BOOLEAN NOT NULL,
    created DATE,
    modified DATE,
    afficher BOOLEAN
);

--
-- Création de la table fg_formulaires
--
CREATE TABLE fg_formulaires (
    id SERIAL  NOT NULL PRIMARY KEY,
    organisations_id  INTEGER NOT NULL FOREIGN KEY REFERENCES organisations (id) ON DELETE CASCADE,
    libelle VARCHAR(50) NOT NULL,
    active BOOL NOT NULL,
    created  DATE,
    modified DATE,
    description TEXT
);

--
-- Création de la table champs
--
CREATE TABLE fg_champs (
    id SERIAL  NOT NULL PRIMARY KEY,
    formulaires_id  INTEGER NOT NULL FOREIGN KEY REFERENCES fg_formulaires (id) ON DELETE CASCADE,
    type VARCHAR(25) NOT NULL,
    ligne INTEGER NOT NULL,
    colonne INTEGER NOT NULL,
    details TEXT NOT NULL,
    created  DATE,
    modified DATE
);

--
-- Création de la table models
--
CREATE TABLE modeles (
    id SERIAL  NOT NULL PRIMARY KEY,
    formulaires_id  INTEGER NOT NULL FOREIGN KEY REFERENCES fg_formulaires (id) ON DELETE CASCADE,
    fichier VARCHAR(300) NOT NULL,
    created  DATE,
    modified DATE
);

--
-- Création de la table users
--
CREATE TABLE valeurs (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL FOREIGN KEY REFERENCES fiches(id),
    valeur TEXT NOT NULL,
    created DATE,
    modified DATE,
    champ_name VARCHAR(100) NOT NULL
);

CREATE TABLE extraits
(
    id_serial NOT NULL,
    id_fiche integer,
    data bytea,
    CONSTRAINT pk_extraits PRIMARY KEY (id),
    CONSTRAINT fk_extraits FOREIGN KEY (id_fiche)
        REFERENCES fiches (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION
);