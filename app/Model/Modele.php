<?php
App::uses('AppModel', 'Model');

class Modele extends AppModel
{
    public $name = 'Modele';

    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
        'Formulaire' => array(
            'className' => 'Formulaire',
            'foreignKey' => 'formulaires_id'
        )
    );


    public function saveFile($data, $id = null)
    {

        if(isset($data['Modele']['modele']) && !empty($data['Modele']['modele'])) {
            $file = $data['Modele']['modele'];
            $success = true;
            if(!empty($file['name'])) {
                $this->begin();
                $folder = WWW_ROOT . 'files/modeles';
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if(!empty($file['tmp_name'])) {
                    $url = time();
                    $success = $success && move_uploaded_file($file['tmp_name'], $folder . '/' . $url . '.' . $extension);
                    if($success) {
                        $adel = $this->find('all', array('conditions' => array('formulaires_id' => $id)));
                        foreach($adel as $value) {
                            //unlink($folder . '/' . $value['Modele']['fichier']);
                        }
                        $this->deleteAll(array('formulaires_id' => $id));
                        $this->create(array(
                            'fichier' => $url . '.' . $extension,
                            'formulaires_id' => $id
                        ));
                        $success = $success && $this->save();
                    }
                } else {
                    $success = false;
                }
            }

            if($success) {
                $this->commit();
            } else {
                $this->rollback();
                return false;
            }
        }

        return true;
    }
}