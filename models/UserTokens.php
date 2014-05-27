<?php

namespace li3_auth\models;

use lithium\security\Password;


class UserTokens extends \li3_fieldwork\extensions\data\Model {
    
    
   /**
    * Generates, logs and returns a new user token
    */
    
    public static function getNewToken($user, $expires = 86400, $series = 'default') {
        $token = UserTokens::findToken($user->id, $series);
        if (!$token) {
            $token = UserTokens::create();
            $token->user_id = $user->id;
            $token->series = $series;
        }
        $token->expires = $expires;
        $code = UserTokens::generateCode($user);
        $token->code = Password::hash($code);
        return ($token->save()) ? $code : false;
    }
    
    
   /**
    * Generates the token code
    */
    
    public static function generateCode($user) {
        return md5(serialize($user->data()) . time() . rand(1000,9999));
    }
    
    
   /**
    * Finds a token in the database based on User ID and token series
    */
    
    public static function findToken($user_id, $series = 'default') {
        return UserTokens::first(array('conditions' => array(
            'user_id' => $user_id,
            'series' => $series
        )));
    }


   /**
    * Check a code against a token and return true in they match and the token has not expired
    */
    
    public function check($entity, $code) {
        return ($entity->updated + $entity->expires >= time() 
            && Password::check($code, $entity->code)) ? true : false;
    }
    

}




?>