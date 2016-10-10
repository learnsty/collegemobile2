<?php 

use \Providers\Services\NativeSessionService as NativeService;
use \Providers\Services\RedisSessionService as RedisService;

class Session {

     private static $instance = NULL;

     private  $session_service;
 
     private function __construct($driver){

        // @TODO: use {SessioManager} class in the next code update to do the below more efficiently...

        if($driver === 'native'){

             $this->session_service = new NativeService();  

        }else if($driver === 'redis'){

             $this->session_service = new RedisService();
        }
     }

    /**
     *
     *
     * @param void
     * @return string 
     */
    
    public function getDriver(){

        return $this->session_service;
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

         return static::$instance->session_service->destroy(static::$instance->session_service->getName());
     }

     public static function id(){

        return static::$instance->session_service->getId();
     }

     public static function hasDropped(){

         $name = static::$instance->session_service->getName();

         return (!array_key_exists($name, $_COOKIE));
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