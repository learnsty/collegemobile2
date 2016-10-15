<?php

use \Providers\Tools\TemplateRunner as Runner;
use \Request;

class Response {

     private static $instance = NULL;
 
     private function __construct(){
            
            $this->bufferOutput(); // this is done intentionally

            $this->runner = new Runner();
     }

     private function bufferOutput(){
         if(index_of(static::getInfo('HTTP_ACCEPT_ENCODING'), 'gzip') > -1){
              ob_start("ob_gzhandler");
         }else{
              ob_start();
         }
     }

     public static function createInstance(){
         if(static::$instance == NULL){
               static::$instance = new Response();
               return static::$instance;
         }    
     }

     public static function header($key, $value){

           header($key . ': ' . $value);               
     }

     private static function getInfo($var){
       if(array_key_exists($var, $_SERVER)){
            return $_SERVER[$var];
       }else if(array_key_exists($var, $_REQUEST)){
            return $_REQUEST[$var];
       }
       return '';
     }

     public static function text($data, $statusCode){

        if(index_of(Request::header('HTTP_ACCEPT'), 'text/event-stream') > -1){

              static::header('Content-type', 'text/event-stream');

              static::header('Cache-Control', 'no-cache');

              $data .= PHP_EOL;
        }else{
      
             static::header('Content-type', 'text/plain; charste=UTF-8');
        }  

          http_response_code(intval($statusCode));

          static::end($data, 'text');
     }

     public static function json(array $data, $statusCode){

          static::header('Vary', 'Accept');
          
          if(index_of(Request::header('HTTP_ACCEPT'), 'application/json') > -1){

              static::header('Content-type', 'application/json; charset=UTF-8');

          }else{

              static::header('Content-type', 'text/plain; charste=UTF-8');
          }    

          http_response_code(intval($statusCode));

          static::end(json_encode($data), 'text');
        
     }

     public static function error(\Exception $e){

          static::header('Content-type', 'text/plain; charset=UTF-8');

          static::end($e->getMessage(), 'text');

     }

     public static function file($filename){


     }

     public static function view($name, $data){

         static::header('Content-type', 'text/html; charset=UTF-8');

         static::end(static::$instance->runner->render($name, $data), 'view');

     } 

     private static function end($data, $from){

            $GLOBALS['app']->shutDown();
                     
            echo $data; 

            if($from === 'text'){
                 ob_flush(); 
                 flush();
            }elseif ($from === 'view'){
                 ; // ob_end_flush(); // ob_end_clean();
            }     

          // ob_implicit_flush(TRUE);

     }

     public static function redirect($route){

           $base = $GLOBALS['env']['app.path.base']; 

          static::header('Location', /* $base . */ $route);

     }

     public static function setCookie($key, $value){
 
          $config = $GLOBALS['env']['app.settings.cookie'];

          $val = setcookie($key, $value, (time()+$config['max_life']), '/' ,$config['domain_factor'], $config['secure'],$config['server_only']);
     }

}

?>