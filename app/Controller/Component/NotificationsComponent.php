<?php

/**
 * NotificationsComponent
 * Component de gestion des notifications
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
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     Component
 */
class NotificationsComponent extends Component {

    /**
     * @param type $content
     * @param int $fiche_id
     * @param int $user_id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function add($content, $fiche_id, $user_id) {
        $notification = ClassRegistry::init('Notification');
        $user = ClassRegistry::init('User');
        $userInfo = $user->find('first', array(
            'conditions' => array(
                'id' => $user_id
            )
        ));
        $notification->create(array(
            'Notification' => array(
                'fiche_id' => $fiche_id,
                'content' => $content,
                'user_id' => $user_id,
                'vu' => false
            )
        ));
        if ($notification->save()) {

            //$Email = new CakeEmail();
            //$Email->config('email');
            //$Email->to($userInfo['User']['email']);
            //$Email->subject('Nouvelle notification');
            //$Email->send('Vous avez reçu une nouvelle notification sur WebCIL');


            return true;
        } else {
            return false;
        }
    }

    /**
     * @param type $content
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function del($content, $id) {
        $notification = ClassRegistry::init('Notification');
        $notification->deleteAll(array(
            'content' => $content,
            'fiche_id' => $id
        ));
    }

}
