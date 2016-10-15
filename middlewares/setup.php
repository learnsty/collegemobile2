<?php

 /*--------------------------------------------------------
  !
  ! Application middlewares are needed to ensure that certain
  ! route requests meetup with special prerequisites before 
  ! these requests are allowed to PASS and the route activated
  !
  ! We set middlewares up here...
  !
  !*
  !*
  !*
  !*
  !*
  !-------------------------------------------------------*/

  System::middleware('csrf', function($currentRoute){
      $result = TRUE;
      Logger::info("csrf middleware executing: method=" . Request::method() . " route=" . $currentRoute);
      if(Request::method() == 'POST'){
         if($currentRoute == '/login/authenticate' 
             || $urrentRoute == '/register/createuser'){
              if(Request::isAjax()){
                  $token = array();
                  $token['_token'] = Request::rawHeader('X-CSRF-Token');
              }else{ 
                  $token = Request::input()->getFields(array('_token'));
              }    
             if(isset($token) 
                && $token['_token'] !== Session::token()){
                  $result = FALSE;
             }
         }    
      }
      return $result;
  });

  System::middleware('modHeaders', function($currentRoute){
        
        // if the request is for SSE (Server-Sent Events), then override caching default for the browser
        if(index_of($currentRoute, 'activity/streams') > -1){
            
              if(Request::isAjax()){

                    TextStream::setForAjax();
              }

              Response::header("Cache-Control", "no-cache");
        }else{
            // if the request is for HTML payload/view/page then
            if(index_of(Request::rawHeader('Accept'), 'text/html') > -1){
              // don't allow this page to be loaded into a frame (<iframe>, <frameset>) except same origin
              // prevent click-jacking
              Response::header("X-Frame-Options",  "SAMEORIGIN"); # DENY
            }  
        }
            
        return TRUE;
  });

  System::middleware('userPermissions', function($currentRoute){
      $signedCookie = NULL;
      $result = TRUE;
      if(Request::hasCookie('_marker__stat_002')){
          $signedCookie = Request::getCookie('_marker__stat_002');
      }
      // validate signed cookie (JWT) 
      return $result;
  });

?>