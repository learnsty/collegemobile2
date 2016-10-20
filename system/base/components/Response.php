<?php

/*!
 * Vyke Mini Framework (c) 2016
 * 
 * {Response.php}
 */

use \Request;
use \System;

use \Providers\Tools\TemplateRunner as Runner;

class Response {

    /**
     * @var Response
     */

     private static $instance = NULL;

    /**
     * Constructor.
     *
     * @param void
     *
     * @scope private
     */
 
     private function __construct(){
            
            $this->openOutputBuffers(); // this is done intentionally

            $this->runner = new Runner();
     }

    /**
     * Buffers output to the client 
     *
     * @param void
     * @return void
     *
     * @scope private
     */

     private function openOutputBuffers(){
         if(index_of(static::getInfo('HTTP_ACCEPT_ENCODING'), 'gzip') > -1){
              ob_start("ob_gzhandler");
         }else{
              ob_start();
         }
     }

    /**
     * Factory to supply instance
     *
     * @param void
     * @return Response
     *
     * @api
     */
     public static function createInstance(){
         if(static::$instance == NULL){
               static::$instance = new Response();
               return static::$instance;
         }    
     }

    /**
     * Sets up HTTP response header
     *
     * @param string $key 
     * @param string $value
     * @return void
     *
     * @api
     */

     public static function header($key, $value, $replace = TRUE){

          if(headers_sent()){
             
              return false;
          }

          return header($key . ': ' . $value, $replace);               
     }

    /**
     * Retrieves HTTP Server details 
     *
     * @param string $var 
     * @return string $value
     *
     *
     * @scope private
     */

     private static function getInfo($var){
       $value = '';
       if(array_key_exists($var, $_SERVER)){
            $value = $_SERVER[$var];
       }else if(array_key_exists($var, $_REQUEST)){
            $value = $_REQUEST[$var];
       }
       return $value;
     }

    /**
     * Returns text data back to the client 
     *
     * @param string $data 
     * @param int $statusCode
     * @return bool
     *
     * @api
     */

     public static function text($data, $statusCode){

        if(Request::isAjax()){
              ;
        }

        if(strtolower(Request::rawHeader('Connection')) === 'keep-alive' 
               && empty(Request::rawHeader('X-Hub-Signature'))){
                      
                Response::header("Keep-Alive", "timeout=15, max=100"); // we need to suspend the request for some time
                Response::header("Connection", "Keep-Alive");
        }

        if(index_of(Request::header('HTTP_ACCEPT'), 'text/event-stream') > -1){

              static::header('Content-type', 'text/event-stream');

              static::header('Cache-Control', 'no-cache');

              if(!is_null($data)){
              
                  $data .= PHP_EOL;
              }
        }else{
      
             static::header('Content-type', 'text/plain; charste=UTF-8');
        }  

          http_response_code(intval($statusCode));

          return static::end($data, 'text');
     }

     public static function json(array $data, $statusCode){

          static::header('Vary', 'Accept');
          
          if(index_of(Request::header('HTTP_ACCEPT'), 'application/json') > -1){

              static::header('Content-type', 'application/json; charset=UTF-8');

          }else{

              static::header('Content-type', 'text/plain; charste=UTF-8');
          }    

          http_response_code(intval($statusCode));

          return static::end(json_encode($data), 'text');
        
     }

     public static function error(\Exception $e){

          static::header('Content-type', 'text/plain; charset=UTF-8');

          return static::end($e->getMessage(), 'text');

     }

     public static function file($filename){
         ;
     }

     public static function download($filename){

          $filename = realpath($filename);

          //static::header('Content-type', finfo_file(finfo, $filename));

          static::header('Content-Disposition', 'attachment; filename='.$filename);

          readfile($filename);
     }

     public static function view($name, $data){

         static::header('Content-type', 'text/html; charset=UTF-8');

         return static::end(static::$instance->runner->render($name, $data), 'view');

     } 

     private static function end($data, $from){
                     
            if((!is_null($data)) || (!static::isEmpty())){         
            
                 echo $data; 
            }

            $GLOBALS['app']->shutDown();
             
            if(function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }elseif('cli' !== PHP_SAPI){
                static::$instance->closeOutputBuffers(0, true, $from);
            }

                 
          // ob_implicit_flush(TRUE);
          return TRUE;
     }

     private function closeOutputBuffers($targetLevel, $flush, $type){

        $status = ob_get_status(true);
        $level = count($status);

        while ($level-- > $targetLevel
            && (!empty($status[$level]['del'])
                || (isset($status[$level]['flags'])
                    && ($status[$level]['flags'] & PHP_OUTPUT_HANDLER_REMOVABLE)
                    && ($status[$level]['flags'] & ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE))
                )
            )
        ) {
            if($flush){
                if($from === 'text'){
                    ob_flush(); 
                    flush();
                }else($from === 'view'){
                    ob_end_flush();
                }
            }

            /* else {
                ob_end_clean();
            }*/
        }      
     }

     /**
      * Checks if response will have an empty entity body
      *
      * @use Response::isEmpty();
      *
      * @param void
      * @return bool
      *
      * @api
      */

     public static function isEmpty(){

        return in_array(http_response_code(), array(204, 304)); // 'No Content' OR 'Not Modified'
    }

     /**
      * Sets up redirection for client response
      *
      * @use Response::redirect('/');
      *
      * @param string $route
      * @param bool $temporary
      * @return bool
      *
      * @throws InvalidArgumentException
      * @api
      */

     public static function redirect($route, $temporary = TRUE){

           if(!isset($route) || empty($route)){
              throw new \InvalidArgumentException("Cannot redirect to empty destination");
           }

           $root = $GLOBALS['env']['app.root']; 

           $host = $GLOBALS['app']->getHost();

           if(!starts_with($route, "/")){
               $route = "/" . $route;
           }

           if(!starts_with($root, "/")){
               $root = "/" . $root;
           }

           $url = ends_with($host, "localhost")? ($host . $root . $route) : ($host . $route);

           \Logger::info('redirect URL: ' . $url);

           http_response_code(($temporary)? 302 : 301);

           static::header('Location', $url);

           return TRUE;

     }

     public static function setCookie($key, $value){
 
          $config = $GLOBALS['env']['app.settings.cookie'];

          $GLOBALS['app']->pushCookieQueue($key, $value);

          $val = setcookie($key, $value, (time()+$config['max_life']), '/' , $config['domain_factor'], $config['secure'], $config['server_only']);
     }

}

?>