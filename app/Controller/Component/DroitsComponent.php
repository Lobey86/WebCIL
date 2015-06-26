<?php

/**
 ***Component de gestion des droits
 **/
class DroitsComponent extends Component
{
    public $components = array('Session');


// Fonction de vérification des droits dans le tableau en Session

    public function authorized($level)
    {
        $table = $this->Session->read('Droit.liste');
        if(is_array($level)) {
            foreach($level as $value) {
                foreach($table as $valeur) {
                    if($valeur == $value) {
                        return true;
                    }
                }
            }
        } else {
            if(in_array($level, $table)) {
                return true;
            }
        }
        if($this->isSu()) {
            return true;
        }
        return false;
    }


// Verification du propriétaire de la fiche

    public function isOwner($idFiche)
    {
        $Fiche = ClassRegistry::init('Fiche');
        $id_user_fiche = $Fiche->find('first', array(
            'conditions' => array('id' => $idFiche),
            'fields' => array('user_id')
        ));
        if(isset($id_user_fiche['Fiche']['user_id']) && $id_user_fiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id')) {
            return true;
        }
        return false;
    }


// Vérification si l'user est CIL de son organisation

    public function isCil()
    {
        if($this->Session->read('Organisation.cil') != NULL) {
            if($this->Session->read('Organisation.cil') == $this->Session->read('Auth.User.id')) {
                return true;
            }
        }
        return false;
    }


// Verification si la fiche est visible par l'user

    public function isReadable($id)
    {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');

        $infoFiche = $Fiche->find('first', array(
            'conditions' => array('id' => $id),
            'fields' => array(
                'id',
                'organisation_id',
                'user_id'
            )
        ));
        if($this->isSu()) {
            return true;
        } elseif($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id')) {
            return true;
        } elseif($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $this->Session->read('Auth.User.id') == $this->Session->read('Organisation.cil')) {
            return true;
        } else {
            $validations = $EtatFiche->find('all', array(
                'conditions' => array(
                    'fiche_id' => $id,
                    'etat_id' => 2
                )
            ));
            $consultations = $EtatFiche->find('all', array(
                'conditions' => array(
                    'fiche_id' => $id,
                    'etat_id' => 6
                )
            ));
            foreach($validations as $key => $value) {
                if($value['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id') || $value['EtatFiche']['previous_user_id'] == $this->Session->read('Auth.User.id')) {
                    return true;
                }
            }
            foreach($consultations as $key => $value) {
                if($value['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id') || $value['EtatFiche']['previous_user_id'] == $this->Session->read('Auth.User.id')) {
                    return true;
                }
            }
        }
        return false;
    }


// Verification si l'user a le droit de modifier une fiche

    public function isEditable($id)
    {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');

        $infoFiche = $Fiche->find('first', array('conditions' => array('id' => $id)));
        $infoEtat = $EtatFiche->find('count', array(
            'conditions' => array(
                'fiche_id' => $id,
                'etat_id' => array(
                    5,
                    7
                )
            )
        ));
        $infoValidateur = $EtatFiche->find('first', array(
            'conditions' => array(
                'fiche_id' => $id,
                'etat_id' => '2'
            )
        ));
        if(!empty($infoValidateur)) {
            if($infoValidateur['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id')) {
                return true;
            }
        }
        if($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id') && $infoEtat < 1) {
            return true;
        }
        if($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && ($this->Session->read('Auth.User.id') == $this->Session->read('Organisation.cil') || $this->isSu())) {
            return true;
        }
        return false;
    }


//Verification si l'user peut supprimer une fiche

    public function isDeletable($id)
    {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');
        $infoEtat = $EtatFiche->find('count', array(
            'conditions' => array(
                'id' => $id,
                'etat_id' => array(
                    5,
                    7
                )
            )
        ));
        $infoFiche = $Fiche->find('first', array('conditions' => array('id' => $id)));
        if($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id') && $infoEtat < 1) {
            return true;
        }
        return false;
    }


// Vérification du super utilisateur
    public function isSu()
    {

        if($this->Session->read('Su')) {
            return true;
        }
        return false;
    }


// Vérification de l'organisation du rôle
    public function currentOrgaRole($id)
    {
        $Role = ClassRegistry::init('Role');
        $verification = $Role->find('first', array(
            'conditions' => array('id' => $id),
            'fields' => 'organisation_id'
        ));
        if($verification['Role']['organisation_id'] == $this->Session->read('Organisation.id')) {
            return true;
        }
        return false;
    }
}