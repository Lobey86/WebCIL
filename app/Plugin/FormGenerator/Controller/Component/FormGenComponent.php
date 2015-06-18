<?php

class FormGenComponent extends Component
{


    /**
     * Retourne les informations d'un formulaire spécifié
     *
     * @param $id : id du formulaire à récupérer
     *
     * @return array: Tableau des données du formulaire
     */
    public function get($id)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $table = $Formulaires->find('first', array('conditions' => array('id' => $id)));
        return $table;
    }


    /**
     * Retourne tous les formulaires qui match avec les conditions.
     * Les conditions sont facultatives et doivent correspondre à des conditions SQL
     *
     * @param array $conditions : Conditions de recherche
     *
     * @return array: Tableau des données des différents formulaires retournés
     */
    public function getAll(array $conditions = null)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $table = $Formulaires->find('all', array(
            'conditions' => $conditions,
            'order' => 'active DESC'
        ));

        return $table;
    }


    /**
     * Retourne tous les formulaires qui match avec les conditions et qui sont actifs.
     * Les conditions sont facultatives et doivent correspondre à des conditions SQL
     *
     * @param array $conditions : Conditions de recherche
     *
     * @return array: Tableau des données des différents formulaires retournés
     */
    public function getActive(array $conditions = null)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $conditions[ 'active' ] = true;
        $table = $Formulaires->find('all', array(
            'conditions' => $conditions
        ));
        return $table;
    }


    /**
     * Retourne tous les formulaires qui match avec les conditions et qui sont inactifs.
     * Les conditions sont facultatives et doivent correspondre à des conditions SQL
     *
     * @param array $conditions : Conditions de recherche
     *
     * @return array: Tableau des données des différents formulaires retournés
     */
    public function getUnActive(array $conditions = null)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $conditions[ 'active' ] = false;
        $table = $Formulaires->find('all', array(
            'conditions' => $conditions
        ));
        return $table;
    }


    /**
     * Insère un nouveau formulaire en base de donnée
     *
     * @param array $data : Données à insérer en base au format array('Formulaire'=>array('field'=>'value'))
     *
     * @return boolean: Retourne true si l'ajout a fonctionné, false sinon
     */
    public function add(array $data)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $Formulaires->begin();
        $Formulaires->create($data);
        if ( $Formulaires->save() ) {
            $Formulaires->commit();
            return true;
        }
        else {
            $Formulaires->rollback();
        }
    }


    /**
     * Supprime un formulaire de la base de donnée et tous les fields reliés
     *
     * @param $id : id du formulaire à supprimer
     *
     * @return bool
     */
    public function del($id)
    {
        $Formulaires = ClassRegistry::init('FormGenerator.Formulaire');
        $Formulaires->begin();
        if ( $Formulaires->delete($id) ) {
            $Formulaires->commit();
            return true;
        }
        else {
            $Formulaires->rollback();
            return false;
        }
    }

}

