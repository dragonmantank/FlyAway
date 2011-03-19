<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH));
        return $moduleLoader;
    }
	
	protected function _initCache()
	{
		$frontend = array('automatic_serialization' => true);
		$backend = array('cache_dir' => APPLICATION_PATH.'/../cache');
		$cache = Zend_Cache::factory('Core', 'File', $frontend, $backend);
		
		return $cache;
	}
	
    protected function _initRegisterNamespaces()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('OpenSwitch_');
    }
	
	protected function _initTwitter()
	{
		$twitter = null;
		$config = $this->getOption('twitter');
		if(isset($_SESSION['TWITTER_ACCESS_TOKEN'])) {
			$options = array(
				'accessToken' => unserialize($_SESSION['TWITTER_ACCESS_TOKEN']),
				'consumerKey' => $config['key'],
				'consumerSecret' => $config['secret'],
			);

			$twitter = new Zend_Service_Twitter($options);
		}
		
		return $twitter;
	}
}

