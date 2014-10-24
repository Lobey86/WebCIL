<?php
class CakeflowAppController extends AppController
{

    public $components = array('RequestHandler');
    public $helpers = array('Html', 'Time');

    /**
     * Initialisation des champs 'user_created_id' et 'user_modified_id'
     * @param array $data tableau de données
     * @param string $modelClass nom du modèle des données contenues dans $data
     * @param string $action nom de l'action 'add' ou 'edit'
     */
    function setCreatedModifiedUser(&$data, $modelClass = '', $action = '')
    {
        // Initialisations
        if (empty($modelClass)) $modelClass = $this->modelClass;
        if (empty($action)) $action = $this->action;

        // lecture en session de l'id de l'utilisateur connecté
            $userId =$this->Auth->user('id');


        if ($action == 'add') $data[$modelClass]['created_user_id'] = $userId;
        $data[$modelClass]['modified_user_id'] = $userId;
    }

    /**
     * Mise en forme pour l'affichage des utilisateurs de création et de modification dans les vue détaillées
     * @param integer $userId id de l'utilisateur
     * @return string utilisateur formaté selon le fichier de config
     */
    function formatUser($userId)
    {
        // vérifie si l'acces à l'utilisateur en session est défini
        if (!CAKEFLOW_USER_IDSESSION)
            return '';
        else
            return $this->formatLinkedModel('User', $userId);
    }

    /**
     * Lit et formate l'occurence $modelId du modèle $modelName ('User', 'Trigger' ou 'Target') en fonction des paramètres du fichier de config
     * @param integer $modelName nom du model ('User', 'Trigger' ou 'Target')
     * @param integer $modelId id de l'utilisateur
     * @return string occurence du modele formatée
     */
    function formatLinkedModel($modelName, $modelId)
    {
        // initialisations
        $model = constant('CAKEFLOW_' . strtoupper($modelName) . '_MODEL');
        $fields = explode(',', constant('CAKEFLOW_' . strtoupper($modelName) . '_FIELDS'));
        $format = constant('CAKEFLOW_' . strtoupper($modelName) . '_FORMAT');
        if ((strtolower($modelName) == 'trigger') && ($modelId == 0))
            return 'Rédacteur du projet';
        elseif ((strtolower($modelName) == 'trigger') && ($modelId == -1))
            return '[i-Parapheur]';
        // lecture des informations en base
        $this->loadModel($model);
        $occ = $this->{$model}->find('first', array(
            'recursive' => -1,
            'fields' => $fields,
            'conditions' => array('id' => $modelId)));

        // mise en forme de la réponse
        if (empty($occ))
            return '';
        else {
            $occ = $occ[$model];
            return vsprintf($format, $occ);
        }
    }

    /**
     * Lit et formate l'occurence $modelId du modèle $modelName ('User', 'Trigger' ou 'Target') en fonction des paramètres du fichier de config
     * @param integer $modelName nom du model ('User', 'Trigger' ou 'Target')
     * @param array $modelIds tableau d'id utilisateur
     * @return string occurence du modele formatée
     */
    function formatLinkedModels($modelName, $modelIds)
    {
        // initialisations
        $model = constant('CAKEFLOW_' . strtoupper($modelName) . '_MODEL');
        $fields = explode(',', constant('CAKEFLOW_' . strtoupper($modelName) . '_FIELDS'));
        $format = constant('CAKEFLOW_' . strtoupper($modelName) . '_FORMAT');
        $this->loadModel($model);

        $results = array();
        foreach ($modelIds as $modelId){
            if ((strtolower($modelName) == 'trigger') && ($modelId == 0))
                $results[] = 'Rédacteur du projet';
            elseif ((strtolower($modelName) == 'trigger') && ($modelId == -1))
                $results[] = '[Parapheur]';
            $occ = $this->{$model}->find('first', array(
                'recursive' => -1,
                'fields' => $fields,
                'conditions' => array('id' => $modelId)));

            // mise en forme de la réponse
            if (empty($occ))
                $results[] = '';
            else {
                $occ = $occ[$model];
                $results[] = vsprintf($format, $occ);
            }
        }
        return $results;
    }

    /**
     * Retourne la liste des occurences en vu de l'utiliser dans un select
     * @param integer $modelName nom du model ('User', 'Trigger' ou 'Target')
     * @return array liste formatée
     */
    function listLinkedModel($modelName)
    {
        // initialisations
        $ret = array();
        $model = constant('CAKEFLOW_' . strtoupper($modelName) . '_MODEL');
        $modelConditions = constant('CAKEFLOW_' . strtoupper($modelName) . '_CONDITIONS');

        // initialisation de la condition
        $conditions = array();
        if ($modelConditions) {
            $criters = explode(';', $modelConditions);
            foreach ($criters as $criter) {
                $fieldValue = explode(',', $criter);
                $conditions[$fieldValue[0]] = $fieldValue[1];
            }
        }

        // lecture des informations en base
        $this->loadModel($model);
        $occs = $this->{$model}->find('all', array(
            'recursive' => -1,
            'fields' => 'id',
            'order' => 'nom',
            'conditions' => $conditions));

        // formatage des occurences
        $ret[0] = "Rédacteur du projet";
        foreach ($occs as $occ) {
            $ret[$occ[$model]['id']] = $this->formatLinkedModel($modelName, $occ[$model]['id']);
        }

        return $ret;
    }


}
