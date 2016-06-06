<?php
/**
*
* PHP 5.3
*
* @package app.Test.Case.Model
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/
App::uses('FusionConvBuilder', 'FusionConv.Utility');
App::uses('File', 'Utility');
App::uses('phpOdtApi', 'ModelOdtValidator.Lib');
App::uses('AppModel', 'Model');


class VariableTest extends CakeTestCase {

    private $Gedooo;
    private $phpOdtApi;
    
    private $file;

    public function setUp() {
        parent::setUp();
        //gedFusion
        $this->phpOdtApi = new phpOdtApi;
        //$this->Tdt = new Tdt;
        $this->file=new File(PLUGIN_TESTS_MODELE_DIR.'File.odt', false);
        $this->fileDocumentTexte=new File(PLUGIN_TESTS_VARIABLES_DIR.'DocumentTexte.odt', false);
    }

    /**
     * Méthode exécutée avant chaque test.
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();
        unset($this->Gedooo);
        unset($this->phpOdtApi);
        //$this->file->close();
        unset($this->file);
    }

    /**
     * Test updateAll()
     * @return void
     */
//    public function testSimpleVariable(){
//        $datas['auteur']='beethoven';
//        
//        $return = $this->Gedooo->ged(new Model, $datas, $this->file->read());
//        
//         //Initialisation de la librairie
//        
//        $this->phpOdtApi->loadFromOdtBin($return);
//        $this->phpOdtApi->getUserFields('auteur');
//        $nbr=$this->phpOdtApi->countUserFields('auteur');
//        $result=$this->phpOdtApi->getUserField('auteur');
//        
//        $this->assertEquals( array(1,'beethoven'), array($nbr,$result), var_export( array($nbr,$result), true));
//    }
    
//    /**
//     * Test updateAll()
//     * @return void
//     */
//    public function testDateVariable(){
//        $datas['naissance']='2014-09-15 18:00:00';
//        
//        $return = $this->Gedooo->ged(new Model, $datas, $this->file->read());
//        
//         //Initialisation de la librairie
//        
//        $this->phpOdtApi->loadFromOdtBin($return);
//        $this->phpOdtApi->getUserFields('naissance');
//        $result=$this->phpOdtApi->getUserFieldValue('naissance');
//        debug($this->phpOdtApi->content);
//        
//        $this->assertEquals( 'jeudi 15 janvier 2014', $result, var_export( $result, true));
//    }
    
    /**
     * Test updateAll()
     * @return void
     */
//    public function testMoneyVariable(){
//        $datas['prix']='90';
//        
//        $return = $this->Gedooo->ged(new Model, $datas, $this->file->read());
//        
//         //Initialisation de la librairie
//        $this->phpOdtApi->loadFromOdtBin($return);
//        $this->phpOdtApi->getUserFields('prix');
//        $result=$this->phpOdtApi->getUserFieldValue('prix');
//
//        $this->assertEquals( '90,00 €', $result, var_export( $result, true));
//    }
    
    /**
     * Test updateAll()
     * @return void
     */
    public function testFileVariable(){
        
        $data = array(
                'texte_projet' => $this->fileDocumentTexte->read(),
                'file_texte' => 'lalalalala',
                'file_texte_multiple' => $this->fileDocumentTexte2->read()
                );
        $types = array(
            'texte_projet' => 'file',
            'file_texte' => 'lines',
            'file_texte_multiple' => 'file'
            );
        $correspondances = array(
                           'texte_projet' => 'texte_projet',
                           'file_texte' => 'file_texte',
                           'file_texte_multiple'=>'file_texte_multiple' 
            );
        
        
        
        $oTemplate = new GDO_ContentType("",
            "modele.odt",
            "application/vnd.oasis.opendocument.text",
            "binary",
            $this->file->read());

        // initialisation de la racine du document
        $oMainPart = new GDO_PartType();

        
        // initialisation des variables du model de données
        $datas = FusionConvBuilder::main( $oMainPart, $data, $types, $correspondances);
        
        //$model->beforeFusion($oMainPart, $model->modelTemplateOdtInfos, $this->_id, $this->_modelOptions);
        $oMainPart->addElement($datas);
        
        file_put_contents('/tmp/__modele.odt', $this->file->read());
        file_put_contents('/tmp/__fileDocumentTexte.odt', $this->fileDocumentTexte->read());
        file_put_contents('/tmp/__fileDocumentTexte2.odt', $this->fileDocumentTexte2->read());
//        $Fusion = new GDO_FusionType(new GDO_ContentType(   "", 
//                                                            "modele.odt", 
//                                                            "application/vnd.oasis.opendocument.text", 
//                                                            "binary", 
//                                                            $this->file->read()),
//                'application/vnd.oasis.opendocument.text', 
//                $datas
//        );
        
        // initialisation de la fusion
        $oFusion = new GDO_FusionType($oTemplate, "application/vnd.oasis.opendocument.text", $oMainPart);
        
        $oFusion->process();
        
        $odtFusionResult = $oFusion->getContent();
       
        file_put_contents('/tmp/__sortie.odt', $odtFusionResult->binary);
        exit;
        
        
         //Initialisation de la librairie
        
        $this->phpOdtApi->loadFromOdtBin($return);
        $this->phpOdtApi->getUserFields('file_texte');
        $result=$this->phpOdtApi->getUserFieldValue('file_texte');
        debug($this->phpOdtApi->content);
        
        
       // $this->assertEquals( 'jeudi 15 janvier 2014', $result, var_export( $result, true));
    }


}
