<?php

class Helpers {

      const CIPHER_KEY = 'ABCDEF123JKLMNOPQvwxUVWYZ0GHI456789abcghi]|klp[_#$qrstuRSTyz@,!def^*/?><:;+% -=.")(}{mn&o\'`~©';

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

    public static function lowEncipher($plain_str, $key=NULL){
        /* implementing simple substitutuion cipher algorithm with nulls using random number generation +3 points */
          if($key === NULL){
             $key = self::CIPHER_KEY;
          }
          $valid_key = strrev(trim($key));
          $text_length = strlen(trim($plain_str));
          $cipher_str = '';
   
            if($text_length < (strlen(trim($key)) - 3)){
              for($i = 0;$i < $text_length;$i++){
                 $index = index_of($valid_key, char_at(trim($plain_str), $i));
                 $cipher_str .=  ($index > -1) ? char_at($valid_key, index_of($valid_key , char_at(trim($plain_str), $i)) + 3) : char_at($valid_key, $i);
              }
            }
          return $cipher_str;
       }
       
       
       public static function lowDecipher($cipher_str, $key=NULL){
            /* implementing simple substitutuion cipher algorithm with alternate null characters using random number generation -3 points*/
            if($key === NULL){
                $key = self::CIPHER_KEY;
            }
            $valid_key = trim($key);
            $key_length = strlen($valid_key); 
            $plain_str = '';
  
            for($i = 0;$i < $key_length;$i++){
                  $index = index_of($valid_key, char_at(trim($cipher_str), $i));
                  $plain_str .=  ($index > -1) ? char_at($valid_key, index_of($valid_key , char_at(trim($cipher_str), $i)) + 3) : '' ;
            }
            return $plain_str;
       }
       
       public static function delay(string $input, string $secret_key) {
              $hash = crc32(serialize($secret_key . $input . $secret_key));
              // make it take a maximum of 0.1 milliseconds
              time_nanosleep(0, abs($hash % 100000));
       }

       public static function clamp(callable $op, array $args, $time = 100) {
            $start = microtime(true);
            $return = call_user_func_array($op, $args);
            $end = microtime(true);
            // convert float seconds to integer nanoseconds
            $diff = floor((($end - $start) * 1000000000) % 1000000000);
            $sleep = $diff - $time;
            if ($sleep > 0) {
                time_nanosleep(0, $sleep);
            }
            return $return;
       }
       
       public static function objectToArray($anyObj){
           if(is_object($anyObj)){
               $anyObj = get_object_vars($anyObj);
           }
           
           if(is_array($anyObj)){
               return array_map(__FUNCTION__, $anyObj);
           }else{
               return $anyObj;
           }
       }
       
        /**
         * A timing safe equals comparison
         *
         * To prevent leaking length information, it is important
         * that user input is always used as the second parameter.
         *
         * @param string $safe The internal (safe) value to be checked
         * @param string $unsafe The user submitted (unsafe) value
         *
         * @return boolean True if the two strings are identical.
         */
         public static function timingSafeCompare($safe, $unsafe) {
            // Prevent issues if string length is 0
            $safe .= chr(0);
            $unsafe .= chr(0);

            $safeLen = strlen($safe);
            $userLen = strlen($unsafe);

            // Set the result to the difference between the lengths
            $result = $safeLen - $userLen;

            // Note that we ALWAYS iterate over the user-supplied length
            // This is to prevent leaking length information
            for ($i = 0; $i < $userLen; $i++) {
                // Using % here is a trick to prevent notices
                // It's safe, since if the lengths are different
                // $result is already non-0
                $result |= (ord($safe[$i % $safeLen]) ^ ord($unsafe[$i]));
            }

            // They are only identical strings if $result is exactly 0...
            return $result === 0;
       }
       
       public static function encodeJWTObject(array $item){
       
          return base64_encode(json_encode($item));
       }

       public static function createJWT(array $settings, $s_key, $h_key, $hash_algos = "HS256"){
             // @TODO: might change from HMAC Keys to Asymmetric Public/Private Keys at production (RSA)
             $header = array(
                   "typ" => "JWT",
                   "alg" => $hash_algos
             );
              
              $_time = time();
              
              // reserved claims
              $payload = array(
                  "iss" => "LEARNSTY_INC", // issuer -- private claim
                  "iat" => $_time, // issued at -- private claim
                  "sub" => "", // sub -- private claim
                  "exp" => ($_time+3600), // expiration -- private claim
                  "jti" => $s_key, // jwt identifier -- used to prevent token replay attacks -- private claim
                  "profileFields" => $settings // -- public claims
              );
              
              $header = static::encodeJWTObject($header);
              $payload = static::encodeJWTObject($payload);
              
              $signature = hash_hmac('sha256', ($header.".".$payload), $h_key);
              $signature = base64_encode($signature);

              // This will form part and parsel of our SSO signed cookie for SWAP
              $settings = ($header.".".$payload.".".$signature);
              
              return $settings;
       }
       
       public static function parseJWT($webtoken){ // when read from Cookie as a string
            $token_bits = explode('.', $webtoken);
            
            $parse_obj = array();
            $parse_obj['header'] = $token_bits[0];
            $parse_obj['payload'] = $token_bits[1];
            $parse_obj['signature'] = $token_bits[2];
    

            return $parse_obj;          
       }

       public static function uuidV4($inputstr){ // TCAPI sessionRegistration for CollegeMobile (when implemented)
    
            assert(strlen($inputstr) == 16);

            $inputstr[6] = chr(ord($inputstr[6]) & 0x0f | 0x40); // set version to 0100
            $inputstr[8] = chr(ord($inputstr[8]) & 0x3f | 0x80); // set bits 6-7 to 10

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($inputstr), 4));
       }

       public static function generateRandomByPattern($pattern = "xxxxxxxx-xxxxxxxx-xxxr4xxx-xxxxxxkx"){
            
            return preg_replace_callback('/[xy]/', function ($matches){

                              $r = rand(1, (16|0));
                              $v = ($matches[0] == "x") ? $r : ($r&0x3|0x8);
                              return dechex($v);
          
            }, $pattern);
            
       }
       
       public static function validateJWTObject(array $jwt_arr,  string $hash_key){ 
              // pick both $jwt_arr and $h_key from the redis server
              $jwt_plain = static::decodeJWTObject($jwt_arr); 
              $head = $jwt_plain['header'];
              $pload= $jwt_plain['payload'];
              $signature = $jwt_plain['signature'];
              $time = time();
              $message = ($jwt_arr['header'].".".$jwt_arr['payload']);
              // prevents timing attacks
              if(static::timingSafeCompare($signature, hash_hmac('sha256', $message, $hash_key)) /* && $time <= $pload['exp'] */){
                  return $pload;
              }else{
                  return NULL;
              }
       }
       
       public static function decodeJWTObject(array $jwt_obj){
             $token_bits = array();
             /*array_walk($token_bits, function(&$value, $key){
                $value = base64_decode((string) $value);
                if($key == "header" || $key == "payload"){  
                    $value = json_decode($value, TRUE);
                }  
             });*/

            foreach ($jwt_obj as $key => $value) {
                $value = base64_decode((string) $value);
                if($key == "header" || $key == "payload"){  
                    $value = json_decode($value, TRUE);
                }  
                $token_bits[$key] = $value;
             } 

            return $token_bits;
       }

       public static function generateLockCode($prefix = ""){

            return uniqid($prefix, true);
       }


}

?>