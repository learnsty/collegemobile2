<?php 

 class Activity extends Controller { 

	 protected $params; 

	 public function __construct($params){ 

	 	 if(!Session::has('update_count')){
              Session::put('update_count', 0);
	 	 }

	 	 if(!Session::has('noupdate_count')){
              Session::put('noupdate_count', 0);
	 	 }

		 parent::__construct($params); 

	 }  
 
	 protected function index($models){  

		 # code... 

	 } 

	 protected function streams($models){  

		   $updateCount = Session::get('update_count'); #default 0

		   $noUpdateCount = Session::get('noupdate_count'); #default 0

		   if(Request::isAjax()){

		   	   $lastId = Request::rawHeader('X-Last-Poll-EventID');
		  
		   }else{

		   	   $lastId = Request::rawHeader('Last-Event-ID');
		   }

		   $events = TextStream::getEvents($models['AppActivities'], $noUpdateCount, $lastId); # return an instance of stdClass

		   if($events->noupdate){

		   	   Session::put('noupdate_count', (++$noUpdateCount));
		   }else{
		  
		   	   Session::put('update_count', ($updateCount + $events->updateSize));
		   }

           Response::text($events->data, 200);
	 }

 ?>