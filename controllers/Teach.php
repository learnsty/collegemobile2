<?php 

class Teach extends Controller {

   protected $params;

   public function __construct($params){

      $this->params = $params;   
   }

   // @Override - Master View

   public function index($models){

      Response::view('app/index', array());
   }

   // @Override - Partial View (AngularJS)

   public function feeds($models){

      Response::view('app/teach/partials/feeds', array());
   }

   // @Override - Partial View (AngularJS)

   public function profile($models){

      Response::view('app/teach/partials/profile', array());
   }

} 

?>