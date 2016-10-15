<?php

use \Providers\Services\CacheService as CacheService;

class Cache {

   private static $instance = NULL;

   private $cache_service;

   private function __construct($driver){

       if($driver === "memcached"){  

            $this->cache_service = new CacheService();
       }
   }

   public static function createInstance($driver){

       if(static::$instance == NULL){
             static::$instance = new Cache($driver);
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