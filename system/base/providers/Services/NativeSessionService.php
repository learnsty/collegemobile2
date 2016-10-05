<?php

namespace Providers\Services;

use \Contracts\Policies\SessionAccessInterface as SessionAccessInterface;


class NativeSessionService implements SessionAccessInterface {

    protected $driver;

	public function __construct(string $driver = null)
	{
		if (isset($driver))
		{
			$this->driver = $driver;
		}

		$this->open();
	}

	
	public function __destruct()
	{
		$this->close();
	}

	public function hasKey($key){

        return array_key_exists($key, $_SESSION);
	}

	
	protected function getDriver()
	{
		return $this->driver;
	}

	/**
	 * Put a value in the Sentry session.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function write($key, $value){

		$this->setSession($key, $value);
	}

	/**
	 * Get the Sentry session value.
	 *
	 * @return mixed
	 */
	public function read($key)
	{
		return $this->getSession($key);
	}

	
	public function destroy($key)
	{
		$this->forgetSession($key);
	}

	private function open()
	{
		// Let's start the session
		if (session_id() == '')
		{
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

	private function setSession($key, $value){

		$_SESSION[$key] = serialize($value);
	}

	private function getSession($key){

		if ($this->hasKey($key) && isset($_SESSION[$key])){

			return unserialize($_SESSION[$key]);
		}
	}

	
	private function forgetSession($key){

		if (isset($_SESSION[$key])){

			 unset($_SESSION[$key]);
		}
	}

}

?>