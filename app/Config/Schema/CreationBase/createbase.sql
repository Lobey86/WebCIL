BEGIN;

--
-- CREATION DES TABLES DE LA BASE DE DONNEES
--


--
-- Création de la table users
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
    cil INT DEFAULT NULL REFERENCES users(id),
    created DATE,
    modified DATE
);

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

--
-- Création de la table commentaires
--
CREATE TABLE services (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    organisation_id INTEGER NOT NULL REFERENCES organisations(id),
    created DATE,
    modified DATE
);

--
-- Création de la table roles
--
CREATE TABLE roles (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50),
    organisation_id INTEGER NOT NULL REFERENCES organisations(id),
    created DATE,
    modified DATE
);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_services (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE,
    service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_roles (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE,
    role_id INTEGER NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

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

--
-- Création de la table role_droits
--
CREATE TABLE role_droits(
    id SERIAL NOT NULL PRIMARY KEY,
    role_id INTEGER NOT NULL REFERENCES roles(id),
    liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id),
    created DATE,
    modified DATE
);

--
-- Création de la table admins
--
CREATE TABLE admins
(
    id serial NOT NULL PRIMARY KEY,
    user_id integer NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE NO ACTION ,
    created date,
    modified date
);

--
-- Création de la table fiches
--
CREATE TABLE fiches
(
    id serial NOT NULL PRIMARY KEY,
    user_id integer,
    created date,
    modified date,
    form_id integer NOT NULL,
    organisation_id integer,
    numero character varying(200)
);

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

--
-- Création de la table etat_fiches
--
CREATE TABLE etat_fiches (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
    etat_id INTEGER NOT NULL REFERENCES etats(id),
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    previous_user_id INTEGER NOT NULL REFERENCES users(id),
    previous_etat_id INTEGER DEFAULT NULL,
    actif BOOLEAN DEFAULT TRUE,
    created DATE,
    modified DATE
);

--
-- Création de la table fichiers
--
CREATE TABLE fichiers (
    id SERIAL NOT NULL PRIMARY KEY,
    nom VARCHAR(50),
    url VARCHAR(100),
    fiche_id INTEGER NOT NULL REFERENCES fiches(id),
    created DATE,
    modified DATE
);

--
-- Création de la table commentaires
--
CREATE TABLE commentaires (
    id SERIAL NOT NULL PRIMARY KEY,
    etat_fiches_id INTEGER NOT NULL REFERENCES etat_fiches(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users(id),
    destinataire_id INTEGER NOT NULL REFERENCES users(id),
    created DATE,
    modified DATE
);

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

--
-- Création de la table historiques
--
CREATE TABLE historiques (
    id SERIAL NOT NULL PRIMARY KEY,
    content VARCHAR(300),
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
    created DATE,
    modified DATE
);

--
-- Création de la table champs
--
CREATE TABLE modifications (
    id SERIAL  NOT NULL PRIMARY KEY,
    fiches_id  INTEGER NOT NULL REFERENCES fiches (id) ON DELETE CASCADE,
    modif VARCHAR(300) NOT NULL,
    created  DATE,
    modified DATE
);

--
-- Création de la table commentaires
--
CREATE TABLE notifications (
    id SERIAL NOT NULL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id),
    content INTEGER NOT NULL,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
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
    organisations_id  INTEGER NOT NULL REFERENCES organisations (id) ON DELETE CASCADE,
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
    formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE,
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
    formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE,
    fichier VARCHAR(300) NOT NULL,
    created  DATE,
    modified DATE
);

--
-- Création de la table users
--
CREATE TABLE valeurs (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
    valeur TEXT NOT NULL,
    created DATE,
    modified DATE,
    champ_name VARCHAR(100) NOT NULL
);

CREATE TABLE extraits
(
    id SERIAL NOT NULL PRIMARY KEY,
    id_fiche INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE NO ACTION,
    data bytea
);

COMMIT;