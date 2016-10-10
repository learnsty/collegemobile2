<?php

namespace Providers\Services;


use \Contracts\Policies\SessionAccessInterface as SessionAccessInterface;
use \Providers\Tools\RedisStorage as RedisStorage;

class RedisSessionService implements SessionAccessInterface {

    protected $driver;

    protected $sessionId;

    protected $sessionName;

    protected $gabageCollectTimeout;

    protected $sessionCacheExpires;

    protected $sessionCookieExpires;

    protected $sessionBag;

    protected $previousReqTime;

    protected $novelReqTime;

	public function __construct(){

        $this->sessionId = NULL;

        $this->sessionName = '';

        $this->sessionBag = array();
 
		$this->open();		

		$this->cacheRequestTime();
	}

	
	public function __destruct(){
		
		$this->close();
	}

	public function hasKey($key){

        return array_key_exists($key, $this->sessionBag);
	}

	/**
	 *
	 *
	 * @param void
	 * @return void
	 */

	public function cacheRequestTime(){
         $reqtime = array_key_exists('REQUEST_TIME', $_SERVER) ? $_SERVER['REQUEST_TIME'] : NULL;
         if(!isset($reqtime)){
         	$reqtime = time()-2; // just an estimation... no biggie!
         }
         
         $this->previousReqTime = intval($this->getSessionData('_lastreq'));
         
         if($this->previousReqTime === FALSE){
         	 $this->previousReqTime = 0;
         }
         $this->setSessionData('_lastreq', $reqtime);
         $this->novelReqTime = $reqtime;
	}

	/**
     *
     *
     * @param void
     * @return string 
     */
	
	public function getId(){

		return $this->sessionId;
	}
    
    /**
     *
     *
     * @param void
     * @return string 
     */
	
	public function getName(){

		return $this->sessionName;
	}


	public function open(){
        // start the session

		if ((!array_key_exists($this->sessionName, $_COOKIE)) 
			|| $_COOKIE[$customSessionName] == $this->sessionName){
		   $this->sessionId = custom_session_id();
		   $this->sessionName = $GLOBALS['env']['app.custom.sessionname'];				
	    }

        # connect to redis db server...
	}

	public function setSessionCookie(){

        # set session cookie back on client via 'Set-Cookie' response header
	}

	public function close(){

        # write array data back to the redis db server at the {Session_Id} key and close the server connection.
	}

	public function write($key, $value){

	}

	public function read($key){

	}

	public function delete($key){


	}

	private function setSessionData($key, $value){


	}

	private function getSessionData($key){


	}

	private function forgetSessionData($key){

    }

}

?>