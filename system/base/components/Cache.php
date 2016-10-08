<?php

use \Providers\Services\CacheService as CacheService;

class Cache {

   private static $instance = NULL;

   private $cache_service;

   private function __construct(){

       $this->cache_service = new CacheService();
   }

   public static function createInstance(){

       if(static::$instance == NULL){
             static::$instance = new Cache();
             return static::$instance;
        }
   }

   public static function set($key, $value){

       static::$instance->cache_service->set($key, $value);
   }

   public static function get($key){

   	   static::$instance->cache_service->get($key);
   }

}


?>