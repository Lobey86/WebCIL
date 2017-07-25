<?php
	echo $this->Html->script('users.js');
	$actionAdminIndex = 'admin_index' === $this->request->params['action'];

	$this->set(
		'title',
		false === $actionAdminIndex
			? __d('user', 'user.titreListeUser')
			: __d('user', 'user.titreUsersApplication')
	);
	$pagination = null;

	// Filtrer les utilisateur
	// Bouton du filtre des utilisateurs
	echo $this->Form->button(
		'<span class="fa fa-filter fa-lg"></span> Filtrer les utilisateurs',
		[
			'type' => 'button',
			'class' => 'btn btn-default-default pull-right',
			'id' => 'filtrageUsers'
		]
	);

	$filters = $this->request->data;
	unset($filters['sort'], $filters['direction'], $filters['page']);
?>
<div id="filtreUsers" <?php if(true === empty($filters)) {echo 'style="display: none;"';}?>>
	<div class="row">
		<?php
		echo $this->Form->create('users', [
			'controller' => $this->request->params['controller'],
			'action' => $this->request->params['action']
		]);

		// Filtrer par organisation
		if( true === $actionAdminIndex ) {
			echo $this->Form->input( 'organisation', [
				'empty' => __d( 'user', 'user.placeholderChoisirOrganisation' ),
				'class' => 'usersDeroulant transformSelect form-control',
				'label' => 'Filtrer par organisation',
				'options' => $options['organisations'],
				'before' => '<div class="col-md-4 col-md-offset-1">',
				'after' => '</div>'
			] );
		}

	// Filtrer par nom complet
		echo $this->Form->input('nom', [
			'empty' => 'Chercher par utilisateur',
			'class' => 'usersDeroulant transformSelect form-control',
			'label' => 'Nom complet',
			'options' => $options['users'],
			'before' => '<div class="col-md-4 col-md-offset-2">',
			'after' => '</div>'
		]);

		// Filtrer par profil
		if( false === $actionAdminIndex ) {
			echo $this->Form->input( 'profil', [
				'empty' => 'Chercher par profil',
				'class' => 'usersDeroulant transformSelect form-control',
				'label' => 'Profil',
				'options' => $options['roles'],
				'before' => '<div class="col-md-4 col-md-offset-2">',
				'after' => '</div>'
			] );
		}
	?>
	</div>

	<!-- Groupe de bouton -->
	<div class="row top30">
		<div class="col-md-4 col-md-offset-5 btn-group">
			<?php
		// Bouton Réinitialiser le filtre
		echo $this->Html->link('<i class="fa fa-undo fa-lg"></i>' . __d('user','user.btnReinitialiserFiltre'), [
			'controller' => $this->request->params['controller'],
			'action' => $this->request->params['action']
				], [
			'class' => 'btn btn-default-danger',
			'escape' => false,
		]);

		// Bouton Appliquer les filtres
		echo $this->Form->button('<i class="fa fa-filter fa-lg"></i>' . __d('user','user.btnFiltrer'), [
			'type' => 'submit',
			'class' => 'btn btn-default-success'
		]);
			?>
		</div>
	</div>
	<?php echo $this->Form->end();?>
	</div>
	<?php
	// Résultats
	if(false === empty($results)) {
		$this->Paginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '.' ) )
		);
		$pagination = $this->element('pagination');

		echo $pagination;
		echo '<table class="table users table-striped">';
		echo '<thead>';
		echo $this->Html->tableHeaders(
			array_filter(
				[
					[$this->Paginator->sort('User.nom_complet_court', __d('user', 'user.titreTableauUtilisateur')) => ['class' => 'col-md-2', 'colspan' => 2]],
					[$this->Paginator->sort('User.username', __d('user', 'user.titreTableauIdentifiant')) => ['class' => 'col-md-2']],
					[__d('user', 'user.titreTableauEntite') => ['class' => 'col-md-2']],
					[__d('user', 'user.titreTableauProfil') => ['class' => 'col-md-2']],
					false === $hasService ? null : [__d('user', 'user.titreTableauService') => ['class' => 'col-md-2']],
					[__d('user', 'user.titreTableauAction') => ['class' => 'col-md-2']]
				]
			)
		);
		echo '</thead>';
		echo '<tbody>';
		foreach($results as $idx => $result) {
			$organisations = (array)Hash::get($result, 'Organisation');
			$count = count($organisations);
			$rows = 1 < $count ? $count : 1;
			$trOptions = ['class' => 0 === ($idx+1)%2 ? 'even' : 'odd'];

			// Si l'utilisateur est CIL de l'organisation, on affiche le logo
			$isCil = Hash::get($result, 'Organisation.0.OrganisationUser.is_cil');
			$image = null;
			if (true === $isCil && file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')){
				$image = $this->Html->image('logos' . DS . 'logo_cil.jpg', ['class' => 'logo-well']);
			}

			// Actions
			$actions = '';
			if ($this->Autorisation->authorized(9, $droits)) {
				//Bouton de modification
				$actions .= $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', [
					'controller' => 'users',
					'action' => 'edit',
					$result['User']['id']
						], [
					'class' => 'btn btn-default-default btn-sm my-tooltip',
					'title' => __d('user', 'user.commentaireModifierUser'),
					'escapeTitle' => false
				]);
				//Bouton de suppression
				if ($this->Session->read('Auth.User.id') != $result['User']['id']){
					if ($this->Autorisation->authorized(10, $droits)) {
						if ($result['User']['id'] != 1) {
							//Bouton de suppression
							$actions .= $this->Html->link('<span class="fa fa-trash fa-lg"></span>', [
								'controller' => 'users',
								'action' => 'delete',
								$result['User']['id']
									], [
								'class' => 'btn btn-default-danger btn-sm my-tooltip',
								'title' => __d('user', 'user.commentaireSupprimerUser'),
								'escapeTitle' => false
									], __d('user', 'user.confirmationSupprimerUser') . $result['User']['prenom'] . ' ' . $result['User']['nom'] . ' ?');
						} else {
							$actions .= $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
								'controller' => 'users',
								'action' => 'delete',
								$result['User']['id']
									], [
								'class' => 'btn btn-default-danger btn-sm my-tooltip',
								'escapeTitle' => false,
								'title' => __d('user', 'user.commentaireSupprimerUser'),
								"disabled" => "disabled"
									], __d('user', 'user.confirmationSupprimerUser') . $result['User']['prenom'] . ' ' . $result['User']['nom'] . ' ?');
						}
					}
				}
			}

			$services = Hash::extract($result, 'Organisation.0.OrganisationUser.Service.{n}.libelle');
			$services = true === empty($services)
				? $this->Html->tag('strong', 'Aucun service')
				: '<ul><li>'.implode('</li><li>', $services).'</li></ul>';

			$entites = Hash::extract($result, 'Organisation.{n}.raisonsociale');
			$entite = true === empty($entites)
				? $this->Html->tag('strong', __d('user', 'user.textTableauAucuneEntite'))
				: Hash::get($result, 'Organisation.0.raisonsociale');

			echo $this->Html->tableCells(
				[
					array_filter(
						[
							[$image, ['class' => 'tdleft']],
							[$result['User']['nom_complet'], ['rowspan' => $rows, 'class' => 'tdleft']],
							[$result['User']['username'], ['rowspan' => $rows, 'class' => 'tdleft']],
							[$entite, ['class' => 'tdleft']],
							[Hash::get($result, 'Organisation.0.OrganisationUser.Role.libelle'), ['class' => 'tdleft']],
							false === $hasService ? null : [$services, ['class' => 'tdleft']],
							[$this->Html->tag('div', $actions, ['class' => 'btn-group']), ['rowspan' => $rows, 'class' => 'tdleft']]
						]
					)
				],
				$trOptions,
				$trOptions
			);


			if(1 < $rows) {
				for($i=1;$i<$rows;$i++) {
					// Si l'utilisateur est CIL de l'organisation, on affiche le logo
					$isCil = Hash::get($result, "Organisation.{$i}.OrganisationUser.is_cil");
					$image = null;
					if (true === $isCil && file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')){
						$image = $this->Html->image('logos' . DS . 'logo_cil.jpg', ['class' => 'logo-well']);
					}

					$services = Hash::extract($result, "Organisation.{$i}.OrganisationUser.Service.{n}.libelle");
					$services = true === empty($services)
						? $this->Html->tag('strong', 'Aucun service')
						: '<ul><li>'.implode('</li><li>', $services).'</li></ul>';

					echo $this->Html->tableCells(
						[
							array_filter(
								[
									[$image, ['class' => 'tdleft']],
									[Hash::get($result, "Organisation.{$i}.raisonsociale"), ['class' => 'tdleft']],
									[Hash::get($result, "Organisation.{$i}.OrganisationUser.Role.libelle"), ['class' => 'tdleft']],
									false === $hasService ? null : [$services, ['class' => 'tdleft']],
								]
							)
						],
						$trOptions,
						$trOptions
					);

				}
			}
		}
		echo '</tbody>';
		echo '</table>';
	} else {?>
		<div class='text-center'>
			<h3>
				<?php echo __d('user', 'user.textAucunUserCollectiviter'); ?>
			</h3>
		</div>
	<?php } ?>
<!-- Ajout d'un nouveau utilisateur en fonction des droits de l'utilisateur connecté pour la création -->
<?php if ($this->Autorisation->authorized(8, $droits)): ?>
<div class="row text-center">
	<?php
	//Bouton " + Ajouter un utilisateur
	echo $this->Html->link('<span class="fa fa-plus-circle fa-lg"></span>' . __d('user', 'user.btnAjouterUser'), [
		'controller' => 'users',
		'action' => 'add'
			], [
		'class' => 'btn btn-default-primary sender',
		'escapeTitle' => false
	]);
	?>
</div>
<?php endif;?>
<?php echo $pagination;?>