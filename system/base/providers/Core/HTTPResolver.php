<?php

namespace Providers\Core;

use \Router;

class HTTPResolver{

	 protected $currentController;

	 private $resolverUri;

	 private  $resolverMethod;

	 public function __construct(){

	 	 $this->currentController = NULL;

	 }

	 public function draftRouteHandler(string $uri, string $method){

	 	  $this->resolverUri = $uri;

	 	  $this->resolverMethod = $method;
	
 	 }

 	 public function getResolverURI(){

 	 	 return explode('/', preg_replace('/^\/|\/$/', '', $this->resolverUri));

 	 }

 	 public function getResolverMethod(){

         return $this->resolverMethod;

 	 }

     public function handleCurrentRoute(Router $router, System $sys){
            
            $uriParts = $this->getResolverURI();
            $method = $this->getResolverMethod();


            if(!$router->findRoute()){
                if($sys->hasBlindRouteCallback()){
                   $sys->fireCallback('BLIND_ROUTE_CALLBACK', array(implode('/', $uriParts)));
                }else{
                   throw new \Exception("Route Not Found >> [" . implode('/', $uriParts) . "] ");
                }
            }

            $models = $router->getRouteSettings($method, $sys);
            
            $controllerClass = (array_key_exists(0, $uriParts))? ucfirst($uriParts[0]) : 'Controller';
            $controllerMethod = (array_key_exists(1, $uriParts) || index_of($uriParts[1], '@') != 0)? $uriParts[1] : 'index';

            if(class_exists($controllerClass)){
                 $this->currentController = new $controllerClass($router->getCurrentRouteParameters());
                 // TODO: we could do dependency injection to controller methods...
                 $this->currentController->{$controllerMethod}($models);
            }else{
                 throw new \Exception("Controller Not Found >> [". $controllerClass . "] ");
            } 

     }
}


?>