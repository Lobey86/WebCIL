<?php
    $title = 'add' === $this->request->params['action']
        ? __d('user', 'user.titreAjouterUser')
        : __d('user', 'user.titreEditerUser');
    $this->set(compact('title'));
?>
<?php if (isset($this->validationErrors['User']) && !empty($this->validationErrors['User'])):?>
	<div class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Ces erreurs se sont produites:
		<ul>
			<?php
			foreach ($this->validationErrors as $donnees) {
				foreach ($donnees as $champ) {
					foreach ($champ as $error) {
						echo '<li>' . $error . '</li>';
					}
				}
			}
			?>
		</ul>
	</div>
<?php endif;?>
<?php
    echo $this->WebcilForm->create('User',[
        'autocomplete' => 'off',
        'inputDefaults' => ['div' => false],
        'class' => 'form-horizontal',
        'novalidate' => 'novalidate'
    ]);

    echo '<div class="users form">';
    echo '<div class="col-md-6">';

    echo $this->WebcilForm->inputs([
        'User.password' => ['id' => false, 'value' => '', 'type' => 'hidden'],
        'User.username' => ['id' => 'username', 'autocomplete' => 'off', 'required' => true]
    ]);

    // Champ caché pour éviter l'autocomplete du navigateur pour le mot de passe
    $password = $this->WebcilForm->inputs([
            'User.password' => ['id' => 'password', 'autocomplete' => 'off', 'required' => true],
            'User.passwd' => ['id' => 'passwd', 'autocomplete' => 'off', 'required' => true],
        ]);

    if('edit' === $this->request->params['action']) {
        echo $this->Html->tag(
            'div',
            __d('user', 'user.textInfoMotDePasse').$password,
            ['class' => 'alert alert-info']
        );
    } else {
        echo $password;
    }

    echo $this->WebcilForm->inputs([
        'User.civilite' => ['id' => 'civilite', 'options' => $options['User']['civilite'], 'empty' => true, 'required' => true, 'placeholder' => false],
        'User.nom' => ['id' => 'nom', 'required' => true],
        'User.prenom' => ['id' => 'prenom', 'required' => true],
        'User.email' => ['id' => 'email', 'required' => true],
        'User.telephonefixe' => ['id' => 'telephonefixe'],
        'User.telephoneportable' => ['id' => 'telephoneportable']
    ]);
    echo '</div>';

    echo '<div class="col-md-6">';
    // Organisations
    echo $this->WebcilForm->input('User.organisation_id', [
        'options' => $options['organisation_id'],
        'class' => 'form-control',
        'id' => 'deroulant',
        'label' => [
            'text' => __d('user', 'user.champEntite') . '<span class="requis">*</span>',
            'class' => 'col-md-4 control-label'
        ],
        'between' => '<div class="col-md-8">',
        'after' => '</div>',
        'multiple' => 'multiple',
        'placeholder' => false
    ]);

    foreach($options['organisation_id'] as $organisation_id => $raisonsociale) {
        echo '<div class="form-group">';
        echo '<fieldset id="organisation-block-'.$organisation_id.'" class="organisation-block">';
        echo $this->Html->tag('legend', $raisonsociale);

        // Services
        $services = (array)Hash::get($options, "service_id.{$organisation_id}");
        if(false === empty($services)) {
            echo $this->WebcilForm->input('User.service_id', [
                'options' => $services,
                'class' => 'form-control',
                'id' => 'service_id'.$organisation_id,
                'name' => "data[User][{$organisation_id}][service_id]",
                'label' => [
                    'text' => __d('user', 'user.champService'),
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'multiple' => 'multiple',
                'empty' => false,
                'placeholder' => false,
                'value' => (array)Hash::get($this->request->data, "User.{$organisation_id}.service_id")
            ]);
        }

        // Rôle
        $value = array_filter((array)Hash::get($this->request->data, "User.{$organisation_id}.role_id"));
        $hasError = ($this->request->is('post') || $this->request->is('put')) && true === empty($value);
        echo $this->WebcilForm->input('User.role_id', [
            'options' => (array)Hash::get($options, "role_id.{$organisation_id}"),
            'class' => 'form-control'.(true === $hasError ? ' form-error' : null),
            'id' => 'role_id'.$organisation_id,
            'name' => "data[User][{$organisation_id}][role_id]",
            'label' => [
                'text' => __d('user', 'user.champProfilEntite') . $raisonsociale . ' <span class="requis">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'empty' => true,
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'placeholder' => false,
            'value' => $value
        ]);
        if(true === $hasError) {
            echo $this->Html->tag('div', __d('database', 'Validate::notEmpty'), ['class' => 'error-message']);
        }
        echo '</fieldset>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';

    echo '<div style="clear: both">';
    echo $this->WebcilForm->buttons( array( 'Cancel', 'Save' ) );
    echo '</div>';

    echo $this->WebcilForm->end();
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#deroulant").select2({
            placeholder: "Sélectionnez une ou plusieurs entités",
            allowClear: true
        });

        <?php $selected = (array)Hash::get($this->request->data, 'User.organisation_id');?>
        <?php foreach(array_keys($options['organisation_id']) as $organisation_id): ?>
            $("#<?php echo 'service_id'.$organisation_id;?>").select2({
                placeholder: "Sélectionnez un ou plusieurs service",
                allowClear: true
            });

            <?php if(false === in_array($organisation_id, $selected)):?>
                $("#organisation-block-<?php echo $organisation_id;?>").hide();
            <?php endif;?>
        <?php endforeach;?>

        $('#deroulant').change(function(event) {
            var select = $(this),
                values = $.map($(select).children('option'),function(option){return option.value;}),
                selected = $(select).val();

            $.each(values, function(index, value){
                if(-1 !== $.inArray(value, selected)) {
                    $("#organisation-block-"+value).show();
                } else {
                    $("#organisation-block-"+value).hide();
                }
            });
        });
    });
</script>