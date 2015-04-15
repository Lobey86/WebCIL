<?php
class RegistresController extends AppController {
	public $uses=array('EtatFiche', 'Fiche', 'OrganisationUser');

	public function index(){


		$condition= array(
			'EtatFiche.etat_id' => array(5,7),
			'Fiche.organisation_id' => $this->Session->read('Organisation.id')
			);
		$search = false;
		if(!empty($this->request->data['Registre']['user'])){
			$condition['Fiche.user_id'] = $this->request->data['Registre']['user'];
			$search = true;
		}
		if(!empty($this->request->data['Registre']['outil'])){
			$condition['Fiche.outilnom'] = $this->request->data['Registre']['outil'];
			$search = true;
		}
		if(isset($this->request->data['Registre']['archive']) && $this->request->data['Registre']['archive'] == 1){
			$condition['EtatFiche.etat_id'] = 7;
			$search = true;
		}
		if(isset($this->request->data['Registre']['nonArchive']) && $this->request->data['Registre']['nonArchive'] == 1){
			$condition['EtatFiche.etat_id'] = 5;
			$search = true;
		}

		if($this->Droits->authorized(array('4','5','6'))){
			$fichesValid = $this->EtatFiche->find('all', array(
				'conditions' => $condition,
				'contain' => array(
					'Fiche' => array(
						'id',
						'outilnom',
						'created',
						'User' => array(
							'nom',
							'prenom'
							)
						)
					)
				)
			);
			foreach ($fichesValid as $key => $value) {
				if($this->Droits->isReadable($value['Fiche']['id'])){
					$fichesValid[$key]['Readable']=true;
				}
				else{
					$fichesValid[$key]['Readable']=false;
				}
			}

			$this->set('search', $search);
			$this->set('fichesValid', $fichesValid);


			// Listing des utilisateurs de l'organisation
			$liste=$this->OrganisationUser->find('all', array(
				'conditions' => array(
					'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
					),
				'contain' => array(
					'User' => array(
						'id',
						'nom',
						'prenom'
						)
					)
				)
			);
			$listeUsers = array();
			foreach ($liste as $key => $value) {
				$listeUsers[$value['User']['id']] = $value['User']['prenom'].' '.$value['User']['nom'];
			}

			$this->set('listeUsers', $listeUsers);


		}
		else
		{
			$this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
			$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
		}

	}
}