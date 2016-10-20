<?php
/*!
 * Vyke Mini Framework (c) 2016
 * 
 * {CacheSerivce.php}
 */

namespace Providers\Services;

use \Contracts\Policies\CacheAccessInterface as CacheAccessInterface;

class CacheService implements CacheAccessInterface {


      protected $memcache;

      protected $isCacheAvialable;
     
      public function __construct(){

      	 // $this->memcache = new Memcache;

      	 // $this->isCacheAvialable = $this->memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT);
      }

      public function set($key, $val){

          // $this->memcache->set($key, $val);
      }

      public function get($key){

          // return $this->memcache-get($key); 
      }

}

?>