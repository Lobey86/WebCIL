<?php

/**
 * Code source de la classe WebcilUsersComponent.
 *
 * PHP 5.3
 *
 * @package app.Controller.Component
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
App::uses('Component', 'Controller');

/**
 * La classe WebcilUsersComponent ...
 *
 * @package app.Controller.Component
 */
class WebcilUsersComponent extends Component {
    /**
     * Components utilisés par ce component.
     *
     * @var array
     */
    public $components = array('Droits', 'Session');

	/**
	 * Retourne (un querydata donnant) la liste des entités auxquelles
	 * l'utilisateur connecté a accès.
	 *
	 * Si un ou plusieurs droits sont spécifiés, on vérifiera en plus que
	 * l'utilisateur a bien ce droit dans les entités concernées.
	 *
	 * @param string $type "query" ou une des valeurs de find de CakePHP
	 * @param array $params Les clés suivantes sont prises en compte:
	 *	- "restrict": boolean|string, false par défaut
	 *		- "false" pour restreindre à l'ensemble des organisations auxquelles
	 *			l'utilisateur a accès
	 *		- "true" pour restreindre à l'organisation actuellement sélectionnée
	 *			(en session)
	 *	- "droits": integer|array, vide par défaut; voir les constantes définies
	 *		dans le modèle ListeDroit
	 *	- "fields": array, par défaut, tous les champs du modèle Organisation et
	 *		lorsque le type est "list", les valeurs de primaryKey et de displayField
	 *		du modèle Organisation
	 * @return array
	 */
	public function organisations($type = 'all', array $params = []) {
		$controller = $this->_Collection->getController();
		$params += [
			'restrict' => false,
			'droits' => [],
			'fields' => null
		];
		$params['droits'] = (array)$params['droits'];

		if(false === isset($controller->User)) {
			$controller->loadModel('User');
		}

		$query = [
			'fields' => $controller->User->OrganisationUser->Organisation->fields(),
			'conditions' => [],
			'order' => ["Organisation.{$controller->User->OrganisationUser->Organisation->displayField} ASC"]
		];

		if(false === $this->Droits->isSu()) {
			if(false === empty($params['droits'])) {
				// Limitation au niveau des droits dans mes entités
				$aliases = [
					'OrganisationUser' => 'organisations_users',
					'OrganisationUserRole' => 'organisation_user_roles',
					'Role' => 'roles',
					'RoleDroit' => 'role_droits',
					'ListeDroit' => 'liste_droits'
				];

				$subQuery = [
					'alias' => 'OrganisationUser',
					'fields' => ['OrganisationUser.id'],
					'joins' => [
						$controller->User->OrganisationUser->join('OrganisationUserRole', ['type' => 'INNER']),
						$controller->User->OrganisationUser->OrganisationUserRole->join('Role', ['type' => 'INNER']),
						$controller->User->OrganisationUser->OrganisationUserRole->Role->join('RoleDroit', ['type' => 'INNER']),
						$controller->User->OrganisationUser->OrganisationUserRole->Role->RoleDroit->join('ListeDroit', ['type' => 'INNER'])
					],
					'conditions' => [
						'OrganisationUser.organisation_id = Organisation.id',
						'ListeDroit.value' => $params['droits']
					]
				];

				if (true === $params['restrict']) {
					$subQuery['conditions'][] = ['OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')];
				} else {
					$subQuery['conditions'][] = ['OrganisationUser.user_id' => $this->Session->read('Auth.User.id')];
				}

				$sql = $controller->User->OrganisationUser->sql(words_replace($subQuery, $aliases));
				$query['conditions'][] = "EXISTS( {$sql} )";
			} else {
				// Limitation au niveau de mes entités
				$subQuery = [
					'alias' => 'organisations_users',
					'fields' => ['organisations_users.id'],
					'conditions' => [
						'organisations_users.organisation_id = Organisation.id'
					]
				];

				if (true === $params['restrict']) {
					$subQuery['conditions'][] = ['organisations_users.organisation_id' => $this->Session->read('Organisation.id')];
				} else {
					$subQuery['conditions'][] = ['organisations_users.user_id' => $this->Session->read('Auth.User.id')];
				}

				$sql = $controller->User->OrganisationUser->sql($subQuery);
				$query['conditions'][] = "EXISTS( {$sql} )";
			}
		} elseif (true === $params['restrict']) {
			$query['conditions'][] = ['Organisation.id' => $this->Session->read('Organisation.id')];
		}

		if('list' === $type && null === $params['fields']) {
			$query['fields'] = [
				"Organisation.{$controller->User->OrganisationUser->Organisation->primaryKey}",
				"Organisation.{$controller->User->OrganisationUser->Organisation->displayField}"
			];
		} elseif (null !== $params['fields']) {
			$query['fields'] = $params['fields'];
		}

		if('query' === $type) {
			return $query;
		} else {
			return $controller->User->OrganisationUser->Organisation->find($type, $query);
		}
	}

	/**
	 * Retourne (un querydata donnant) la liste des profils auxquels l'utilisateur
	 * connecté a accès en fonction des entités auxquelles il a accès.
	 *
	 * @param string $type "query" ou une des valeurs de find de CakePHP
	 * @param array $params Les clés suivantes sont prises en compte:
	 *	- "restrict": boolean|string, false par défaut
	 *		- "false" pour restreindre à l'ensemble des organisations auxquelles
	 *			l'utilisateur a accès
	 *		- "true" pour restreindre à l'organisation actuellement sélectionnée
	 *			(en session)
	 *	- "fields": array, par défaut, tous les champs des modèles Role et
	 *		Organisation et lorsque le type est "list", les valeurs de displayField,
	 *		uniques, du modèle Role
	 * @return array
	 */
	public function roles( $type = 'all', array $params = [] ) {
		$controller = $this->_Collection->getController();
		$params += [
			'restrict' => false,
			'fields' => null
		];

		if( false === isset( $controller->Role ) ) {
			$controller->loadModel( 'Role' );
		}

		$query = [
			'fields' => array_merge(
				$controller->Role->fields(),
				$controller->Role->Organisation->fields()
			),
			'conditions' => [],
			'joins' => [
				$controller->Role->join('Organisation', ['type' => 'INNER'])
			],
			'order' => ["Role.{$controller->Role->displayField} ASC", "Role.{$controller->Role->primaryKey} ASC"]
		];

		// Limitation à l'entité actuellement sélectionnée
		if( true === $params['restrict'] ) {
			$query['conditions']['Role.organisation_id'] = $this->Session->read( 'Organisation.id' );
		// Limitation au niveau de mes entités (en base de données si je ne suis pas super admin)
		} elseif( false === $this->Droits->isSu() ) {
			$subQuery = [
				'alias' => 'organisations_users',
				'fields' => [ 'organisations_users.id' ],
				'conditions' => [
					'organisations_users.organisation_id = Role.organisation_id',
					'organisations_users.user_id' => $this->Session->read( 'Auth.User.id' )
				]
			];
			$sql = $controller->Role->OrganisationUserRole->OrganisationUser->sql( $subQuery );
			$query['conditions'][] = "EXISTS( {$sql} )";
		}

		if('list' === $type && null === $params['fields']) {
			$query['fields'] = [
				"Role.{$controller->Role->displayField}",
				"Role.{$controller->Role->displayField}"
			];
		} elseif (null !== $params['fields']) {
			$query['fields'] = $params['fields'];
		}

		if('query' === $type) {
			return $query;
		} else {
			return $controller->Role->find( $type, $query );
		}
	}


	/**
	 * Retourne (un querydata donnant) la liste des services auxquels l'utilisateur
	 * connecté a accès en fonction des entités auxquelles il a accès.
	 *
	 * @param string $type "query" ou une des valeurs de find de CakePHP
	 * @param array $params Les clés suivantes sont prises en compte:
	 *	- "restrict": boolean|string, false par défaut
	 *		- "false" pour restreindre à l'ensemble des organisations auxquelles
	 *			l'utilisateur a accès
	 *		- "true" pour restreindre à l'organisation actuellement sélectionnée
	 *			(en session)
	 *	- "fields": array, par défaut, tous les champs du modèle Service et
	 *		lorsque le type est "list", les valeurs de displayField, uniques, du
	 *		modèle Service
	 * @return array
	 */
	public function services( $type = 'all', array $params = [] ) {
		$controller = $this->_Collection->getController();
		$params += [
			'restrict' => false,
			'fields' => null
		];

		if( false === isset( $controller->Service ) ) {
			$controller->loadModel( 'Service' );
		}

		$query = [
			'fields' => array_merge(
				$controller->Service->fields(),
				$controller->Service->Organisation->fields()
			),
			'conditions' => [],
			'joins' => [
				$controller->Service->join('Organisation', ['type' => 'INNER'])
			],
			'order' => ["Service.{$controller->Service->displayField} ASC", "Service.{$controller->Service->primaryKey} ASC"]
		];

		// Limitation à l'entité actuellement sélectionnée
		if( true === $params['restrict'] ) {
			$query['conditions']['Service.organisation_id'] = $this->Session->read( 'Organisation.id' );
		// Limitation au niveau de mes entités (en base de données si je ne suis pas super admin)
		} elseif( false === $this->Droits->isSu() ) {
			$subQuery = [
				'alias' => 'organisations_users',
				'fields' => [ 'organisations_users.id' ],
				'conditions' => [
					'organisations_users.organisation_id = Service.organisation_id',
					'organisations_users.user_id' => $this->Session->read( 'Auth.User.id' )
				]
			];
			$sql = $controller->Service->OrganisationUserService->OrganisationUser->sql( $subQuery );
			$query['conditions'][] = "EXISTS( {$sql} )";
		}

		if('list' === $type && null === $params['fields']) {
			$query['fields'] = [
				"Service.{$controller->Service->displayField}",
				"Service.{$controller->Service->displayField}"
			];
		} elseif (null !== $params['fields']) {
			$query['fields'] = $params['fields'];
		}

		if('query' === $type) {
			return $query;
		} else {
			return $controller->Service->find( $type, $query );
		}
	}

	/**
	 * Retourne (un querydata donnant) la liste des utilisateurs auxquels
	 * l'utilisateur connecté a accès en fonction des entités auxquelles il a
	 * accès.
	 *
	 * @param string $type "query" ou une des valeurs de find de CakePHP
	 * @param array $params Les clés suivantes sont prises en compte:
	 *	- "restrict": boolean|string, false par défaut
	 *		- "false" pour restreindre à l'ensemble des organisations auxquelles
	 *			l'utilisateur a accès
	 *		- "true" pour restreindre à l'organisation actuellement sélectionnée
	 *			(en session)
	 *	- "fields": array, par défaut, tous les champs du modèle User et
	 *		lorsque le type est "list", les valeurs de primaryKey et de
	 *		displayField du modèle User
	 * @return array
	 */
	public function users( $type = 'all', array $params = [] ) {
		$controller = $this->_Collection->getController();
		$params += [
			'restrict' => false,
			'fields' => null
		];

		if( false === isset( $controller->Role ) ) {
			$controller->loadModel( 'User' );
		}

		$query = [
			'fields' => array_merge(
				$controller->User->fields()
			),
			'conditions' => [],
			'order' => ['User.nom_complet_court ASC']
		];

		if('list' === $type && null === $params['fields']) {
			$query['fields'] = [
				'User.id',
				'User.nom_complet'
			];
		} elseif (null !== $params['fields']) {
			$query['fields'] = $params['fields'];
		}

		// Limitation à l'entité actuellement sélectionnée
		if( true === $params['restrict'] ) {
			$subQueryOrganisationUser = [
				'alias' => 'organisations_users',
				'fields' => ['organisations_users.user_id'],
				'conditions' => [
					'organisations_users.user_id = User.id',
					'organisations_users.organisation_id' => $this->Session->read( 'Organisation.id' )
				]
			];
			$sqlOrganisationUser = $controller->User->OrganisationUser->sql($subQueryOrganisationUser);

			$subQueryAdmin = [
				'alias' => 'admins',
				'fields' => [ 'admins.user_id' ],
				'conditions' => [
					'admins.user_id = User.id'
				]
			];
			$sqlAdmin = $controller->User->Admin->sql( $subQueryAdmin );

			$query['conditions'][] = [
				"User.id IN ( {$sqlOrganisationUser} )",
				"User.id NOT IN ( {$sqlAdmin} )"
			];
		// Limitation au niveau de mes entités (en base de données si je ne suis pas super admin)
		} elseif( false === $this->Droits->isSu() ) {
			$subQueryOrganisations = [
				'alias' => 'organisations',
				'fields' => [ 'organisations.organisation_id' ],
				'conditions' => [
					'organisations.user_id' => $this->Session->read( 'Auth.User.id' )
				],
				'group' => [ 'organisations.organisation_id' ]
			];
			$sqlOrganisations = $controller->User->OrganisationUser->sql( $subQueryOrganisations );

			$subQuery = [
				'alias' => 'organisations_users',
				'fields' => [ 'organisations_users.user_id' ],
				'conditions' => [
					"organisations_users.organisation_id IN ( {$sqlOrganisations} )",
					'organisations_users.user_id = User.id'
				]
			];
			$sql = $controller->User->OrganisationUser->sql( $subQuery );
			$query['conditions'][] = "User.id IN ( {$sql} )";
		}

		if('query' === $type) {
			return $query;
		} else {
			return $controller->User->find($type, $query);
		}
	}

}

