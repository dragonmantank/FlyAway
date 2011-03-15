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
}

