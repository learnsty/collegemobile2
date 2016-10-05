<?php

/*-------------------------------------------------------
 !
 !
 !
 !
 !
 ! 
 !
 !*
 !*
 !*
 !*
 !*
 !*
 --------------------------------------------------------*/

 System::onAppError(function($code, $message, $file, $line){
       $devStatus = $GLOBALS['env']['app.status'];
       
       Logger::error("LearnstyPHP Error: " . $message);
       switch ($devStatus) {
       	case 'dev':
       	   Response::view('errors/report', array('message' => $message, 'file' => $file, 'line' => $line));
       	break;
       	case 'prod':
               Response::view('errors/report', array());
       	break;
       	default:
       		# code...
       	break;
       }
 });

 System::onBlindRoute(function($route){

       Response::view('appstate/missing', array('url' => $route));
 });

 System::onFiltered(function($reqMethod, $route){

 });


?>