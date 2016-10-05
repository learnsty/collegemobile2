<?php

class System {

    private static $instance = NULL;
 
    private $errorHandler;

    private $blindRouteHandler;

    private $middlewares;

   
    private function __construct(){
 
        $this->middlewares = array();

        $this->errorHandler = NULL;

        $this->blindRouteHandler = NULL;
        
        // Tell PHP to use the CLI error handler
        set_error_handler('error_handler');
    }

    public function __destruct(){

    }

    public static function createInstance(){

         if(static::$instance == NULL){
               static::$instance = new System();
               return static::$instance;
         }     
    }

    // A custom error handler
    private function error_handler($errno, $errstr, $errfile, $errline){
      
       $handler = self::$instance->getErrorHandler();

       if(self::$instance->isCLIEnabled()){
           fwrite(STDERR, "Learnsty App Exception => " . PHP_EOL . " $errstr in [$errfile] on :$errlinen");
       } 
 
       if(isset($handler) && is_callbale($handler)){
           $handler($errno, $errstr, $errfile, $errline);
       }     
    }

    private function isCLIEnabled(){

       return FALSE;
    }

    private function setErrorHandler($callback){

       $this->errorHandler = $callback;
    }

    private function getErrorHandler(){

       return $this->errorHandler;
    }

    private function setBlindRouteCallback($callback){

       $this->blindRouteHandler = $callback;
    }

    private function getBlindRouteCallback(){

       return $this->blindRouteHandler;
    }

    private function addMiddlewares($name, $callback){

       $this->middlewares[$name] = $callback;
    }

    public function hasBlindRouteCallback(){

       return isset($this->blindRouteHandler);
    }

    public function fireCallback(string $callbackName, array $callbackArgs){

        switch($callbackName){
            case 'BLIND_ROUTE_CALLBACK':
               $this->blindRouteHandler($callbackArgs[0]);
            break;
        }

    }

    public static function fire(){


    }

    public static function on(){


    }

    public function getFaultedMiddlewares(){


    }

    public function executeAllMiddlewares($route){
           $result = array();
           // PHP 5.0+
           foreach($this->middlewares as $name => $callback){
             if(is_callable($callback)){
                $result[] = $callback($route);
             }else{
                throw new \Exception("Error Processing Request >> Middleware Callback Undefined");
             }
           }
           return (bool) array_reduce($result, 'reduce_boolean');
    }

    public static function onAppError(callable $callback){
      
       static::$instance->setErrorHandler($callback);
    }

    public static function middleware(string $middleware_name, callable $callback){
           
       static::$instance->addMiddlewares($middleware_name,  $callback);           
    }

    public static function onBlindRoute(callable $callback){

        static::$instance->setBlindRouteCallback($callback);
    }

    public static function onFiltered(callable $callback){

    }

}

?>