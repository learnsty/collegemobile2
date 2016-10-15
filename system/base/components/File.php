<?php

class File {

    private static $instance = NULL;

    protected $file_name;
 
    private function __construct(){

    }

    public static function createInstance(){
       
        if(static::$instance === NULL){
            static::$instance = new File();
            return static::$instance;
        }    
    }

    public static function read($file_path){
       
        return read_from_file($file_path);
    }

    public static function readAsArray($file_path){
        
        return file($file_path);
    }

    public static function write($file_path, $content, $overwrite){

        return write_to_file($content, $file_path, $overwrite);
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

        $content = file_get_contents($file_path);

        /*if('<=PHP5'){
            $read = file_get_contents('viewsey.php', true);
       /* }else{
            $read = file_get_contents('viewsey.php', FILE_USE_INCLUDE_PATH);
        } */

        /*
           // Create a stream
            $opts = array(
              'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                          "Cookie: foo=bar\r\n"
              )
            );

            $context = stream_context_create($opts);

            // Open the file using the HTTP headers set above
            $file = file_get_contents('http://www.example.com/', false, $context);


        */

        sleep(2);

        return $content;

    }

    public static function writeChunk($file_path, $content){

        return file_put_contents($file_path, $content, LOCK_EX);
    }

}

?>