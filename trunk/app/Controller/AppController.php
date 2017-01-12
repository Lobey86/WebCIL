<?php

/**
 * Application level Controller
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $uses = [
        'Organisation',
        'Droit',
        'OrganisationUser',
        'Notification',
        'Pannel',
        'Service'
    ];
    public $components = [
        'Session',
        'FormGenerator.FormGen',
        'Droits',
        'Notifications',
        'Auth' => [
            'loginRedirect' => [
                'controller' => 'organisations',
                'action' => 'change'
            ],
            'logoutRedirect' => [
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ]
        ]
    ];

    /**
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function beforeFilter() {
        $locale = Configure::read('Config.language');

        if ($locale && file_exists(APP . 'View' . DS . $locale . DS . $this->viewPath)) {
            $this->viewPath = $locale . DS . $this->viewPath;
        }

        $this->set('referer', $this->referer());
        $this->set('nom', $this->Auth->user('nom'));
        $this->set('prenom', $this->Auth->user('prenom'));
        $this->set('userId', $this->Auth->user('id'));

        if ($this->Droits->isSu()) {
            $this->set('organisations', $this->Organisation->find('all', []));
        } else {
            $this->set('organisations', $this->OrganisationUser->find('all', [
                        'conditions' => [
                            'OrganisationUser.user_id' => $this->Auth->user('id')
                        ],
                        'contain' => [
                            'Organisation'
                        ]
            ]));
        }
        $this->set('droits', $this->Session->read('Droit.liste'));

        $notificationsStayed = $this->Notification->find('all', [
            'conditions' => [
                'Notification.user_id' => $this->Auth->user('id'),
                'Notification.afficher' => true,
            ],
            'contain' => [
                'Fiche' => [
                    'Valeur' => [
                        'conditions' => [
                            'champ_name' => 'outilnom'
                        ],
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ]
            ],
            'order' => [
                'Notification.content'
            ]
        ]);
        $this->set('notificationsStayed', $notificationsStayed);
    }

    /**
     * @access public
     * @created 26/06/2015
     * @version V0.9.0
     */
    public function beforeRender() {
        parent::beforeRender();
        $this->set('formulaires_actifs', $this->FormGen->getAll([
                    'organisations_id' => $this->Session->read('Organisation.id'),
                    'active' => true
        ]));

        $serviceUser = $this->OrganisationUser->find('all', [
            'conditions' => [
                'user_id' => $this->Auth->user('id'),
                'organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'OrganisationUserService' => [
                    'Service'
                ]
            ]
        ]);
        $userServices = Hash::extract($serviceUser, '{n}.OrganisationUserService.Service');
        $this->set('userServices', $userServices);

        $serviceEntitee = $this->Service->find('all', [
            'conditions' => [
                'organisation_id' => $this->Session->read('Organisation.id')
            ]
        ]);
        $this->set('serviceEntitee', $serviceEntitee);
    }

}
