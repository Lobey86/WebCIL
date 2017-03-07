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
            echo $this->Html->script('fadeflash.js', ['inline' => FALSE]);
            echo $this->Html->script('bootstrap-filestyle.min.js', ['inline' => FALSE]);
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
                    <?php echo $this->Session->flash();
                        echo '<div class="col-md-12 text-center" style="margin-bottom: 55px;">' . $this->Html->image('logo_WebCil.png', ['class' => 'text-center']) . '</div>';
                        echo $this->fetch('content'); ?>
                </div>
                <div id="footer" class="container-fluid-custom">
                    <div class="text-center versioning">
                        <?php echo "Web-CIL v" . VERSION . " - Libriciel SCOP"; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>