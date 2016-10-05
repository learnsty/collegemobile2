<?php

namespace Providers\Services;


class DBService {

   
	protected $connectionHandle = NULL;

	protected $connectionString = '';

	protected $param_types = array(
		"int" => PDO::PARAM_INT,
		"str" => PDO::PARAM_STR
    );

	protected $config; 

	protected $isMSREnabled; // Master-Slave Replication

    public function __construct(array $config){
       
          $this->config = $config;

          $this->isMSREnabled = bool ($this->config['msr_enabled']);

          $engines = $this->config['engines']

          $engine = $engines['mysql'];

          switch($engine['driver']){

          	   case "PDO":
          	       $this->connectionString = ';dbname='.$engine['accessname'].';charset='.$engine['charset'];
          	   break;
              
          }

    }

    public  function __destruct(){ # this will be used to disconnect from DB automatically

    }

    public function __clone(){


    }

    public function getParamTypes(){

    	return $this->param_types;
    }

    public function connect($base_path_env){

    	 $engines = $this->config['engines']

    	 $engine = $engines['mysql'];

    	 $settings = file($base_path_env);

         foreach ($settings as $line){
         	$split = explode('=', $line);
            if(index_of($split[0], 'db_') === 0){
                $engine[substr($split[0], 2)] = $split[1];
            }
         }

    	 if($this->connectionHandle !== NULL){
              return; // do not try to connect to the DB if we already have an active connection 
    	 }
         
         try {

            $this->connectionString = 'mysql:host=' . $engine['hostname'] . $this->connectionString;

            $this->connectionHandle = new \PDO($this->connectionString, $engine['username'], $engine['password'], $engine['settings']);

              #$this->connectionHandle->setAttribute(PDO::FETCH_CLASS);
              $this->connectionHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
              $this->connectionHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $this->config['engines']['mysql'] = $engine;

         }catch (\Exception $e) { ## PDOException ##

             fwrite(STDOUT, $e->getMessage());

         }
    } 

    public function disconnect(){

    }

    public function pool(){

    }

}