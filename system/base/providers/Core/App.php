<?php

namespace Providers\Core;

use \Providers\Core\HTTPResolver as Resolver;
use \Providers\Services\DBService as DBService;
use \Providers\Services\EnvService as EnvService;

use \Router;
use \Session;
use \File;
use \Request;
use \Response;
use \Validator;
use \Logger;

class App {

     private $apphost;

     private $os;

     protected $resolver;

     protected $dbservice;

     protected $envservice;

     protected $instances;

     public function __construct(){

        if(!isset($_SERVER['argv']) && !isset($_SERVER['argc'])){

             $this->resolver = new Resolver();
        }     

        $this->instances = array();

        $this->apphost = "http://" . Request::getInfo('HTTP_HOST');

        $this->os = preg_replace('/^(?:.+)?(Win32|Linux)(?:.+)?$/i', '${1}', Request::getInfo('SERVER_SOFTWARE'));

     }


     public function installDBService($DBCONFIG){
            
            if (! extension_loaded($DBCONFIG['engines']['driver']) 
          	   || ! extension_loaded("pdo_mysql")){
	            
	           exit(1);
            }

			if(!is_array($DBCONFIG)){
             
                exit(1);
			}

			$this->dbservice = new DBService($DBCONFIG); // extract($DBCONFIG, EXTR_PREFIX_ALL , "db");
     }

     public function installENVService($ENVCONFIG){

     	    if ( ! extension_loaded($ENVCONFIG['encryption_scheme'])){
                 # "Error: 01:29:35 - 03/09/2016 [.]" . PHP_EOL;

                 exit(1);
            }

            if ($ENVCONFIG['image_processing_enabled'] && ! extension_loaded('gd')){

                 #  "Error: [.]" . PHP_EOL;

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

     public function exposeEnvironment(){

         $this->envservice->exposeEnvironment();

     }

     public function registerAllComponents(){

           // TODO: later, try to see if you can do the below in a loop! will be much cleaner code
           
     	   $this->instances['System'] = System::createInstance();
     	   $this->instances['Request'] = Request::createInstance();
     	   $this->instances['Response'] = Response::createInstance();
     	   $this->instances['Session'] = Session::createInstance($this->envservice->getConfig('session_driver'));
     	   $this->instances['Router'] = Router::createInstance();
     	   $this->instances['Validator'] = Validator::createInstance();
     	   $this->instances['File'] = File::createInstance();
     	   $this->instances['Logger'] = Logger::createInstance();
     	   //$this->instances['Auth'] = Auth::createInstance();

     }

     public function shutDown(){

     	 # Response::text();
     	 Looger::info("Application is Shutting Down...");

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
