<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
		$twitter = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('twitter');
		
		$this->view->twitter = $twitter;
		if(isset($_SESSION['TWITTER_ACCESS_TOKEN'])) {
			$this->view->access_token = unserialize($_SESSION['TWITTER_ACCESS_TOKEN']);
		}
    }
}

