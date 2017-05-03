# Plugin Postgres pour CakePHP 2.x

## Description

Le plugin Postgres fournit un shell de maintenance de base de données, diverses
méthodes utilitaires spécifiques à PostgreSQL, des règles de check à la mode
CakePHP en Pl/PgSQL qui seront automatiquement ajoutées aux règles de validation des
modèles

Testé avec PHP 5.5.9, CakePHP 2.9.0, PostgreSQL 8.4.19

## Installation

### Patches SQL à passer dans la base de données (surtout test_database_name)

```sql
\i <Plugin Postgres>/Config/sql/cakephp_validate_core.sql
\i <Plugin Postgres>/Config/sql/cakephp_validate_custom.sql
```

#### Remarque

Les règles de validation CakePHP correspondant aux règles de validation du fichier
cakephp_validate_custom.sql se trouvent dans la classe PostgresExtraValidationRulesBehavior.

### Configuration

#### Base de données

Pour pouvoir profiter des fonctionnalités de la classe PostgtresAutovalidateBehavior,
il faut ajouter des contraintes de CHECK aux champs.

Cela permet de s'assurer que les données stockées en base sont correctes, tandis
que la classe PostgtresAutovalidateBehavior permettra de les ajouter directement
aux règles de validation du modèle concerné.

Exemple d'ajout de la contrainte permettant de s'assurer que la valeur du champ
active de la table users soit uniquement 0 ou 1 (ou NULL).
```sql
ALTER TABLE users ADD CONSTRAINT users_active_in_list_chk CHECK ( cakephp_validate_in_list( active, ARRAY[0, 1] ) );
```

#### CakePHP

Dans le fichier app/Config/bootstrap.php
```php
CakePlugin::load( 'Postgres', array( 'bootstrap' => true ) );
```

Dans le fichier app/Config/database.php
```php
class DATABASE_CONFIG {
	// ...
	public $default = array(
		'datasource' => 'Postgres.Database/PostgresPostgres',
		// ...
	);
	// ...
}
```

## Utilisation

### Datasource

#### PostgresPostgres

- *addPostgresForeignKey*: ajoute une contrainte de clé étrangère dans le schéma de la base de données
- *checkPostgresIntervalSyntax*: vérification de la syntaxe d'un intervalle
- *checkPostgresSqlSyntax*: vérification de la syntaxe d'un morceau de code SQL
- *dropPostgresForeignKey*: supprime une contrainte de clé étrangère dans le schéma de la base de données
- *existsPostgresForeignKey*: vérifie si une contrainte de clé étrangère existe dans le schéma de la base de données
- *getPostgresCheckConstraints*: retourne la liste des contraintes de type check d'une table
- *getPostgresForeignKeys*: retourne la liste des clés étrangères présentes en base de données
- *getPostgresFunctions*: retourne la liste des fonctions disponibles
- *getPostgresVersion*: permet d'obtenir la version de PostgreSQL utilisée

### Behaviors

#### PostgresAutovalidateBehavior

Ce behavior permet d'ajouter automatiquement des règles de validation aux modèles
auxquels il est attaché en fonction des contraintes de check de la table au niveau
de la base de données.

#### PostgresExtraValidationRulesBehavior

Ce behavior ajoute les règles de validation compareDates et inlusiveRange.
- *compareDates*: compare deux champs de type date au moyen d'un opérateur de la meme manière que la la règle de validation comparison de CakePHP
- *inlusiveRange*: comme la règle de validation range de CakePHP, mais bornes incluses

### PostgresTableBehavior

- *getPostgresCheckConstraints*: retourne la liste des contraintes de type check concernant la table liée au modèle
- *getAllPostgresForeignKeys*: retourne l'ensemble des clés étrangères de la base de données
- *getPostgresForeignKeys*: retourne la liste des clés étrangères depuis ou vers les champs de la table à laquelle le modèle est lié
- *getPostgresForeignKeysFrom*: retourne la liste des clés étrangères depuis les champs de la table à laquelle le modèle est lié
- *getPostgresForeignKeysTo*: retourne la liste des clés étrangères vers les champs de la table à laquelle le modèle est lié

### Shells

### PostgresCheckForeignKeysShell

Les commandes suivantes sont disponibles
- *missing*: vérifie la correspondance entre les clés étrangères définies en base de données et les clés étrangères définies dans les relations entre modèles

#### Exemple

```bash
sudo -u www-data lib/Cake/Console/cake Postgres.PostgresCheckForeignKeys missing
```

### PostgresMaintenanceShell

Les commandes suivantes sont disponibles
- *all*: effectue toutes les opérations de maintenance ( reindex, sequence, vacuum )
- *sequences*: mise à jour des compteurs des champs auto-incrémentés
- *vacuum*: nettoyage de la base de données et mise à jour des statistiques du planificateur
- *reindex*: reconstruction des indexes

#### Exemple

```bash
sudo -u www-data lib/Cake/Console/cake Postgres.PostgresMaintenance all
```

## Intégration continue avec Jenkins

### Test

```bash
sudo -u www-data ant quality -f plugins/Postgres/Vendor/Jenkins/build.xml
```

### Préparation

```shell
sudo -u www-data ant clear -f plugins/Postgres/Vendor/Jenkins/build.xml
mkdir app/tmp/build
sudo chmod a+rw app/tmp/build
wget http://localhost:8080/jnlpJars/jenkins-cli.jar
```

### Ajout des jobs dans Jenkins

```shell
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Postgres" < plugins/Postgres/Vendor/Jenkins/jobs/build.xml
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Postgres Qualité" < plugins/Postgres/Vendor/Jenkins/jobs/quality.xml
```