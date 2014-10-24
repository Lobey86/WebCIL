<?php
/**
 * Fichier de configuration du plugin CakeFlow
 * il exite trois classes qui entrent en jeux dans l'utilisation du plugin
 * - la classe des utilisateurs qui vont ajouter, modifier et supprimer la définition des circuits de traitement
 * - la classe des déclencheurs qui vont exécuter le traitement du circuit de traitement (valider, refuser, renvoyer à l'étape précédente, ...)
 * - la classe des cibles qui sont les objets insérés dans un circuit de traitement
 * Définition des paramètres communs de configuration
 *    CAKEFLOW_xxx_MODEL : permet de faire le lien entre le plugin et les classes des différents modèles. Ex: 'User', 'Utilisateur', ...
 *    CAKEFLOW_xxx_FIELDS : permet de spécifier les champs séparés par des virgule, utilisés pour afficher les occurences du modèle lié. Ex : 'first_name,name,username'
 *    CAKEFLOW_xxx_FORMAT : permet spécifier le format d'affichage des occurences du model lié. Ex : '%s %s - %s'
 * Définition des paramètres particuliers à chaque classe
 *    CAKEFLOW_USER_IDSESSION : lorsqu'il est renseigné, permet spécifier la variable de session de l'id de l'utilisateur connecté. Ex : 'Auth.user.id'
 *    CAKEFLOW_TRIGGER_TITLE : libellé utilisé dans les listes et formulaire pour désigner les déclencheurs
 *    CAKEFLOW_TRIGGER_CONDITIONS : lorsqu'il est renseigné, permet spécifier les conditions pour aller lire la liste des déclencheurs formaté comme suit : 'field1,condition1;field2,condition2'. Ex : 'actif,true;id >,10'
 *    CAKEFLOW_TARGET_TITLE : libellé utilisé dans les listes et formulaire pour désigner les cibles. Ex : 'Délibérations'
 */

/**
 * Cakeflow.App : application dans laquelle le plugin est installé
 */
define('CAKEFLOW_APP', 'WEBDELIB');

/**
 * cakeflow.User : classe des utilisateurs de l'application'
 */
define('CAKEFLOW_USER_MODEL', 'User');
define('CAKEFLOW_USER_FIELDS', 'prenom,nom,login');
define('CAKEFLOW_USER_FORMAT', '%s %s (%s)');
define('CAKEFLOW_USER_IDSESSION', 'user.User.id');

/***
 * cakeflow.Trigger : classe des déclencheurs
 */
define('CAKEFLOW_TRIGGER_MODEL', 'User');
define('CAKEFLOW_TRIGGER_FIELDS', 'nom,prenom');
define('CAKEFLOW_TRIGGER_FORMAT', '%s %s');
define('CAKEFLOW_TRIGGER_TITLE', 'Utilisateur');
define('CAKEFLOW_TRIGGER_CONDITIONS', '');

/***
 * cakeflow.Target : classe des cibles
 */
define('CAKEFLOW_TARGET_MODEL', 'Deliberation');
define('CAKEFLOW_TARGET_FIELDS', 'libelle');
define('CAKEFLOW_TARGET_FORMAT', '%s');
define('CAKEFLOW_TARGET_TITLE', 'Délibération');

/**
 * fonctionnement du plugin
 */
define('CAKEFLOW_GERE_SIGNATURE', false);
define('CAKEFLOW_GERE_DEFAUT', false);
define('CAKEFLOW_ACTIONS_HORSTRAITEMENT', 'ST,JS,IN');

/**
 * Type des étapes: ! ne pas modifier les lignes ci-dessous !
 */
define('CAKEFLOW_SIMPLE', 1);
define('CAKEFLOW_CONCURRENT', 2);
define('CAKEFLOW_COLLABORATIF', 3);

/**
 * Association du trigger -1 au parapheur
 */
define('CAKEFLOW_TRIGGER_PARAPHEUR', -1);
