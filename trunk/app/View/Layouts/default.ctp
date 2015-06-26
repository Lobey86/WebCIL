<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'WebCIL';
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>
    </title>
    <?php
    echo $this->Html->script('jquery-1.11.0.min');
    echo $this->Html->script('bootstrap.min.js');
    echo $this->Html->script('formulaire.js');
    echo $this->Html->script('fadeflash.js', array('inline' => false));
    echo $this->Html->script('bootstrap-filestyle.min.js', array('inline' => false));
    echo $this->Html->script('chosen.jquery.min.js');
    echo $this->Html->script('main.js');

    echo $this->Html->meta('icon');

    echo $this->Html->css('chosen.min.css');
    echo $this->Html->css('bootstrap-theme.min.css');
    echo $this->Html->css('bootstrap.min.css');
    echo $this->Html->css('chosen.min.css');
    echo $this->Html->css('main.css');
    echo $this->Html->css('jquery-ui.css');
    echo $this->Html->css('font-awesome.min.css');


    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>

<div id="container">
    <div id="content">
        <div class="container-fluid container-fluid-custom theme-showcase" id="relatif" role="main"
             style="margin-top: 60px;">
            <div id="unprintable_div">
                <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target=".navbar-collapse">
                                <span class="sr-only">Navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <?php echo $this->Html->link('WebCIL', array(
                                'plugin' => null,
                                'controller' => 'pannel',
                                'action' => 'index'
                            ), array('class' => 'navbar-brand')); ?></div>
                        <div class="navbar-collapse collapse">

                            <?php
                            if(isset($prenom) && isset($nom)) {
                                ?>
                                <ul class="nav navbar-nav">
                                    <?php
                                    if($this->Autorisation->authorized(array(
                                        '1',
                                        '2',
                                        '3',
                                        '5'
                                    ), $this->Session->read('Droit.liste'))
                                    ) {
                                        ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown">Fiches
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php
                                                if($this->Autorisation->authorized(array(
                                                    '1'
                                                ), $this->Session->read('Droit.liste'))
                                                ) {
                                                    echo '<li>' . $this->Html->link('<i class="fa fa-files-o fa-fw"></i> Mes fiches', array(
                                                            'controller' => 'pannel',
                                                            'action' => 'index'
                                                        ), array('escape' => false)) . '</li>';
                                                }
                                                if($this->Autorisation->authorized(array(
                                                    '1',
                                                    '2',
                                                    '5'
                                                ), $this->Session->read('Droit.liste'))
                                                ) {
                                                    echo '<li>' . $this->Html->link('<i class="fa fa-inbox fa-fw"></i> Fiches reçues', array(
                                                            'controller' => 'pannel',
                                                            'action' => 'inbox'
                                                        ), array('escape' => false)) . '</li>';
                                                }
                                                if($this->Autorisation->authorized(array(
                                                    '1'
                                                ), $this->Session->read('Droit.liste'))
                                                ) {

                                                    echo '<li>' . $this->Html->link('<i class="fa fa-check fa-fw"></i> Mes fiches validées', array(
                                                            'controller' => 'pannel',
                                                            'action' => 'archives'
                                                        ), array('escape' => false)) . '</li>';
                                                    echo '<li class="divider"></li>';
                                                    echo '<li>' . $this->Html->link('<i class="fa fa-plus fa-fw"></i> Créer une fiche', array('#' => '#'), array(
                                                            'escape' => false,
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#myModal'
                                                        )) . '</li>';

                                                }
                                                ?>
                                            </ul>
                                        </li>

                                    <?php
                                    }
                                    if($this->Autorisation->authorized(array(
                                        '4',
                                        '5',
                                        '6',
                                        '7'
                                    ), $this->Session->read('Droit.liste'))
                                    ) { ?>
                                        <li><?php echo $this->Html->link('Registre', array(
                                            'plugin' => '',
                                            'controller' => 'registres',
                                            'action' => 'index'
                                        )); ?></li><?php } ?>



                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle"
                                           data-toggle="dropdown">Administration
                                                                  de <?php echo $this->Session->read('Organisation.raisonsociale'); ?>
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php

                                            echo '<li>' . $this->Html->link('<i class="fa fa-info fa-fw"></i> Informations générales', array(
                                                    'controller' => 'organisations',
                                                    'action' => 'edit',
                                                    $this->Session->read('Organisation.id')
                                                ), array('escape' => false)) . '</li>';


                                            echo '<li>' . $this->Html->link('<i class="fa fa-sitemap fa-fw"></i> Services', array(
                                                    'controller' => 'services',
                                                    'action' => 'index'
                                                ), array('escape' => false)) . '</li>';

                                            echo '<li>' . $this->Html->link('<i class="fa fa-check-square-o fa-fw"></i> Formulaire', array(
                                                    'controller' => 'Formulaires',
                                                    'action' => 'index'
                                                ), array('escape' => false)) . '</li>';
                                            echo '<li>' . $this->Html->link('<i class="fa fa-file-text-o fa-fw"></i> Modèles', array(
                                                    'controller' => 'modeles',
                                                    'action' => 'index'
                                                ), array('escape' => false)) . '</li>';


                                            ?>
                                        </ul>
                                    </li>

                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle"
                                           data-toggle="dropdown">Administration des utilisateurs
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php

                                            echo '<li>' . $this->Html->link('<i class="fa fa-tags fa-fw"></i> Profils', array(
                                                    'controller' => 'roles',
                                                    'action' => 'index'
                                                ), array('escape' => false)) . '</li>';


                                            echo '<li>' . $this->Html->link('<i class="fa fa-users fa-fw"></i> Utilisateurs', array(
                                                    'controller' => 'users',
                                                    'action' => 'index'
                                                ), array('escape' => false)) . '</li>';

                                            echo '<li class="divider"></li>';
                                            echo '<li>' . $this->Html->link('<i class="fa fa-user-plus fa-fw"></i> Ajouter un utilisateur', array(
                                                    'controller' => 'users',
                                                    'action' => 'add'
                                                ), array('escape' => false)) . '</li>';


                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                    if($this->Session->read('Su')) {
                                        ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown">Administration
                                                                      de l'application
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php
                                                echo '<li>' . $this->Html->link('<i class="fa fa-institution fa-fw"></i> Organisations', array(
                                                        'controller' => 'organisations',
                                                        'action' => 'index'
                                                    ), array('escape' => false)) . '</li>';

                                                echo '<li>' . $this->Html->link('<i class="fa fa-group fa-fw"></i> Administrateurs', array(
                                                        'controller' => 'admins',
                                                        'action' => 'index'
                                                    ), array('escape' => false)) . '</li>';
                                                ?>
                                            </ul>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                                <ul class="nav navbar-nav pull-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle"
                                           data-toggle="dropdown"><?php echo $prenom . " " . $nom . " - " . $this->Session->read('Organisation.raisonsociale'); ?>
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="dropdown-header">Mes organisations</li>
                                            <?php
                                            foreach($organisations as $datas) {
                                                ?>
                                                <li><?php echo $this->Html->link($datas['Organisation']['raisonsociale'], array(
                                                        'controller' => 'organisations',
                                                        'action' => 'change',
                                                        $datas['Organisation']['id']
                                                    )); ?></li>
                                            <?php
                                            }
                                            ?>
                                            <li class="divider"></li>
                                            <li class="dropdown-header">Mon compte</li>
                                            <li><?php echo $this->Html->link('<i class="fa fa-cog fa-fw"></i> Modifier mon compte', array(
                                                    'controller' => 'users',
                                                    'action' => 'edit',
                                                    $userId
                                                ), array('escapeTitle' => false)); ?></li>
                                            <li><?php echo $this->Html->link('<i class="fa fa-lock fa-fw"></i> Déconnexion', array(
                                                    'controller' => 'users',
                                                    'action' => 'logout'
                                                ), array('escapeTitle' => false)); ?></li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="nav navbar-nav pull-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle"
                                           data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-envelope"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">

                                            <?php
                                            if(empty($notificationsStayed)) echo '<li class="dropdown-header">Aucune notification</li>';
                                            foreach($notificationsStayed as $key => $value) {
                                                switch($value['Notification']['content']) {
                                                    case 1:
                                                        echo '<li class="list-group-item list-group-item-info">Votre avis est demandé sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                                                        break;
                                                    case 2:
                                                        echo '<li class="list-group-item list-group-item-info">Votre validation est demandée sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                                                        break;
                                                    case 3:
                                                        echo '<li class="list-group-item list-group-item-success">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été validée</li>';
                                                        break;
                                                    case 4:
                                                        echo '<li class="list-group-item list-group-item-danger">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été refusée</li>';
                                                        break;
                                                    case 5:
                                                        echo '<li class="list-group-item list-group-item-info">Un commentaire a été ajouté à la fiche du traitement<strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                                                        break;
                                                }
                                            }
                                            if(!empty($notificationsStayed)) {
                                                echo '<li>' . $this->Html->link('<i class="fa fa-fw fa-trash"></i> Effacer les notifications', array(
                                                        'controller' => 'pannel',
                                                        'action' => 'dropNotif'
                                                    ), array(
                                                        'escape' => false
                                                    )) . '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            <?php
                            }
                            ?>
                        </div>

                    </div>

                </div>

            </div>
            <?php echo $this->Session->flash();
            if($this->params['action'] != 'login') {
                ?>
                <div class="row head">
                    <div class="col-md-6">
                        <h2><?php echo $title; ?></h2>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php
                        if(file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'))) {
                            echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'logo-well'));
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            echo $this->fetch('content'); ?>

            <!-- Modal de notification -->
            <?php
            if(!empty($notifications)) {
                echo '
                    <div class="modal fade" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Nouvelles notifications</h4>
                        </div>
                        <div class="modal-body">
                        <ul class="list-group">';
                foreach($notifications as $key => $value) {
                    switch($value['Notification']['content']) {
                        case 1:
                            echo '<li class="list-group-item list-group-item-info">Votre avis est demandé sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                            break;
                        case 2:
                            echo '<li class="list-group-item list-group-item-info">Votre validation est demandée sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                            break;
                        case 3:
                            echo '<li class="list-group-item list-group-item-success">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été validée</li>';
                            break;
                        case 4:
                            echo '<li class="list-group-item list-group-item-danger">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été refusée</li>';
                            break;
                        case 5:
                            echo '<li class="list-group-item list-group-item-info">Un commentaire a été ajouté à la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></li>';
                            break;
                    }
                }


                echo '</ul>
                        </div>
                        <div class="modal-footer">';
                echo $this->Html->link('Fermer', array(
                    'controller' => 'pannel',
                    'action' => 'validNotif'
                ), array(
                    'class' => 'btn btn-default-primary',
                    'escapeTitle' => false
                ));

                echo '</div>
                    </div>
                </div>
            </div>';
            }
            ?>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Choisir un formulaire</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <th class="col-md-3">
                                Nom
                            </th>
                            <th class="col-md-7">
                                Description
                            </th>
                            <th class="col-md-2">
                                Action
                            </th>
                            </thead>
                            <tbody>
                            <?php
                            foreach($formulaires_actifs as $key => $value) {
                                echo '<tr>
<td>' . $value['Formulaire']['libelle'] . '</td>
<td>' . $value['Formulaire']['description'] . '</td>
<td>' . $this->Html->link('Choisir', array(
                                        'controller' => 'fiches',
                                        'action' => 'add',
                                        $value['Formulaire']['id']
                                    ), array('class' => 'btn btn-default-default')) . '</td>
</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default-default" data-dismiss="modal"><i
                                class="fa fa-fw fa-arrow-left"></i> Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer" class="container-fluid-custom">
            <div class="text-center versioning">
                WebCIL V0.9 - Adullact-Projet
            </div>
        </div>
    </div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>