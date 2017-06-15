<?php

/**
 * Code source de la classe WebcilFormHelper.
 */
App::uses('FormHelper', 'View/Helper');

/**
 * La classe WebcilFormHelper simplifie l'écriture des éléments de formulaire pour web-CIL
 */
class WebcilFormHelper extends FormHelper {

    public $helpers = ['Html'];
    
    protected function _options( array $defaults, array $options ) {
        $options += $defaults;

        foreach(array_keys($defaults) as $key) {
            if(true === is_array($defaults[$key])) {
                $options[$key] += $defaults[$key];
            }
        }

        return $options;
    }

    public function input($fieldName, $options = array()) {
        if (false === strpos($fieldName, '.')) {
            $modelName = Inflector::classify($this->request->params['controller']);
        } else {
            list($modelName, $fieldName) = explode('.', $fieldName);
        }
        
        $defaults = [
            'id' => null,
            'name' => null,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => null,
            'label' => [
                'text' => null,
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'required' => false,
            'value' => null
        ];
        $options = $this->_options($defaults, $options);

        if ($options['id'] === null) {
            $options['id'] = $modelName . ucfirst($fieldName);
        }
        
        if ($options['name'] === null) {
            $options['name'] = 'data['.$modelName.']['.$fieldName.']';
        }
        
        if (null === $options['placeholder']) {
            $options['placeholder'] = __m(Inflector::underscore($modelName) . '.placeholderChamp' . Inflector::camelize($fieldName));
        }

        if (null === $options['label']['text']) {
            $options['label']['text'] = __m(Inflector::underscore($modelName) . '.champ' . Inflector::camelize($fieldName));
        }

        if (true === $options['required']) {
            //$options['label']['text'] .= ' <abbr class="requis" title="Champ obligatoire">*</abbr>';
            $options['label']['text'] .= ' <span class="requis">*</span>';
        }
        
        if ($options['value'] === null) {
            $options['value'] = $this->request->data($modelName.'.'.$fieldName);
        }

        if (isset($this->validationErrors['User'][$fieldName]) && !empty($this->validationErrors['User'][$fieldName])) {
            $options['after'] .= '<div class="error-message">' . $this->validationErrors[$modelName][$fieldName][0] . '</div>';
        }
        
        return $this->Html->tag('div', parent::input($fieldName, $options), ['class' => 'form-group']);
    }

    public function inputs($fields = null, $blacklist = null, $options = array()) {
        $fields += [
            'fieldset' => false,
            'legend' => false
        ];
        
        return parent::inputs($fields, $blacklist, $options);
    }
    
    public function buttons( array $buttons, array $options = array()) {
        $defaults = [
            'Cancel' => [
                'i' => 'fa-times-circle',
                'button' => 'btn-default-default'
            ],
            'Save' => [
                'i' => 'fa-floppy-o',
                'button' => 'btn-default-success'
            ]
        ];
        
        $options = $this->_options($defaults, $options);

        $result = '';
        
        foreach(Hash::normalize($buttons) as $button => $params) {
            $classes = true === isset( $options[$button] )
                ? $options[$button]
                : [ 'i' => '', 'button' => 'btn-default-default' ];
            
            $result .= $this->button("<i class=\"fa {$classes['i']} fa-lg\"></i> ".__m("default.btn{$button}"),
                [
                    'type' => 'submit',
                    'name' => 'submit',
                    'value' => $button,
                    'escape' => false,
                    'class' => "btn {$classes['button']}"
                ]
            );
        }
        
        return $this->Html->tag(
            'div',
            $this->Html->tag( 'div', $result, ['class' => 'btn-group send'] ),
            ['class' => 'text-center']
        );
    }

}
