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

     public static function create(Model $user, array $props){
           
           $user->set(array_keys($props), $props);
     }

     public static function verify(array $props){

     }

     private static function validateToken(){

     }

}

?>