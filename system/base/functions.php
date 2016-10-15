<?php
/**
 * LearnstyPHP (c) 2016
 * {functions.php}
 */
  
  
if(! function_exists('char_at') ){
    function char_at($str, $num){
	        if(gettype($str) == 'string' && gettype($num) == 'integer'){
               if($num > -1 && $num < strlen($str)){
	                for($i = 0; $i < strlen($str); $i++){
		               if($i == $num){
			              return substr($str, $i, ($i + (1 - $i)));
			           }
		            }
	            }else{ return -1; }
	        }
	}
}

if(! function_exists('getallheaders')){
   function getallheaders(){
       return array();
   }
} 

// Fix for overflowing signed 32 bit integers,
// works for sizes up to 2^32-1 bytes (4 GiB - 1):
if(! function_exists('fix_integer_overflow')){
	function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }
}
  
if(! function_exists('index_of') ){
    function index_of($str, $seed, $radix  = -1){
	      $mixed = FALSE;
	      if($radix == -1){
             $mixed = strpos($str, $seed);
	      }else if($radix > -1){
	         $mixed = strpos($str, $seed, $radix);
	      }
		  return (gettype($mixed) === 'integer')? $mixed : -1;
	}
}

if(! function_exists('ignorecase_index_of') ){
    function ignorecase_index_of($str, $seed, $radix = -1){
	      if($radix == -1){
               return stripos($str, $seed);
	      }else if($radix > -1){
	           return stripos($str, $seed, $radix);
	      }else{
	          return -1;
	      }
	}
}

  
if(! function_exists('str_compare_to') ){ 
    function str_compare_to($str1, $str2){
        if(gettype($str1) == 'string' && gettype($str2) == 'string'){
           if(strcmp($str1,$str2) == 0){
	         return 0;
	       }else{ return 1; }
	    }else{
	       return -1;
	    }
    }
} 

if(! function_exists('index_of_any') ){
    function index_of_any($str, $seed, $arr){

    }
}

if(! function_exists('http_response_code') ){
   function http_response_code($code = NULL){
         $text = '';
         if($code === NULL){
             return $text;
         }
         switch(intval($code)){
               case 100:
                  $text = 'Continue';
               break;
               case 101:
                  $text = 'Switching Protocols';
               break;
               case 200:
                  $text = 'OK';
               break;
               case 201:
                  $text = 'Created';
               break;
               case 202:
                  $text = 'Accepted';
               break;
               case 203:
                  $text = 'Non-Authoritative Information';
               break;
               case 204:
                  $text = 'No Content';
               break;
               case 205:
                  $text = 'Reset Content';
               break;
               case 206:
                  $text = 'Partial Content';
               break;
               case 401:
                  $text = 'Unauthorized';
               break;
               default:
                  return $text;
               break;                      
         }

         $proto = (isset($_SERVER['SERVER_PROTOCOL'])? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
         header($proto . ' ' . $code . ' ' . $text);
   }
}

if(! function_exists('last_index_of') ){
    function last_index_of($str, $seed){
      if(gettype($str) == 'string' && gettype($seed) == 'string'){
         $rstr = strrev($str);
         $lx = (strlen($str)-1) - (index_of($rstr, $seed)); 
             if(index_of($str, $seed, ($lx-1)) + index_of($rstr, $seed) == (strlen($str)-1)){
                                   return $lx;
             }else{ return -1; } 
      }
    }
}

if(! function_exists('all_index_of') ){
    function all_index_of($str, $seed){

    }
}	

if(! function_exists('generate_uniq_string') ){
    function generate_uniq_string($input){  
        if(!isset($input)){
           $input = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*";
        }
        $index = strlen($keyset); 
        $modT = rand(1, 28);
        $keyset = str_shuffle($input);
	      $keyset = substr($keyset, 0 , ($index * 2) - ($index + $modT));
	      $crypt = sha1($keyset);
	      return strrev($crypt);
    }
}

if(! function_exists('starts_with') ){
   function starts_with($str, $begin, $ignorecase = FALSE){
       $len = strlen($begin);
       $slen = strlen($str);
       $sub = substr($str, 0, $len);
       if((gettype($str) == 'string' && gettype($begin) == 'string') && ($slen > $len)){
	        if($ignorecase){
		       $begin = strtolower($begin);
			   $sub = strtolower($sub);
		    }
             if(strcmp($sub, $begin) == 0){ 
                 return TRUE;
             }else{
                return FALSE;
             }
		  
       }else{ 
	          return NULL; 
	   }  
   }
}

if(! function_exists('ends_with') ){
    function ends_with($str, $end){
        $len = strlen($end);
        $slen = strlen($str);
        $sub = substr($str, (-$len), ($slen-1));
        if((gettype($str) == 'string' && gettype($end) == 'string') && ($slen > $len)){
            if(strcmp($sub, $end) == 0){
                 return TRUE;
            }else{
                 return FALSE;
            }
        }else{ 
		        return NULL; 
		}
    }
}

if(! function_exists('region_matches') ){
    function region_matches(){

    }
}


if(! function_exists('get_file_extension') ){
    function get_file_extension($file_path){
        $isdir = false;
        $args = func_get_args();
        $filename = '';
        if(strpos($file_path,'/') > 0){
            $isdir = true;
	        $filename = basename($args[0]);
        }else{
            $filename = $file_path;
        }
        $ext = explode('.',$filename);
        return $ext[1];
    }
}

if(! function_exists('is_image_file') ){
    function is_image_file($file){
        $est = get_file_extension($file);
        $result = false;
            switch(strtolower($est)){
               case "jpg":
	              $result =  true;
	           break;
	           case "png":
	              $result =  true;
	           break;
	           case "gif":
	              $result =  true;
	           break;
	           case "jpeg":
	              $result =  true;
	           break; 
               default:
                  $result = false;	
            }
            return $result;
    }
}

if(! function_exists('get_file_name') ){
    function get_file_name($file_path){
        $isdir = false;
        $args = func_get_args();
        $filename = '';
        if(strpos($file_path,'/') > 0){
            $isdir = true;
	        $filename = basename($args[0]);
        }
        else{
            $filename = $file_path;
        }
        $ext = explode('.',$filename);
        return $ext[0];
    }
}

if(! function_exists('custom_session_id') ){
    function custom_session_id($native = FALSE){
         return substr(generate_uniq_string(), 1, ($native? 31 : 23));  
    }
}    

if(! function_exists('is_binary_file') ){
    function is_binary_file($file, $asString=FALSE){
        $out = array();
        if(index_of($_SERVER['SERVER_SOFTWARE'], "Linux") > -1
          || index_of($_SERVER['SERVER_SOFTWARE'], "Unix") > -1){
           exec("file -bi" . $file, $out);
            return $asString? $out[0] : index_of($out[0], "charset=binary") > -1;
        }
    }
}

if(! function_exists('delete_file') ){
    function delete_file($file){
        if(file_exists($file)){
           return system('del '.$file);
        }
    }
}

if(! function_exists('reduce_boolean')){
    function reduce_boolean($accum, $item){
      $accum &= $item;
      return $accum;
    }
}

if(! function_exists('get_random_from_string') ){
    function get_random_from_string($str){
       return generate_uniq_string($str);
    }
}

if(! function_exists('update_in_keys')){
    function update_in_keys($key){
        return "$key = ?";
    }
}

if(! function_exists('array_swap_values')){
    function array_swap_values($str, $arr){
        $ret = array();
        foreach($arr as $key => $val){
            $ret[$key] = $str;
        }
        return $ret;
    }
}

if(! function_exists('make_file') ){
    function make_file($file){
	   return system('echo. >> '.$file); 
	}
}

if(! function_exists('make_folder') ){
    function make_folder($folder, $hide){
	    $val = mkdir($folder);
		  if($hide === TRUE)
		     system('attrib +h +s '.$folder); # TODO: this command may not work on linux check again

		  return $val;
	}
}

if(! function_exists('delete_text_from_file') ){
    function delete_text_from_file($file, $str){
           $isdir = is_dir($file); 
           $isfile = is_file($file);
           $oldx = ($isfile) ? file_get_contents($file) : $isdir ? file_get_contents(basename($file)) : "";
           if(index_of($oldx, $str) > -1){
              $newx =  str_replace($str, "", $oldx);
              file_put_contents($newx, $file);
              return TRUE;
           }
           return FALSE;   
    }
}

if(! function_exists('get_random_as_range') ){
    function get_random_as_range($useText=FALSE, $len=10, $range=10){
        $text = array();
        for($i=0;$i < $len; $i++){
            $rnd = rand(0, $range);
	        if($useText)
                $text[] = base_convert(mt_rand(0xaaff355db, 0x543dbbca310) >> 0xffa, 10, 36);
        }
        return join($text);
    }
}


if(! function_exists('write_to_file') ){
    function write_to_file($entry, $file, $overwrite=true){
         $is_dir = is_dir($file); // (index_of(trim($file), '/') > -1 && last_index_of(trim($file),'.') > -1);
         $is_file = is_file($file) && file_exists($file); //(index_of(trim($file), '/') == -1 && last_index_of(trim($file),'.') > -1);
   
         if($is_dir || $is_file){      
            if(!$overwrite && get_file_extension($file) == 'rtc' || get_file_extension($file) == 'log'){
                 $fh = fopen($file, 'a');
            }
            else if(!$overwrite && get_file_extension($file) == 'txt'){
                 $fh = fopen($file, 'at');
            }
      			else if(!$overwrite && get_file_extension($file) == 'json'){
      			     $fh = fopen($file, 'a');
      			}
            else if($overwrite){
                 $fh = fopen($file, 'w+');
            }else{}
   
            if(isset($fh) && gettype($entry) == 'string'){
	            fwrite($fh, $entry);
		        fclose($fh);
		        return TRUE;
	        }else{
	            return FALSE;
	        }
        }else{ 
		       return FALSE; 
	    }	
    }
}

if(! function_exists('read_from_file') ){
    function read_from_file($file){
        if(gettype($file) == 'string' && !is_dir($file) && is_file($file) && file_exists($file)){
            $fr = fopen($file, 'r');
   
            while(!feof($fr)){
               $reader = fread($fr, filesize($fr)); 
            }
	 
	        fclose($fr);
	        return $reader;
        }
    }
}

if(! function_exists('is_file_in_dir') ){
    function is_file_in_dir($file, $dir){
        $isDir = is_dir($dir);
        if(gettype($dir) == 'string' && gettype($file) == 'string' && $isDir){
            $dh = opendir($dir);
            while($filename = readdir($dh)){
	            if($filename == $dir.'/'.$file && is_file($dir.'/'.$file)){
		          return TRUE;
		        }else{
		          return false;
		        }
	        }
        }
    }
}

/*if(! function_exists('read_from_file_as_array')){
     function read_from_file_as_array($file){
     	 if(is_file($file)){
              return file($file);
         }     
     }
}*/

########### DB access function #############

/*
 HOW TO USE THE BELOW FUNCTIONS
 
 db_get("SELECT * FROM tbl_client_testimonies WHERE client = ?", array("str" => "Alexis"), 3);
 db_put("INSERT/UPDATE INTO tbl_news_subscribers (email, location, created_at) SET logged = ? / VALUES (?, ?, ?)", array("str" => "xyz@gmail.com", "str" => "Abuja", "int" => time(), TRUE);
 db_delete("DELETE * FROM tbl_saas_pricing WHERE admin_id = ?", array("str" => ""), TRUE);
 
 @TODO: THINKING OF USING db_let("UPDATE tbl_saas_pricing SET =  WHERE admin_id = ?"); FOR UPDATE QUERIES ??
 
 */


if(! function_exists('db_get') ){ ## SQL SELECT
    function db_get($pdo, $param_types, $query = "", $params = array(), $rows_limit = NULL, $resultset_cols_filter = array()){
		  
		  if(strlen($query) == 0 || !is_array($params) || !is_object($pdo)){
		      return NULL;
		  }
		  
		  $query = trim($query);
		  
		  
		  if(!starts_with($query, "SELECT", TRUE)){
		      return NULL;
		  }
		  
		  //if(count($params) !== count(all_index_of($query, "?"))){
		    //  throw new Exception("Error: 'db_get' function has entered an unstable state: insufficient/excessive query paramters supplied");
		 // }
          
		try{
		  
		  $param_count = 0;
		  $set_array = array();
		  $set_filter = array();
          $stmt = $pdo->prepare($query);
		  foreach($params as $type => $param){ // params filter by rows (obviously !?)
		     $stmt->bindParam(++$param_count, ("int" != $type? $param : intval($param)), $param_types[$type]);
		  }
		  
             if($stmt->execute()){
    
                while($resultset = $stmt->fetch(PDO::FETCH_ASSOC)){
                
				    if(count($resultset_cols_filter) > 0){
				       foreach($resultset as $key => $val){
					      if(in_array($resultset_cols_filter, $key)) // filtering by columns...
                              $set_filter[$key] = $val;
				       }
					   $set_array[] =  (count($set_filter) > 0)? $set_filter : $resultset;
                       $set_filter = array();					   
					}else{  
					   $set_array[] = $resultset;
					}  
                }
      
                if($stmt->rowCount() > 0){ 
                    if(count($set_array) > 0){				
                           return $set_array;
					}else{
					       return FALSE;
					}	   
                }else{
				
				}
			}else{
			        return NULL;
			}	
          }catch(\Exception $e){
                  throw $e;
          }
	   }
}

if(! function_exists('db_put') ){ ## SQL INSERT
    function db_put($pdo, $param_types, $query = "", $params = array(), $commit = FALSE, $transact = TRUE){
		  
		  if(strlen($query) == 0 || !is_array($params) || !is_object($pdo)){
		      return NULL;
		  }

		  $query = trim($query); 
		  
		  if(!starts_with($query, "INSERT", TRUE)){
		      return NULL;
		  }
		  //if(count($params) !== count(all_index_of($query, "?"))){
		     //  throw new Exception("Error: 'db_put' function has entered an unstable state: insufficient/excessive query paramters supplied");
           //}
		  
		  
		  try{
		  
		       $param_count = 0;
		       $set_array = array();
               $stmt = $pdo->prepare($query);
		       foreach($params as $type => $param){
		           $stmt->bindParam(++$param_count, ("int" != $type? $param : intval($param)), $param_types[$type]);
		       }
	   
              if($transact)
                 $pdo->beginTransaction();
 
              if($stmt->execute()){
                   
				   if($commit)
				       $pdo->commit();
				
                 if(starts_with($query, "INSERT"))				
				             return $pdo->lastInsertId();
	             else
				             return 0;
				   
	          }else{
             
                  $pdo->rollBack();
                  return NULL;						   
              } 		
         
          }catch(\Exception $e){
                 throw $e;
          } 
    }
}

if(! function_exists('db_post') ){  ## SQL UPDATE
    function db_post($pdo ,$param_types, $query = "", $params = array(), $commit = FALSE, $transact = TRUE){
	
		  
		  if(strlen($query) == 0 || !is_array($params) || !is_object($pdo)){
		      return NULL;
		  }
		  $query = trim($query); 
		  
		  if(!starts_with($query, "UPDATE", TRUE)){
		      return NULL;
		  }
	}
}	

if(! function_exists('db_del') ){ ## SQL DELETE
    function db_del($pdo ,$param_types, $query = "", $params = array(), $commit = FALSE){
	     
		  
		  if(strlen($query) == 0 || !is_array($params) || !is_object($pdo)){
		      return NULL;
		  }
		  $query = trim($query); 
		  
		  if(!starts_with($query, "DELETE", TRUE)){
		      return NULL;
		  }
	}
}

if(! function_exists('db_copy') ){ ## SQL INSERT/SELECT
    function db_copy($query, $params = array()){
	
	}
}	


?>