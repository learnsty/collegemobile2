<?php 
/*!
 * Vyke Mini Framework (c) 2016
 * 
 * {Auth.php}
 */

use \Session;
use \Request;
use \Response;
use \Helpers;

use Providers\Tools\LoginThrottle as Throttle;

class Auth {


     private static $instance = NULL;

     protected $loginFields;

     protected $options;

     protected $JWTCookieName;

     protected $guestRoutes;

     protected $throttle;

     protected $clientIP;

     private function __construct(array $options){
        
         $this->loginFields = array('username', 'password', 'user_id');

         $this->clientIP = Request::ip();

         $this->options = $options;

         $this->actionMap = array(
             'GET' => 'read',
             'POST' => 'write',
             'PUT' => 'write',
             'DELETE' => 'write'
         );

         // These routes can be accessed only if the user is not logged in.
         $this->guestRoutes = $this->options['guest_routes'];

         $this->JWTCookieName = '_learnsty_ic'; # Learnsty Identity Carrier

         $this->logGuest();

         if($this->options['throttle_enabled']){

              $this->throttle = new Throttle();
         }
         
     }

     public static function createInstance(array $options){
 
          if(static::$instance == NULL){
               static::$instance = new Auth($options);
               return static::$instance;
          }

     }

     public static function createUser(Model $user, array $props = array()){
           
           return $user->set($props)->exec();
     }

     private function hasSession(){

          $isLogged = Session::has("accessLogin");
          $hasSignedCookie = ($this->options['jwt_enabled'])? Request::hasCookie($this->getJWTCookieName()) : TRUE;

          return ($isLogged && $hasSignedCookie);
     }

     private function getGuestRoutes(){

        return $this->guestRoutes;
     }

     private function fetchRequestAction(){
       
         $method = Request::method();

         if(array_key_exists($method, $this->actionMap)){

             return $this->actionMap[$method];
         }

         return '';
     }

     private function getLoginFields(){

         return $this->loginFields;
     }

     private function getJWTCookieName(){

         return $this->JWTCookieName;
     }

     private function getClientIP(){

         return $this->clientIP;
     }

     private function logGuest(){

          $hasSession = $this->hasSession();

          $props = array(
              'iss' => "LEARNSTY_INC",
              'sub' => 'guest_54773973132539',
              'jti' => Helpers::randomCode(20),
              'routes' => '{}'
          );

          if(!$hasSession){

              $secret = Helpers::generateRandomByPattern("xxxxxxxxyxxxxxxxyxxxxxxxxxyy");

              Session::put("accessLogin", array('id' => 'guest_54773973132539', 'jwt_secret' => $secret, 'role' => 'Guest'));
             
              if($this->options['jwt_enabled']){

                   Response::setCookie($this->getJWTCookieName(), Helpers::createJWT($props, $secret));
              }
          }
     }

     public static function check($routeToPermit = NULL){

           $hasSession = static::$instance->hasSession();

           if($routeToPermit === NULL){
               return $hasSession;
           }

            $session = Session::get('accessLogin');

            $cookie = Request::getCookie(static::$instance->getJWTCookieName());

            $cookieArray  = Helpers::parseJWT($cookie);

            $payload = $cookieArray['payload'];

            if((!array_key_exists('sub', $payload))
                || ($payload['sub'] !== $session['id'])){

                throw new \Exception("Vyke Identity Carrier has been tampered with");
                return FALSE;
            } 
            
            $validStatus = Helpers::validateJWT($cookieArray, $session['jwt_secret']);

            if($validStatus !== NULL){

                // check whether this session is for a guest or a user

                $type = static::$instance->getSession($session['id']);

                $action = static::$instance->fetchRequestAction();

                $userRoutes = $payload['routes'];

                $allRoutes = array_merge(static::$instance->getGuestRoutes(), $userRoutes[$action]);

                switch($type){
                    case 'guest':
                    case 'user':
                      if(in_array($routeToPermit, $allRoutes)){
                          return ($type == 'user');
                      }else{
                          return FALSE;
                      }
                    break;
                    default:
                       return FALSE;
                    break;
                }
            }
           
     }

     public function getUserRole(){

        $hasSession = $this->hasSession();

        if($hasSession){
             
             $session = Session::get("accessLogin");

             return $session['role'];
        }

        return '';

     }

     public static function logUser(Model $user, Model $userRole, Model $userThrottle, array $fields = array()){

        $hasSession = static::$instance->hasSession();

        $session = Session::get("accessLogin");

        $throttle = static::$instance->getThrottle($userThrottle);

        $throttle->updateSessionDataStore($session);

        $throtts = array(); 

        if(!$throttle->isUserBanned()){

             $credentials = static::$instance->getUserCredentials($user, $fields);

        }else{

             $credentials = array();
        }    

        
        if(count($credentials) > 0){

           $secret = Helpers::generateRandomByPattern("xxxxxxxxyxxxxxxxyxxxxxxxxxyy"); 

           $clause = array('user_id' => array('=' , $credentials['user_id']));

           $permissions = $userRole->get(array('role', 'permissions'), $clause)->exec();

           $props = array(
                'iss' => "LEARNSTY_INC",
                'sub' => "user_" . $credentials['user_id'],
                'jti' => Helpers::randomCode(20),
                'routes' => $permission['permissions'],
           );

           if($hasSession){

                Session::forget("accessLogin");
           }
          
           Session::put("accessLogin", array(
                    'id' => "user_" . $credentials['user_id'], 
                    'jwt_secret' => $secret, 
                    'role' => $permission['role']
           ));

           Response::setCookie(static::$instance->getJWTCookieName(), Helpers::createJWT($props, $secret));       

        }else{

            # more code ...

            $throtts = array(
                  'throttle_id' => static::getThrottleId(),
                  'ip_address' => static::$instance->getClientIP(), 
                  'user_id' => $credentials['user_id']
            );

            $throttle->setAttempt($throtts, array('throttle_count')); // UPDATE ON DUPLICATE KEY
            
            if($throttle->attemptLimit()){
                //$userThrottle->let(array('banned' => 1), $throtts)->exec();
                $throttle->ban();
            }

        }   

        return (Session::has("accessLogin"));
     }

     public static function setThrottleId($id){


     }

     public static function getThrottleId(){

     }

     public static function willBeReturnedToURL($url = ''){

         $hasSession = static::$instance->hasSession();

         $session = array();

         if($hasSession){

              $session = Session::get("accessLogin");

         }

         return (array_key_exists('return_to_url', $session) && $url === $session['return_to_url']);

     }

     public static function getReturnToURL(){

         $session = Session::get("accessLogin");
         
         $url = (array_key_exists('return_to_url', $session))? $session['return_to_url'] : "";

         unset($session['return_to_url']);

         Session::forget("accessLogin");

         Session::put("accessLogin", $session);

         return $url;
     }

     private function getThrottle(){

         return $this->throttle;
     }

     public function setReturnToUrl($route){

            $session = Session::get("accessLogin");

            $session['return_to_url'] = $route;

            Session::forget("accessLogin");

            Session::put("accessLogin", $session);
     }

     private function getSession($id, $asType = TRUE){

           $bits = explode('_', $id);

           $session = ($asType)? $bits[0] : $bits[1];

           return $session;
                    
     }

     private function getUserCredentials(Model $user, array $fields = array()){

        $loginFields = $this->getLoginFields();

        if(count($fields) != count($loginFields)){
            $fields = array_slice($fields, 0, 2, TRUE);
        }
 
        $compositeFieldValues = array();

        array_walk($fields, function($value, $key){
            if($key == 'password'){
                 ; // Hash the password with MD5
            }

            $compositeFieldValues[] = array('=', $value);
        });

        $credentials = $user->get($loginFields, array_combine($loginFields,  $compositeFieldValues))->exec();

        return $credentials;

     }

}

?>