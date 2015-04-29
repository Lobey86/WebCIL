<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Modifier l'utilisateur</h1>
</div>
<div class="users form">
    <?php
    echo $this->Form->create('User', array('autocomplete' => 'off')); ?>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-user"></span>
        </span>
        <?php
        if ( $userid != 1 ) {
            echo $this->Form->input('username', array(
                'class' => 'form-control',
                'placeholder' => 'Nom d\'utilisateur',
                'label' => false,
                'autocomplete' => 'off'
            ));
        }
        else {
            echo $this->Form->input('username', array(
                'class' => 'form-control',
                'placeholder' => 'Nom d\'utilisateur',
                'label' => false,
                "disabled" => "disabled"
            ));
        }
        ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-lock"></span>
        </span>
        <?php echo $this->Form->input('new_password', array(
            'class' => 'form-control',
            'placeholder' => 'Mot de passe',
            'label' => false,
            'type' => 'password',
            'autocomplete' => 'off'
        )); ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-lock"></span>
        </span>
        <?php echo $this->Form->input('new_passwd', array(
            'class' => 'form-control',
            'placeholder' => 'Mot de passe (verification)',
            'label' => false,
            'type' => 'password',
            'autocomplete' => 'off'
        )); ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-user"></span>
        </span>
        <?php
        echo $this->Form->input('nom', array(
            'class' => 'form-control',
            'placeholder' => 'Nom',
            'label' => false
        ));
        ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-user"></span>
        </span>
        <?php
        echo $this->Form->input('prenom', array(
            'class' => 'form-control',
            'placeholder' => 'Prenom',
            'label' => false
        ));
        ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-envelope"></span>
        </span>
        <?php
        echo $this->Form->input('email', array(
            'class' => 'form-control',
            'placeholder' => 'E-mail',
            'label' => false
        ));
        ?>
    </div>
    <?php
    if ( $userid != 1 ) {
        if ( $this->request->data[ 'User' ][ 'id' ] == $this->Session->read('Auth.User.id') ) {
            echo '<div class="sr-only">';
        }
        ?>
        <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
            <?php
            $listeOrganisations = array();
            foreach ( $tableau[ 'Organisation' ] as $key => $datas ) {
                $listeOrganisations[ $datas[ 'infos' ][ 'id' ] ] = $datas[ 'infos' ][ 'raisonsociale' ];
            }
            echo $this->Form->input('Organisation.Organisation_id', array(
                'options' => $listeOrganisations,
                'class' => 'form-control',
                'id' => 'deroulant',
                'label' => false,
                'multiple' => 'multiple',
                'selected' => $tableau[ 'Orgas' ]
            )); ?>
        </div>
        <?php
        foreach ( $tableau[ 'Organisation' ] as $key => $datas ) {
            $listeroles = array();
            echo "<script type='text/javascript'>";

            foreach ( $datas[ 'roles' ] as $clef => $value ) {
                $listeroles[ $value[ 'infos' ][ 'id' ] ] = $value[ 'infos' ][ 'libelle' ];
                echo 'var tableau_js' . $value[ 'infos' ][ 'id' ] . '= new Array();';
                foreach ( $value[ 'droits' ] as $k => $v ) {
                    echo "tableau_js" . $value[ 'infos' ][ 'id' ] . ".push(" . $v[ 'liste_droit_id' ] . ");";
                }
            }
            echo "</script>";
            ?>

            <div class="panel panel-default inputsForm droitsVille" id="droitsVille<?php echo $key; ?>">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $datas[ 'infos' ][ 'raisonsociale' ]; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="input-group login">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-tag"></span>
                        </span>

                        <?php
                        if ( !empty($listeroles) ) {
                            if ( !empty($tableau[ 'UserRoles' ]) ) {
                                echo $this->Form->input('Role.role_ida', array(
                                    'options' => $listeroles,
                                    'class' => 'form-control deroulantRoles' . $key,
                                    'selected' => $tableau[ 'UserRoles' ],
                                    'id' => $key,
                                    'label' => false,
                                    'multiple' => 'multiple'
                                ));
                            }
                            else {
                                echo $this->Form->input('Role.role_ida', array(
                                    'options' => $listeroles,
                                    'class' => 'form-control deroulantRoles' . $key,
                                    'id' => $key,
                                    'label' => false,
                                    'multiple' => 'multiple'
                                ));
                            }
                        }
                        else {
                            echo "Aucun rôle n'a été créé pour cette organisation";
                        }
                        ?>
                    </div>
                    <div class="role form droitsParticuliers" id="droitsParticuliers<?php echo $key; ?>">
                        <?php
                        foreach ( $listedroits as $clef => $value ) {
                            if ( $this->Controls->inArray($tableau[ 'User' ][ $key ], $clef) ) {
                                echo $this->Form->input('Droits.' . $key . '.' . $clef, array(
                                    'type' => 'checkbox',
                                    'label' => $value,
                                    'class' => 'checkDroits' . $key . $clef,
                                    'checked' => 'checked'
                                ));
                            }
                            else {
                                echo $this->Form->input('Droits.' . $key . '.' . $clef, array(
                                    'type' => 'checkbox',
                                    'label' => $value,
                                    'class' => 'checkDroits' . $key . $clef
                                ));
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
        if ( $this->request->data[ 'User' ][ 'id' ] == $this->Session->read('Auth.User.id') ) {
            echo '</div>';
        }
    }

    echo $this->Html->link('Annuler', array(
        'controller' => 'users',
        'action' => 'index'
    ), array('class' => 'btn btn-danger pull-right sender'), 'Voulez-vous vraiment quitter cette page?');
    echo $this->Form->submit('Enregistrer', array('class' => 'btn btn-primary pull-right sender'));
    ?>
</div>
<?php
echo $this->Html->script('users.js');
?>