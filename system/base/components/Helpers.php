<?php

class Helpers {

      private static $instance = NULL;

      private function __construct(){


      }

      public static function createInstance(){

         if(static::$instance == NULL){
            static::$instance = new Helpers();
            return static::$instance;
         }  
      }

    public static function randomCode($length = 10){
        $code = '';
        $total = 0;

        do
        {
            if (rand(0, 1) == 0)
            {
                $code.= chr(rand(97, 122)); // ASCII code from **a(97)** to **z(122)**
            }
            else
            {
                $code.= rand(0, 9); // Numbers!!
            }
            $total++;
        } while ($total < $length);

        return $code;
    }	


    public static function emptyCheck($value){

       return !isset($value) || empty($value); 	
    }



    public static function limitWords($string, $word_limit){
        $words = explode(" ",$string);
        return implode(" ",array_splice($words,0,$word_limit));
    }


    public static function dateSet($date,$dateadd){
        $date = new DateTime($date);
        date_add($date, new DateInterval("P".$dateadd."D"));
        return $date->format("d-m-Y");
    }

    public static function encodeValue($value,$padding='[abcd1234abcdefg]') {
    	if(!static::emptyCheck($padding)) {
    		$value = urlencode(base64_encode($padding.$value.$padding));
    	} else {
    		$value = urlencode(base64_encode(trim($value)));
    	}
    	return $value;
    }



    public static function decodeValue($value, $padding='[abcd1234abcdefg]') {
    	
    	$value = base64_decode(urldecode($value));
    	if(!static::emptyCheck($padding)) {
    		$value = str_replace($padding,'',$value);
    	}
    	return $value;
    }

}

?>