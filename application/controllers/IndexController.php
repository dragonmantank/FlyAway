<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
		$twitter = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('twitter');
		
		if(isset($_SESSION['TWITTER_ACCESS_TOKEN'])) {
			$this->view->access_token = unserialize($_SESSION['TWITTER_ACCESS_TOKEN']);
		}

		$this->view->allowSubscribe = (isset($_SESSION['IDENTICA_ACCESS_TOKEN']) ? 'true' : 'false');		
		$this->view->twitter = $twitter;
    }
}

