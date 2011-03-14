<?php

class OpenSwitch_Identica
{
	static public function userExists($username)
	{
		$ch = curl_init('http://identi.ca/'.$username);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($status == 200) {
			return true;
		} else {
			return false;
		}
	}
}
