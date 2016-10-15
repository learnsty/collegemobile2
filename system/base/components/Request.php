<?php

use \Providers\Core\RequestManager as Manager;

class Request {

     private static $instance = NULL;

     private $method;

     private $url_elements;

     private $inputManger;

     private $headers;

     private $accepted_formats = array(
          'application/json' => 'json',
          'application/xhtml+xml' => 'xml',
          'application/xml' => 'xml',
          'application/x-www-form-urlencoded' => 'html',
          'text/html' => 'html',
          'text/plain' => 'text',
          'multipart/form-data' => 'multipart'
     );

     private $format = '';

     private $base_dir = '';

     protected $parameters;

     private function __construct(){

           $this->headers = getallheaders();

           $this->setRequestFormat();

           $this->inputManger = NULL;

           $this->parameters = NULL;

           $this->method = static::getInfo('REQUEST_METHOD');

           $this->base_dir = static::getInfo('DOCUMENT_ROOT');
     }

     public static function createInstance(){

          if(static::$instance == NULL){
             static::$instance = new Request();
             return static::$instance;
          }   
     }

     public static function header($key){
         
         return static::getInfo($key);
     }

     public static function rawHeader($key){

         $headers = static::$instance->headers;

         if(array_key_exists($key, $headers)){
             return $headers[$key];
         }

         return NULL;
     }

     private static function getInfo($var){
           if(array_key_exists($var, $_SERVER)){
                 return $_SERVER[$var];
           }
           return '';
     }

     public function getInputManager(){

        if($this->inputManger === NULL){
            $this->inputManger = new Manager($this->getParameters());
        }

        return $this->inputManger;
     }

     public static function isAjax(){
         
         $x_header = static::getInfo('HTTP_X_REQUESTED_WITH');
         return (!empty($x_header) && strtolower($x_header) == 'xmlhttprequest'); 

     }

     private function parseRequestInput($headerKeys){
          $sliced;

          $this->parameters = array();
          $this->url_elements = explode('/', static::getInfo('PATH_INFO'));
        
          $qs = static::getInfo('QUERY_STRING');
        
          \Logger::info(" PIPO " . $this->method);
          switch($this->method){
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
                       switch ($this->format){
                          case 'json':
                              $body_params = json_decode($body, TRUE);
                              if(isset($body_params)){
                                  foreach ($body_params as $param_name => $param_value) {
                                      $this->parameters[$param_name] = trim(strip_tags($param_value));
                                  }
                              }
                          break;
                          case 'text':
                              $this->parameters['nothing'] = "";
                          break;
                          case 'html':
                              parse_str($body, $postvars);
                              foreach ($postvars as $field => $value) {
                                  $this->parameters[$field] = trim(strip_tags($value));
                              }
                          break;
                          default:
                             $this->parameters['nothing'] = "";
                          break;
                       }
                   }else if(count($_POST) > 0){
                       $sliced = array_slice($_POST, 0);
                       array_merge($sliced, $this->parameters);
                   }    
               break;
          }
          if($headerKeys !== NULL){ 
              foreach ($headerKeys as $hkey) {
                  $this->parameters[$hkey] = (array_key_exists($hkey, $this->headers)? $this->headers[$hkey] : '');  
              }  
          }  
     }

     private function setRequestFormat(){
         $content_type = static::getInfo('CONTENT_TYPE');
         if(!isset($content_type)){ // this mostly works out for only POST, PUT requests (not GET)
            $content_type = static::getInfo('HTTP_CONTENT_TYPE'); // this works for GET (custom .htaccess plug)
         }
         $this->format = (array_key_exists($content_type, $this->accepted_formats))? $this->accepted_formats[$content_type] : '';
     }

     private function getFormat(){

         return $this->format;
     }

     private function getParameters(){

         return $this->parameters;
     } 

     private function getMethod(){

         return $this->method;
     }

     public static function method(){

          return static::$instance->getMethod();

     }

     public static function referer(){

         return static::getInfo('HTTP_REFERER');
     }

     public static function getHost(){

         return static::getInfo('HTTP_HOST');
     }

     public static function upload($file_upload_folder, &$errors){

         $manager = static::$instance->getInputManager();

         $upload_map = array_swap_values($file_upload_folder, $manager->getFiles()); 

         return $manager->uploadFiles($upload_map, $errors);

     }

     public static function download($file_download_path){

        # code...
     }

     public static function contentType(){
           if(static::$instance !== NULL){
                return static::$instance->getFormat();
           }
           return NULL;     
     }

     public static function input($headerKeys = NULL){

         if(is_null(static::$instance->getParameters())){ 
              static::$instance->parseRequestInput($headerKeys);
         }
 
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
        $uri = static::getInfo('REQUEST_URI');
        $host = static::getInfo('HTTP_HOST');
        if($host === 'localhost' 
          || preg_match('/^\d{2,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(?:\:\d{2,5})?$/', $host)){
            return str_replace('/'. $GLOBALS['env']['app.root'], '', parse_url($uri, PHP_URL_PATH));
        }else{  
            return parse_url($uri, PHP_URL_PATH);
        }   
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