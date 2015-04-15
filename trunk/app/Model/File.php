<?php
App::uses('AppModel', 'Model');

class File extends AppModel {
	public $name = 'File';



    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo =
    array(
    	'Fiche'	=> array(
    		'className'			=> 'Fiche',
    		'foreignKey'		=> 'fiche_id'	
    		)
    	);

    public function saveFile($data, $id = null){
    	if (isset($data['Fiche']['fichiers']) && !empty($data['Fiche']['fichiers'] && $data['Fiche']['fichiers']['0']['error']==0)) {
    		foreach ($data['Fiche']['fichiers'] as $key => $file) {
    			$success = true;
    			$this->begin();
    			$folder=WWW_ROOT.'files';
    			$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    			$name = $file['name'];
    			if (!empty($file['tmp_name'])) {
    				$url=time();
    				$success = $success && move_uploaded_file($file['tmp_name'],
    					$folder.'/'.$url.$key.'.'.$extension);
    				if ( $success ) {
    					$this->create(array('nom'=>$name, 'url'=>$url.$key.'.'.$extension, 'fiche_id'=>$id));
    					$success = $success && $this->save();
    				}
    			}
    			else{
    				$success = false;
    			}
    			if( $success ) {
    				$this->commit();
    			}
    			else {
    				$this->rollback();
    				return false;
    			}
    		}
    	}
    	return true;
    }
}