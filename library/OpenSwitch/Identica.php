<?php

class OpenSwitch_Identica
{
	static public function userExists($username)
	{
		$cache = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cache');		
		$key = 'statusnet_user_'.$username;
		$status = $cache->load($key);
	
		if($status == false) {
			$ch = curl_init('http://identi.ca/'.$username);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$cache->save($status, $key);
		}
		
		if($status == 200) {
			$isUser = true;
		} else {
			$isUser = false;
		}
		
		return $isUser;
	}
}
