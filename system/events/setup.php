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
      
       $status = $GLOBALS['env']['app.status'];

       $reporter = $GLOBALS['app']->getRemoteErrorReporter();
       
       Logger::error("[LearnstyPHP - " . $code . "]  " . $message . " in " . $file . " on line " . $line);

       switch ($status) {
       	case 'dev': # Development Environment
       	   Response::view('errors/report', array('err' => $code, 'msg' => $message, 'file' => $file, 'line' => $line));
       	break;
       	case 'prod': # Staging/Production Environment
           if($reporter !== NULL){
               $headers = array(
                  'method' => 'GET',
                  'url' => 'http://appmonitor.collegemobile.net/tracking/errors/', 
                  'fields' => array(
                     'browser' => Request::header('HTTP_USER_AGENT'), # optional field
                     'timing' => Request::header('REQUEST_TIME'), # required field
                     'session' => Session::id() # required field
                   )
               );
               $headers['fields']['details'] = json_encode((compact('code', 'message', 'file', 'line'))); # required field
               $reporter->sendError($headers, function(){

               });
           }
       	break;
       	default:
       		# code...
       	break;
       }
    exit(0);   
 });

 System::onBlindRoute(function($route){
       // This view deals with all 404 errors - View/Page Not Found
       // (where a route could not be found on the routes table for the application)
       Response::view('appstate/missing', array('url' => $route));
 });

 System::onFiltered(function($reqMethod, $route){

 });

 /*System::on('readyChatService', function(){

     return NULL;
 });*/

 System::on('newUserRegistered', function(array $userDetails){
 
     $ip = Request::ip();

     Logger::info("{App Activity}->newUserRegistered  " . (implode(', ', $userDetails)) . " IP address: " . $ip, 'events');

     return NULL;
 });

 System::on('userCreatedClassroom', function($userDetails, $classRoom){

     $ip = Request::ip();

     Logger::info("{App Activity}->userCreatedClassroom  " . (implode(', ', $userDetails)) . " IP address: " . $ip, 'events');

     return NULL;
 });

 System::on('userJoinedClassroom', function($userDetails, $classRoom){

     $ip = Request::ip();

     Logger::info("{App Activity}->userJoinedClassroom  " . (implode(', ', $userDetails)) . " IP address: " . $ip, 'events');

     return NULL;
 });

 System::on('userLeftClassroom', function($userDetails, $classRoom){

     $ip = Request::ip();

     Logger::info("{App Activity}->userLeftClassroom  " . (implode(', ', $userDetails)) . " IP address: " . $ip, 'events');

     return NULL;
 });


?>