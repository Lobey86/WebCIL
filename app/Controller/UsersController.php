<?php
// app/Controller/UsersController.php
class UsersController extends AppController {

    public $uses = array('User','Organisation','Role', 'ListeDroit', 'OrganisationUser', 'Droit', 'RoleDroit', 'OrganisationUserRole');
    public $helpers = array('Controls');


/**
 * Récupère le beforefilter de AppController (login)
 */
public function beforeFilter() {
    parent::beforeFilter();
}


/**
 * Index des utilisateurs. Liste les utilisateurs enregistrés
 */
public function index() {
    if($this->Droits->authorized(array('8','9','10'))){
       $users = $this->OrganisationUser->find('all', array(
        'conditions' => array(
            'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
            ),
        'contain'  => array(
            'User' => array(
                'id',
                'nom',
                'prenom',
                'created'
                )
            )
        )
       );

       $this->set('users', $users);
   }
   else
   {
    $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
}
}


/**
 * Affiche les informations sur un utilisateur
 * @param  [integer] $id [id de l'utilisateur à afficher]
 */
public function view($id = null) {
    if($this->Droits->authorized(array('8','9','10'))){
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User invalide');
        }
        $this->set('user', $this->User->read(null, $id));
    }
    else
    {
        $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }
}


/**
 * Affiche le formulaire d'ajout d'utilisateur, ou enregistre l'utilisateur et ses droits
 */
public function add() {
    if($this->Droits->authorized(8)){
        $this->set('idUser', $this->Auth->user('id'));
        if ($this->request->is('post')) {
            $this->User->create($this->request->data);
            if($this->request->data['User']['password'] == $this->request->data['User']['passwd']){
                if ($this->User->save()) {
                    $userId = $this->User->getInsertID();
                    foreach ($this->request->data['Organisation']['Organisation_ida'] as $value) {
                        $this->OrganisationUser->create(array('user_id'=>$userId, 'organisation_id'=>$value));
                        $this->OrganisationUser->save();
                        $organisationUserId = $this->OrganisationUser->getInsertID();
                        foreach($this->request->data['Droits'][$value] as $key=>$donnee){
                            if($donnee){
                                $this->Droit->create(array('organisation_user_id'=>$organisationUserId, 'liste_droit_id'=>$key));
                                $this->Droit->save();
                            }
                        }
                        if(!empty($this->request->data['Role']['role_ida'])){
                            foreach($this->request->data['Role']['role_ida'] as $key=>$donnee){
                                if($donnee){
                                    if($this->Role->find('count', array('conditions'=>array('Role.organisation_id'=>$value, 'Role.id'=>$key)))>0){
                                        $this->OrganisationUserRole->create(array('organisation_user_id'=>$organisationUserId, 'role_id'=>$key));
                                        $this->OrganisationUserRole->save();
                                    }
                                }
                            }
                        }
                    }
                    $this->Session->setFlash('L\'user a été sauvegardé', 'flashsuccess');
                    $this->redirect(array('controller'=>'users', 'action'=>'index'));
                } 
                else {
                    $this->Session->setFlash('L\'user n\'a pas été sauvegardé. Merci de réessayer.', 'flasherror');
                    $this->redirect(array('controller'=>'users', 'action'=>'index'));
                }
            }
            else{
                $this->Session->setFlash('Les deux mots de passe ne correspondent pas.', 'flasherror');
                $this->redirect(array('controller'=>'users', 'action'=>'index'));
            } 
        }
        else{
            $tableau=array('Organisation'=>array());
            $organisations = $this->Organisation->find('all', array('conditions' => array('id' => $this->Session->read('Organisation.id'))));
            foreach ($organisations as $key => $value) {
                $tableau['Organisation'][$value['Organisation']['id']]['infos']=array('raisonsociale'=>$value['Organisation']['raisonsociale'], 'id'=>$value['Organisation']['id']);
                $roles = $this->Role->find('all',array('recursive'=>-1, 'conditions'=>array('organisation_id'=>$value['Organisation']['id'])));
                $tableau['Organisation'][$value['Organisation']['id']]['roles']=array();
                foreach($roles as $clef => $valeur){
                    $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]=array('infos'=>array('id'=>$valeur['Role']['id'], 'libelle'=>$valeur['Role']['libelle'], 'organisation_id'=>$valeur['Role']['organisation_id']));
                    $droitsRole=$this->RoleDroit->find('all', array('recursive'=>-1, 'conditions'=>array('role_id'=>$valeur['Role']['id'])));
                    foreach ($droitsRole as $k => $val) {
                        $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]['droits'][$val['RoleDroit']['id']]=$val['RoleDroit'];
                    }
                } 
            }
            $this->set('tableau', $tableau);
            $listedroits=$this->ListeDroit->find('all', array('recursive'=>-1));
            $ld=array();
            foreach ($listedroits as $c => $v) {
                $ld[$v['ListeDroit']['value']]=$v['ListeDroit']['libelle'];
            }
            $this->set('listedroits', $ld);
        }
    }
    else
    {
        $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }
}


/**
 * Modification d'un utilisateur
 * @param  [integer] $id [id de l'utilisateur à modifier]
 * TODO: ajouter la modification des droits
 */
public function edit($id = null) {
    if($this->Droits->authorized(9 ) || $id == $this->Auth->user('id')){
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User Invalide');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if($this->request->data['User']['new_password'] == $this->request->data['User']['new_passwd']){
                if ($this->request->data['User']['new_password'] != '') {
                    $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                }
                if ($this->User->save($this->request->data)) {
                    $this->OrganisationUser->deleteAll(array('user_id'=>$id), true, false);

                    foreach ($this->request->data['Organisation']['Organisation_ida'] as $value) {
                        $this->OrganisationUser->create(array('user_id'=>$id, 'organisation_id'=>$value));
                        $this->OrganisationUser->save();
                        $organisationUserId = $this->OrganisationUser->getInsertID();
                        foreach($this->request->data['Droits'][$value] as $key=>$donnee){
                            if($donnee){
                                $this->Droit->create(array('organisation_user_id'=>$organisationUserId, 'liste_droit_id'=>$key));
                                $this->Droit->save();
                            }
                        }

                        if(!empty($this->request->data['Role']['role_ida'])){
                            foreach($this->request->data['Role']['role_ida'] as $key=>$donnee){
                                if($this->Role->find('count', array('conditions'=>array('Role.organisation_id'=>$value, 'Role.id'=>$donnee)))>0){
                                    $this->OrganisationUserRole->create(array('organisation_user_id'=>$organisationUserId, 'role_id'=>$donnee));
                                    $this->OrganisationUserRole->save();
                                    
                                }
                            }
                        }
                        
                    }
                    $this->Session->setFlash('L\'user a été sauvegardé', "flashsuccess");
                    return $this->redirect(array('controller'=> 'users', 'action' => 'index'));
                } 
                else {
                    $this->Session->setFlash('L\'user n\'a pas été sauvegardé. Merci de réessayer.', "flasherror");
                    return $this->redirect(array('controller'=> 'users', 'action' => 'index'));
                }
            }
            else {
                $this->Session->setFlash('L\'user n\'a pas été sauvegardé. Merci de réessayer.', "flasherror");
                return $this->redirect(array('controller'=> 'users', 'action' => 'index'));
            } 
        }
        else {
            $this->set("userid", $id);

            $tableau=array('Organisation'=>array());
            $organisations = $this->Organisation->find('all', array('conditions' => array('id' => $this->Session->read('Organisation.id'))));
            foreach ($organisations as $key => $value) {
                $tableau['Organisation'][$value['Organisation']['id']]['infos']=array('raisonsociale'=>$value['Organisation']['raisonsociale'], 'id'=>$value['Organisation']['id']);
                $roles = $this->Role->find('all',array('recursive'=>-1, 'conditions'=>array('organisation_id'=>$value['Organisation']['id'])));
                $tableau['Organisation'][$value['Organisation']['id']]['roles']=array();
                foreach($roles as $clef => $valeur){
                    $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]=array('infos'=>array('id'=>$valeur['Role']['id'], 'libelle'=>$valeur['Role']['libelle'], 'organisation_id'=>$valeur['Role']['organisation_id']));
                    $droitsRole=$this->RoleDroit->find('all', array('recursive'=>-1, 'conditions'=>array('role_id'=>$valeur['Role']['id'])));
                    foreach ($droitsRole as $k => $val) {
                        $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]['droits'][$val['RoleDroit']['id']]=$val['RoleDroit'];
                    }
                } 
            }
            $organisationUser=$this->OrganisationUser->find('all', array(
                'conditions'=>array(
                    'user_id'=>$id
                    ), 
                'contain'=>array(
                    'Droit'
                    )
                )
            );

            foreach ($organisationUser as $key => $value) {
                $tableau['Orgas'][]=$value['OrganisationUser']['organisation_id'];
                
                $userroles = $this->OrganisationUserRole->find('all', array('conditions' => array('OrganisationUserRole.organisation_user_id'=>$value['OrganisationUser']['id'])));
                foreach($userroles as $cle => $val){
                    $tableau['UserRoles'][]=$val['OrganisationUserRole']['role_id'];
                }
                foreach ($value['Droit'] as $clef => $valeur) {
                    $tableau['User'][$value['OrganisationUser']['organisation_id']][]=$valeur['liste_droit_id'];
                }

            }
            $this->set('tableau', $tableau);
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
            $listedroits=$this->ListeDroit->find('all', array('recursive'=>-1));
            $ld=array();
            foreach ($listedroits as $c => $v) {
                $ld[$v['ListeDroit']['value']]=$v['ListeDroit']['libelle'];
            }
            $this->set('listedroits', $ld);
        }
    }
    else
    {
        $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }
}


/**
 * Suppression d'un utilisateur
 * @param  [integer] $id [id de l'utilisateur à supprimer]
 */
public function delete($id = null) {
    if($this->Droits->authorized(10)){
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User invalide');
        }
        if ($id!=1) {
            if ($this->User->delete()) {
                $this->Session->setFlash('User supprimé', 'flashsuccess');
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->Session->setFlash('L\'user n\'a pas été supprimé', 'flasherror');
        return $this->redirect(array('action' => 'index'));
    }
    else
    {
        $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }
}


/**
 * Page de login
 */
public function login() {
    if ($this->request->is('post')) {
        if ($this->Auth->login()) {
            $this->_cleanSession();
            $this->redirect(array('controller' => 'organisations', 'action' => 'change'));
        } else {
            $this->Session->setFlash('Nom d\'user ou mot de passe invalide, réessayer', 'flasherror');
        }
    }
}


/**
 * Page de deconnexion
 */
public function logout() {
    $this->_cleanSession();
    return $this->redirect($this->Auth->logout());
}


/**
 * Fonction de suppression du cache (sinon pose des problemes lors du login)
 */
protected function _cleanSession(){
    $this->Session->delete('Organisation');
}
}