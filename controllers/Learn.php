<?php 

class Learn extends Controller {

        protected $params;

        public function __construct($params){

             parent::__construct($params);
             
        }

        // @Override - Master View

        public function index($models){

            Response::view('app/index', array());
        }

        // @Override - Partial View (AngularJS)

        public function feeds($models){

            Response::view('app/learn/partials/feeds', array());
        }

        // @Override - Partial View (AngularJS)
 
        public function profile($models){

           Response::view('app/learn/partials/profile', array());
        }

  }

?>