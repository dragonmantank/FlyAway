<?php

class AuthController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$config = $this->getInvokeArg('bootstrap')->getOptions();
		$consumer = new Zend_Oauth_Consumer(array(
			'callbackUrl' => 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->view->baseUrl().'/auth',
			'siteUrl' => 'http://twitter.com/oauth',
			'consumerKey' => $config['twitter']['key'],
			'consumerSecret' => $config['twitter']['secret'],
		));

		if(!empty($_GET) && isset($_SESSION['TWITTER_REQUEST_TOKEN']))
		{
			$token = $consumer->getAccessToken($_GET, unserialize($_SESSION['TWITTER_REQUEST_TOKEN']));
			$_SESSION['TWITTER_ACCESS_TOKEN'] = serialize($token);
			header('Location: /');
			die();
		}

		if(!isset($_SESSION['TWITTER_ACCESS_TOKEN'])) {
			$token = $consumer->getRequestToken();
			$_SESSION['TWITTER_REQUEST_TOKEN'] = serialize($token);
			$consumer->redirect();
		}

	}
	
	public function identicaAction()
	{
		$config = $this->getInvokeArg('bootstrap')->getOptions();
		$consumer = new Zend_Oauth_Consumer(array(
			'callbackUrl' => 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->view->baseUrl().'/auth/identica',
			'siteUrl' => 'https://identi.ca/api/oauth/',
			'consumerKey' => $config['identica']['key'],
			'consumerSecret' => $config['identica']['secret'],
		));

		if(!empty($_GET) && isset($_SESSION['IDENTICA_REQUEST_TOKEN']))
		{
			$token = $consumer->getAccessToken($_GET, unserialize($_SESSION['IDENTICA_REQUEST_TOKEN']));
			$_SESSION['IDENTICA_ACCESS_TOKEN'] = serialize($token);
			header('Location: /');
			die();
		}

		if(!isset($_SESSION['IDENTICA_ACCESS_TOKEN'])) {
			$token = $consumer->getRequestToken();
			$_SESSION['IDENTICA_REQUEST_TOKEN'] = serialize($token);
			$consumer->redirect();
		}

	}
}
