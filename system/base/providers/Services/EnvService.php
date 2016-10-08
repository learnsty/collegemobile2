<?php

namespace Providers\Services;

class EnvService {

	    protected $config; 

        protected $appPaths;

        public function __construct(array $config){
       
              $this->config = $config;

              $this->appPaths = new \stdClass();

              $this->setupAppEnvironment();

              $this->setupAppPaths();

        }

        public function getConfig($key){
 
          if(array_key_exists($key, $this->config)){
              return $this->config[$key];
          }
          return NULL;
        }

        private function setupAppEnvironment (){

        	    $app_env = $this->config['app_environment'];
              $can_upload = $this->config['app_uploads']['uploads_enabled'];

                if($app_env == "prod"){
   
					           error_reporting(-1); // don't display PHP error on web page

					           ini_set("expose_php", "Off"); // remove PHP stamp from HTTP response Headers

        				}else if($app_env == "dev"){

        				     error_reporting(E_ALL | E_STRICT);

        				}

                if(!$can_upload){

                    ini_set("file_uploads", "Off");
                }
        }

        private function setupAppPaths(){

                $app_pths = $this->config['app_paths'];

                foreach ($app_pths as $key => $value) {
                    if(is_dir($value)){
                         $this->appPaths->{$key} = $value;    
                    }
                }     

        }

      
        public function exposeEnvironment($root){

             $arr = array(
                  /* paths */
                  'app.path.base'=>$this->appPaths->base,
                  'app.path.upload'=>$this->appPaths->storage . '/cabinet/uploads/',
                  'app.path.assets'=>$this->appPaths->public.'/assets/', 
                  'app.path.storage'=>$this->appPaths->storage.'/',
                  'app.path.packages'=>$this->appPaths->packages.'/',
                  'app.path.views'=>$this->appPaths->views . '/',

                  /* app specifics */
                  'app.root'=> $root,
                  'app.status' =>$this->config['app_environment'],
                  'app.settings.cookie'=>$this->config['app_cookies'],
                  'app.extractuploadedzip'=>$this->config['app_uploads']['can_extract_zip'], #default: TRUE
                  'app.maxuploadsize' => $this->config['app_uploads']['max_upload_size'] # default: 10MB
             );

             return $arr;

        }


}

?>
