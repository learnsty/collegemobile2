<?php

class Mail {

     private static $instance = NULL;

     private function __construct($driver){

     }

     public static function createInstance($driver){
          if(static::$instance == NULL){
               static::$instance = new Mail($driver);
               return static::$instance;
          } 
     }  

}

?>