<?php
/*!
 * Vyke Mini Framework (c) 2016
 * 
 * {App.php}
 */

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
use TextStream;
use Mail;

class App {

     private $apphost;

     private $os;

     /**
     * @var array
     */ 

     protected $cookieQueue;

     protected $hasCachedModels;

     protected $resolver;

     protected $dbservice;

     protected $envservice;

     protected $instances;

     /**
     * Constructor.
     *
     * @param void
     *
     * @scope public
     */

     public function __construct(){

        if(!$this->inCLIMode()){

             $this->resolver = new Resolver();
        }     

        $this->instances = array();

        $this->cookieQueue = array();

        $this->hasCachedModels = FALSE;

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

     public function setDBConnection($env_path){ 

         $this->dbservice->connect($env_path);
     }

     public function getCookieQueue(){

         return $this->cookieQueue;
     }

     public function pushCookieQueue($ckey, $cval){

         $this->cookieQueue[$ckey] = $cval;
     }

     public function initHTTPResolver(){

     	 $this->resolver->draftRouteHandler(strtolower(Request::method()));

     	 $this->resolver->handleCurrentRoute($this->getInstance('Router'), $this->getInstance('System'), $this->getInstance('Auth'));

     }

     public function cacheModelInstances(array $models){
 
        if($this->hasCachedModels === FALSE){ 

            $this->dbservice->setModelsToBuilder($models);

            $this->hasCachedModels = TRUE;

        }    

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

         // TODO: later, try to see if you can do the below in a loop! it probably will be a much cleaner code
         if(!$this->inCLIMode()){  
               $this->instances['Logger'] = Logger::createInstance();
               $this->instances['System'] = System::createInstance();
               $this->instances['Session'] = Session::createInstance($this->envservice->getConfig('session_driver'));
               $this->instances['Response'] = Response::createInstance();
         	   $this->instances['Request'] = Request::createInstance();
               $this->instances['Cache'] = Cache::createInstance($this->envservice->getConfig('app_cache'));
               $this->instances['Router'] = Router::createInstance();
               $this->instances['Validator'] = Validator::createInstance();
         	   $this->instances['File'] = File::createInstance();
         	   $this->instances['Auth'] = Auth::createInstance($this->envservice->getConfig('app_auth'));
               $this->instances['Helpers'] = Helpers::createInstance();
               $this->instances['Mail'] = Mail::createInstance($this->envservice->getConfig('app_mails'));
               $this->instances['TextStream'] = TextStream::createInstance();
         }  
     }

     public function inCLIMode(){

         return (isset($_SERVER['argv']) && isset($_SERVER['argc']));
     }

     public function shutDown(){

     	 Logger::info("Application is Shutting Down...");

         $this->hasCachedModels = FALSE;

         $this->dbservice = NULL; // call __destruct to disconnect DB connection (PDO style) & recover memory

         $this->envservice = NULL; // call __desstruct to ...

         // $this->resolver = NULL;

         $this->instances = array(); // recover more memory

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
