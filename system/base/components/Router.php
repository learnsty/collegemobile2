<?php 



class Router {

     private static $instance = NULL;

     protected $routesTable;

     protected $routeParameters;

     protected $currentRouteUrl;

     private function __construct(){
        
           $this->routesTable = array();

           $this->routeParameters = array();

           $this->currentRouteUrl = '';
          
     }

     public static function createInstance(){

          if(static::$instance === NULL){
               static::$instance = new Router();
               return static::$instance;
          }     
     }

     private function addToRoutesTable($routeUrl, array $settings){

         if(!array_key_exists($routeUrl, $this->routesTable)){
                $this->routesTable[$routeUrl] = array();
         }

         // $temp = $this->routesTable[$routeUrl];

     	 array_push($this->routesTable[$routeUrl], $settings);
     }

     private function setCurrentRouteParameter($param_name = 'NULL', $param_value = NULL){

     	   $this->routeParameters[$param_name] = $param_value;
     }

     public function getCurrentRouteParameters(){

           $this->routeParameters;
     }

     private function purgeParameters(){

     	  // more code here ---> remove 'NULL' keys and values...

          //$temp = array_filter($this->routeParameters, '');

          //$this->routeParameters = $temp;
     }

     public static function bind($routeUrl, array $settings = array()){

          if(static::$instance !== NULL){
               
               static::$instance->addToRoutesTable($routeUrl, $settings);
          }
     
     }

     public static function currentRoute(){

        return static::$instance->getCurrentRouteUrl();
     }

     public function getCurrentRouteUrl(){

     	 return $this->currentRouteUrl;
     }

     public function findRoute($uri){

         $routeUrlParts = explode('/', preg_replace('/^\/|\/$/', '', $uri));
 
         $routes = array_keys($this->routesTable);

     	 foreach ($routes as $route){
	     	 	 $routeParts = explode('/',  preg_replace('/^\/|\/$/', '', $route));
	     	 	 $checks = array();
	     	 	 $len = count($routeUrlParts);
                 $index = -1;

	     	 	 for($i = 0; $i < $len; $i++){
                         $hasKey = array_key_exists($i, $routeParts);

                         if($hasKey){
	     	 	 	         $index = index_of($routeParts[$i], '@');
                         }   
	     	 	 	     // validation: 
                         if($index === 0 && $i === 0){ // No route parameter should be at the beginning of a route url
                             throw new \Exception("Invalid Route URL >> [" . $route . "] ");
                         }

                         if($index === -1 && ($i === ($len - 1)) && $i != 0){ // No route part should be at the end of a route url except if it is the first and last part
                             throw new \Exception("Invalid Route URL >> [" . $route . "] ");
                         }

		     	 	 	 $urlPart = $routeUrlParts[$i];
                         
                         // detect a route parameter
                         if($index > -1){
                             $criteria = array_slice($checks, 0, $i);
    		     	 	 	 if(($i === ($len - 1)) // The parameter must be the last thing about defined route
                                 && (count($criteria) === ($len - $i))){ 
                                  $this->setCurrentRouteParameter(substr($routeParts[$i], ($index+1)), $urlPart);
    			     	 	 	  array_splice($routeParts, $i, 1);
    			     	 	 	  continue; 
    			     	 	 }
                         }else{ // detect a route part
                             
                             // match up each segment of the route url
			     	 	     if($hasKey && $urlPart === $routeParts[$i]){
                                 $checks[] = TRUE;
			     	 	     }
			     	 	 }   
		     	 }
		     	 if(count($checks) === count($routeParts)){
		     	 	  $this->currentRouteUrl = $route;
                      \Logger::info("The Current Route: " . $this->currentRouteUrl);
                      return TRUE;
		     	 }	 
     	 }
     	 return FALSE;
     }

     public function getRouteSettings($requestMethod, System $instance, Auth $auth){

         $models = array();
         $settingsList = NULL;

         $this->purgeParameters();
 
         if(array_key_exists($this->currentRouteUrl, $this->routesTable)){  
     	     $settingsList = $this->routesTable[$this->currentRouteUrl];
     	 }else{
     	     $settingsList = array(array('verb'=>'', 'params'=>array(), 'models'=>array()));	
     	 }

         $sLen = count($settingsList);

         for($i = 0; $i < $sLen; $i++){  
              
             $settings = $settingsList[$i]; 

             if(!array_key_exists('ajax', $settings)){
                $settings['ajax'] = -1; // '-1' indicates that this setting doesn't really matter
             }

             if(!array_key_exists('verb', $settings)){
                 $settings['verb'] = 'get';
             }

             if(!array_key_exists('params', $settings)){
                 $settings['params'] = array();
             }

             if(!array_key_exists('models', $settings)){
                 $settings['models'] = array();
             }

             if($settings['ajax'] === -1){
                 unset($settings['ajax']); // AJAX doesn't matter
             }else{
                 if(gettype($settings['ajax']) === "boolean"){ // AJAX matters
                     if(Request::isAjax() !== $settings['ajax']){

                         throw new \Exception("Error Processing Request on Route >> ['" . $this->currentRouteUrl . "'] Route Access Must Be AJAX");   
                     }
                 }
             }

             \Logger::info("Current HTTP Verb: " . strtolower($settings['verb']) . " Requested: " . $requestMethod);
         	 if(strtolower($settings['verb']) !== $requestMethod){
                 if($i !== ($sLen - 1)){
                    // this route may not be the one we are looking for... so keep checking
                    continue;
                 }else{
                    // we have completed the check (this is the last one) and we still can't find a matching verb
                    throw new \Exception("Error Processing Request on Route >> ['" . $this->currentRouteUrl . "'] Route Verb is Undefined");      
                 }   
         	 }

             if(!$instance->executeAllMiddlewares($this->currentRouteUrl, $auth)){
                 throw new \Exception("System Middleware(s) ['" . (implode(', ', $instance->getFaultedMiddlewares())) . "'] have truncated Request on Route >> ['" . $this->currentRouteUrl . "'] ");
             }

         	 // validate parameters
         	 foreach ($settings['params'] as $param_name => $regex){
                 $param_key_found = array_key_exists($param_name, $this->routeParameters);
                 $param_value = ($param_key_found)? $this->routeParameters[$param_name] : '';
         	 	 if(!preg_match($regex, $param_value)){
                      throw new \Exception("Invalid Parameter For Current Route >> ['". $this->currentRouteUrl . "'] ");
         	 	 }
         	 }

         	 // build out models and return models array
         	 foreach ($settings['models'] as $modelClass) {
    	            if(class_exists($modelClass)){
    	     	 	    $models[$modelClass] = new $modelClass();
    	            }else{
                        $models[$modelClass] = NULL;
    	            }
         	 }
         }   
         
         return $models;  
     }

}

?>