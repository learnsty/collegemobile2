<?php 

namespace Providers\Core;


class RequestManager {

     protected $maxUploadSize; # 350000;

     protected $binaryFilesAllowed = array(
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG
    
     );

     protected $textFilesAllowed = array(

     );

     protected $allowedFileExtentions = array(
         'zip',
         'mp4',
         'mp3',
         'docx',
         'mpeg',
         'avi',
         'pdf',
         'png',
         'gif',
         'jpeg',
         'jpg'
     );

     protected $httpInput = array('fields'=>array(), 'files'=>array());    

     public function __construct(array $httpInput){
           
          $this->maxUploadSize = $GLOBALS['env']['app.maxuploadsize'];
          foreach($httpInput as $name => $value){
              if(in_array($name, $_FILES)){
                 $this->httpInput['files'][$name] = ($value === $_FILES[$name]) ? $value : $_FILES[$name];
              }else{
                 $this->httpInput['fields'][$name] = $value;
              }
          }

     }

     public function uploadFiles(array $upload_path_map, array &$errors){

           $upload_base_dir = $GLOBALS['env']['app.path.upload'];
           $results = array();

           foreach ($upload_path_map as $name => $upload_path){
                if (array_key_exists($name, $this->httpInput['files'])){
                    $file = $this->httpInput['files'];
                    $errors[$name] = array();
                    if(defined('UPLOAD_ERR_OK') && 
                       $file['error'] !== UPLOAD_ERR_OK){
                       $errors[$name]['upload_error'] = 'file seems to have a problem';
                       $results[$name] = NULL;
                       continue;
                    }
                    // validate  file size
                    $size = $file['size'];
                    if($size >= $this->maxUploadSize){
                       $errors[$name]['size_error'] = 'file is too large';
                       $results[$name] = NULL;
                       continue;
                    }

                    // sanitizing file name and create unique file name
                    $name = preg_replace("/^A-Z0-9._-/i", "-", $file['name']);
                    $file_parts = pathinfo($name);
                    $ext = $file_parts['extention'];
                    $zip = NULL;
                    
                    if(!in_array($ext, $this->allowedFileExtentions)){
                       $errors[$name]['type_error'] = 'the system cannot process this file';
                       $results[$name] = NULL;
                       continue;
                    }

                    // avoid accidentally overwriting a file (collisions can and do occur)
                    do{
                      if(!file_exists($upload_path)){ // if the directory doesn't exist, create it!
                          make_folder($upload_path);
                      }    
                      $name = get_random_from_string($file_parts['filename']);
                      $target_path = $upload_base_dir . $upload_path . $name . '.' . $ext;
                    }while(file_exists($target_path));

                    if(!is_binary_file($file['tmp_name'], $ext)){
                        // validate (text files)
                        ;
                    }else{
                        //validate (binary files)
                        if($this->isAllowedBinary($file['tmp_name'], $ext)){
                            $errors[$name]['security_error'] = is_image_file($file['tmp_name']) ? 'image file not trusted' : 'binary file not trusted';
                            $results[$name] = NULL;
                            continue;
                        }
                    }
                     
                    if(!is_uploaded_file($file['tmp_name'])){
                         if($ext === "zip"){
                             $zip = new \ZipArcive();
                            //$target_path = str_replace('/'.$name, '/'.$file['name'], $target_path);
                         }
                         $result = move_uploaded_file($file['tmp_name'], $target_path);
                         if(!$result){
                             $errors[$name]['write_error'] = 'file could not be uploaded';
                             $results[$name] = NULL;
                             continue;
                         }
                         if($zip !== NULL && $result){
                            if($GLOBALS['env']['app.extractuploadedzip'] === TRUE){ 
                                 $x = $zip->open($target_path);
                                 if($x === TRUE){
                                      $target_path_temp = str_replace('.zip', '', $target_path);
                                      $zip->extractTo($target_path_temp);
                                      $zip->close();
                                      unlink($target_path);
                                      $target_path = $target_path_temp;
                                 }
                            }   
                         }
                    }else{
                         $errors[$name]['write_error'] = 'file is already created';
                         $results[$name] = NULL;
                         continue;
                    }   
 
                    // set proper permissions on new file
                    chmod($target_path, 0600); // 0644

                    if(count($errors[$name]) == 0){
                        unset($errors[$name]);
                    }

                    // cache unique name for return 
                    $_temp = explode('/', $target_path);
                    $results[$name] = $_temp[count($_temp)-1];
                
                }
           }

           return $results;
     }

     private function isAllowedBinary($file_tmp_name){

           $file_type = exif_imagetype($file_tmp_name);

           return in_array($file_type, $this->binaryFilesAllowed);
     }

     public function getFields(array $field_keys = array()){

         return $this->filterInput($field_keys, $this->httpInput['fields']);
     }

     public function getFiles(array $file_keys = array()){

        return $this->filterInput($file_keys, $this->httpInput['files']);
     }

     private function filterInput(array $vars = array(), $parameters){
            $filtered = NULL;
            if(count($vars) > 0){ 
                $filtered = array();
                foreach($vars as $field){
                    if(array_key_exists($field, $parameters)){
                        $filtered[$field] = $parameters[$field];
                    }
                }
            }else{
                $filtered = $parameters;
            }
            
            return $filtered;
     }

}


?>