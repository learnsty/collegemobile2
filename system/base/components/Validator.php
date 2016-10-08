<?php

use \Providers\Tools\InputFilter as Filter;

class Validator{

    const FAILED = "failed!";

    protected static $instance = NULL;

    protected static $allowed = NULL;

    protected static $allowed_errors = false;

    protected static $errors = array();

    private $filter = NULL;

    private function __construct(){

         $this->filter = new Filter();

    }

    public static function createInstance(){

        if(static::$instance == NULL){
            static::$instance = new Validator();
            return static::$instance;
        }    
    }

    public static function getErrors(){
	      
        if(count(static::$errors) == 0){
		          return NULL;
		    }

        return static::$errors;
    }

    public static function checkAndSanitize($data, $fieldRules){ // $data should be the data from $_POST super global always...
         $valid = TRUE;
         $results = array();
         $fieldvalue = '';
         $pattern = '';
         
         // extract all callbacks

         foreach($fieldRules as $fieldname => $rule){
            $callbacks = NULL;
            $fieldvalue = '';
            $pattern = '';
            if(is_array($rule)){
               static::$allowed = $rule["allowed"];
               $callbacks = explode("|", $rule["rule"]);
			         $callbacks[] = 'useAllowed';
            }else if(is_string($rule)){
               $index = index_of($rule, '/');
               if($index > -1){
                  $pattern = substr($rule, $index);
               }
               $callbacks = explode("|", substr($rule, 0, $index-1));
            }else{
               throw new Exception("Validation could not process rule for $fieldname");
            }
  
            // setup callbacks to work on each field(s)
            foreach($callbacks as $callback){
               $fieldvalue = $data[$fieldname];
               // escaping ==> $fieldvalue = htmlspecialchars($fieldvalue, ENT_QUOTES, 'UTF_8');
               $valid = static::$callback($fieldvalue, $fieldname, $pattern);
               if(!is_boolean($valid)){
                    static::$errors[] = $valid; // read out the error message in $valid first!!
				            $valid = TRUE; // then set it to the default boolean value
               }
            }
             
            $magic_quotes_active=get_magic_quotes_gpc();
            if(function_exists("mysql_real_escape_string")){ //ie. PHP >= v4.3.0 
                if($magic_quotes_active){
                       $fieldvalue = stripslashes($fieldvalue);
                }
                $fieldvalue = mysql_real_escape_string($fieldvalue);
            }else{ // PHP < v4.3.0
                ;
            }  

            $results[] = $fieldvalue;
            
         }

         return $results; //$valid;   
    }

    private static function email(&$value, $fieldname, $pattern){
	       $valid =  static::$instance->filter->sanitizeInput($value, 4); // filter_vars($value, FILTER_VALIDATE_EMAIL);
		   if($valid === FALSE){
		     return "The is not a valid email address"; 
		   }
		   return $valid;
		
    }
	
	private static function useAllowed($value, $fieldname, $pattern){
  	   if(!isset(static::$allowed)){
  	      return "Dropdown options not accessible for $fieldname";
  	   }
       $valid = in_array($value, static::$allowed);
  	   return ($valid === FALSE)? "This $value is invalid" : $valid;
	}

  private static function required($value, $fieldname){
        $valid = !empty($value);
    		if($valid === FALSE){
    		  return "The $fieldname is required";
    		}
    		return $valid;
  }

  private static function full_name(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid name";
         }
         return $valid;
  }

  private static function mobile_number(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid mobile number";
         }
         return $valid;
  }

  private static function password(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid password";
         }
         return $valid;
  }

  private static function cmi_activity(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid SCORM cmi_activity value";
         }
         return $valid;
  }

  private static function cmi_learner(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid SCORM cmi_learner value";
         }
         return $valid;
  }

  private static function adl_course(&$value, $fieldname, $pattern){
         $value = trim($value);
         $valid = (bool) preg_match($pattern, $value);
         if($valid === FALSE){
            return "This is not a valid SCORM adl_course value";
         }
         return $valid;
  }

}



/*

$fieldRules = array(
           'email' => "email|required",
           'password' => "password|required|/^(?:[^\t\r\n\f\b\~\"\']+)$/i",
           'sex' => array('allowed'=>"male, female", 'rule'=>"required"),
           'full_name' => 'full_name|required|/^(?:[^\S\d\t\r\n]+)$/i',
           'mobile_number' => 'mobile_number|required|/^(?:070|071|081|080|090|091)(?:\d{8})$/'
);

*/
?>
