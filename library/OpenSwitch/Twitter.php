<?php

class OpenSwitch_Twitter
{
	static protected function fetchFriends($page = 1)
	{
		$twitter = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('twitter');
		$data = $twitter->user->friends(array('cursor' => $page));
		return $data;
	}
	
	static public function getFriendsList($username)
	{
		$cache = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cache');
		$key = 'twitter_friendslist_'.$username;
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
}
