<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
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
        <?php echo $cakeDescription ?>:
        <?php echo $title_for_layout; ?>
    </title>
    <?php
    echo $this->Html->script('jquery-1.11.0.min');
    echo $this->Html->script('bootstrap.min.js');
    echo $this->Html->script('jquery.blockUI.js');
    echo $this->Html->script('formulaire.js');
    echo $this->Html->script('jqprint.js',array('inline'=>false));
    echo $this->Html->script('fadeflash.js',array('inline'=>false));
    echo $this->Html->script('jquery-ui.js',array('inline'=>false));
    echo $this->Html->script('bootstrap-filestyle.min.js',array('inline'=>false));
    echo $this->Html->script('chosen.jquery.min.js');

    echo $this->Html->meta('icon');

    echo $this->Html->css('chosen.min.css');
    echo $this->Html->css('bootstrap-theme.min.css');
    echo $this->Html->css('bootstrap.min.css');
    echo $this->Html->css('chosen.min.css');
    echo $this->Html->css('main.css');
    echo $this->Html->css('jquery-ui.css');


    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>

<div id="container">
    <div id="content">
        <div class="container theme-showcase" id="relatif" role="main" style="margin-top: 60px">
            <div id="unprintable_div">
                <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <?php echo $this->Html->link('WebCIL', array('plugin' => null, 'controller' => 'pannel', 'action' => 'index'), array('class' => 'navbar-brand'));?></div>
                        <div class="navbar-collapse collapse">

                            <?php
                            if(isset($prenom) && isset($nom)){
                                ?>
                                <ul class="nav navbar-nav">
                                    <li <?php if($this->params['controller'] == "pannel" || $this->params['controller'] == "fiches"){echo "class='active'";} ?>><?php echo $this->Html->link('Pannel', array('plugin'=> '', 'controller' => 'pannel', 'action' => 'index'));?></li>
                                    <?php if($this->Autorisation->authorized(array('4', '5', '6', '7'), $this->Session->read('Droit.liste'))){ ?>
                                    <li <?php if($this->params['controller'] == "registres"){echo "class='active'";} ?>><?php echo $this->Html->link('Registre', array('plugin'=> '', 'controller' => 'registres', 'action' => 'index'));?></li><?php } ?>
                                    
                                    <?php if($this->Autorisation->authorized(array('8', '9', '10'), $droits)){ ?>
                                    <li <?php if($this->params['controller'] == "users"){echo "class='active'";} ?>><?php echo $this->Html->link('Gestion des utilisateurs', array('plugin'=> '', 'controller' => 'users', 'action' => 'index'));?></li> <?php } ?>

                                    <?php if($this->Autorisation->authorized(array('11', '12'), $droits)){ ?>
                                    <li <?php if($this->params['controller'] == "organisations"){echo "class='active'";} ?>><?php echo $this->Html->link('Gestion des organisations', array('plugin'=> '', 'controller' => 'organisations', 'action' => 'index'));?></li>
                                            <?php } ?>
                        
                                    <?php if($this->Autorisation->authorized(array('13', '14', '15'), $droits)){ ?>
                                    <li <?php if($this->params['controller'] == "roles"){echo "class='active'";} ?>><?php echo $this->Html->link('Gestion des rôles', array('plugin'=> '', 'controller' => 'roles', 'action' => 'index'));?></li>
                                    <?php } ?>
                                </ul>
                                <ul class="nav navbar-nav pull-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $prenom." ".$nom." - ".$this->Session->read('Organisation.raisonsociale'); ?> <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><?php echo $this->Html->link('Mon pannel', array('controller' => 'pannel', 'action' => 'index'));?></li>
                                            <li class="divider"></li>
                                            <li class="dropdown-header">Votre Compte</li> 
                                            <li><?php echo $this->Html->link('Modifier mon compte', array('controller' => 'users', 'action' => 'edit', $userId));?></li>
                                            <li><?php echo $this->Html->link('Déconnexion', array('controller' => 'users', 'action' => 'logout'));?></li>
                                            <li class="divider"></li>
                                            <li class="dropdown-header">Vos organisations</li>
                                            <?php
                                            foreach($organisations as $datas){
                                                ?>
                                                <li><?php echo $this->Html->link($datas['Organisation']['raisonsociale'], array('controller' => 'organisations', 'action' => 'change', $datas['Organisation']['id']));?></li>
                                            <?php
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

            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content');?>

        </div>

        <div id="footer">

        </div>
    </div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>