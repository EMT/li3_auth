<?php 


/**
 * Handles basic user account actions
 */


namespace li3_auth\extensions\action;

use app\models\Users;
use fieldwork\access\Access;



class UsersBaseController extends \app\extensions\action\Controller {

	
	

	public function updatePassword() {
		$user = Users::findById($this->request->id);
		Access::check($this->auth, ['is_me' => $user, 'is_admin', 'is_super_admin']);
		if ($this->request->data) {
			if (empty($this->request->data['current_password'])) {
				$this->request->data['current_password'] = '1';
			}
			if ($user->save($this->request->data)) {
				return $this->redirect(['Users::edit', 'id' => $user->id]);
			}
		}
		return compact('user');
	}
	
	
	public function newVerifyEmail() {
		$user = Users::findById($this->request->id);
		Access::check($this->auth, ['is_me' => $user, 'is_admin', 'is_super_admin']);
		if ($this->request->data && $user->emailVerifyCode()) {
			return $this->redirect(['Users::dashboard', 'id' => $user->id]);
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
					return $this->redirect(['Users::dashboard', 'id' => $user->id]);
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
				return $this->redirect('/messages/password-email-sent');
	    	}
	    	else {
		    	Messages::add('email-not-found');
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
					return $this->redirect('/messages/password-reset');
				}
			}
    	}
    	else if (!$user->checkPasswordResetCode($this->request->query['c'])) {
    		return $this->redirect('/messages/bad-reset-code');
    	}
    	
    	if (count($user->errors())) {
			Messages::add('form-errors');
		}
    	
    	$user->code = (isset($this->request->query['c'])) 
    		? $this->request->query['c'] : $this->request->data['code'];
    	return compact('user');
    }
    
    
    


}
