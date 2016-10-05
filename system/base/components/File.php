<?php

class File {

    private static $instance = NULL;

    protected $file_name;
 
    private function __construct(){

    }

    public static function createInstance(){
       
        if(static::$instance === NULL)
            static::$instance = new File();

        return static::$instance;
    }

    public static function read($file_path){
       
        return read_from_file($file_path);
    }

    public static function readAsArray($file_path){
        
        return file($file_path);
    }

    public static function write($file_path, $content){
        return write_to_file($content, $file_path, FALSE);
    }

    public static function make($file_path){

        return (bool) make_file($file_path);
    }

    public static function delete($file_path){

        return (bool) delete_file($file_path);
    }

    public static function folder($folder_name, $hide){

        return (bool) make_folder($folder_name, $hide);
    }

    public static function readChunk($file_path){

        return file_get_contents($file_path);

    }

}

?>