<?php
App::import('Helper', 'Form');

class MyFormHelper extends FormHelper
{
function errors($model)
{
$model = Inflector::camelize($model);
$this->setEntity($model);
$errors = array();
if (ClassRegistry::isKeySet($model))
{
$object =& ClassRegistry::getObject($model);
$errors = $object->error_messages();
}
if (empty($errors))
{
return false;
}
$out = '<div class="error-messages">' . "\n";
    $out .= '<h2>Veuillez corriger les erreurs mentionnées ci-dessous</h2>' . "\n";
    $out .= '<ul>' . "\n";
        foreach ($errors as $error)
        {
        $out .= "<li>$error</li>\n";
        }
        $out .= '</ul>' . "\n";
    $out .= '</div>' . "\n";
return $out;
}
}
