<?php

/**
***Component de gestion des notifications
**/
class NotificationsComponent extends Component {


	public function add($content, $fiche_id, $user_id){
		$notification = ClassRegistry::init('Notification');
		$user = ClassRegistry::init('User');
		$userInfo = $user->find('first', array(
			'conditions' => array(
				'id' => $user_id
				)
			)
		);
		$notification->create(array('Notification'=>array('fiche_id'=>$fiche_id, 'content'=>$content, 'user_id'=>$user_id)));
		if($notification->save()){

			//$Email = new CakeEmail();
			//$Email->config('email');
			//$Email->to($userInfo['User']['email']);
			//$Email->subject('Nouvelle notification');
			//$Email->send('Vous avez reçu une nouvelle notification sur WebCIL');


			return true;
		}
		else{
			return false;
		}
	}


}