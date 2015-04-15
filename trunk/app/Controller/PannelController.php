<?php

/**************************************************
************** Controller du pannel ***************
**************************************************/


class PannelController extends AppController {
    public $uses=array('Pannel', 'Fiche', 'Users', 'OrganisationUser', 'Droit', 'EtatFiche', 'Commentaire', 'Notification');


/**
*** Accueil de la page, listing des fiches et de leurs catégories
**/

public function index(){

// Requète récupérant les fiches en cours de rédaction

    $db = $this->EtatFiche->getDataSource();
    $subQuery = $db->buildStatement(
        array(
            'fields'     => array('"EtatFiche2"."fiche_id"'),
            'table'      => $db->fullTableName($this->EtatFiche),
            'alias'      => 'EtatFiche2',
            'limit'      => null,
            'offset'     => null,
            'joins'      => array(),
            'conditions' => array('EtatFiche2.etat_id BETWEEN 2 AND 5'),
            'order'      => null,
            'group'      => null
            ),
        $this->EtatFiche
        );
    $subQuery = '"Fiche"."user_id" = '.$this->Auth->user('id').' AND "Fiche"."organisation_id" = '.$this->Session->read('Organisation.id').' AND "EtatFiche"."fiche_id" NOT IN (' . $subQuery . ') ';
    $subQueryExpression = $db->expression($subQuery);

    $conditions[] = $subQueryExpression;
    $conditions[] = 'EtatFiche.etat_id = 1';
    $encours = $this->EtatFiche->find('all', array(
        'conditions'=>$conditions,
        'contain'=>array(
            'Fiche'=>array(
                'fields'=>array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User'=>array(
                    'fields'=>array(
                        'id','nom','prenom')
                    )
                ), 
            'User'=> array(
                'fields'=>array(
                    'id','nom','prenom')
                )
            )

        )
    );
    $this->set('encours', $encours);


// Requète récupérant les fiches en cours de validation

    $requete = $this->EtatFiche->find('all', array(
        'conditions' => array(
            'EtatFiche.etat_id' => 2, 
            'Fiche.user_id' => $this->Auth->user('id'), 
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain' => array(
            'Fiche' => array(
                'fields' => array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User' => array(
                    'fields' => array(
                        'id','nom','prenom'
                        )
                    )
                ), 
            'User' =>  array(
                'fields' => array(
                    'id','nom','prenom')
                )
            )
        )

    );
    $this->set('encoursValidation', $requete);



// Requète récupérant les fiches validées par le CIL

    $requete = $this->EtatFiche->find('all', array(
        'conditions' => array(
            'EtatFiche.etat_id' => 5, 
            'Fiche.user_id' => $this->Auth->user('id'), 
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain' => array(
            'Fiche' => array(
                'fields' => array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User' => array(
                    'fields' => array(
                        'id','nom','prenom')
                    )
                ), 
            'User' => array(
                'fields' => array(
                    'id','nom','prenom')
                )
            )
        )

    );
    $this->set('validees', $requete);



// Requète récupérant les fiches refusées par un validateur

    $requete = $this->EtatFiche->find('all', array(
        'conditions' => array(
            'EtatFiche.etat_id' => 4, 
            'Fiche.user_id' => $this->Auth->user('id'), 
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain' => array(
            'Fiche' => array(
                'fields' => array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User' => array(
                    'fields' => array(
                        'id','nom','prenom')
                    )
                ), 
            'User' => array(
                'fields' => array(
                    'id','nom','prenom')
                )
            )
        )
    );
    $this->set('refusees', $requete);



// Requète récupérant les fiches qui demande une validation

    $requete = $this->EtatFiche->find('all', array(
        'conditions' => array(
            'EtatFiche.etat_id' => 2, 
            'EtatFiche.user_id' => $this->Auth->user('id'), 
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain' => array(
            'Fiche' => array(
                'fields' => array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User' => array(
                    'fields' => array(
                        'id','nom','prenom')
                    )
                ), 
            'User' => array(
                'fields' => array(
                    'id','nom','prenom')
                ),
            'PreviousUser' => array(
                'fields' => array(
                    'id', 'nom', 'prenom'

                    )
                )
            )
        )
    );
    $this->set('dmdValid', $requete);


    // Requète récupérant les fiches qui demande un avis

    $requete = $this->EtatFiche->find('all', array(
        'conditions' => array(
            'EtatFiche.etat_id' => 6, 
            'EtatFiche.user_id' => $this->Auth->user('id'), 
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain' => array(
            'Fiche' => array(
                'fields' => array(
                    'id', 'outilnom', 'created', 'modified'), 
                'User' => array(
                    'fields' => array(
                        'id','nom','prenom')
                    )
                ), 
            'User' => array(
                'fields' => array(
                    'id','nom','prenom')
                ),
            'PreviousUser' => array(
                'fields' => array(
                    'id', 'nom', 'prenom'

                    )
                )
            )
        )
    );
    $this->set('dmdAvis', $requete);


// Requète récupérant les utilisateurs ayant le droit de consultation

    $queryConsultants = array(
        'fields' => array(
            'User.id',
            'User.nom',
            'User.prenom'
            ),
        'joins' => array(
            $this->Droit->join( 'OrganisationUser', array( 'type' => "INNER" ) ),
            $this->Droit->OrganisationUser->join( 'User', array( 'type' => "INNER" ) )
            ),
        'recursive' => -1,
        'conditions'=> array(
            'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
            'User.id != '.$this->Auth->user('id'),
            'Droit.liste_droit_id' => 3
            ),
        );
    $consultants = $this->Droit->find( 'all', $queryConsultants);
    $consultants = Hash::combine( $consultants, '{n}.User.id', array( '%s %s', '{n}.User.nom', '{n}.User.prenom') );
    $this->set( compact( 'consultants' ) );


// Requète récupérant les utilisateurs ayant le droit de validation
    $cil = $this->Session->read('Organisation.cil');
    $queryValidants = array(
        'fields' => array(
            'User.id',
            'User.nom',
            'User.prenom'
            ),
        'joins' => array(
            $this->Droit->join( 'OrganisationUser', array( 'type' => "INNER" ) ),
            $this->Droit->OrganisationUser->join( 'User', array( 'type' => "INNER" ) )
            ),
        'conditions' => array(
            'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
            'NOT' => array(
                'User.id' => array(
                    $this->Auth->user('id'),
                    $cil
                    )
                ),
            'Droit.liste_droit_id' => 2
            )
        );
    $validants = $this->Droit->find( 'all', $queryValidants );
    $validants = Hash::combine( $validants, '{n}.User.id', array( '%s %s', '{n}.User.nom', '{n}.User.prenom') );
    $this->set( compact( 'validants' ) );
}

// Fonction appelée pour le composant parcours, permettant d'afficher le parcours parcouru par une fiche et les commentaires liés (uniquement ceux visibles par l'utilisateur)

public function parcours($id){
   $parcours = $this->EtatFiche->find('all', array(
    'conditions' => array(
        'EtatFiche.fiche_id' => $id
        ),
    'contain' => array(
        'Fiche' => array(
            'id',
            'organisation_id',
            'user_id',
            'created',
            'modified',
            'User' => array(
                'id',
                'nom',
                'prenom'
                )
            ),
        'User' => array(
            'id',
            'nom',
            'prenom'
            ),
        'Commentaire' => array(
            'conditions' => array(
                'OR' => array(
                    'Commentaire.user_id' => $this->Auth->user('id'),
                    'Commentaire.destinataire_id' => $this->Auth->user('id')
                    )
                ),
            'User' => array(
                'id',
                'nom',
                'prenom'
                )
            )
        )
    )
   );
   return $parcours;
}


// Fonction de suppression des notifications

public function dropNotif(){
    $this->Notification->deleteAll(array('Notification.user_id' => $this->Auth->user('id'), false));
    $this->redirect(array('controller' => 'pannel', 'action' => 'index'));
}

}