<?php

class Controller {

	protected $params;

    public function __construct($params){

            $this->params = $params;
             
    }

    protected function index($models){

        Response::view('site/index', array());	
    } 

    protected function feeds($models){
        
        // Prep Code
    }

    protected function profile($models){

    	// Prep Code
    }

}

?>