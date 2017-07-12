-- @todo Théo les INDEX (pas uniques) sur les clés étrangères
-- @fixme ON DELETE NO ACTION pour plus de sécurité ?

BEGIN;

--
-- CREATION DES TABLES DE LA BASE DE DONNEES
--


--
-- Création de la table users
--
CREATE TABLE users (
    id SERIAL NOT NULL PRIMARY KEY,
    civilite VARCHAR(4) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephonefixe VARCHAR(15),
    telephoneportable VARCHAR(15),
    createdby INT DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX users_username_idx ON users (username);
CREATE UNIQUE INDEX users_email_idx ON users (email);

ALTER TABLE users ADD CONSTRAINT users_civilite_in_list_chk CHECK (cakephp_validate_in_list(civilite, ARRAY['M.', 'Mme.']));
ALTER TABLE users ADD CONSTRAINT users_email_email_chk CHECK (cakephp_validate_email(email));
ALTER TABLE users ADD CONSTRAINT users_username_min_length_chk CHECK (cakephp_validate_min_length(username, 3));
ALTER TABLE users ADD CONSTRAINT users_telephonefixe_phone_chk CHECK ( cakephp_validate_phone( telephonefixe, NULL, 'fr' ) );
ALTER TABLE users ADD CONSTRAINT users_telephoneportable_phone_chk CHECK ( cakephp_validate_phone( telephoneportable, NULL, 'fr' ) );
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
    nomresponsable VARCHAR(50) NOT NULL,
    prenomresponsable VARCHAR(50) NOT NULL,
    emailresponsable VARCHAR(75) NOT NULL,
    telephoneresponsable VARCHAR(15) NOT NULL,
    fonctionresponsable VARCHAR(75) NOT NULL,
    cil INT DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    numerocil VARCHAR(50) DEFAULT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX organisations_raisonsociale_idx ON organisations (raisonsociale);

ALTER TABLE organisations ADD CONSTRAINT organisations_telephone_phone_chk CHECK ( cakephp_validate_phone( telephone, NULL, 'fr' ) );
ALTER TABLE organisations ADD CONSTRAINT organisations_fax_phone_chk CHECK ( cakephp_validate_phone( fax, NULL, 'fr' ) );
ALTER TABLE organisations ADD CONSTRAINT organisations_email_email_chk CHECK ( cakephp_validate_email( email ) );
ALTER TABLE organisations ADD CONSTRAINT organisations_ape_alpha_numeric_chk CHECK ( cakephp_validate_alpha_numeric( ape ) );
ALTER TABLE organisations ADD CONSTRAINT organisations_emailresponsable_email_chk CHECK ( cakephp_validate_email( emailresponsable ) );
ALTER TABLE organisations ADD CONSTRAINT organisations_telephoneresponsable_phone_chk CHECK ( cakephp_validate_phone( telephoneresponsable, NULL, 'fr' ) );

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisations_users (
    id SERIAL PRIMARY KEY NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    organisation_id INTEGER NOT NULL REFERENCES organisations(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX organisations_users_user_id_organisation_id_idx ON organisations_users (user_id, organisation_id);

--
-- Création de la table commentaires
--
CREATE TABLE services (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    organisation_id INTEGER NOT NULL REFERENCES organisations(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX services_libelle_organisation_id_idx ON services (libelle, organisation_id);

--
-- Création de la table roles
--
CREATE TABLE roles (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    organisation_id INTEGER NOT NULL REFERENCES organisations(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX roles_libelle_organisation_id_idx ON roles (libelle, organisation_id);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_services (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX organisation_user_services_organisation_user_id_service_id_idx ON organisation_user_services (organisation_user_id, service_id);

--
-- Création de la table de jointure Users Organisations
--
CREATE TABLE organisation_user_roles (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    role_id INTEGER NOT NULL REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX organisation_user_roles_organisation_user_id_role_id_idx ON organisation_user_roles (organisation_user_id, role_id);

--
-- Création de la table liste_droits
--
CREATE TABLE liste_droits (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    value INTEGER NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX liste_droits_libelle_idx ON liste_droits (libelle);
CREATE UNIQUE INDEX liste_droits_value_idx ON liste_droits (value);

--
-- Création de la table role_droits
--
CREATE TABLE role_droits(
    id SERIAL NOT NULL PRIMARY KEY,
    role_id INTEGER NOT NULL REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE,
    liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE UNIQUE INDEX role_droits_role_id_liste_droit_id_idx ON role_droits (role_id, liste_droit_id);

--
-- Création de la table admins
--
CREATE TABLE admins
(
    id serial NOT NULL PRIMARY KEY,
    user_id integer NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX admins_user_id_idx ON admins (user_id);

--
-- Création de la table fg_formulaires
--
CREATE TABLE fg_formulaires (
    id SERIAL  NOT NULL PRIMARY KEY,
    organisations_id  INTEGER NOT NULL REFERENCES organisations(id) ON DELETE CASCADE ON UPDATE CASCADE,
    libelle VARCHAR(50) NOT NULL,
    active BOOL NOT NULL,
    description TEXT,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table fiches
--
CREATE TABLE fiches
(
    id serial NOT NULL PRIMARY KEY,
    user_id integer NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    form_id integer NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE ON UPDATE CASCADE,
    organisation_id integer NOT NULL REFERENCES organisations (id) ON DELETE CASCADE ON UPDATE CASCADE,
    numero VARCHAR(50),
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table etats
--
CREATE TABLE etats (
    id SERIAL NOT NULL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    value INTEGER NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX etats_libelle_idx ON etats (libelle);
CREATE UNIQUE INDEX etats_value_idx ON etats (value);

--
-- Création de la table etat_fiches
--
CREATE TABLE etat_fiches (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    etat_id INTEGER NOT NULL REFERENCES etats(id) ON DELETE CASCADE ON UPDATE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    previous_user_id INTEGER DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    previous_etat_id INTEGER DEFAULT NULL,
    actif BOOLEAN DEFAULT TRUE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table fichiers
--
CREATE TABLE fichiers (
    id SERIAL NOT NULL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    --@fixme champ contenu de fichier à la place de URL qui désigne l'emplacement sur disque
    url VARCHAR(100) NOT NULL,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE UNIQUE INDEX fichiers_nom_fiche_id_idx ON fichiers (nom, fiche_id);

--
-- Création de la table commentaires
--
CREATE TABLE commentaires (
    id SERIAL NOT NULL PRIMARY KEY,
    etat_fiches_id INTEGER NOT NULL REFERENCES etat_fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    destinataire_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table droits
--
CREATE TABLE droits (
    id SERIAL NOT NULL PRIMARY KEY,
    organisation_user_id INTEGER NOT NULL REFERENCES organisations_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    liste_droit_id INTEGER NOT NULL REFERENCES liste_droits(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table historiques
--
CREATE TABLE historiques (
    id SERIAL NOT NULL PRIMARY KEY,
    content VARCHAR(300),
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table champs
--
CREATE TABLE modifications (
    id SERIAL  NOT NULL PRIMARY KEY,
    etat_fiches_id INTEGER NOT NULL REFERENCES etat_fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    modif VARCHAR(300) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table commentaires
--
CREATE TABLE notifications (
    id SERIAL NOT NULL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    content INTEGER NOT NULL,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    vu BOOLEAN DEFAULT FALSE,
    afficher BOOLEAN DEFAULT FALSE,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);


--
-- Création de la table champs
--
CREATE TABLE fg_champs (
    id SERIAL  NOT NULL PRIMARY KEY,
    formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE ON UPDATE CASCADE,
    type VARCHAR(25) NOT NULL,
    ligne INTEGER NOT NULL,
    colonne INTEGER NOT NULL,
    details TEXT NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table modeles
--
CREATE TABLE modeles (
    id SERIAL  NOT NULL PRIMARY KEY,
    name_modele VARCHAR(100) NOT NULL,
    formulaires_id  INTEGER NOT NULL REFERENCES fg_formulaires (id) ON DELETE CASCADE ON UPDATE CASCADE,
    fichier VARCHAR(100) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table modeles extrait registre
--
CREATE TABLE modele_extrait_registres (
    id SERIAL  NOT NULL PRIMARY KEY,
    organisations_id  INTEGER NOT NULL REFERENCES organisations (id) ON DELETE CASCADE ON UPDATE CASCADE,
    name_modele VARCHAR(100) NOT NULL,
    fichier VARCHAR(100) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

--
-- Création de la table valeurs
--
CREATE TABLE valeurs (
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE CASCADE,
    valeur TEXT NOT NULL,
    champ_name VARCHAR(100) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE TABLE extrait_registres
(
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE NO ACTION,
    data bytea,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

CREATE TABLE traitement_registres
(
    id SERIAL NOT NULL PRIMARY KEY,
    fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE ON UPDATE NO ACTION,
    data bytea,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL
);

COMMIT;