<?php

/**
 * DroitsComponent
 * Component de gestion des droits
 *
 * WebCIL : Outil de gestion du Correspondant Informatique et Libertés.
 * Cet outil consiste à accompagner le CIL dans sa gestion des déclarations via 
 * le registre. Le registre est sous la responsabilité du CIL qui doit en 
 * assurer la communication à toute personne qui en fait la demande (art. 48 du décret octobre 2005).
 * 
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webcil/
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     Component
 */
App::uses('EtatFiche', 'Model');

class DroitsComponent extends Component {

    public $components = array('Session');

    /**
     * Fonction de vérification des droits dans le tableau en Session
     * 
     * @param type $level
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function authorized($level) {
        $table = $this->Session->read('Droit.liste');
        
        if (is_array($level)) {
            foreach ($level as $value) {
                foreach ($table as $valeur) {
                    if ($valeur == $value) {
                        return true;
                    }
                }
            }
        } else {
            if (in_array($level, $table)) {
                return true;
            }
        }
        
        if ($this->isSu()) {
            return true;
        }
        
        return false;
    }

    /**
     * Verification du propriétaire de la fiche
     * 
     * @param int $idFiche
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isOwner($idFiche) {
        $Fiche = ClassRegistry::init('Fiche');
        
        $id_user_fiche = $Fiche->find('first', [
            'conditions' => ['id' => $idFiche],
            'fields' => ['user_id']
        ]);
        
        if (isset($id_user_fiche['Fiche']['user_id']) && $id_user_fiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id')) {
            return true;
        }
        
        return false;
    }

    /**
     * Vérification si l'user est CIL de son organisation
     * 
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isCil() {
        if ($this->Session->read('Organisation.cil') != null) {
            if ($this->Session->read('Organisation.cil') == $this->Session->read('Auth.User.id')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Verification si la fiche est visible par l'user
     * 
     * @param int $id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isReadable($id) {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');

        $infoFiche = $Fiche->find('first', [
            'conditions' => ['id' => $id],
            'fields' => [
                'id',
                'organisation_id',
                'user_id'
            ]
        ]);
        
        if ($this->isSu()) {
            return true;
        } elseif ($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id')) {
            return true;
        } elseif ($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $this->Session->read('Auth.User.id') == $this->Session->read('Organisation.cil')) {
            return true;
        } else {
            $validations = $EtatFiche->find('all', [
                'conditions' => [
                    'fiche_id' => $id,
                    'etat_id' => EtatFiche::ENCOURS_VALIDATION
                ]
            ]);
            
            $consultations = $EtatFiche->find('all', [
                'conditions' => [
                    'fiche_id' => $id,
                    'etat_id' => EtatFiche::DEMANDE_AVIS
                ]
            ]);
            
            foreach ($validations as $key => $value) {
                if ($value['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id') || $value['EtatFiche']['previous_user_id'] == $this->Session->read('Auth.User.id')) {
                    return true;
                }
            }
            
            foreach ($consultations as $key => $value) {
                if ($value['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id') || $value['EtatFiche']['previous_user_id'] == $this->Session->read('Auth.User.id')) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Verification si l'user a le droit de modifier une fiche
     * 
     * @param int $id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isEditable($id) {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');

        $infoFiche = $Fiche->find('first', [
            'conditions' => ['id' => $id]
        ]);
        
        $infoEtat = $EtatFiche->find('count', [
            'conditions' => [
                'fiche_id' => $id,
                'etat_id' => [
                    EtatFiche::VALIDER_CIL,
                    EtatFiche::ARCHIVER
                ]
            ]
        ]);
        
        $infoValidateur = $EtatFiche->find('first', [
            'conditions' => [
                'fiche_id' => $id,
                'etat_id' => EtatFiche::ENCOURS_VALIDATION
            ]
        ]);
        
        if (!empty($infoValidateur)) {
            if ($infoValidateur['EtatFiche']['user_id'] == $this->Session->read('Auth.User.id')) {
                return true;
            }
        }
        
        if ($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id') && $infoEtat < 1) {
            return true;
        }
        
        if ($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && ($this->Session->read('Auth.User.id') == $this->Session->read('Organisation.cil') || $this->isSu())) {
            return true;
        }
        
        return false;
    }

    /**
     * Verification si l'user peut supprimer une fiche
     * 
     * @param int $id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isDeletable($id) {
        $Fiche = ClassRegistry::init('Fiche');
        $EtatFiche = ClassRegistry::init('EtatFiche');
        
        $infoEtat = $EtatFiche->find('count', [
            'conditions' => [
                'fiche_id' => $id,
                'etat_id' => [
                    EtatFiche::VALIDER_CIL,
                    EtatFiche::ARCHIVER
                ]
            ]
        ]);
        
        $infoFiche = $Fiche->find('first', [
            'conditions' => ['id' => $id]
        ]);
        
        if ($infoFiche['Fiche']['organisation_id'] == $this->Session->read('Organisation.id') && $infoFiche['Fiche']['user_id'] == $this->Session->read('Auth.User.id') && $infoEtat < 1) {
            return true;
        }
        
        return false;
    }

    /**
     * Vérification du super utilisateur
     * 
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function isSu() {
        if ($this->Session->read('Su')) {
            return true;
        }
        
        return false;
    }

    /**
     * Vérification de l'organisation du rôle
     * 
     * @param int $id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function currentOrgaRole($id) {
        $Role = ClassRegistry::init('Role');
        $verification = $Role->find('first', [
            'conditions' => ['id' => $id],
            'fields' => 'organisation_id'
        ]);
        
        if ($verification['Role']['organisation_id'] == $this->Session->read('Organisation.id')) {
            return true;
        }
        
        return false;
    }

}
