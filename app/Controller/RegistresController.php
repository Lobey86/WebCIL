<?php
class RegistresController extends AppController {

    public function index(){
    	if($this->Droits->authorized(array('4','5','6'))){

    
    }
        else
        {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }

}
}
