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

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>
            <?php echo $cakeDescription ?>
        </title>
        <base href="/">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ng2-bootstrap/1.6.2/ng2-bootstrap.umd.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <style>
			body {
				margin: 51px 0 0 0;
			}

            /*noinspection CssOptimizeSimilarProperties*/
            .libriciel-background {
                height: calc(101vh - (2 * 51px));
                background-color: #E0EFFA;
                background-image:
                    linear-gradient(-1.2217rad, rgba(255, 255, 255, 0.5) 10%, transparent 10%),
                    linear-gradient(1.2217rad, transparent 85%, rgba(255, 255, 255, 0.5) 85%),
                    linear-gradient(-0.6981rad, rgba(255, 255, 255, 0.5) 15%, transparent 15%),
                    linear-gradient(1.2217rad, rgba(255, 255, 255, 0.5) 20%, transparent 20%),
                    linear-gradient(-0.7853rad, rgba(66, 161, 227, 0.08) 30%, transparent 30%),
                    linear-gradient(0.6981rad, rgba(217, 236, 250, 0.5) 40%, transparent 40%),
                    linear-gradient(1.3962rad, transparent 65%, rgba(255, 255, 255, 0.31) 65%),
                    linear-gradient(-1.0471rad, rgba(217, 236, 250, 0.5) 50%, transparent 50%),
                    linear-gradient(1.0471rad, rgba(255, 255, 255, 0.5) 60%, transparent 60%),
                    linear-gradient(-1.3962rad, rgba(255, 255, 255, 0.5) 70%, transparent 70%),
                    linear-gradient(1.3089rad, rgba(142, 199, 241, 0.5) 80%, transparent 80%),
                    linear-gradient(-1.3089rad, rgba(58, 139, 215, 0.55) 90%, transparent 90%),
                    linear-gradient(-1.3962rad, rgba(255, 255, 255, 0.5) 65%, transparent 70%);
                display: flex;
                align-items: center;
            }

            .login-block {
                border: 1px solid #080808;
                box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
                background-color: white;
                display: flex;
                padding: 0;
                max-width: 650px;
            }

            .collectivity-block {
                background-color: #173A94;
                border-right: solid #080808 1px;
                padding: 30px;
                display: flex;
                align-items: center;
            }

            .collectivity-logo {
                max-height: 100%;
                max-width: 100px;
            }

            .form-block {
                width: 100%;
                padding: 30px 75px 60px 75px;
            }

            .main-logo {
                max-height: 100px;
                max-width: 100%;
            }

            .control-label.normal-left {
                text-align: left;
                font-weight: normal;
                padding-left: 0;
            }

            .color-inverse {
                background-color: #222;
                color: white;
            }

            .buffer-top {
                margin-top: 15px;
            }

            .double-buffer-top {
                margin-top: 30px;
            }

            .navbar-icons-buffer-top {
                padding-top: 12px;
            }

        </style>
    </head>
    <body>

        <!-- Header -->

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#" title="WebCil">
                        <?php
                        echo $this->Html->image('web_cil_white100_h24px_.png');
                        ?>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Content -->

        <div class="libriciel-background">

            <div class="col-xs-10
                 col-sm-7
                 col-md-6
                 col-lg-5
                 center-block login-block">

                <!--<div class="hidden-xs-->
                <!--hidden-sm-->
                <!--col-md-3-->
                <!--col-lg-3-->
                <!--collectivity-block">-->
                <!--<img class="collectivity-logo center-block"-->
                <!--src="https://gitlab.libriciel.fr/outils/charte-graphique/raw/develop/markdown_resources/logo_Montpellier.jpg">-->
                <!--</div>-->

                <?php
                echo $this->Session->flash();
                echo $this->fetch('content');
                ?>
            </div>
        </div>

         Footer

        <footer>
            <div class="navbar navbar-inverse navbar-fixed-bottom">
                <div class="container-fluid">
                    <span class="hidden-xs
                          col-sm-8
                          col-md-4
                          col-lg-4
                          navbar-left navbar-icons-buffer-top"
                          style="padding-left: 15px;">

                        <a data-toggle="tooltip"
                           title="asal@e"
                           target="_blank"
                           href="https://www.libriciel.fr/asalae">
                            <?php
                            echo $this->Html->image('reduced_asalae.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="i-delibRE"
                           target="_blank"
                           href="https://www.libriciel.fr/i-delibre">
                            <?php
                            echo $this->Html->image('i-delibRE_white50_h24px.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="iParapheur"
                           target="_blank"
                           href="https://www.libriciel.fr/i-parapheur">
                            <?php
                            echo $this->Html->image('reduced_iParapheur.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="Pastell"reduced_S2low
                           target="_blank"
                           href="https://www.libriciel.fr/pastell">
                            <?php
                            echo $this->Html->image('reduced_Pastell.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="S2low"
                           target="_blank"
                           href="https://www.libriciel.fr/s2low">
                            <?php
                            echo $this->Html->image('reduced_S2low.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="WebCil"
                           target="_blank"
                           href="https://www.libriciel.fr/web-cil">
                            <?php
                            echo $this->Html->image('web_cil_white100_h24px_.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="WebDelib"
                           target="_blank"
                           href="https://www.libriciel.fr/web-delib">
                            <?php
                            echo $this->Html->image('reduced_WebDelib.png');
                            ?>
                        </a>

                        <a data-toggle="tooltip"
                           title="WebGFC"
                           target="_blank"
                           href="https://www.libriciel.fr/web-gfc">
                            <?php
                            echo $this->Html->image('reduced_WebGFC.png');
                            ?>
                        </a>
                    </span>

                    <span class="hidden-xs
                          hidden-sm
                          col-md-4
                          col-lg-4
                          text-center h5 text-muted"
                          style="padding-top: 6px;">
                <?php
                echo "web-CIL v" . VERSION . " / © Libriciel SCOP 2006-2017 ";
                ?>
                    </span>

                    <span class="col-xs-12
                          col-sm-4
                          col-md-4
                          col-lg-4
                          navbar-right navbar-icons-buffer-top"
                          style="padding-right: 30px;">
                        <a target="_blank" href="https://www.libriciel.fr" class="pull-right">
                             <?php
                            echo $this->Html->image('Libriciel_white_h24px.png');
                            ?>
                        </a>
                    </span>
                </div>
            </div>
        </footer>
    </body>
</html>
