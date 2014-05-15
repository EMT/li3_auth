<?php

namespace li3_auth\extensions\data;

use lithium\security\Password;
use lithium\storage\Session;

class PersistentSessions extends \app\extensions\data\Model {

	public $validates = [];
	
	
	
   /**
    *	Creates a new session in the database and sets the cookie
    */
    
	public static function add($user_id, $data = '') {
		PersistentSessions::destroy($user_id);
		$session = PersistentSessions::create();
		$token = PersistentSessions::generateToken();
		$session->user_id = $user_id;
		if ($session->user_id && $session->save(['token' => Password::hash($token)])) {
			// set cookie
			$cookie = $session->user_id . '-' . $token;
			if (!empty($data)) {
				$cookie .= '-' . $data;
			}
			Session::write('pl', $cookie, ['name' => 'cookie', 'expires' => '+3 months']);
		}
		return false;
	}
	
	
   /**
    *	Checks for a valid persistent session cookie and returns a user_id if found and authenticated
    *	If authenticated the persistent session is destroyed and replaced with a new one
    */
    
	public static function check() {
		if ($cookie = Session::read('pl', ['name' => 'cookie'])) {
			$cookie = explode('-', $cookie);
			if (!empty($cookie[0]) && !empty($cookie[1])) {
				if ($session = PersistentSessions::findSession($cookie[0], $cookie[1])) {
					$user_id = $session->user_id;
					$session->delete();
					if (!empty($cookie[2])) {
						PersistentSessions::add($user_id, $cookie[2]);
						return [
							'user_id' => $user_id,
							'data' => $cookie[2]
						];
					}
					PersistentSessions::add($user_id);
					return $user_id;
				}
			}
		}
		return false;
	}
	

   /**
    *	Remove the persistent session for this user on this computer
    */
    
	public static function destroy($user_id) {
		if ($cookie = Session::read('pl', ['name' => 'cookie'])) {
			$cookie = explode('-', $cookie);
			if (!empty($cookie[0]) && !empty($cookie[1])) {
				if ($session = PersistentSessions::findSession($cookie[0], $cookie[1])) {
					Session::delete('pl', ['name' => 'cookie']);
					return $session->delete();
				}
			}
		}
		return false;
	}
	
	
   /**
    *	Remove all persistent sessions for this user
    */
    
	public static function destroyAllForUser($user_id) {
		Session::delete('pl', ['name' => 'cookie']);
		return PersistentSessions::remove(['user_id' => $user_id]);
	}
	
	
   /**
    *	Find a valid session, given a user_id and persistemt session token
    */
	
	public static function findSession($user_id, $token) {
		$sessions = PersistentSessions::find(['conditions' => ['user_id' => $user_id]]);
/*
	var_dump($sessions->data());
	var_dump($token);
*/
		foreach ($sessions as $session) {
			if (Password::check($token, $session->token)) {
				return $session;
			}
		}
		return null;
	}
	
	
	public static function generateToken() {
		return md5(uniqid() . time() . Password::salt());
	}
}

?>