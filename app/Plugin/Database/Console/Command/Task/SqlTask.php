<?php
/**
 *
 * WebDavTask file
 *
 * web-delib : Application de gestion des actes administratifs
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webdelib web-delib Project
 * @since       web-delib v4.2
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 */
// @codeCoverageIgnoreStart
App::uses('ConnectionManager', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
// @codeCoverageIgnoreEnd

/**
 * Application Task for Cake
 *
 * Tache de mise à jour
 * Permet de passer simplement des fichiers sql à la base
 * Avec prise en charge transactionnelle
 *
 * @version 4.2
 * @package app.Console.Command.Task
 */
class SqlTask extends Shell
{

    protected $db;

    /**
     * Function execute
     *
     * Initialisation de l'objet ConnectionManager
     * (récupération de la connexion à la base de données à partir de database.php)
     *
     * @version 4.2
     */
    public function execute()
    {
        $this->db = ConnectionManager::getDataSource('default');
        if (!$this->db->isConnected()) {
            $this->out("<error>Impossible d'établir une connexion à la base de données. Veuillez vérifier vos paramètres dans le fichier app/Config/database.php et réessayer.</error>");
            return false;
        }
    }

    /**
     * Function run
     *
     * @param $sqlpath chemin vers le fichier sql à exécuter
     * @return bool résultat de l'exécution du sql
     * @version 4.2
     */
    public function run($sqlpath)
    {
        $sqlfile = new File($sqlpath);

        if (!$sqlfile->exists()) {
            $this->out("<error>Patch sql $sqlpath introuvable, veuillez vous assurer d'avoir la dernière version des sources</error>");
            return false;
        }

        //Démarrage de la transaction
        try {
            //Lecture ligne par ligne (séparateur ;)
            $content = $sqlfile->read();
            $sqlfile->close();
            //Supprime les lignes de commentaire
            $content = preg_replace('/--.*\n/', '', $content);
            $sql = explode(';', $content);
            foreach ($sql as $sqlline) {
                //Suppression des espaces en début ou fin de ligne
                $line = trim($sqlline);
                //Saut des mots clés begin et commit (transaction déjà démarrée)
                if (!empty($line) && !in_array(strtolower($line), array('begin', 'commit'))) {
                    $this->db->rawQuery($line); // Exécute la ligne sql
                }
            }
            //Fin de la transaction, tout s'est bien passé
            return true;
        } catch (Exception $e) {
            //Fin de la transaction, une erreur a été rencontrée
            $this->out("<important>Erreur SQL : {$e->getMessage()}</important>");
            if (!empty($line)) {
                $this->out("<error>Requête en erreur : $line</error>");
            }
            return false;
        }
    }

    /**
     * Function runrunSkipErrors
     *
     * @param $sqlpath chemin vers le fichier sql à exécuter
     * @return bool résultat de l'exécution du sql
     * @version 4.2
     */
    public function runSkipErrors($sqlpath)
    {
        $sqlfile = new File($sqlpath);

        if (!$sqlfile->exists()) {
            $this->out("<error>Patch sql $sqlpath introuvable, veuillez vous assurer d'avoir la dernière version des sources</error>");
            return false;
        }

        //Lecture ligne par ligne (séparateur ;)
        $content = $sqlfile->read();
        $sqlfile->close();

        //Supprime les lignes de commentaire
        $content = preg_replace('/--.*\n/', '', $content);
        $sql = explode(';', $content);
        foreach ($sql as $sqlline) {
            //Suppression des espaces en début ou fin de ligne
            $line = trim($sqlline);
            //Saut des mots clés begin et commit (transaction déjà démarrée)
            if (!empty($line) && !in_array(strtolower($line), array('begin', 'commit'))) {
                try {
                    $this->db->rawQuery($line); // Exécute la ligne sql
                } catch (Exception $e) {
                    //Fin de la transaction, une erreur a été rencontrée
                    $this->out("<important>Erreur SQL : {$e->getMessage()}</important>");
                    if (!empty($line)) {
                        $this->out("<error>Requête en erreur : $line</error>");
                    }
                }
            }
        }
        //Fin de la transaction, tout s'est bien passé
        return true;
    }

    /**
     * Function begin
     *
     * Démarrage de la transaction
     *
     * @version 4.2
     */
    public function begin()
    {
        $this->db->begin();
    }

    /**
     * Function commit
     *
     * Fin de la transaction, tout s'est bien passé
     *
     * @version 4.2
     */
    public function commit()
    {
        $this->db->commit();
    }

    /**
     * Function rollback
     *
     * Fin de la transaction, une erreur a été rencontrée
     *
     * @version 4.2
     */
    public function rollback()
    {
        $this->db->rollback();
    }
}
