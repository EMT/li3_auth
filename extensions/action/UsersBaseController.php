<?php 


/**
 * Handles basic user account actions
 */


namespace li3_auth\extensions\action;

use app\models\Users;
use li3_fieldwork\access\Access;
use li3_fieldwork\messages\Messages;



class UsersBaseController extends \li3_fieldwork\extensions\action\Controller {

	
	

	public function updatePassword() {
		$user = Users::findById($this->request->id);
		Access::check($this->request->auth, ['is_me' => $user, 'is_admin']);
		if ($this->request->data) {
			if (empty($this->request->data['current_password'])) {
				$this->request->data['current_password'] = '1';
			}
			if ($user->save($this->request->data)) {
                Messages::add(['success', 'Your password has been updated.']);
				return $this->redirect(['Users::edit', 'id' => $user->id]);
			}
		}
		return compact('user');
	}
	
	
	public function newVerifyEmail() {
		$user = Users::findById($this->request->id);
		Access::check($this->request->auth, ['is_me' => $user, 'is_admin']);
		if ($this->request->data && $user->emailVerifyCode()) {
			return compact('user') + ['success' => true];
		}
		return compact('user');
	}
	
	
   /**
    * Verify email verification code and forward
    */
	
	public function verify() {
	
		if (isset($this->request->query['c'])) {
			$user = Users::findById($this->request->id);
			
			if ($user->checkVerifyCode($this->request->query['c'])) {
				//	Flag user as verified & save
				$user->verified = 1;
				if ($user->save()) {
					return compact('user') + ['success' => true];
				}
			}
			
			throw new \Exception('Bad verification token.');
		}
	}
	
	
	/**
    * Request a password reset
    */
    
    public function password() {
    	if ($this->request->data) {
			$user = Users::findByEmail($this->request->data['email']);
	    	if ($user && $user->emailPasswordReset()) {
				Messages::add(['success', 'We just sent you an email. Please check your inbox.']);
	    	}
	    	else {
		    	Messages::add(['error', 'We don’t have that email address on record. Please check for typos and try again.']);
	    	}
    	}
    	return array();
    }
    
    
   /**
    * Reset password
    */
    
    public function reset() {
    	$user = Users::findById($this->request->id);
    	if ($this->request->data && $user->checkPasswordResetCode($this->request->data['code'])) {
	    	if ($this->request->data['password']) {
				if ($user->save($this->request->data)) {
					$user->expirePasswordResetCode();
                    Messages::add(['success', 'Your password has been changed and you can now log in with your new one.']);
					return $this->redirect(['Sessions::add']);
				}
			}
    	}
    	else if (empty($this->request->query['c']) || !$user->checkPasswordResetCode($this->request->query['c'])) {
    		Messages::add(['error', 'The link in your password reset email has expired. Please start again.']);
            return $this->redirect(['Users::password']);
    	}
    	
    	if (count($user->errors())) {
			Messages::add(['error', 'Your two passwords didn’t match.']);
		}
    	
    	$user->code = (isset($this->request->query['c'])) 
    		? $this->request->query['c'] : $this->request->data['code'];
    	return compact('user');
    }
    
    
    


}
