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
    public $components = array(
		'Droits',
		'Session'
	);

	/**
	 * Retourne (un querydata donnant) la liste des entités auxquelles
	 * l'utilisateur connecté a accès.
	 *
	 * Si un ou plusieurs droits sont spécifiés, on vérifiera en plus que
	 * l'utilisateur a bien ce droit dans les entités concernées.
	 *
	 * @param string $type query ou une des valeurs de find de CakePHP
	 * @param integer|array $droits Un ou plusieurs droits à vérifier
	 * @return array
	 */
	public function organisations($type = 'all', $droits = null) {
		$controller = $this->_Collection->getController();
		$droits = (array)$droits;

		if(false === isset($controller->User)) {
			$controller->loadModel('User');
		}

		$query = ['conditions' => []];

		if(false === $this->Droits->isSu()) {
			if(false === empty($droits)) {
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
						'OrganisationUser.user_id' => $this->Session->read('Auth.User.id'),
						'ListeDroit.value' => $droits
					]
				];

				$sql = $controller->User->OrganisationUser->sql(words_replace($subQuery, $aliases));
				$query['conditions'][] = "EXISTS( {$sql} )";
			} else {
				// Limitation au niveau de mes entités
				$subQuery = [
					'alias' => 'organisations_users',
					'fields' => ['organisations_users.id'],
					'conditions' => [
						'organisations_users.organisation_id = Organisation.id',
						'organisations_users.user_id' => $this->Session->read('Auth.User.id')
					]
				];
				$sql = $controller->User->OrganisationUser->sql($subQuery);
				$query['conditions'][] = "EXISTS( {$sql} )";
			}
		}

		if('query' === $type) {
			return $query;
		} else {
			return $controller->User->OrganisationUser->Organisation->find($type, $query);
		}
	}
}

