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
	
	public function twitterfriendsAction()
	{
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$friends = OpenSwitch_Twitter::getFriendsList();

			if(count($friends)) {
				$result = array();
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
