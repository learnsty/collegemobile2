<?php

class Logger {

    private static $instance = NULL;

    protected $log_file;

    private function __construct(){

         $this->log_file = $_GLOBALS['env']['app.path.storage'] . '/exec.log';

    }

    public static function createInstance(){
    	if(static::$instance == NULL)
             static::$instance = new Logger();

         return static::$instance;

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

    public static function info(string $message){

        static::$instance->publishToFile($message, "info");
    }

    public static function warn(string $message){

        static::$instance->publishToFile($message, "warn");
    }

    public static function error(string $message){

        static::$instance->publishToFile($message, "error");
    }

    private function publishToFile(string $line, string $type){
        
    	write_to_file($this->marker($type) . ' ~ ' . $line . '\r\n', $this->log_file, FALSE);
    }

    private function marker(string $type){
       
        return strtoupper($type) . ':' . date('Y H:i:s');
    }

}

?>