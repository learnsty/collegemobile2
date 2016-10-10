<?php

class Logger {

    private static $instance = NULL;

    protected $log_file;

    private function __construct(){

         $this->log_file = $GLOBALS['env']['app.path.storage'] . 'exec.log';

    }

    public static function createInstance(){

    	if(static::$instance == NULL){
             static::$instance = new Logger();
             return static::$instance;
        }
    }

    /*switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            default:
                return '';
    }*/

    public static function info($message){

        static::$instance->publishToFile($message, "info");
    }

    public static function warn($message){

        static::$instance->publishToFile($message, "warn");
    }

    public static function error($message){

        static::$instance->publishToFile($message, "error");
    }

    private function publishToFile($line, $type){
        
    	write_to_file($this->marker($type) . ' ~ ' . $line . PHP_EOL . PHP_EOL, $this->log_file, FALSE);
    }

    private function marker($type){
       
        return '' . strtoupper($type) . ':' . date('Y-m-d H:i:s');
    }

}

?>