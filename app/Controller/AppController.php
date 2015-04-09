<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller', 'OrganisationUser');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */



class AppController extends Controller {
    public $uses=array('Organisation', 'Droit', 'OrganisationUser');
    public $components = array(
        'Session',
        'Droits',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'organisations', 'action' => 'change'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home')
            )
        );

    public function beforeFilter() {
        $this->set('nom', $this->Auth->user('nom'));
        $this->set('prenom', $this->Auth->user('prenom'));
        $this->set('userId', $this->Auth->user('id'));

        if($this->Droits->isSu()){
            $this->set('organisations',$this->Organisation->find('all', array(
                )
            )
            );
        }
        else{
            $this->set('organisations',$this->OrganisationUser->find('all', array(
                'conditions' => array(
                    'OrganisationUser.user_id' => $this->Auth->user('id')
                    ),
                'contain' => array(
                    'Organisation'
                    )
                )
            )
            );
        }
        $this->set('droits', $this->Session->read('Droit.liste'));
    }
}
