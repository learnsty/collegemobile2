<?php

class Controller {

	protected $params;

    public function __construct($params){

            $this->params = $params;
             
    }

    public function index($models){

        Response::view('site/index', array());	
    } 

    public function feeds($models){
        
        // Prep Code
    }

    public function profile($models){

    	// Prep Code
    }

}

?>