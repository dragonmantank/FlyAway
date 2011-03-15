<?php

class OpenSwitch_Twitter
{
	static protected function fetchFriends($username, $cursor = -1)
	{
		$ch = curl_init('http://api.twitter.com/1/statuses/friends/'.$username.'.json?cursor='.$cursor);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		return json_decode($result);
	}
	
	static public function getFriendsList($username)
	{
		$data = self::fetchFriends($username);	
		$friends = self::processList($data);

		while($data->next_cursor > 0) {
			$data = self::fetchFriends($username, $data->next_cursor);
			$friends = array_merge($friends, self::processList($data));
		}
		
		return $friends;
	}
	
	static protected function processList($data)
	{
		if(is_array($data->users)) {
			$friends = array();
			foreach($data->users as $friend) {
				$friends[$friend->screen_name] = array(
					'profile_image' => $friend->profile_image_url,
					'screen_name' => $friend->screen_name,
					'name' => $friend->name,
				);
			}
			return $friends;
		} else {
			return array();
		}
	}
}