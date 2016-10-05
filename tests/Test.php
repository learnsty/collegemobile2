<?php

class Test {


    public function __destruct(){

        $this->tearDownApplication();
    }

    public startApplication(array $config = array()){
        
          extract($config); #unitTesting = true

          require_once(__DIR__ . '../system/boot.php');

    }

    public function __call($method, $args){

         if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
         {
               return $this->call($method, $args[0]);
         }
 
         throw new BadMethodCallException;
    }

    public function tearDownApplication(){


    }

}


?>