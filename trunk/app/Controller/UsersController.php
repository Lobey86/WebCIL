<?php

/**
 * UsersController
 *
 * WebCIL : Outil de gestion du Correspondant Informatique et Libertés.
 * Cet outil consiste à accompagner le CIL dans sa gestion des déclarations via
 * le registre. Le registre est sous la responsabilité du CIL qui doit en
 * assurer la communication à toute personne qui en fait la demande (art. 48 du décret octobre 2005).
 *
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webcil/
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     App.Controller
 */
App::uses('ListeDroit', 'Model');

class UsersController extends AppController {

    public $uses = [
        'User',
        'Organisation',
        'Role',
        'ListeDroit',
        'OrganisationUser',
        'Droit',
        'RoleDroit',
        'OrganisationUserRole',
        'Service',
        'OrganisationUserService',
        'Admin',
        'AuthComponent'
    ];
    public $helpers = [
        'Controls'
    ];

    /**
     * Liste des utilisateurs (Liste des utilisateurs de l'application)
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
	public function index() {
        if ('admin_index' === $this->request->params['action'] && true !== $this->Droits->isSu()) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        } else if ('index' === $this->request->params['action'] && true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

		$conditions = [];
		if('admin_index' !== $this->request->params['action']) {
			$conditions = [
				'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
			];
		}

        $query = [
			'fields' => array_merge(
				$this->User->fields(),
				['User.nom_complet']
			),
			'joins' => [
				$this->User->join('OrganisationUser', ['type' => 'LEFT OUTER'])
			],
            'contain' => [
				'Organisation' => [
					'fields' => [
						'Organisation.raisonsociale',
						'("OrganisationUser"."id" IS NOT NULL AND "Organisation"."cil" IS NOT NULL AND "Organisation"."cil" = "OrganisationUser"."user_id") AS "OrganisationUser__is_cil"',
					],
					'order' => ['Organisation.raisonsociale ASC']
				]
			],
			'conditions' => $conditions,
			'group' => $this->User->fields(),
			'order' => 'User.nom_complet_court ASC',
			'limit' => 20
        ];

		// "Transformation" des paramètres nommés en request params pour le filtre et la pagination
		$named = Hash::expand((array)Hash::get($this->request->params, 'named'));
		if(false === empty($named)) {
			$this->request->data = $named;
		}

		// Conditions venant implicitement de l'action, de l'utilisateur connecté et des filtres
		if ($this->request->is('post') || false === empty($named)) {
			// Filtre par entité
			$organisation_id = (string)Hash::get($this->request->data, 'users.organisation');
			if ('' !== $organisation_id) {
				$query['conditions']['OrganisationUser.organisation_id'] = $organisation_id;
			}

			// Filtre par CIL
			$cil = (string)Hash::get($this->request->data, 'users.cil');
			if ('' !== $cil) {
				$subQuery = [
					'alias' => 'organisations',
					'fields' => ['organisations.id'],
					'conditions' => [
						'organisations.cil = User.id',
						'organisations.id' => array_keys(
							$this->WebcilUsers->organisations(
								'list',
								['restrict' => 'index' === $this->request->params['action']]
							)
						)
					]
				];
				$sql = $this->User->Organisation->sql($subQuery);
				$query['conditions'][] = '1' === $cil ? "EXISTS ({$sql})" : "NOT EXISTS ({$sql})";
			}

			// Filtre par utilisateur
			$user_id = (string)Hash::get($this->request->data, 'users.nom');
			if ('' !== $user_id) {
				$query['conditions']['User.id'] = $user_id;
			}

			// Filtre par identifiant
			$username = trim((string)Hash::get($this->request->data, 'users.username'));
			if ('' !== $username) {
				$query['conditions']['UPPER(User.username) LIKE'] = mb_convert_case(str_replace('*', '%', $username), MB_CASE_UPPER);
			}

			// Filtre par service
			$service = (string)Hash::get($this->request->data, 'users.service');
			if ('' !== $service) {
				$subQuery = [
					'alias' => 'organisation_user_services',
					'fields' => ['organisation_user_services.organisation_user_id'],
					'joins' => [
						words_replace(
							$this->User->OrganisationUser->OrganisationUserService->join('Service', ['type' => 'INNER']),
							['Service' => 'services', 'OrganisationUserService' => 'organisation_user_services']
						)
					],
					'conditions' => [
						'services.libelle' => $service
					]
				];
				$sql = $this->User->OrganisationUser->OrganisationUserService->sql($subQuery);
				$query['conditions'][] = "OrganisationUser.id IN ({$sql})";
			}

			// Filtre par profil
			$profil = (string)Hash::get($this->request->data, 'users.profil');
			if ('' !== $profil) {
				$subQuery = [
					'alias' => 'organisation_user_roles',
					'fields' => ['organisation_user_roles.organisation_user_id'],
					'joins' => [
						words_replace(
							$this->User->OrganisationUserRole->join('Role', ['type' => 'INNER']),
							['Role' => 'roles', 'OrganisationUserRole' => 'organisation_user_roles']
						)
					],
					'conditions' => [
						'roles.libelle' => $profil
					]
				];
				$sql = $this->User->OrganisationUserRole->sql($subQuery);
				$query['conditions'][] = "OrganisationUser.id IN ({$sql})";
			}
		}

        if(false === $this->Droits->isSu() && 'admin_index' !== $this->request->params['action']) {
            $query['conditions'][] = ['User.id <>' => 1];
        }

		$this->paginate = $query;
		$results = $this->paginate('User');

		foreach($results as $resultIdx => $result) {
			if(true === Hash::check($result, 'Organisation.{n}.OrganisationUser')) {
				foreach($result['Organisation'] as $orgIdx => $organisation) {
					// Roles
					$query = [
						'fields' => [
							'Role.libelle'
						],
						'contain' => [
							'Role'
						],
						'conditions' => [
							'OrganisationUserRole.organisation_user_id' => $organisation['OrganisationUser']['id']
						],
						'order' => ['Role.libelle']
					];
					$role = $this->User->OrganisationUser->OrganisationUserRole->find('first', $query);
					$results[$resultIdx]['Organisation'][$orgIdx]['OrganisationUser']['Role'] = Hash::get($role, 'Role');

					// Services
					$query = [
						'fields' => [
							'Service.libelle'
						],
						'contain' => [
							'Service'
						],
						'conditions' => [
							'OrganisationUserService.organisation_user_id' => $organisation['OrganisationUser']['id']
						],
						'order' => ['Service.libelle']
					];
					$services = $this->User->OrganisationUser->OrganisationUserService->find('all', $query);
					$results[$resultIdx]['Organisation'][$orgIdx]['OrganisationUser']['Service'] = Hash::extract($services, '{n}.Service');
				}
			}
		}

		// Possède-t-on au moins un service ?
		$hasService = [] !== $this->Service->find('first', ['fields' => ['id']]);

		// Options
		$restrict = 'index' === $this->request->params['action'] ? true : false;
		$options = [
			'organisations' => $this->WebcilUsers->organisations( 'list', [ 'restrict' => $restrict ] ),
			'roles' => $this->WebcilUsers->roles( 'list', [ 'restrict' => $restrict ] ),
			'services' => $this->WebcilUsers->services( 'list', [ 'restrict' => $restrict ] ),
			'users' => $this->WebcilUsers->users( 'list', [ 'restrict' => $restrict ] ),
			'cil' => [ 0 => 'Non', 1 => 'Oui' ]
		];

		$this->set(compact('results', 'hasService', 'options'));
		$this->view = 'index';
	}

    /**
     * Affiche les informations sur un utilisateur
     *
     * @param int|null $id
     * @throws NotFoundException
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function view($id = null) {
        $this->set('title', 'Voir l\'utilisateur');

        if ($this->Droits->authorized([
                    ListeDroit::CREER_UTILISATEUR,
                    ListeDroit::MODIFIER_UTILISATEUR,
                    ListeDroit::SUPPRIMER_UTILISATEUR
                ])
        ) {
            $this->User->id = $id;

            if (!$this->User->exists()) {
                throw new NotFoundException('User invalide');
            }

            $this->set('user', $this->User->read(null, $id));
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');

            $this->redirect($this->Referers->get());
        }
    }

    /**
     * Affiche le formulaire d'ajout d'utilisateur, ou enregistre l'utilisateur et ses droits
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function add() {
        return $this->edit();
    }

    /**
     * Modification d'un utilisateur en tant qu'administrateur
     *
     * @param int $id
     * @throws NotFoundException
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function edit($id = null) {
        if (true !== ($this->Droits->authorized(ListeDroit::CREER_UTILISATEUR) || $this->Droits->isSu())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

		$mesOrganisations = $this->WebcilUsers->organisations(
			'list',
			[
				'droits' => 'add' === $this->request->params['action']
					? ListeDroit::CREER_UTILISATEUR
					: ListeDroit::MODIFIER_UTILISATEUR
			]
		);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ('Cancel' === Hash::get($this->request->data, 'submit')) {
                $this->redirect($this->Referers->get());
            }

            // Tentative de sauvegarde
            $this->User->begin();
            $success = true;

			// Travail préparatoire dans le cas d'une modification
            if('edit' === $this->request->params['action']) {
                $this->request->data['User']['id'] = $id;

                $password = (string)Hash::get($this->request->data, 'User.password');
                $passwd = (string)Hash::get($this->request->data, 'User.passwd');
                if('' === $password && $password === $passwd) {
                    unset($this->request->data['User']['password'], $this->request->data['User']['passwd']);
                }

				// On n'opère que sur les organisations que je peux voir
				$conditions = [
					'user_id' => $id,
					'organisation_id' => array_keys($mesOrganisations)
				];
                $success = $this->User->OrganisationUser->deleteAll($conditions) && $success;
            }

            $this->User->create($this->request->data);
            $success = $this->User->save() && $success;

			// On n'opère que sur les organisations que je peux voir
			$organisations = array_intersect(
				array_filter((array)Hash::get($this->request->data, 'User.organisation_id')),
				array_keys($mesOrganisations)
			);

            if(true === empty($organisations)) {
                $this->User->invalidate('organisation_id', __d('database', 'Validate::notEmpty'));
                $success = false;
            }

            foreach($organisations as $organisation_id) {
                // Organisation
                $record = [
                    'OrganisationUser' => [
                        'organisation_id' => $organisation_id,
                        'user_id' => $this->User->id
                    ]
                ];

                $this->User->OrganisationUser->create($record);
                $success = $this->User->OrganisationUser->save() && $success;

                // Services
                $services = array_filter((array)Hash::get($this->request->data, "User.{$organisation_id}.service_id"));
                foreach($services as $service_id) {
                    $record = [
                        'OrganisationUserService' => [
                            'organisation_user_id' => $this->User->OrganisationUser->id,
                            'service_id' => $service_id
                        ]
                    ];
                    $this->User->OrganisationUser->OrganisationUserService->create($record);
                    $success = $this->User->OrganisationUser->OrganisationUserService->save() && $success;
                }

                // Role
                $role_id = Hash::get($this->request->data, "User.{$organisation_id}.role_id");
                $record = [
                    'OrganisationUserRole' => [
                        'organisation_user_id' => $this->User->OrganisationUser->id,
                        'role_id' => $role_id
                    ]
                ];

                if(true === empty($role_id)) {
                    $success = false;
                }
                $this->User->OrganisationUser->OrganisationUserRole->create($record);
                $success = $this->User->OrganisationUser->OrganisationUserRole->save() && $success;

                // Droits
                $query = [
                    'conditions' => [
                        'RoleDroit.role_id' => $role_id
                    ]
                ];
                $droits = $this->RoleDroit->find('all', $query);

                foreach($droits as $droit) {
                    $record = [
                        'Droit' => [
                            'organisation_user_id' => $this->User->OrganisationUser->id,
                            'liste_droit_id' => Hash::get($droit, 'RoleDroit.liste_droit_id')
                        ]
                    ];

                    $this->Droit->create($record);
                    $success = false !== $this->Droit->save() && $success;
                }
            }

            if (true === $success) {
                $this->User->commit();
                $this->Session->setFlash(__d('user', 'user.flashsuccessUserEnregistrer'), 'flashsuccess');

                $this->redirect($this->Referers->get());
            } else {
                $this->User->rollback();
                $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');
            }
        } else if('edit' === $this->request->params['action']) {
            $query = [
                'conditions' => [
                    'User.id' => $id
                ]
            ];
            $user = $this->User->find('first', $query);

            if(true === empty($user)) {
                throw new NotFoundException();
            }
            unset($user['User']['password']);
            $user['User']['organisation_id'] = [];

            $query = [
                'contain' => [
                    'OrganisationUserRole',
                    'OrganisationUserService'
                ],
                'conditions' => [
                    'OrganisationUser.user_id' => $id
                ]
            ];
            $records = $this->User->OrganisationUser->find('all', $query);

            foreach($records as $record) {
                $organisation_id = Hash::get($record, 'OrganisationUser.organisation_id');
                $user['User']['organisation_id'][] = $organisation_id;
                if(false === isset($user['User'][$organisation_id]['service_id'])) {
                    $user['User'][$organisation_id]['service_id'] = [];
                }
                $user['User'][$organisation_id]['service_id'][] = Hash::get($record, 'OrganisationUserService.service_id');
                if(false === isset($user['User'][$organisation_id]['role_id'])) {
                    $user['User'][$organisation_id]['role_id'] = [];
                }
                $user['User'][$organisation_id]['role_id'] = array_merge(
                    $user['User'][$organisation_id]['role_id'],
                    Hash::extract($record, 'OrganisationUserRole.{n}.role_id')
                )   ;
            }

            $this->request->data = $user;
        }

        $options = array_merge(
            $this->User->enums(),
            [
                'organisation_id' => $mesOrganisations,
                'service_id' => $this->WebcilUsers->services('list', ['fields' => ['id', 'libelle', 'organisation_id']]),
                'role_id' => $this->WebcilUsers->roles('list', ['fields' => ['id', 'libelle', 'organisation_id']])
            ]
        );

        $this->set(compact('options'));
        $this->view = 'edit';
    }

    /**
     * Modification du mot de passe par un utilisateur connecté
     *
     * @param int $id
     * @throws NotFoundException
     *
     * @access public
     * @created 03/02/2016
     * @version V1.0.0
     */
    public function changepassword($id) {
        $this->set('title', __d('user', 'user.titreModificationInfoUser'));

        if ($id != $this->Auth->user('id')) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->User->id = $id;

        if (!$this->User->exists()) {
            throw new NotFoundException('User Invalide');
        }

        $infoUser = $this->User->find('first', [
            'conditions' => ['id' => $id]
        ]);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ('Cancel' === Hash::get($this->request->data, 'submit')) {
                $this->redirect($this->Referers->get());
            }

            $success = true;
            $this->User->begin();

            $message = __d('user', 'user.flasherrorErreurEnregistrementUser');

            /**
             * Si l'ancien mot de passe est vide on enregistre les
             * nouvelle valeur.
             * Sinon on vérifie que le mot de passe soit égale au mot de passe
             * présent en base de données, si cela correspond on vérifie à la
             * suite que le nouveau mot de passe soit égale à la vérification
             * du nouveau mot de passe.
             */
            if ($this->request->data['User']['old_password'] != "") {
                if (AuthComponent::password($this->request->data['User']['old_password']) == $infoUser['User']['password']) {
                    if ($this->request->data['User']['new_password'] != "") {
                        if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_passwd']) {
                            if ($this->request->data['User']['new_password'] != "") {
                                $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                            }
                        } else {
                            $success = false;
                            $message = __d('user', 'user.flasherrorErreurNewPassword');
                        }
                    } else {
                        $success = false;
                        $message = __d('user', 'user.flasherrorNewPasswordVide');
                    }
                } else {
                    $success = false;
                    $message = __d('user', 'user.flasherrorPasswordInvalide');
                }
            }

            if ($success == true) {
                $success = false !== $this->User->save($this->request->data) && $success;
            }

            if ($success == true) {
                $this->User->commit();
                $this->Session->setFlash(__d('user', 'user.flashsuccessUserEnregistrerReconnecter'), "flashsuccess");

                $this->redirect([
                    'controller' => 'users',
                    'action' => 'logout'
                ]);
            } else {
                $this->User->rollback();
                $this->Session->setFlash($message, "flasherror");
            }
        } else {
            $table = $this->_createTable($id);
            $this->set('tableau', $table['tableau']);
        }

        $this->set('options', $this->User->enums());
    }

    /**
     * Suppression d'un utilisateur
     *
     * @param int|null $id
     * @throws NotFoundException
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function delete($id = null) {
        if ($this->Session->read('Auth.User.id') != $id) {
            if ($this->Droits->authorized(ListeDroit::SUPPRIMER_UTILISATEUR)) {
                $this->User->id = $id;

                if (!$this->User->exists()) {
                    throw new NotFoundException('User invalide');
                }

                if ($id != 1) {
                    $success = true;
                    $this->User->begin();

                    $success = $success && $this->OrganisationUser->deleteAll(['user_id' => $id]);

                    if ($success == true) {
                        if ($this->Droits->isCil()) {
                            $success = $success && $this->Organisation->updateAll([
                                        'Organisation.cil' => null
                                            ], [
                                        'Organisation.cil' => $id
                                            ]
                                    ) !== false;
                        }

                        if ($success == true) {
                            $success = $success && $this->User->delete();
                        }
                    }

                    if ($success == true) {
                        $this->User->commit();
                        $this->Session->setFlash(__d('user', 'user.flashsuccessUserSupprimer'), 'flashsuccess');
                    } else {
                        $this->User->rollback();
                        $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
                    }

                    $this->redirect($this->Referers->get());
                }

                $this->Session->setFlash(__d('user', 'user.flasherrorErreurSupprimerUser'), 'flasherror');
                $this->redirect($this->Referers->get());
            } else {
                $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
                $this->redirect($this->Referers->get());
            }
        } else {
            $this->Session->setFlash(__d('user', 'user.flasherrorErreurSuppressionImpossibleUser'), 'flasherror');
            $this->redirect($this->referer());
        }
    }

    /**
     * Page de login
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function login() {
//        $hashpass = AuthComponent::password("theog");
//        debug($hashpass);
//        die;

        $this->layout = 'login';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->_cleanSession();

                $su = $this->Admin->find('count', [
                    'conditions' => [
                        'user_id' => $this->Auth->user('id')
                    ]
                ]);

                if ($su) {
                    $this->Session->write('Su', true);
                } else {
                    $this->Session->write('Su', false);
                }

                $service = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'user_id' => $this->Auth->user('id')
                    ],
                    'contain' => [
                        'OrganisationUserService' => [
                            'Service'
                        ]
                    ]
                ]);

                $serviceUser = Hash::extract($service, '{n}.OrganisationUserService.Service');
                $serviceUser = Hash::combine($serviceUser, '{n}.id', '{n}.libelle');

                $this->Session->write('User.service', $serviceUser);

                $this->redirect([
                    'controller' => 'organisations',
                    'action' => 'change'
                ]);
            } else {
                $this->Session->setFlash(__d('user', 'user.flasherrorNameUserPasswordInvalide'), 'flasherror');
            }
        } else {
            if ($this->Session->check('Auth.User.id')) {
                $this->redirect($this->Referers->get());
            }
        }
    }

    /**
     * Page de deconnexion
     *
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function logout() {
        $this->_cleanSession();
        $this->redirect($this->Auth->logout());
    }

    /**
     * Fonction de suppression du cache (sinon pose des problemes lors du login)
     *
     * @access protected
     * @created 17/06/2015
     * @version V1.0.0
     */
    protected function _cleanSession() {
        $this->Session->delete('Organisation');
    }

    /**
     * Fonction de création du tableau de droits pour le add et edit user
     *
     * @param int|null $id
     * @return type
     *
     * @access protected
     * @created 17/06/2015
     * @version V1.0.0
     */
    protected function _createTable($id = null) {
        $tableau = ['Organisation' => []];

        if ($this->Droits->isSu()) {
            $organisations = $this->Organisation->find('all');
        } else {
            $organisations = $this->Organisation->find('all', [
                'conditions' => [
                    'id' => $this->Session->read('Organisation.id')
                ]
            ]);
        }

        foreach ($organisations as $key => $value) {
            $tableau['Organisation'][$value['Organisation']['id']]['infos'] = [
                'raisonsociale' => $value['Organisation']['raisonsociale'],
                'id' => $value['Organisation']['id']
            ];

            $roles = $this->Role->find('all', [
                'recursive' => -1,
                'conditions' => ['organisation_id' => $value['Organisation']['id']]
            ]);

            $tableau['Organisation'][$value['Organisation']['id']]['roles'] = [];

            foreach ($roles as $clef => $valeur) {
                $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']] = [
                    'infos' => [
                        'id' => $valeur['Role']['id'],
                        'libelle' => $valeur['Role']['libelle'],
                        'organisation_id' => $valeur['Role']['organisation_id']
                    ]
                ];

                $droitsRole = $this->RoleDroit->find('all', [
                    'recursive' => -1,
                    'conditions' => ['role_id' => $valeur['Role']['id']]
                ]);

                foreach ($droitsRole as $k => $val) {
                    $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]['droits'][$val['RoleDroit']['id']] = $val['RoleDroit'];
                }
            }
        }

        if ($id != null) {
            $this->set("userid", $id);

            $organisationUser = $this->OrganisationUser->find('all', [
                'conditions' => [
                    'user_id' => $id
                ],
                'contain' => [
                    'Droit'
                ]
            ]);

            foreach ($organisationUser as $key => $value) {
                $tableau['Orgas'][] = $value['OrganisationUser']['organisation_id'];

                $userroles = $this->OrganisationUserRole->find('all', [
                    'conditions' => [
                        'OrganisationUserRole.organisation_user_id' => $value['OrganisationUser']['id']
                    ]
                ]);

                foreach ($userroles as $cle => $val) {
                    $tableau['UserRoles'][] = $val['OrganisationUserRole']['role_id'];
                }

                foreach ($value['Droit'] as $clef => $valeur) {
                    $tableau['User'][$value['OrganisationUser']['organisation_id']][] = $valeur['liste_droit_id'];
                }

                $servicesUsers = $this->OrganisationUserService->find('all', [
                    'conditions' => [
                        'OrganisationUserService.organisation_user_id' => $value['OrganisationUser']['id']
                    ]
                ]);

                if (!empty($servicesUsers)) {
                    foreach ($servicesUsers as $serviceUser) {
                        $tableau['UserService'][] = $serviceUser['OrganisationUserService']['service_id'];
                    }
                }
            }

            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }

        $listedroits = $this->ListeDroit->find('all', [
            'recursive' => -1
        ]);

        $ld = [];

        foreach ($listedroits as $c => $v) {
            $ld[$v['ListeDroit']['value']] = $v['ListeDroit']['libelle'];
        }

        $retour = [
            'tableau' => $tableau,
            'listedroits' => $ld
        ];

        return $retour;
    }

	/**
	 * Liste des utilisateurs de l'application
	 */
    public function admin_index() {
        $this->index();
    }
}
