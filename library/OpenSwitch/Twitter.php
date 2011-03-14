<?php

class OpenSwitch_Twitter
{
	static public function getFriendsList($username)
	{
		$ch = curl_init('http://api.twitter.com/1/statuses/friends/'.$username.'.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$data = json_decode($result);

		if(is_array($data)) {
			$friends = array();
			foreach($data as $friend) {
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