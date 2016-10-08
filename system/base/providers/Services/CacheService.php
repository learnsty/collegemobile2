<?php

namespace Providers\Services;

use \Contracts\Policies\CacheAccessInterface as CacheAccessInterface;

class CacheService implements CacheAccessInterface {


      protected $memcache;

      protected $isCacheAvialable;
     
      public function __construct(){

      	 // $this->memcache = new Memcache;

      	 // $this->isCacheAvialable = $this->memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT);
      }

      public function set(\string $key, \string $val){

          // $this->memcache->set($key, $val);
      }

      public function get(\string $key){

          // return $this->memcache-get($key); 
      }

}

?>