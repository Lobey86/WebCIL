<?php
App::uses('AppHelper', 'View/Helper');

class AutorisationHelper extends AppHelper {
    public $helpers = array('Session');

    public function authorized($level, $table) {
        if($this->isSu()){
            return true;
        }
        if(is_array($level)){
          foreach ($level as $value) {
             foreach ($table as $valeur){
                if($valeur == $value){
                   return true;
               }
           }
       }
   }
   else{
      if(in_array($level, $table)){
         return true;
     }
 }

 return false;
}


public function isCil(){
    if($this->Session->read('Organisation.cil') != NULL){
       if($this->Session->read('Organisation.cil') == $this->Session->read('Auth.User.id')){
           return true;
       }
   }
   return false;
}


public function existCil(){
 if($this->Session->read('Organisation.cil') != NULL){
     return true;
 }
 return false;
}

public function isSu(){
    if($this->Session->read('Auth.User.id') == 1){
        return true;
    }
    return false;
}



}
