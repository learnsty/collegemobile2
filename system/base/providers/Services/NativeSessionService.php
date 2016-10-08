<?php

namespace Providers\Services;

use \Contracts\Policies\SessionAccessInterface as SessionAccessInterface;


class NativeSessionService implements SessionAccessInterface {

    protected $driver;

	public function __construct($driver = ''){

		if (isset($driver)){

			$this->driver = $driver;
		}

		$this->open();
	}

	
	public function __destruct(){
		
		$this->close();
	}

	public function hasKey($key){

        return array_key_exists($key, $_SESSION);
	}

	
	public function getDriver(){

		return $this->driver;
	}

	/**
	 * Put a value in the session.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function write(string $key, string $value){

		return $this->setSession($key, $value);
	}

	/**
	 * Get the session value.
	 *
	 * @param string {$key}
	 * @return mixed
	 */
	public function read(string $key){

		return $this->getSession($key);
	}

	
	public function destroy(string $key){

		if($key !== ''){

			 session_destroy();

			 // session_cache_expire();

			 return TRUE;
		}

		return FALSE;
	}

	public function erase(string $key){

         return $this->forgetSession($key);		
	}

	public function open(){
        // start the session
		if (session_id() == ''){
session_start();
		}
	}

	/**
	 * Writes the session.
	 *
	 * @return void
	 */
	public function close(){
		
		session_write_close();
	}

	private function setSession($key, $value, $overwrite=TRUE){

		$_SESSION[$key] = serialize($value);
	}

	private function getSession($key){

		if ($this->hasKey($key) && isset($_SESSION[$key])){

			return unserialize($_SESSION[$key]);
		}

		return FALSE;
	}

	
	private function forgetSession($key){

		if ($this->hasKey($key) && isset($_SESSION[$key])){

			 unset($_SESSION[$key]);
		}

		return $this->hasKey($key);
	}

}

?>