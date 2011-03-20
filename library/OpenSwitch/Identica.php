<?php

class OpenSwitch_Identica
{
	static protected function fetchFriends($page = 1)
	{
		$identica = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('identica');
		$data = $identica->user->friends(array('cursor' => $page));
		return $data;
	}
	
	static public function getFriendsList()
	{
		$cache = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cache');
		$access_token = unserialize($_SESSION['IDENTICA_ACCESS_TOKEN']);
		$key = 'statusnet_friendslist_'.$access_token->screen_name;
		$friends = $cache->load($key);
		$friends = false;
		if($friends == false) {
			
			$friends = array();
			$cursor = -1;
			do {
				$data = self::fetchFriends($cursor);
				$cursor = sprintf('%.0f', $data->next_cursor);
				$list = self::processList($data->user);
				$friends = array_merge($friends, $list);
			} while (count($data->user) == 99);
			if($data->error) {
				return $data->error;
			}
			
			$cache->save($friends, $key);
		}
		return $friends;
	}
	
	static protected function processList($users)
	{
		$friends = array();
		foreach ($users as $friend) {
			$screen_name = (string)$friend->screen_name;
			$profile_image = (string)$friend->profile_image_url;
			$name = (string)$friend->name;
			
			$friends[$screen_name] = array(
				'profile_image' => $profile_image,
				'screen_name' => $screen_name,
				'name' => $name,
			);
		}
		return $friends;
	}
	
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
