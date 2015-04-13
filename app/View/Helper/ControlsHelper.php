<?php
/* /app/View/Helper/LienHelper.php */
App::uses('AppHelper', 'View/Helper');

class ControlsHelper extends AppHelper {
    public function inArray($array=array(), $valeur) {
        if(in_array($valeur, $array)){
            return true;
        }
        else{
            return false;
        }
    }
}