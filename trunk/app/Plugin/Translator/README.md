# Plugin Translator pour CakePHP 2.x

## Description

Le plugin Translator permet d'effectuer des traductions sur un ensemble de
domaines, donnant la possibilité de "surcharger" ou d'"hériter" de traductions.

### Premier besoin

Dans une application métier, une même entité logique (entité métier) pour avoir
une appellation différente suivant le contexte.

Par exemple, si on a en base de données une table `recipes` recensant des recettes
de cuisine (le modèle associé étant `Recipe`), comportant un champ `name` précisant
le nom de la recette et un champ `cooking_time` donnant le temps de cuisson, on
peut avoir besoin des traductions décrites ci-dessous.

Dans la liste des recettes vues par l'administrateur du site (/recipes/admin_index):

- `Recipe.name`: *Titre*
- `Recipe.cooking_time`: *Temps de cuisson*

Dans la fiche descriptive d'une recette (/recipes/view/`<id>`):

- `Recipe.name`: *Recette culinaire*
- `Recipe.cooking_time`: *Temps de cuisson*

La traduction de `Recipe.name` diffère alors que la traduction de `Recipe.cooking_time`
est la meme.

### Second besoin

Il arrive parfois que des clients d'une telle application veuillent utiliser un
vocabulaire un peu différent pour décrire les memes entités métier.

Par exemple, pour un autre client francophone, il faudrait que l'intitulé de `Recipe.name`
dans la fiche descriptive d'une recette soit *Recette de cuisine*.

### Résumé des besoins

La maintenance des fichiers de traduction peut être longue et fastidieuse dès lors
qu'il existe des répétitions ou des cas particuliers.

Il faudrait donc que la traduction d'une entité logique ne soit présente qu'une
seule fois, mais avec tout de même la possibilité de la spécialiser dans certaines
pages ou pour certains clients, sans toucher au code de l'application, seulement
aux fichiers de traductions.

Par ailleurs, la recherche de traductions en temps réel aura une influence négative
sur les performances de l'application. Il faudra donc maintenir un cache des traductions
en fonction du contexte en mode production.

### Solution

### Fichiers de configurations

Dans le fichier `app/Config/bootstrap.php` de tous mes clients francophones.

```php
// Remarque: en CakePHP < 2.4.0, la valeur serait "fre"
Configure::write( 'Config.language', 'fra' );

CakePlugin::load( 'Translator', array( 'bootstrap' => true ) );
```

Plus bas, dans le fichier `app/Config/bootstrap.php` de mon autre client francophone:

```php
Configure::write( 'Translator.suffix', 'monclient' );
```

### Dans la classe `AppController`

```php
public $components = array(
    'TranslatorAutoload'
);
```

Les domaines qui seront successivement pris en compte pour les traductions sont:

- `<plugin>`\_`<contrôleur>`\_`<action>`\_`<suffixe>`
- `<plugin>`\_`<contrôleur>`\_`<action>`
- `<contrôleur>`\_`<action>`\_`<suffixe>`
- `<contrôleur>`_`<action>`
- `<contrôleur>`_`<suffixe>`
- `<contrôleur>`
- default_`<suffixe>`
- default

#### Avec les paramètres par défaut explicités

```php
public $components = array(
    'TranslatorAutoload' => array(
        // Le nom de la classe utilitaire utilisée pour les traductions
        'translatorClass' => 'Translator',
        // Les événements permettant de charger et de sauvegarder le cache des traductions "magiques"
        // Par défaut, les traductions sont chargées à l'initialisation du component
        // et sauvegardées soit juste avant la redirection, soit juste après le rendu de la page
        'events' => array(
            'initialize' => 'load',
            'startup' => null,
            'beforeRender' => null,
            'beforeRedirect' => 'save',
            'shutdown' => 'save'
        )
    )
);
```

### Fichiers de traductions

Pour les clients francophones, ces fichiers sont situés dans le répertoire
`app/Locale/fra/LC_MESSAGES` (ou `app/Locale/fre/LC_MESSAGES` en CakePHP < 2.4.0).

#### recipes.po

Ce fichier contient les traductions communes des pages du contrôleur `recipes`.

```
msgid "Recipe.cooking_time"
msgstr "Temps de cuisson"
```

#### recipes_admin_index.po

Ce fichier contient les traductions de la page `admin_index` du contrôleur `recipes`.

```
msgid "Recipe.name"
msgstr "Titre"
```

#### recipes_view.po

Ce fichier contient les traductions de la page `view` du contrôleur `recipes`.

```
msgid "Recipe.name"
msgstr "Recette culinaire"
```

#### recipes_view_monclient.po

Ce fichier contient les traductions de la page `view` du contrôleur `recipes` pour
mon autre client francophone identifié par `monclient`.

```
msgid "Recipe.name"
msgstr "Recette de cuisine"
```

### Traductions contextuelles

Dans mon contrôleur ou dans mes vues, je peux dès lors utiliser le code ci-dessous.

```php
// Notation abrégée, équivalente à la fonction __() de CakePHP
// Autre notation abrégée, équivalente à la fonction __n() de CakePHP est __mn()
echo __m( 'Recipe.name' );

// Notation plus longue, avec la classe utilitaire Translator, équivalente à la fonction __() de CakePHP
App::uses( 'Translator', 'Translator.Utility' );
echo Translator::getInstance()->translate( 'Recipe.name' );
// Autre notation plus longue, avec la classe utilitaire Translator, équivalente à la fonction __n() de CakePHP
// echo Translator::getInstance()->translate( '<singulier>', '<pluriel>', <count> );
```

### Extension des classes du plugin

@todo (interface Translator.TranslatorInterface) + exemple

## Compatibilité

Testé avec PHP 5.5.9, CakePHP 2.2.4 et 2.9.0.

## Intégration continue avec Jenkins

### Test

```bash
sudo -u www-data ant quality -f plugins/Translator/Vendor/Jenkins/build.xml
```

### Préparation

```shell
sudo -u www-data ant clear -f plugins/Translator/Vendor/Jenkins/build.xml
mkdir app/tmp/build
sudo chmod a+rw app/tmp/build
wget http://localhost:8080/jnlpJars/jenkins-cli.jar
```

### Ajout des jobs dans Jenkins

```shell
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Translator" < plugins/Translator/Vendor/Jenkins/jobs/build.xml
java -jar jenkins-cli.jar -s http://localhost:8080 create-job "Plugin CakePHP 2.x Translator Qualité" < plugins/Translator/Vendor/Jenkins/jobs/quality.xml
```