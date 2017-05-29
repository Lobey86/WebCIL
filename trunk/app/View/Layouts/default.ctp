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
$cakeDescription = 'Web-CIL';
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
        echo $this->Html->script('fadeflash.js', ['inline' => false]);
        echo $this->Html->script('bootstrap-filestyle.min.js', ['inline' => false]);
        echo $this->Html->script('chosen.jquery.min.js');
        echo $this->Html->script('jquery-ui.js');
        echo $this->Html->script('main.js');
        echo $this->Html->script('bootstrap-datetimepicker.min');
        echo $this->Html->script('locales/bootstrap-datetimepicker.fr.js');
        echo $this->Html->script('select2-4.0.2/dist/js/select2.full.min.js');
        echo $this->Html->script('select2-4.0.2/dist/js/select2.min.js');

        echo $this->Html->meta('icon');

        echo $this->Html->css('chosen.min.css');
        echo $this->Html->css('bootstrap-theme.min.css');
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('chosen.min.css');
        echo $this->Html->css('main.css');
        echo $this->Html->css('jquery-ui.css');
        echo $this->Html->css('font-awesome.min.css');
        echo $this->Html->css('bootstrap-datetimepicker.min');
        echo $this->Html->css('/js/select2-4.0.2/dist/css/select2.min.css');

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
                                    <?php
                                    echo $this->Html->image('logo_WebCil.png', [
                                        'alt' => 'Web-Cil',
                                        'url' => [
                                            'controller' => 'pannel',
                                            'action' => 'index'
                                        ],
                                        'class' => 'navbar-brand'
                                    ]);
                                    ?>
                                </div>

                                <div class="navbar-collapse collapse">
                                    <?php
                                    if (isset($prenom) && isset($nom)) {
                                        ?>
                                    <ul class="nav navbar-nav">
                                            <?php
                                            if (!$this->Session->read('Su')) {
                                                if ($this->Autorisation->authorized([
                                                            '1',
                                                            '2',
                                                            '3',
                                                            '5'
                                                                ], $this->Session->read('Droit.liste'))
                                                ) {
                                                    ?>
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle"
                                                           data-toggle="dropdown"> <?php echo __d('default', 'default.titreTraitement'); ?>
                                                            <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <?php
                                                            echo '<li>' . $this->Html->link('<i class="fa fa-files-o fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitement'), [
                                                                'controller' => 'pannel',
                                                                'action' => 'index'
                                                                ], ['escape' => false]) . '</li>';

                                                            if ($this->Autorisation->authorized([
                                                                        '1'
                                                                            ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-pencil-square-o fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitementEnCoursRedaction'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'encours_redaction'
                                                                        ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                        '1'
                                                                            ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-info fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitementEnAttente'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'attente'
                                                                        ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                        '1'
                                                                            ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-times fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitementRefuser'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'refuser'
                                                                        ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                        '2'
                                                                            ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-pause fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitementRecuValidation'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'recuValidation'
                                                                        ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                        '3'
                                                                ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-inbox fa-fw"></i>' . __d('default', 'default.sousTitreMesTraitementRecuConsultation'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'recuConsultation'
                                                                        ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                '2',
                                                                '3'
                                                                ], $this->Session->read('Droit.liste'))
                                                            ) {
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-eye fa-fw"></i>' . __d('default', 'default.sousTitreTraitementVu'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'consulte'
                                                                     ], ['escape' => false]) . '</li>';
                                                            }

                                                            if ($this->Autorisation->authorized([
                                                                        '1'
                                                                            ], $this->Session->read('Droit.liste'))
                                                            ) {

                                                                echo '<li>' . $this->Html->link('<i class="fa fa-check fa-fw"></i>' . __d('default', 'default.sousTitreTraitementValidees'), [
                                                                    'controller' => 'pannel',
                                                                    'action' => 'archives'
                                                                        ], ['escape' => false]) . '</li>';
                                                                echo '<li class="divider"></li>';
                                                                echo '<li>' . $this->Html->link('<i class="fa fa-plus fa-fw"></i>' . __d('pannel', 'pannel.btnCreerTraitement'), ['#' => '#'], [
                                                                    'escape' => false,
                                                                    'data-toggle' => 'modal',
                                                                    'data-target' => '#myModal'
                                                                ]) . '</li>';
                                                            }
                                                            ?>
                                                        </ul>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        <li>
                                            <?php
                                            if ($this->Autorisation->authorized('4', $this->Session->read('Droit.liste'))) {
                                                echo $this->Html->link(__d('default', 'default.titreRegistre'), [
                                                    'plugin' => '',
                                                    'controller' => 'registres',
                                                    'action' => 'index'
                                                ]);
                                            }
                                            ?>
                                        </li>

                                            <?php
                                            if ($this->Autorisation->authorized([
                                                        '11',
                                                        '12'
                                                            ], $this->Session->read('Droit.liste'))
                                            ) {
                                                ?>

                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown"><?php echo __d('default', 'default.titreAdministration') . $this->Session->read('Organisation.raisonsociale'); ?>
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php
                                                if ($this->Autorisation->authorized([
                                                    '12',
                                                    '11'
                                                        ], $this->Session->read('Droit.liste'))
                                                ) {
                                                    echo '<li>' . $this->Html->link('<i class="fa fa-info fa-fw"></i>' . __d('default', 'default.sousTitreInfoGenerale'), [
                                                        'controller' => 'organisations',
                                                        'action' => 'edit',
                                                        $this->Session->read('Organisation.id')
                                                            ], ['escape' => false]) . '</li>';
                                                }

                                                if (!$this->Session->read('Su')) {
                                                    if ($this->Autorisation->authorized([
                                                        '12'
                                                    ], $this->Session->read('Droit.liste'))
                                                    ) {
                                                        echo '<li>' . $this->Html->link('<i class="fa fa-check-square-o fa-fw"></i>' . __d('default', 'default.sousTitreFormulaire'), [
                                                            'controller' => 'Formulaires',
                                                            'action' => 'index'
                                                                ], ['escape' => false]) . '</li>';
                                                        ?>
                                                        <li class="dropdown-submenu">
                                                            <a href="#" >
                                                                Mes modèles
                                                            </a>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li>
                                                                    <a href="#" title="Modèles pour les formulaires">
                                                                        <?php
                                                                         echo '<li>' . $this->Html->link('<i class="fa fa-file-text-o fa-fw"></i>' . __d('default', 'default.sousTitreModeleFormulaire'), [
                                                                            'controller' => 'modeles',
                                                                            'action' => 'index'
                                                                                ], ['escape' => false]) . '</li>';
                                                                        ?>
                                                                    </a>
                                                                    <a href="#" title="Modèle pour l'extrait de registre">
                                                                        <?php
                                                                         echo '<li>' . $this->Html->link('<i class="fa fa-file-text-o fa-fw"></i>' . __d('default', 'default.sousTitreModeleExtraitRegistre'), [
                                                                            'controller' => 'modeleExtraitRegistres',
                                                                            'action' => 'index'
                                                                                ], ['escape' => false]) . '</li>';
                                                                        ?>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                                <?php
                                            }
                                            if ($this->Autorisation->authorized([
                                                        '8',
                                                        '9',
                                                        '10',
                                                        '11',
                                                        '12',
                                                        '13',
                                                        '14',
                                                        '15'
                                                            ], $this->Session->read('Droit.liste'))
                                            ) {
                                                ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown"><?php echo __d('default', 'default.titreAdministrationUser'); ?>
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                        <?php
                                                        if ($this->Autorisation->authorized([
                                                                    '13',
                                                                    '14',
                                                                    '15'
                                                                        ], $this->Session->read('Droit.liste'))
                                                        ) {
                                                            echo '<li>' . $this->Html->link('<i class="fa fa-tags fa-fw"></i>' . __d('default', 'default.sousTitreProfils'), [
                                                                'controller' => 'roles',
                                                                'action' => 'index'
                                                                    ], ['escape' => false]) . '</li>';
                                                        }
                                                        if ($this->Autorisation->authorized([
                                                                    '12',
                                                                    '11'
                                                                        ], $this->Session->read('Droit.liste'))
                                                        ) {
                                                            echo '<li>' . $this->Html->link('<i class="fa fa-sitemap fa-fw"></i>' . __d('default', 'default.sousTitreServices'), [
                                                                'controller' => 'services',
                                                                'action' => 'index'
                                                                    ], ['escape' => false]) . '</li>';
                                                        }
                                                        if ($this->Autorisation->authorized([
                                                                    '8',
                                                                    '9',
                                                                    '10'
                                                                        ], $this->Session->read('Droit.liste'))
                                                        ) {
                                                            echo '<li>' . $this->Html->link('<i class="fa fa-users fa-fw"></i>' . __d('default', 'default.sousTitreUser'), [
                                                                'controller' => 'users',
                                                                'action' => 'index'
                                                                    ], ['escape' => false]) . '</li>';

                                                            echo '<li class="divider"></li>';
                                                            echo '<li>' . $this->Html->link('<i class="fa fa-user-plus fa-fw"></i>' . __d('default', 'default.sousTitreAjouterUser'), [
                                                                'controller' => 'users',
                                                                'action' => 'add'
                                                                    ], ['escape' => false]) . '</li>';
                                                        }
                                                        ?>
                                            </ul>
                                        </li>
                                                <?php
                                            }
                                            if ($this->Session->read('Su')) {
                                                ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown">Administration
                                                de l'application
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                        <?php
                                                        echo '<li>' . $this->Html->link('<i class="fa fa-institution fa-fw"></i> Entités', [
                                                            'controller' => 'organisations',
                                                            'action' => 'index'
                                                                ], ['escape' => false]) . '</li>';

                                                        echo '<li>' . $this->Html->link('<i class="fa fa-group fa-fw"></i> Super administrateurs', [
                                                            'controller' => 'admins',
                                                            'action' => 'index'
                                                                ], ['escape' => false]) . '</li>';
                                                        
                                                        echo '<li>' . $this->Html->link('<i class="fa fa-group fa-fw"></i> Gestion de tous les utilisateurs', [
                                                            'controller' => 'users',
                                                            'action' => 'admin_index'
                                                                ], ['escape' => false]) . '</li>';
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
                                                <li class="dropdown-header"><?php echo __d('default', 'default.sousTitreEntite'); ?></li>
                                                    <?php
                                                    foreach ($organisations as $datas) {
                                                        ?>
                                                <li><?php
                                                            echo $this->Html->link($datas['Organisation']['raisonsociale'], [
                                                                'controller' => 'organisations',
                                                                'action' => 'change',
                                                                $datas['Organisation']['id']
                                                            ]);
                                                            ?></li>
                                                        <?php
                                                    }
                                                    ?>
                                                <li class="divider"></li>
                                                <li class="dropdown-header"><?php echo __d('default', 'default.sousTitreCompte'); ?></li>
                                                <li><?php
                                                        echo $this->Html->link('<i class="fa fa-cog fa-fw"></i>' . __d('default', 'default.sousTitreModifCompte'), [
                                                            'controller' => 'users',
                                                            'action' => 'changepassword',
                                                            $userId
                                                                ], ['escapeTitle' => false]);
                                                        ?></li>
                                                <li><?php
                                                        echo $this->Html->link('<i class="fa fa-sign-out fa-fw"></i>' . __d('default', 'default.sousTitreDeconnexion'), [
                                                            'controller' => 'users',
                                                            'action' => 'logout'
                                                                ], ['escapeTitle' => false]);
                                                        ?></li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <ul class="nav navbar-nav pull-right">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle"
                                               data-toggle="dropdown">
                                                <span class="glyphicon glyphicon-envelope"></span>
                                            </a>
                                            <ul class="info_notification dropdown-menu" role="menu">
                                                    <?php
                                                    foreach ($notificationsStayed as $key => $value) {
                                                        if ($this->Session->read('Organisation.id') == $value['Fiche']['organisation_id']) {
                                                            switch ($value['Notification']['content']) {
                                                                case 1:
                                                                    echo '<a>' . $this->Html->link('<i class="list-group-item list-group-item-info">' . __d('default', 'default.notificationAvisDemandeTraitement') . ' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></i>', [
                                                                        'controller' => 'organisations',
                                                                        'action' => 'changenotification',
                                                                        $value['Fiche']['organisation_id'],
                                                                        'pannel',
                                                                        'recuConsultation',
                                                                        $value['Fiche']['id']
                                                                            ], [
                                                                        'escape' => false
                                                                    ]) . '</a>';
                                                                    break;
                                                                case 2:
                                                                    echo '<a>' . $this->Html->link('<i class="list-group-item list-group-item-info">' . __d('default', 'default.notificationValidationDemandeTraitement') . '<strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></i>', [
                                                                        'controller' => 'organisations',
                                                                        'action' => 'changenotification',
                                                                        $value['Fiche']['organisation_id'],
                                                                        'pannel',
                                                                        'recuValidation',
                                                                        $value['Fiche']['id']
                                                                            ], [
                                                                        'escape' => false
                                                                    ]) . '</a>';
                                                                    break;
                                                                case 3:
                                                                    echo '<a>' . $this->Html->link('<i class="list-group-item list-group-item-success">' . __d('default', 'default.notificationLeTraitement') . ' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>' . __d('default', 'default.notificationTraitementValidee') . '</i>', [
                                                                        'controller' => 'organisations',
                                                                        'action' => 'changenotification',
                                                                        $value['Fiche']['organisation_id'],
                                                                        'registres',
                                                                        'index',
                                                                        $value['Fiche']['id']
                                                                            ], [
                                                                        'escape' => false
                                                                    ]) . '</a>';
                                                                    break;
                                                                case 4:
                                                                    echo '<a>' . $this->Html->link('<i class="list-group-item list-group-item-danger">' . __d('default', 'default.notificationLeTraitement') . '<strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>' . __d('default', 'default.notificationTraitementRefusee') . '</i>', [
                                                                        'controller' => 'organisations',
                                                                        'action' => 'changenotification',
                                                                        $value['Fiche']['organisation_id'],
                                                                        'pannel',
                                                                        'refuser',
                                                                        $value['Fiche']['id']
                                                                            ], [
                                                                        'escape' => false
                                                                    ]) . '</a>';
                                                                    break;
                                                                case 5:
                                                                    echo '<a>' . $this->Html->link('<i class="list-group-item list-group-item-info">' . __d('default', 'default.notificationCommentaireAjouterTraitement') . '<strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></i>', [
                                                                        'controller' => 'organisations',
                                                                        'action' => 'changenotification',
                                                                        $value['Fiche']['organisation_id'],
                                                                        'pannel',
                                                                        'consulte',
                                                                        $value['Fiche']['id']
                                                                            ], [
                                                                        'escape' => false
                                                                    ]) . '</a>';
                                                                    break;
                                                            }
                                                        }
                                                    }

                                                    if (!empty($notificationsStayed) && $this->Session->read('Organisation.id') == $value['Fiche']['organisation_id']) {
                                                        echo '<li>' . $this->Html->link('<i class="fa fa-fw fa-trash"></i>' . __d('default', 'default.btnEffacerNotifications'), [
                                                            'controller' => 'pannel',
                                                            'action' => 'dropNotif'
                                                                ], [
                                                            'escape' => false
                                                        ]) . '</li>';
                                                    } else {
                                                        echo '<li class="dropdown-header">Aucune notification</li>';
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

                    <?php
                    echo $this->Session->flash();
                    if ($this->params['action'] != 'login') {
                        ?>
                    <div class="row head">
                        <div class="col-md-6">
                            <h2><?php echo $title; ?></h2>
                        </div>
                        <div class="col-md-6 text-right">
                                <?php
                                if (file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'))) {
                                    echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), ['class' => 'logo-well']);
                                }
                                ?>
                        </div>
                    </div>
                        <?php
                    }
                    echo $this->fetch('content');
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
                                <h4 class="modal-title" id="myModalLabel">
                                    <?php 
                                    echo __d('default', 'default.popupChoisirFormulaire'); ?>
                                </h4>
                            </div>

                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <th class="col-md-3">
                                            <?php echo __d('default', 'default.popupTitreTableauNom'); ?>
                                    </th>

                                    <th class="col-md-7">
                                            <?php echo __d('default', 'default.popupTitreTableauDescription'); ?>
                                    </th>

                                    <th class="col-md-2">
                                            <?php echo __d('default', 'default.popupTitreTableauAction'); ?>
                                    </th>
                                    </thead>

                                    <tbody>
                                        <?php
                                        foreach ($formulaires_actifs as $key => $value) {
                                            echo '<tr>
                                                <td>' . $value['Formulaire']['libelle'] . '</td>
                                                <td>' . $value['Formulaire']['description'] . '</td>
                                                <td>' . $this->Html->link(__d('default', 'default.popupBtnChoisir'), [
                                                'controller' => 'fiches',
                                                'action' => 'add',
                                                $value['Formulaire']['id']
                                                ], ['class' => 'btn btn-default-default']) . '</td>
                                                </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default-default" data-dismiss="modal"><i
                                        class="fa fa-times-circle fa-lg"></i>
                                        <?php echo(__d('default', 'default.btnAnnuler')); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="footer" class="container-fluid-custom">
                    <div class="text-center versioning">
                        <?php echo "Web-CIL v" . VERSION . " - ";?> <a href="https://www.libriciel.fr/" target="_blank">Libriciel SCOP</a>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>

<!--Pop-up pour informé que ce n'est pas un fichier .odt -->
<div class="modal fade" id="errorExtentionAnnexe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row top17">
                    <div class="col-md-12">
                        <h3> <?php echo __d('fiche', 'fiche.textTypeFichierAccepter'); ?> </h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-arrow-left fa-fw"></i>
                        <?php echo 'OK'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if( isset( $idFicheNotification ) ): ?>
<script type="text/javascript">
//<![CDATA[
    $(document).ready(function () {
        openTarget("<?php echo $idFicheNotification ?>");

    });
//]]>
</script>
<?php endif; ?>