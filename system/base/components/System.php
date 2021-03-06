<?php

class System {

    private static $instance = NULL;
 
    private $errorHandler;

    private $blindRouteHandler;

    private $faultedMiddlewares;

    private $middlewares;
   
    private function __construct(){
 
        $this->middlewares = array();

        $this->errorHandler = NULL;

        $this->blindRouteHandler = NULL;
        
        $this->faultedMiddlewares = array();

        $this->customEventHandlers = array();

        // Plain Text message instead of HTML messages.. Thank you!
        ini_set('html_errors', '0');
        // Tell PHP to use the CLI error handler
        set_error_handler(array(&$this, 'error_handler'));
        // Surpress Warnings
        assert_options(ASSERT_WARNING, 0);
        // Cacth fatal Errors
        register_shutdown_function(array(&$this, 'shutdown'));
    }

    public function __destruct(){

    }

    public static function createInstance(){

         if(static::$instance == NULL){
               static::$instance = new System();
               return static::$instance;
         }     
    }

   
    public function shutdown(){
         $fatalError = error_get_last();
         if($fatalError !== NULL){
             $this->error_handler($fatalError['type'], $fatalError['message'], $fatalError['file'], $fatalError['line']);
         }
    }

    // A custom error handler
    public function error_handler($errno, $errstr, $errfile, $errline){
      
       $handler = self::$instance->getErrorHandler();

       if($GLOBALS['app']->inCLIMode()){
           fwrite(STDERR, "Learnsty App Exception => " . PHP_EOL . " $errstr in [$errfile] on :$errlinen");
       } 
 
       if(isset($handler) && is_callable($handler)){
           $handler($errno, $errstr, $errfile, $errline);
       }     
    }

    private function setErrorHandler($callback){

       $this->errorHandler = (is_callable($callback)) ? $callback : NULL;
    }

    private function getErrorHandler(){

       return $this->errorHandler;
    }

    private function setBlindRouteCallback($callback){

       $this->blindRouteHandler = (is_callable($callback)) ? $callback : NULL;
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

    public function fireCallback($callbackName, array $callbackArgs){
        
        $result = NULL;
        switch($callbackName){
            case 'BLIND_ROUTE_CALLBACK':
               $result = $this->blindRouteHandler($callbackArgs[0]);
            break;
            case 'FILTERED_ROUTE_CALLABACK':
               $result = FALSE;
            break;
            default:
                if(array_key_exists($callbackName, $this->customEventHandlers)){
                     $callback = $this->customEventHandlers[$callbackName];
                     if(is_callable($callback)){
                         $result = call_user_func_array($callback, $callbackArgs);
                     }
                }
            break;
        }

        return $result;
    }

    public static function fire($eventName, array $args){

        return static::$instance->fireCallback($eventName, $args);
    }

    public static function on($eventName, callable $eventHandler){

        static::$instance->setCustomEvent($eventName, $eventHandler);
    }

    public function getFaultedMiddlewares(){

       return $this->faultedMiddlewares;
    }

    private function setCustomEvent($name, $function){

        $this->customEventHandlers[$name] = $function;
    }

    public function executeAllMiddlewares($route, $auth){
           $result = array();
           // PHP 5.0+
           foreach($this->middlewares as $name => $callback){
             if(is_callable($callback)){
                $result[] = $callback($route, $auth);
             }else{
                throw new \Exception("Error Processing Request >> Middleware Callback Undefined");
             }
             try{
                $index = ((count($result)) - 1);
                if($result[$index] === FALSE){
                     $this->faultedMiddlewares[] = $name;
                }else{
                    if($GLOBALS['HTTP_CODE'] === 302 
                        || $GLOBALS['HTTP_CODE'] === 301){
                        exit;
                    }
                }
             }catch(\Exception $e){}
           }

           return (bool) array_reduce($result, 'reduce_boolean', TRUE);
    }

    public static function onAppError(callable $callback){
      
        static::$instance->setErrorHandler($callback);
    }

    public static function middleware($middleware_name, callable $callback){
           
       static::$instance->addMiddlewares($middleware_name,  $callback);           
    }

    public static function onBlindRoute(callable $callback){

        static::$instance->setBlindRouteCallback($callback);
    }

    public static function onFiltered(callable $callback){

    }

}

?>