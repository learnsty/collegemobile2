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

     private function addToRoutesTable(string $routeUrl, array $settings){

         if(!array_key_exists($routeUrl, $this->routesTable)){
                $this->routesTable[$routeUrl] = array();
         }

     	 array_push($this->routesTable[$routeUrl], $settings);
     }

     private function setCurrentRouteParameter($param_name='NULL', $param_value='NULL'){

     	   $this->routeParameters[$param_name] = $param_value;
     }

     private function getCurrentRouteParameters(){

           $this->routeParameters;
     }

     private function purgeParameters(){

     	  // more code here ---> remove 'NULL' keys and values...
     }

     public static function bind(string $routeUrl, array $settings = array()){

          if(static::$instance !== NULL){
               
               static::$instance->addToRoutesTable($routeUrl, $settings);
          }
     
     }

     public static function currentRoute(){

        return static::$instance->currentRouteUrl;
     }

     public function getCurrentRouteUrl(){

     	 return $this->currentRouteUrl;
     }

     public  function findRoute() {

         $routeUrlParts = explode('/', preg_replace('/^\/|\/$/', '', Request::uri()));
 
         $routes =  array_keys($this->routesTable);

     	 foreach ($routes as $route){
	     	 	 $routeParts = explode('/',  preg_replace('/^\/|\/$/', '', $route));
	     	 	 $checks = array();
	     	 	 $len = count($routeUrlParts);

	     	 	 for($i = 0; $i < $len; $i++){
	     	 	 	     $index = index_of($routeParts[$i], '@');
	     	 	 	     // validation: 
                         if($i === 0 && $index === 0){ // No route parameter should be at the beginning of a route url
                             throw new \Exception("Invalid Route URL >> [" . $route . "] ");
                         }

		     	 	 	 $urlPart = $routeUrlParts[$i];

		     	 	 	 if($i === ($len - 1) && $index === -1){ // No route part should be at the end of a route url
                             throw new \Exception("Invalid Route URL >> [".$route . "] ");
		     	 	 	 }

		     	 	 	 if($index > -1){ // detect a route parameter
			     	 	 	  $this->setCurrentRouteParameter(substr($routeParts[$i], $index+1), $urlPart);
			     	 	 	  array_splice($routeParts, $i, 1);
			     	 	 	  continue; 
			     	 	 }
			     	 	 try{ // match up each segment of the route url
			     	 	     if($urlPart === $routeParts[$i]){
                                 $checks[] = TRUE;
			     	 	     }
			     	 	 }catch(\Exception $e){
                             
			     	 	 }    
		     	 }
		     	 if(count($checks) === count($routeParts)){
		     	 	  $this->currentRouteUrl = $route;
                      return TRUE;
		     	 }	 
     	 }
     	 return FALSE;
     }

     public function getRouteSettings(string $requestMethod, System $instance){

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

             if(!array_key_exists('ajax', $settingsList)){
                $settings['ajax'] = -1; // '-1' indicates that this setting doesn't really matter
             }

             if(!array_key_exists('verb', $settingsList)){
                 $settings['verb'] = 'get';
             }

             if(!array_key_exists('params', $settingsList)){
                 $settings['params'] = array();
             }

             if(!array_key_exists('models', $settingsList)){
                 $settings['models'] = array();
             }

             if($settings['ajax'] === -1){
                 unset($settings['ajax']); // AJAX doesn't matter
             }else{
                 if(gettype($settings['ajax']) === "boolean"){ // AJAX matters
                     if(Request::isAjax() !== $settings['ajax']){

                         throw new \Exception("Error Processing Request on Route >> [" . $this->currentRouteUrl . "] Route Access Must Be AJAX");   
                     }
                 }
             }

         	 if($settings['verb'] !== $requestMethod){
                 if($i !== ($sLen - 1)){
                    // this route may not be the one we are looking for... so keep checking
                    continue;
                 }else{
                    // we have completed the check (this is the last one) and we still can't find a matching verb
                    throw new \Exception("Error Processing Request on Route >> [" . $this->currentRouteUrl . "] Route Verb is Undefined");      
                 }   
         	 }

             if(!$instance->executeAllMiddlewares()){
                 throw new \Execption("ddd  [ " . $instance->getFaultedMiddlewares() . " ] ");
             }

         	 // validate parameters
         	 $param_key_found = array_key_exists($param_name, $this->routeParameters);
         	 $param_value = ($param_key_found)? $this->routeParameters[$param_name] : '';
         	 foreach ($settings['params'] as $param_name => $regex) {
         	 	 if(!preg_match($regex, $param_value)){
                      throw new \Exception("Invalid Parameter For Current Route >> [". $this->currentRouteUrl . "] ");
         	 	 }
         	 }

         	 // build out models and return models array
         	 foreach ($settings['models'] as $modelClass) {
    	            if(class_exists($modelClass)){
    	     	 	    $models[$modelClass] = new $modelClass();
    	            }else{
    	            	throw new Exception("Model Not Found >> [". $modelClass . "] ");
    	            }
         	 }
         }   
           
         return $models;  
     }

}

?>