<?php 

use \Providers\Services\NativeSessionService as SessionService;

class Session {

     private static $instance = NULL;

     private  $session_service;
 
     private function __construct($driver){
      
         $this->session_service = new SessionService($driver);      
     }

     public static function createInstance($driver){

          if(static::$instance == NULL){
               static::$instance = new Session($driver);
               return static::$instance;
          }     
     }

     public static function get($key){
       
        return static::$instance->session_service->read($key);
     }

     public static function put($key, $value){

        return static::$instance->session_service->write($key, $value);
     }

     public static function forget($key){

        return static::$instance->session_service->erase($key);
     }

     public static function drop(){

         return static::$instance->session_service->destroy(session_id());
     }

     public static function token(){

     	$_token;
       
        if(static::$instance->session_service->hasKey('_token')){
           
            $_token =  static::read('_token');
        }else{

            $_token = get_random(TRUE);

            static::write('_token',  $token);
        }

        return $_token;
     }


}

?>