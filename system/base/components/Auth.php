<?php 

class Auth {


     private static $instance = NULL;

     private function __construct(){

     }

     public static function createInstance(){
 
        if(static::$instance == NULL){
             static::$instance = new Auth();
             return static::$instance;
        }
     
     }

     public static function create(Model $user, array $props = array()){
           
           $user->set(array_keys($props), $props)->exec();
     }

     public static function login(array $props){

     }

     private static function validateJSONToken(){

     }

}

?>