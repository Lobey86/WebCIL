<?php

/**
 * Code source de la classe BanettesHelper.
 */
App::uses('FormHelper', 'View/Helper');
App::uses('EtatFiche', 'Model');
App::uses('DefaultUrl', 'Default.Utility');
App::uses('DefaultUtility', 'Default.Utility');

/**
 * La classe BanettesHelper ...
 */
class BanettesHelper extends AppHelper {

    public $helpers = ['Html', 'Time', 'Session', 'WebcilForm', 'Form', 'Autorisation'];
    
    /**
     * 
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function thead() {
        $tr = $this->Html->tableHeaders(
            [
                [__d('pannel', 'pannel.motEtat') => ['class' => 'col-md-1']],
                [__d('pannel', 'pannel.motNomTraitement') => ['class' => 'col-md-3']],
                [__d('pannel', 'pannel.motCreee') => ['class' => 'col-md-3']],
                [__d('pannel', 'pannel.motDerniereModification') => ['class' => 'col-md-3']],
                [__d('pannel', 'pannel.motActions') => ['class' => 'col-md-2']],
            ]
        );
        return $this->Html->tag('thead', $tr);
    }

    /**
     * Retourne le contenu de la première cellule, en fonction de l'état du
     * traitement, de l'utilisateur connecté et de l'utilisateur lié à l'état du
     * traitement.
     * 
     * @param array $result
     * @return string
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function etatIcone(array $result) {
        $me = $this->Session->read('Auth.User.id');
        $etat = Hash::get($result, 'EtatFiche.etat_id');
        $user_id = Hash::get($result, 'EtatFiche.user_id');

        switch($etat) {
            case EtatFiche::DEMANDE_AVIS:
                $class = 'fa-eye';
                $text = __d('pannel', 'pannel.textEtatDemandeAvis');
                break;
            case EtatFiche::ENCOURS_REDACTION:
                $class = 'fa-pencil-square-o';
                $text = __d('pannel', 'pannel.textEtatEnCoursRedaction');
                break;
            case EtatFiche::ENCOURS_VALIDATION:
                if($me == $user_id) {
                    $class = 'fa-check-square-o';
                    $text = __d('pannel', 'pannel.textEtatEnCoursValidation');                   
                } else {
                    $class = 'fa-clock-o';
                    $text = __d('pannel', 'pannel.textEtatEnAttente');  
                }
                break;
            case EtatFiche::REFUSER:
                $class = 'fa-times fa-danger';
                $text = '<span class="fa-danger">'.__d('pannel', 'pannel.textEtatRefuser').'</span>';
                break;
            case EtatFiche::VALIDER_CIL:
                $class = 'fa-check fa-success';
                $text = '<span class="fa-success">'.__d('pannel', 'pannel.textEtatValiderCIL').'</span>';
                break;
            case EtatFiche::ARCHIVER:
                $class = 'fa-lock fa-success';
                $text = '<span class="fa-success">'.__d('pannel', 'pannel.textEtatArchiver').'</span>';
                break;
            case EtatFiche::REPLACER_REDACTION:
                $class = 'fa-pencil-square-o';
                $text = __d('pannel', 'pannel.textEtatReplacerRedaction');
                break;
            default:
                $class = null;
                $text = '';
        }

        return $this->Html->tag(
            'div',
            $this->Html->tag('i', '', ['class' => "fa {$class} fa-3x"])
                .'<br/>'
                .$text,
            ['class' => 'etatIcone']
        );
    }
    
    // -------------------------------------------------------------------------
    
    protected function _url($url) {
        if (false === is_array($url)) {
            $params = Router::parse($url);
        } else {
            $params = $url;
        }

        $params['url'] = true === isset($params['url']) ? $params['url'] : array();
        $params['controller'] = Inflector::underscore($params['controller']);

        return Router::normalize(Router::reverse($params));
    }
    
    protected function _urlToArray($url) {
        if (false === is_array($url)) {
            $params = Router::parse($url);
        } else {
            $params = $url;
        }

        $params['url'] = true === isset($params['url']) ? $params['url'] : array();
        $params['controller'] = Inflector::underscore($params['controller']);

        return $params;
    }
    
    /**
     * Retourne la chaîne de caractères $string dont les occurences de
     * #Model.champ# ont été remplacées par leur valeur extraite depuis $data.
     *
     * @param array $data
     * @param string $string
     * @return string
     */
    public static function evaluateString(array $data, $string) {
        if (strpos($string, '#') !== false) {
            $pattern = '/("#[^#]+#"|\'#[^#]#\'|#[^#]+#)/';
            if (preg_match_all($pattern, $string, $out)) {
                $tokens = $out[0];
                foreach (array_unique($tokens) as $token) {
                    // Pour échapper efficacement les guillemets simples et doubles
                    if ($token[0] === '"') {
                        $escape = '"';
                        $token = trim($token, '"');
                    } else if ($token[0] === "'") {
                        $escape = "'";
                        $token = trim($token, "'");
                    } else {
                        $escape = false;
                    }

                    $token = trim($token, '#');
                    $value = Hash::get($data, $token);

                    if (false !== $escape) {
                        $value = str_replace($escape, "\\{$escape}", $value);
                    }

                    $string = str_replace("#{$token}#", $value, $string);
                }
            }
            $string = preg_replace('/^\/\//', '/', $string);
        }

        return $string;
    }

    /**
     * Retourne le paramètre $mixed dont les occurences de #Model.champ# ont
     * été remplacées par leur valeur extraite depuis $data.
     *
     * @see Hash::get()
     *
     * @param array $data
     * @param string|array $mixed
     * @return string|array
     */
    public static function evaluate(array $data, $mixed) {
        if (is_array($mixed)) {
            $array = array();
            if (!empty($mixed)) {
                foreach ($mixed as $key => $value) {
                    $array[self::evaluateString($data, $key)] = self::evaluate($data, $value);
                }
            }
            return $array;
        }

        return self::evaluateString($data, $mixed);
    }

    //--------------------------------------------------------------------------
    
    protected $_linkClasses = [
        '/fiches/show/#Fiche.id#' => 'fa-eye',
        '/fiches/edit/#Fiche.id#' => 'fa-pencil',
        '/EtatFiches/relaunch/#Fiche.id#' => 'fa-reply',
        '/fiches/genereTraitement/#Fiche.id#' => 'fa-cog',
        '/fiches/downloadFileTraitement/#Fiche.id#' => 'fa-download',
        '/fiches/historique/#Fiche.id#' => 'fa-history',//action virtuelle
        '/fiches/reponse/#Fiche.id#' => 'fa-reply',//action virtuelle
        '#refuser' => 'fa-times',//action virtuelle
        '/fiches/reorienter/#Fiche.id#' => 'fa-exchange',//action virtuelle
        '/fiches/envoyer/#Fiche.id#' => 'fa-paper-plane',//action virtuelle
        '/fiches/delete/#Fiche.id#' => 'fa-trash'
    ];
    
    protected function _button($url, array $result, array $params = []) {
        $params += [
            'confirm' => null,
            'onclick' => null
        ];
       
        $icone = true === isset($this->_linkClasses[$url])
            ? $this->_linkClasses[$url]
            : null;
        
        $parts = $this->_urlToArray($url);
       
        if (true === in_array($icone, ['fa-times', 'fa-trash'])) {
            $span = '<span class="fa '.$icone.' fa-lg fa-danger"></span>';
            $class = 'btn-default-danger';
        } elseif ((true === in_array($icone, ['fa-paper-plane']))) {
            $span = '<span class="fa '.$icone.' fa-lg fa-success"></span>';
            $span .= '<span class="caret"></span>';
            $class = 'btn-default-success';
        } else {
            $span = '<span class="fa '.$icone.' fa-lg"></span>';
            $class = 'btn-default-default';
        }

        $link = $this->Html->link(
            $span,
            static::evaluate($result, $url),
            [
                //'class' => 'btn btn-default-default bouton'.Inflector::camelize($parts['action']).' btn-sm my-tooltip',
                'class' => 'btn '. $class .' bouton'.Inflector::camelize($parts['action']).' btn-sm my-tooltip',
                'escapeTitle' => false,
                'title' => __d('pannel', 'pannel.commentaire'.Inflector::camelize($parts['action']).'Traitement'),
                'id' => static::evaluate($result, 'btn'.Inflector::camelize($parts['action']).'#Fiche.id#'),
                'onclick' => self::evaluateString($result, $params['onclick'])
            ],
            $params['confirm']
        );

        return $link;
    }
    
    protected function _buttonReorienter(array $result) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        $etatFiche_id = Hash::get($result, 'EtatFiche.id');
        
        return $this->Html->link('<span class="fa fa-exchange fa-lg"></span>', ['#' => '#'], [
            'data-id' => $fiche_id,
            'data-fiche' => $etatFiche_id,
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modalReorienter',
            'class' => 'btn btn-default-default btn_ReorienterTraitement  btn-sm my-tooltip',
            'title' => __d('pannel', 'pannel.commentaireReorienterTraitement')
        ]);
    }
    
    protected function _menuEnvoyerTraitement($banette, array $result) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        $etatFiche_id = Hash::get($result, 'EtatFiche.id');
        $menu= '';
        
        $fiche_typedeclaration = 'false';
        
        foreach ($result['Valeur'] as $valeur) {
            if ($valeur['champ_name'] === 'typedeclaration' && !empty($valeur['champ_name'])) {
                $fiche_typedeclaration = 'true';
            }
        }
        
        // Envoyer pour consultation
        $menu .= $this->Html->tag(
            'li',
            $this->Html->link(__d('pannel', 'pannel.textEnvoyerConsultation'), ['#' => '#'], [
                'role' => 'menuitem',
                'tabindex' => -1,
                'escape' => false,
                'data-toggle' => 'modal',
                'data-target' => '#modalEnvoieConsultation',
                'data-id' => $fiche_id,
                'data-fiche' => $etatFiche_id,
                'class' => 'btn_envoyerConsultation'
            ]),
            ['role' => 'presentation']
        );
        
        if ($banette == 'encours_redaction') {
            $titreSendValidation = __d('pannel', 'pannel.textEnvoyerValidation'); 
        } else {
            $titreSendValidation = __d('pannel', 'pannel.textValiderEnvoyerValidation');
        }
        
        
        // Envoyer pour validation
        $menu .= $this->Html->tag(
            'li',
            $this->Html->link($titreSendValidation, ['#' => '#'], [
                'role' => 'menuitem',
                'tabindex' => -1,
                'escape' => false,
                'data-toggle' => 'modal',
                'data-target' => '#modalEnvoieValidation',
                'data-id' => $fiche_id,
                'data-fiche' => $etatFiche_id,
                'class' => 'btn_envoyerValideur'
            ]),
            ['role' => 'presentation']
        );
        
        // Envoyer au CIL ou insèrer au registre
        if ($this->Autorisation->isCil()) {
            $menu .= $this->Html->tag(
                'li',
                $this->Html->link(__d('pannel', 'pannel.textValiderInsererRegistre'), ['#' => '#'], [
                    'role' => 'menuitem',
                    'tabindex' => '-1',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalValidCil',
                    'data-type' => $fiche_typedeclaration,
                    'data-id' => $fiche_id,
                    'class' => 'btn-insert-registre'
                ]),
                []
            );
        } else {
            if ($banette == 'encours_redaction') {
                $title = __d('pannel', 'pannel.textEnvoyerCIL');
            } else {
                $title = __d('pannel', 'pannel.textValiderEnvoyerCIL');
            }
            
            $menu .= $this->Html->tag(
                    'li',
                    $this->Html->link($title, [
                        'controller' => 'etatFiches',
                        'action' => 'cilValid',
                        $fiche_id
                            ], [
                        'role' => 'menuitem',
                        'tabindex' => '-1'
                    ]),
                    ['role' => 'presentation']
                );
        }
        
        $span = $this->Html->tag(
            'ul',
            $menu,
            ['class' => 'dropdown-menu', 'role' => 'menu', 'aria-labelledby' => 'dropdownMenuValider', 'id' => 'dropdownMenuValider'.$fiche_id]
        );
        
        return $span;
    }
    
    /**
     * Groupe de boutons correspondant à la banette 
     * "Mes déclarations en cours de rédaction"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsEncoursRedaction(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        $traitement_name = Hash::get($result, 'Valeur.0.valeur');
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/edit/#Fiche.id#', $result)
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"])
            .$this->_button('/fiches/envoyer/#Fiche.id#', $result, ['onclick' => "$('#dropdownMenuValider{$fiche_id}').toggle(); return false;"])
            .$this->_menuEnvoyerTraitement('encours_redaction', $result)
            .$this->_button('/fiches/delete/#Fiche.id#', $result, ['confirm' => __d('pannel', 'pannel.confirmationSupprimerTraitement') . $traitement_name . ' " ?']);

    }
    
    /**
     * Groupe de boutons correspondant à la banette "Mes déclarations en attente"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsAttente(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"])
            .$this->_buttonReorienter($result);
    }
    
    /**
     * Groupe de boutons correspondant à la banette "Mes déclarations refusées"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsRefuser(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        $traitement_name = Hash::get($result, 'Valeur.0.valeur');
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/EtatFiches/relaunch/#Fiche.id#', $result)
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"])
            .$this->_button('/fiches/delete/#Fiche.id#', $result, ['confirm' => __d('pannel', 'pannel.confirmationSupprimerTraitement') . $traitement_name . ' " ?']);
    }
    
    /**
     * Groupe de boutons correspondant à la banette "Mes traitements validés et insérés au registre"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsArchives(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        
        if ($result['EtatFiche']['etat_id'] == 5){
            $action = 'genereTraitement';
        } else if ($result['EtatFiche']['etat_id'] == 7) {
            $action = 'downloadFileTraitement';
        }
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/'.$action.'/#Fiche.id#', $result)    
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"]);
    }
    
    /**
     * Groupe de boutons correspondant à la banette "Etat des traitements passés en ma possession"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsConsulte(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        $etatFiche_etatId = Hash::get($result, 'EtatFiche.etat_id');
        $etatFiche_userId = Hash::get($result, 'EtatFiche.user_id');
        $traitement_name = Hash::get($result, 'Valeur.0.valeur');
        
        $reorienter = null;
        $remiseRedaction = null;
        if ($this->Autorisation->authorized(ListeDroit::VALIDER_TRAITEMENT, $this->Session->read('Droit.liste')) && $etatFiche_etatId == EtatFiche::ENCOURS_VALIDATION && $this->Session->read('Auth.User.id') != $etatFiche_userId) {
            $reorienter = $this->_buttonReorienter($result);
        } elseif ($this->Autorisation->authorized(ListeDroit::INSERER_TRAITEMENT_REGISTRE, $this->Session->read('Droit.liste'))) {
            if ($etatFiche_etatId == EtatFiche::REFUSER) {
                $remiseRedaction = $this->_button('/EtatFiches/relaunch/#Fiche.id#', $result, ['confirm' => __d('pannel', 'pannel.confirmationReapproprierTraitement') . $traitement_name . ' " ?']);
            }
        }

        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"])
            .$reorienter
            .$remiseRedaction;
    }
    
    /**
     * Groupe de boutons correspondant à la banette "Traitements reçus pour validation"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsRecuValidation(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/edit/#Fiche.id#', $result)
            .$this->_button('/fiches/historique/#Fiche.id#', $result, ['onclick' => "$('#listeValidation{$fiche_id}').toggle(); return false;"])
            .$this->_button('/fiches/envoyer/#Fiche.id#', $result, ['onclick' => "$('#dropdownMenuValider{$fiche_id}').toggle(); return false;"])
            .$this->_menuEnvoyerTraitement('recuValidation', $result)
            .$this->_button('#refuser', $result, ['onclick' => "$('#commentaireRefus{$fiche_id}').toggle(); return false;"]);
    }
    
    /**
     * Groupe de boutons correspondant à la banette "Traitements reçus pour consultation"
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttonsRecuConsultation(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');
        
        return $this->_button('/fiches/show/#Fiche.id#', $result)
            .$this->_button('/fiches/reponse/#Fiche.id#', $result, ['onclick' => "$('#commentaireRepondre{$fiche_id}').toggle(); return false;"]);
    }
    
    /**
     * 
     * @param type $banette
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _buttons($banette, array $result, array $params = []) {
        $method = '_buttons'.Inflector::camelize($banette);
        $content = null;

        if(true === method_exists($this, $method)) {
            $content = $this->{$method}($result, $params);
        }

        return $this->Html->tag('div',$content,['class' => 'btn-group']);
    }

    /**
     * Nouvelle ligne dans le tableau de la banette avec l'historique du
     * traitement.
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _rowHistorique(array $result, array $params = []) {
        $fiche_id = Hash::get($result, 'Fiche.id');

        $parcours = $this->requestAction(['controller' => 'Pannel', 'action' => 'parcours', $fiche_id]);
        $historique = $this->requestAction(['controller' => 'Pannel', 'action' => 'getHistorique', $fiche_id]);
        

        $options = ['class' => 'listeValidation', 'id' => "listeValidation{$fiche_id}"];

        return $this->Html->tableCells(
            [
                [
                    ['', []],
                    [$this->_View->element('parcours', ['parcours' => $parcours]), ['class' => 'tdleft', 'colspan' => 3]],
                    [$this->_View->element('historique', ['historique' => $historique, 'id' => $fiche_id]), ['class' => 'tdleft']],
                    ['', []]
                ]   
            ],
            $options,
            $options,
            false
        );
    }
    
    /**
     * Nouvelle ligne dans le tableau de la banette pour répondre dans le cas 
     * d'un refus ou d'une consultation
     * 
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _rowReponse(array $result, array $params = []) {
        $etatFiche_id = Hash::get($result, 'EtatFiche.id');
        $etatFiche_previousUserId = Hash::get($result, 'EtatFiche.previous_user_id');
        $fiche_id = Hash::get($result, 'Fiche.id');

        $options = ['class' => 'commentaireRepondre', 'id' => "commentaireRepondre{$fiche_id}"];

        $content = $this->Form->create('EtatFiche', ['action' => 'answerAvis']);
        $content .= $this->Form->inputs(
            [
                'fieldset' => false,
                'legend' => false,
                'commentaireRepondre' => [
                    'div' => 'input-group inputsForm',
                    'label' => false,
                    'before' => '<span class="labelFormulaire">'. __d('pannel','pannel.textDonnerAvis').'</span><span class="obligatoire">*</span>',
                    'required' => true,
                    'class' => 'form-control',
                    'type' => 'textarea'
                ]
            ]
        );
        $content .= $this->Form->hidden('etatFiche', ['value' => $etatFiche_id]);
        $content .= $this->Form->hidden('previousUserId', ['value' => $etatFiche_previousUserId]);
        $content .= $this->Form->hidden('ficheNum', ['value' => $fiche_id]);
        $content .= $this->WebcilForm->buttons(['Cancel', 'Send']);
        $content .= $this->Form->end();

        return $this->Html->tableCells(
            [
                [
                    ['', []],
                    [$content, ['class' => 'tdleft', 'colspan' => 3]],
                    ['', []]
                ]   
            ],
            $options,
            $options,
            false
        );
    }
    
    protected function _rowRefuser(array $result, array $params = []) {
        $etatFiche_id = Hash::get($result, 'EtatFiche.id');
        $fiche_id = Hash::get($result, 'Fiche.id');

        $options = ['class' => 'commentaireRefus', 'id' => "commentaireRefus{$fiche_id}"];

        $content = $this->Form->create('EtatFiche', ['action' => 'refuse']);
        $content .= $this->Form->inputs(
            [
                'fieldset' => false,
                'legend' => false,
                'commentaireRepondre' => [
                    'div' => 'input-group inputsForm',
                    'label' => false,
                    'before' => '<span class="labelFormulaire">'. __d('pannel','pannel.textExpliquezRaisonRefus').'</span><span class="obligatoire">*</span>',
                    'required' => true,
                    'class' => 'form-control',
                    'type' => 'textarea'
                ]
            ]
        );
        $content .= $this->Form->hidden('ficheNum', ['value' => $fiche_id]);
        $content .= $this->Form->hidden('etatFiche', ['value' => $etatFiche_id]);
        $content .= $this->WebcilForm->buttons(['Cancel', 'Send']);
        $content .= $this->Form->end();

        return $this->Html->tableCells(
            [
                [
                    ['', []],
                    [$content, ['class' => 'tdleft', 'colspan' => 3]],
                    ['', []]
                ]   
            ],
            $options,
            $options,
            false
        );
    }

    /**
     * 
     * 
     * @param type $banette
     * @param array $result
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 19/07/2017
     * @version V1.0.0
     */
    protected function _rowSupplementaire($banette, array $result, array $params = []) {
        if('recuConsultation' === $banette) {
            return $this->_rowReponse($result, $params);
        } elseif ('recuValidation' === $banette) {
            return $this->_rowHistorique($result, $params).$this->_rowRefuser($result, $params);
        } else {
            return $this->_rowHistorique($result, $params);
        }
    }

    /**
     * 
     * @param array $results
     * @param array $params
     * @return type
     * 
     * @access protected
     * @created 18/07/2017
     * @version V1.0.0
     */
    protected function _banette(array $results, array $params = []) {
        $params += [
            'limit' => false,
            'link' => false
        ];
        
        $traitement = Inflector::camelize($params['action']);

        if (true === empty($results)) {
            $h3 = $this->Html->tag('h3', __d('pannel', "pannel.aucunTraitement{$traitement}"));
            $span = null;
            $content = $this->Html->tag('div', $h3, ['class' => 'text-center']);
        } else {
            
            if (false !== $params['limit'] && $params['count'] >= $params['limit']) {
                $span = $this->Html->tag(
                    'span',
                    $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', "pannel.btnVoirTraitement{$traitement}"), [
                            'controller' => 'pannel',
                            'action' => $params['action'],
                                ], [
                            'class' => 'btn btn-default-primary',
                            'escapeTitle' => false,
                    ]),
                    ['class' => 'pull-right']
                );
            } else {
                $span = null;
            }

            $thead = $this->thead();

            $rows = '';
            foreach ($results as $result) {
                $rows .= $this->Html->tableCells(
                    [
                        [
                            // Etat du traitement
                            [$this->etatIcone($result), ['class' => 'tdleft col-md-1']],
                            // Nom du traitement
                            [$result['Valeur'][0]['valeur'], ['class' => 'tdleft']],
                            // Créé par
                            [$result['User']['prenom'] . ' ' . $result['User']['nom'] . ' le ' . $this->Time->format($result['Fiche']['created'], FORMAT_DATE_HEURE), ['class' => 'tdleft']],
                            // Dernière modification le
                            [$this->Time->format($result['Fiche']['modified'], FORMAT_DATE_HEURE), ['class' => 'tdleft']],
                            // Actions
                            [$this->_buttons($params['action'], $result),['class' => 'tdleft']],
                        ]
                    ]
                )
                .$this->_rowSupplementaire($params['action'], $result);
            }
            
            $tbody = $this->Html->tag('tbody', $rows);

            $content = $this->Html->tag('table', $thead.$tbody, ['class' => 'table']);
            
            // @todo: ajouter des actions si besoin ("Créer un traitement" dans "Mes déclarations en cours de rédaction")
        }

        $title = $this->Html->tag(
            'h3',
            sprintf(__d('pannel', "pannel.traitement{$traitement}"), Hash::get($params, 'count')).$span,
            ['class' => 'panel-title']
        );
            
        $head = $this->Html->tag(
            'div',
            $this->Html->tag(
                'div',
                $this->Html->tag('div', $title, ['class' => 'col-md-12']),
                ['class' => 'row']
            ),
            ['class' => 'panel-heading']
        );
        
        $body = $this->Html->tag('div', $content, ['class' => 'panel-body panel-body-custom']);

        $link = false === empty($params['link']) ? $params['link'] : '';
        
        return $this->Html->tag('div', $head.$body.$link, ['class' => 'panel panel-primary']);
    }
    
    /**
     * 
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function encoursRedaction(array $banette, array $params = []) {
        $params += [
            'action' => 'encours_redaction',
            'count' => $banette['count'],
            'link' => $this->Html->tag(
                'div',
                $this->Html->tag(
                    'div',
                    $this->Html->link('<span class="fa fa-plus fa-lg"></span>' . __d('pannel', 'pannel.btnCreerTraitement'), ['#' => '#'], [
                        'escape' => false,
                        'data-toggle' => 'modal',
                        'data-target' => '#myModal',
                        'class' => 'btn btn-default-primary'
                    ]),
                    ['class' => 'col-md-12 text-center']
                ),
                ['class' => 'row bottom10']
            )
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function attente(array $banette, array $params = []) {
        $params += [
            'action' => 'attente',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function recuValidation(array $banette, array $params = []) {
        $params += [
            'action' => 'recuValidation',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function recuConsultation(array $banette, array $params = []) {
        $params += [
            'action' => 'recuConsultation',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function refuser(array $banette, array $params = []) {
        $params += [
            'action' => 'refuser',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function archives(array $banette, array $params = []) {
        $params += [
            'action' => 'archives',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
    
    /**
     * 
     * @param array $banette
     * @param array $params
     * @return type
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function consulte(array $banette, array $params = []) {
        $params += [
            'action' => 'consulte',
            'count' => $banette['count']
        ];

        return $this->_banette($banette['results'], $params);
    }
}