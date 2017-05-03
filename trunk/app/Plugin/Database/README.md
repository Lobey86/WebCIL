# Plugin Database pour CakePHP 2.x

## Introduction

Ce plugin ajoute des fonctionnalités pour travailler avec un SGBD en CakePHP,
quel que soit le SGBD utilisé.

## Compatibilté

Testé avec CakePHP 2.2.4 et 2.9.0, PHP 5.5.9.

## Installation

Dans le fichier app/Config/bootstrap.php
```php
CakePlugin::load( 'Database', array( 'bootstrap' => true ) );
```

## Description

### Console/Command

#### DatabaseCheckRelationsShell

Ce shell parcourt les classes de modèles et vérifie que les relations entre eux
soient bien définies dans les deux sens.

En ligne de commande:
```shell
sudo -u www-data lib/Cake/Console/cake Database.database_check_relations
```

#### DatabaseDictionaryShell

Ce shell parcourt les classes de modèles crée un dictionnaire de données en HTML
pour toutes les tables de la base de données liées à un modèle.


En ligne de commande:
```shell
sudo -u www-data lib/Cake/Console/cake Database.database_dictionary
```

### Lib

#### alias

La fonction `alias` permet de remplacer des mots par d'autres dans une chaine de
caractères ou un array, de façon récursive, tant au niveau des clés que des valeurs.

```php
// Retournera 'SELECT "Bar"."id" AS "Foo__id" FROM "public"."foos" AS "Bar" WHERE "Bar"."name" = \'FooBar\';'
alias( 'SELECT "Foo"."id" AS "Foo__id" FROM "public"."foos" AS "Foo" WHERE "Foo"."name" = \'FooBar\';', array( 'Foo' => 'Bar' ) );
```

### Behavior

#### DatabaseAutovalidateBehavior

Ce behavior permet d'ajouter automatiquement des règles de validation aux modèles
auxquels il est attaché en fonction du schéma de la table au niveau de la base
de données (voir la méthode CakePHP Model::schema()).

Les règles suivantes sont déduites:
  - *notBlank*: si le champ est NOT NULL (pour CakePHP en version >= 2.7.0, sinon *notEmpty*)
  - *maxLength*: si le champ est de type CHAR ou VARCHAR
  - *integer*: si le champ est de type entier
  - *numeric*: si le champ est de type numérique
  - *date*: si le champ est de type date
  - *datetime*: si le champ est de type date et heure
  - *time*: si le champ est de type heure
  - *isUnique*: si le champ possède un index unique

Exemple d'utilisation dans une classe de modèle, avec la configuration par défaut:
```php
public $actsAs = array(
	'Database.DatabaseAutovalidate' => array(
		'rules' => array(
			// notBlank pour CakePHP en version >= 2.7.0, sinon notEmpty
			'notBlank' => true,
			'maxLength' => true,
			'integer' => true,
			'numeric' => true,
			'date' => true,
			'datetime' => true,
			'time' => true,
			'isUnique' => true,
		),
		'domain' => 'validation',
		'translate' => true
	)
);
```

#### DatabaseFormattableBehavior

Ce behavior permet d'appliquer des méthodes de classes utilitaires aux valeurs
de champs de modèles avant la validation et l'enregistrement.

Les classes utilitaires doivent se trouver dans Utility/DatabaseFormatters.

Exemple d'utilisation dans une classe de modèle, avec la configuration par défaut:
```php
public $actsAs = array(
	'Database.DatabaseDefaultFormatters' => array(
		'formatTrim' => array( 'NOT' => array( 'binary' ) ),
		'formatNull' => true,
		'formatNumeric' => array( 'float', 'integer' ),
		'formatSuffix'  => '/_id$/'
	)
);
```

Pour chacun de formateurs, les valeurs acceptées sont:
  - true/null
  - false
  - array()
  - string (expression rationnelle pour le nom du champ)
  - Une clé NOT est possible dans l'array pour prendre tous les types, moins ce qui est en valeur de cette clé.

Les types (PostgreSQL) sont:
  - binary
  - boolean
  - date
  - datetime
  - float
  - inet
  - integer
  - string
  - text
  - time

Il est possible de désactiver l'utilisation du formateur par défaut et d'en
configurer un autre de la manière suivante:
```php
public $actsAs = array(
	'Database.DatabaseDefaultFormatters' => false,
	'MyDefaultFormatters' => array(
		'formatTrim' => array( 'NOT' => array( 'binary' ) )
	)
);
```

#### DatabaseTableBehavior

Ce behavior ajoute les méthodes suivantes aux modèles liés à une table:
  - *fields*: retourne la liste des champs du modèle
  - *hasUniqueIndex*: permet de savoir si une colonne d'un modèle donné a un index unique
  - *join*: retourne un array permettant de faire une jointure ad-hoc en CakePHP
  - *joinAssociationData*: retourne les données d'association avec le modèle aliasé (voir join)
  - *joins*: permet de décrire les jointures à appliquer sur un modèle en spécifiant uniquement les noms des modèles (et éventuellement le type, condition, alias, table) ainsi que des sous-jointures dans la clé joins, un peu à la manière des contain
  - *sql*: retourne une requête SQL à partir d'un querydata (par exemple pour faire des sous-requêtes)
  - *types*: retourne la liste des types de champs de la table liée
  - *uniqueIndexes*: retourne la liste des indexes uniques de la table liée


Dans le fichier app/Model/AppModel.php
```php
public $actsAs = array( 'Database.DatabaseTable' );
```

### Utility

#### DatabaseRelations

Cette classe permet d'obtenir des informations sur les liaisons entre modèles à
partir des classes de modèles.

#### DatabaseFormatters/DatabaseDefaultFormatter

Cette classe possède des méthode publiques format<règle> à utiliser avec la classe
DatabaseFormattableBehavior.

## Intégration continue avec Jenkins

### Test

```bash
sudo -u www-data ant quality -f plugins/Database/Vendor/Jenkins/build.xml
```

### To-do list

- ajouter la validation réelle des valeurs suite à DatabaseAutovalidate
- vérifier les changements date Validate::range entre la 2.2.4 et la 2.9.0

### Préparation

```shell
sudo -u www-data ant clear -f plugins/Database/Vendor/Jenkins/build.xml
mkdir app/tmp/build
sudo chmod a+rw app/tmp/build
wget http://localhost:8080/jnlpJars/jenkins-cli.jar
```

### Ajout des jobs dans Jenkins

```shell
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Database" < plugins/Database/Vendor/Jenkins/jobs/build.xml
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Database Qualité" < plugins/Database/Vendor/Jenkins/jobs/quality.xml
```