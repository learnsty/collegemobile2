<?php

use \Providers\Core\RequestManager as Manager;

class Request {

     private static $instance = NULL;

     protected static $method = NULL;

     protected $url_elements;

     protected $inputManger;

     protected $accepted_formats = array(
          'application/json' => 'json',
          'application/x-www-form-urlencoded' => 'html',
          'text/plain' => 'text',
          'multipart/form-data' => 'multipart'
     );

     protected $format = '';

     protected $parameters = array();

 
     private function __construct(){

           // thou shalt not .....
           session_cache_limiter("must-revalidate");

           $this->setRequestFormat();

           $this->parseRequestInput();

           $this->inputManger = new Manager($this->getParameters());

     }

     public static function createInstance(){

         static::$method = static::getInfo('REQUEST_METHOD');

         if(static::$instance == NULL)
             static::$instance = new Request();

         return static::$instance;
     }

     public static function header($key){
         
         return static::getInfo($key);
                 
     }

     private static function getInfo($var){
           if(array_key_exists($var, $_SERVER)){
                 return $_SERVER[$var];
           }
           return '';
     }

     public function getInputManager(){

        return $this->inputManger;
     }

     public static function isAjax(){
         
         $x_header = static::getInfo('HTTP_X_REQUESTED_WITH');
         return (!empty($x_header) && strtolower($x_header) == 'xmlhttprequest'); 

     }

     private function parseRequestInput(){
          $sliced;
          $this->url_elements = explode('/', static::getInfo('PATH_INFO'));
          $qs = static::getInfo('QUERY_STRING');
          switch(static::$method){
               case "JSONP":
               case "GET": 
                    if(isset($qs)){
                        parse_str($qs, $this->parameters);
                    }else if(count($_GET) > 0){
                        $sliced = array_slice($_GET, 0);
                        array_merge($sliced, $this->parameters);
                    }
               break;
               case "POST":
               case "PUT":
                   $body = file_get_contents("php://input");
                   if(isset($body)){
                       switch ($this->format) {
                          case 'json':
                              $body_params = json_decode($body);
                              if(isset($body_params)){
                                  foreach ($body_params as $param_name => $param_value) {
                                      $this->parameters[$param_name] = trim(strip_tags($param_value));
                                  }
                              }
                          break;
                          case 'text':
                              $this->parameters['null'] = "";
                          break;
                          case 'html':
                              parse_str($body, $postvars);
                              foreach ($postvars as $field => $value) {
                                  $this->parameters[$field] = trim(strip_tags($value));
                              }
                          break;
                          default:
                             
                          break;
                       }
                   }else if(count($_POST) > 0){
                       $sliced = array_slice($_POST, 0);
                       array_merge($sliced, $this->parameters);
                   }    
               break;
          }  
     }

     private function setRequestFormat(){
         $content_type = static::getInfo('CONTENT_TYPE');
         if(!isset($content_type)){ // this mostly works for only POST, PUT requests
            $content_type = static::getInfo('HTTP_CONTENT_TYPE'); // this works for GET (custom from htaccess)
         }
         $this->format = (array_key_exists($content_type, $this->accepted_formats))? $this->accepted_formats[$content_type] : '';
     }

     private function getFormat(){

         return $this->format;
     }

     private function getParameters(){

         return $this->parameters;
     } 

     public static function method(){

          return static::$method;

     }

     public static function referer(){

         return static::getInfo('HTTP_REFERER');
     }

     public static function upload(string $file_upload_folder, &$errors){

         $manager = static::$instance->getInputManager();

         $upload_map = array_swap_values($file_upload_folder, $manager->getFiles()); 

         return $manager->uploadFiles($upload_map, $errors);

     }

     public static function download(string $file_download_path){

     }

     public static function contentType(){
           if(static::$instance !== NULL){
                return static::$instance->getFormat();
           }
           return NULL;     
     }

     public static function input(){
           
          return static::$instance->getInputManager();     
     }

     public static function ip(){
         $cip = static::getInfo('HTTP_CLIENT_IP');
         $xip = static::getInfo('HTTP_X_FORWARDED_FOR');
         if(!empty($cip)){ # client own ip
             return $cip;
         }else if(!empty($xip)){ # if the server is proxied!
             return $xip;
         }else{
             return static::getInfo('REMOTE_ADDR'); # fallback finally to actual server ip
         } 
     }

     public static function uri(){

        return parse_url(static::getInfo('REQUEST_URI'), PHP_URL_PATH);
     }

     public static function hasCookie($key){
        
        return array_key_exists($key, $_COOKIE);
     }

     public static function getCookie($key){
          if(static::hasCookie($key)){
               return $_COOKIE[$key];
          }
          return NULL;
     }

}

?>