<?php

class WorkerController extends Zend_Controller_Action
{
	public function init()
	{
		// Disable the main layout renderer
		$this->_helper->layout->disableLayout();
		// Do not even attempt to render a view
		$this->_helper->viewRenderer->setNoRender(true);
	}
	
	public function identicafollowAction()
	{
		$status = 0;
		if($this->getRequest()->isPost()) {
			
			$data = $this->getRequest()->getPost();
			if(OpenSwitch_Identica::follow($data['screen_name'])) {
				$status = 1;
			}
		}
		
		echo json_encode(array('status' => $status));
	}
	
	public function twitterfriendsAction()
	{
		if($this->getRequest()->isPost()) {
			$identicaFriends = null;
			$result = array();
			
			$data = $this->getRequest()->getPost();
			$friends = OpenSwitch_Twitter::getFriendsList();
			
			if(isset($_SESSION['IDENTICA_ACCESS_TOKEN'])) {
				$identicaFriends = OpenSwitch_Identica::getFriendsList();
			}
			
			if(is_array($identicaFriends)) {
				$result['statusnet_subscribed'] = array_intersect_assoc($friends, $identicaFriends);
				foreach($result['statusnet_subscribed'] as $screen_name => $friend) {
					unset($friends[$screen_name]);
				}
			}
			
			if(count($friends)) {
				foreach($friends as $screen_name => $friend) {
					if(OpenSwitch_Identica::userExists($screen_name)) {
						$result['statusnet'][] = (array)$friend;
					} else {
						$result['twitter'][] = (array)$friend;
					}
				}

				echo json_encode($result);
			} else {
				return json_encode(array('error' => 'User Not Found'));
			}
		}
	}
	
	public function usernameAction()
	{
		if($this->getRequest()->isPost()) {
			$access_token = unserialize($_SESSION['TWITTER_ACCESS_TOKEN']);
			$exists = OpenSwitch_Identica::userExists($access_token->screen_name);
			echo json_encode(array('exists' => $exists));
		}
	}
}
