<?php

/**
 * OdtFusion behavior class.
 *
 * Centralise les fonctions de fusion des modèles odt avec les données des modèles
 *
 * Callbacks :
 *  - getModelTemplateId($this->_id, $this->_modelOptions) : le modèle doit posséder cette méthode qui retourne l'id du modeltemplate à utiliser
 *  - beforeFusion($this->_id, $this->_modelOptions) : le modèle doit posséder cette méthode pour l'initialisation des variables gedooo avant de faire la fusion
 *
 * Variables du modèle appellant initialisées dynamiquement
 *  - odtFusionResult : le résultat de la fusion est stocké dans la variable odtFusionResult du modèle appelent
 *  - modelTemplateOdtInfos : instance de la librairie ModelOdtValidator.Lib.phpOdtApi de manipulation des odt
 *
 */

//App::import( 'Behavior', 'FusionConv.' );
App::uses('CakeTime', 'Utility');

class OdtFusionBehavior extends ModelBehavior {

    // id de l'occurence en base de données à fusionner
    protected $_id = null;

    // variables du modelTemplate utilisé pour la fusion
    protected $_modelTemplateId = null;
    protected $_modelTemplateName = '';
    protected $_modelTemplateContent = '';

    // variable pour la détermination du nom du fichier de fusion
    protected $_fileNameSuffixe = '';

    // options gérées par la classe appelante (Model) qui seront passées aux fonctions de callback
    protected $_modelOptions = array();

    /**
     * Initialisation du comportement : détection et chargement du template
     * Génère une exception en cas d'erreur
     *
     * @param Model $model
     * @param array $options liste des options formatée comme suit :
     *  'id' => id de l'occurence du modèle sujet à la fusion
     *  'fileNameSuffixe' : suffixe du nom de la fusion (défaut : $id)
     *  'modelTemplateId' : id du template à utiliser
     *  'modelOptions' : options gérées par la classe appelante
     * @throws Exception
     * @return void
     */
    public function setup(Model $model, $options = array()) {
        // initialisations des options
        $this->_setupOptions($options);

        // chargement du modèle template
        if (empty($this->_modelTemplateId))
            $this->_modelTemplateId = $model->getModelTemplateId($this->_id, $this->_modelOptions);
        if (empty($this->_modelTemplateId))
            throw new Exception('identifiant du modèle d\'édition non trouvé pour id:' . $this->_id . ' du model de données ' . $model->alias);
        $myModeltemplate = ClassRegistry::init('ModelOdtValidator.Modeltemplate');
        $modelTemplate = $myModeltemplate->find('first', array(
            'recursive' => -1,
            'fields' => array('name', 'content'),
            'conditions' => array('id' => $this->_modelTemplateId)));
        if (empty($modelTemplate))
            throw new Exception('modèle d\'édition non trouvé en base de données id:' . $this->_id);
        $this->_modelTemplateName = $modelTemplate['Modeltemplate']['name'];
        $this->_modelTemplateContent = $modelTemplate['Modeltemplate']['content'];

        // résultat de la fusion
        $model->odtFusionResult = null;

        // instance de manipulation du fichier odt du modèle template
        App::uses('phpOdtApi', 'ModelOdtValidator.Lib');
        $model->modelTemplateOdtInfos = new phpOdtApi();
        $model->modelTemplateOdtInfos->loadFromOdtBin($this->_modelTemplateContent);
    }

    /**
     * Retour la Fusion dans le format demandé
     * @param string $mimeType
     * @return string
     */
    function getOdtFusionResult(Model &$model, $mimeType = 'pdf') {
        App::uses('ConversionComponent', 'Controller/Component');
        App::uses('Component', 'Controller');
        // initialisations
        $collection = new ComponentCollection();
        $this->Conversion = new ConversionComponent($collection);
        try {
            $content = $this->Conversion->convertirFlux($model->odtFusionResult->content->binary, 'odt', $mimeType);
        } catch (ErrorException $e) {
            $this->log('Erreur lors de la conversion : ' . $e->getCode(), 'error');
        }

        return $content;
    }

    /**
     * Suppression en mémoire du retour de la fusion
     */
    function deleteOdtFusionResult(Model &$model) {
        unset($model->odtFusionResult->content->binary);
    }

    /**
     * initialisation des variables du behavior
     * @param array $options liste des options formatée comme suit :
     *  'id' => id de l'occurence du modèle sujet à la fusion
     *  'fileNameSuffixe' : suffixe du nom de la fusion (défaut : $id)
     *  'modelTemplateId' : id du template à utiliser
     *  'modelOptions' : options gérées par la classe appelante
     * @return void
     */
    public function _setupOptions($options) {
        // initialisations
        $defaultOptions = array(
            'id' => $this->_id,
            'fileNameSuffixe' => $this->_fileNameSuffixe,
            'modelTemplateId' => $this->_modelTemplateId,
            'modelOptions' => $this->_modelOptions
        );

        if (!empty($options['modelOptions']) && !empty($this->_modelOptions))
            $options['modelOptions'] = array_merge($this->_modelOptions, $options['modelOptions']);
        $options = array_merge($defaultOptions, $options);

        // affectation des variables de la classe
        $this->_id = $options['id'];
        $this->_fileNameSuffixe = empty($options['fileNameSuffixe']) ? $options['id'] : $options['fileNameSuffixe'];
        $this->_modelTemplateId = $options['modelTemplateId'];
        $this->_modelOptions = $options['modelOptions'];
    }

    /**
     * Retourne un nom pour la fusion qui est constitué du nom (liellé) du modèle odt échapé, suivi de '_'.$suffix.
     * Génère une exception en cas d'erreur
     * @param Model $model modele du comportement
     * @param array $options tableau des parmètres optionnels :
     *    'id' : identifiant de l'occurence en base de données (défaut : $this->_id)
     *    'fileNameSuffixe' : suffixe du nom de la fusion (défaut : $id)
     *  'modelOptions' : options gérées par la classe appelante
     * @return string
     * @throws Exception en cas d'erreur
     */
    public function fusionName(Model &$model, $options = array()) {
        // initialisations
        $this->_setupOptions($options);
        if (empty($this->_modelTemplateId))
            throw new Exception('détermination du nom de la fusion -> modèle d\'édition indéterminé');

        // contitution du nom
        $fusionName = str_replace(array(' ', 'é', 'è', 'ê', 'ë', 'à'), array('_', 'e', 'e', 'e', 'e', 'a'), $this->_modelTemplateName);
        return preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fusionName) . (empty($this->_fileNameSuffixe) ? '' : '_') . $this->_fileNameSuffixe;
    }

    /**
     * Fonction de fusion du modèle odt et des données.
     * Le résultat de la fusion est un odt dont le contenu est stocké dans la variable du model odtFusionResult
     * @param Model $model modele du comportement
     * @param array $options tableau des parmètres optionnels :
     *      'id' : identifiant de l'occurence en base de données (fusionNamedéfaut : $this->_id)
     *      'modelOptions' : options gérées par la classe appelante
     * @return void
     * @throws Exception en cas d'erreur
     */
    public function odtFusion(Model &$model, $options = array()) {
        // initialisations
        $this->_setupOptions($options);
        if (empty($this->_modelTemplateId))
            throw new Exception('détermination du nom de la fusion -> modèle d\'édition indéterminé');

         // initialisation des datas
        $aData = array();
        
        // initialisation des variables communes
        $this->_setVariablesCommunesFusion($model, $aData);

        // initialisation des variables du model de données
        $model->beforeFusion($aData, $model->modelTemplateOdtInfos, $this->_id, $this->_modelOptions);

        App::uses( 'FusionConvBuilder', 'FusionConv.Utility' );
        $MainPart = new GDO_PartType();
        $correspondances=$types=$data=array();
        $this->_format($MainPart, $aData, $data, $types);
        unset($aData);
        
        $Document = FusionConvBuilder::main( $MainPart, $data, $types );

        $sMimeType = 'application/vnd.oasis.opendocument.text';

        $Template = new GDO_ContentType( "", $this->_modelTemplateName, "application/vnd.oasis.opendocument.text", "binary", $this->_modelTemplateContent);
        $Fusion = new GDO_FusionType($Template, $sMimeType, $Document);
        
        $Fusion->process();
        
        $model->odtFusionResult = $Fusion->getContent();
        
        if(is_array($model->odtFusionResult))
            throw new Exception($model->odtFusionResult['Message']);

        // libération explicite de la mémoire
        unset($aData);
    }

    /**
     * fonction de fusion des variables communes : collectivité et dates
     * génère une exception en cas d'erreur
     * @param GDO_PartType $oMainPart variable Gedooo de type maintPart du document à fusionner
     * @param Model $model modele du comportement
     */
    private function _setVariablesCommunesFusion(Model &$model, &$aData) {
        // variables des dates du jour
        if ($model->modelTemplateOdtInfos->hasUserFieldDeclared('date_jour_courant')) {
            $//myDate = new DateComponent;
            $aData['date_jour_courant']= CakeTime::i18nFormat(date('Y-m-d H:i:s'), '%A %d %B %G à %k:%M');
        }
        if ($model->modelTemplateOdtInfos->hasUserFieldDeclared('date_du_jour'))
            $aData['date_du_jour'] = date("d/m/Y", strtotime("now"));

        // variables de la collectivité
        $myCollectivite = ClassRegistry::init('Collectivite');
        $myCollectivite->setVariablesFusion($aData, $model->modelTemplateOdtInfos, 1);
    }
    
    private function _format(&$MainPart, &$aData, &$data, &$types){
        
        foreach($aData as $key=>$value)
        {
            if(ctype_digit($key))
            {
                $this->_format($data, $dataIteration, $typesIteration);
                $MainPart = new GDO_PartType();
                $result = FusionConvBuilder::iteration( $MainPart, 'IterationName', $dataIteration, $typesIteration, null );
            }
            $data[$key]=$value['value'];
            $types[$key]=$value['type'];
            
        }
    }

}
