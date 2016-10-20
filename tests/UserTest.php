<?php

namespace Learnsty\Testing;

use Vyke\Tests\TestCase as TestCase;


class UserTest extends TestCase {

     protected $user;

     public function __construct(){

          parent::__construct(array('User')); 

     }

     public function setUp(){

         parent::setUp();
      
         $this->user = $this->mockObjects['User'];
          
     }

     public function testUserCreation(){


     }


     public function __call($method, $args){

         if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
         {
               return $this->call($method, $args[0]);
         }
 
         throw new BadMethodCallException;
     }

}


?>