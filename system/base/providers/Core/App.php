<?php

namespace Providers\Core;

use Providers\Core\HTTPResolver as Resolver;
use Providers\Services\DBService as DBService;
use Providers\Services\EnvService as EnvService;

use Request;
use Router;
use Session;
use Auth;
use System;
use Cache;
use Helpers;
use File;
use Response;
use Validator;
use Logger;

class App {

     private $apphost;

     private $os;

     protected $resolver;

     protected $dbservice;

     protected $envservice;

     protected $instances;

     public function __construct(){

        if(!$this->inCLIMode()){

             $this->resolver = new Resolver();
        }     

        $this->instances = array();

        $this->apphost = "http://" . Request::getHost();

        $this->os = preg_replace('/^(?:.+)?\((Win32|Linux)\)(?:.+)?$/i', '${1}', Request::header('SERVER_SOFTWARE'));

     }


     public function installDBService(array $DBCONFIG){

            $engine = $DBCONFIG['engines']['mysql'];
            
            if (! extension_loaded($engine['driver']) 
          	   || ! extension_loaded(strtolower($engine['driver']) . "_mysql")){
	            
	           exit(1);
            }

			if(!is_array($DBCONFIG)){
             
                exit(1);
			}

			$this->dbservice = new DBService($DBCONFIG); // extract($DBCONFIG, EXTR_PREFIX_ALL , "db");
     }

     public function installENVService(array $ENVCONFIG){

     	    if ( ! extension_loaded($ENVCONFIG['encryption_scheme'])){
                 # "Error: 01:29:35 - 03/09/2016 [.]" . PHP_EOL;
               
                 exit(1);
            }

            if ($ENVCONFIG['image_processing_enabled'] && ! extension_loaded('gd')){

                 exit(1);
            }

	     	if(!is_array($ENVCONFIG)){
	             
	            exit(1);
				   
			}	   

            $this->envservice = new EnvService($ENVCONFIG);
     }

     public function getOS(){

     	return $this->os;
     }

     public function getHost(){

     	return $this->apphost;
     }

     public function getDBService(){ 

        return $this->dbservice;
     }

     public function initHTTPResolver(){

     	 $this->resolver->draftRouteHandler(strtolower(Router::currentRoute()), strtolower(Request::method()));

     	 $this->resolver->handleCurrentRoute($this->getInstance('Router'), $this->getInstance('System'));

     }

     public function cacheModelInstances($models){


     }

     public function exposeEnvironment($root){

         return $this->envservice->exposeEnvironment($root);

     }

     public function getRemoteErrorReporter(){

         if(file_exists($GLOBALS['env']['app.path.packages'] . 'vendor/autoload.php')){
             return (new \Learnsty\ErrorReporter\Reporter());
         }

         return NULL;
     }

     public function registerAllComponents(){

         // TODO: later, try to see if you can do the below in a loop! will be much cleaner code
         if(!$this->inCLIMode()){  
         	   $this->instances['System'] = System::createInstance();
               $this->instances['Session'] = Session::createInstance($this->envservice->getConfig('session_driver'));
               $this->instances['Response'] = Response::createInstance();
         	   $this->instances['Request'] = Request::createInstance();
               $this->instances['Cache'] = Cache::createInstance();
               $this->instances['Router'] = Router::createInstance();
               $this->instances['Validator'] = Validator::createInstance();
         	   $this->instances['File'] = File::createInstance();
         	   $this->instances['Logger'] = Logger::createInstance();
         	   $this->instances['Auth'] = Auth::createInstance();
               $this->instances['Helpers'] = Helpers::createInstance();
         }  
     }

     public function inCLIMode(){

         return (isset($_SERVER['argv']) && isset($_SERVER['argc']));
     }

     public function shutDown(){

     	 Logger::info("Application is Shutting Down...");

     }

     public function crash(\Exception $e){
     	 
     	  Response::error($e);
     }

     private function getInstance($instance_name){
        
        if(array_key_exists($instance_name, $this->instances)){

             return $this->instances[$instance_name];
        }

        return NULL;
     }

}


?>
