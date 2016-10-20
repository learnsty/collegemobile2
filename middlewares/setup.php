<?php

 /*--------------------------------------------------------
  !
  ! Application middlewares are needed to ensure that certain
  ! route requests meetup with special prerequisites before 
  ! these requests are allowed to PASS and the route activated
  !
  ! We set up middlewares here...
  !
  !
  !* Vyke Mini Framework (c) 2016
  !* 
  !* {setup.php}
  !*
  !* NOTE: DON'T CHANGE THE ORDER OF THE MIDDLEWARES HERE
  !-------------------------------------------------------*/

  // Initilally, every client is logged in as a guest (only if there is no actively logged known user)

  System::middleware('redirectIfUser', function($currentRoute, $auth){
      $result = TRUE;
      if(Request::method() == 'GET'){
          if(!ends_with($currentRoute, '/feeds')){ // route permission (Access Reaffirmation)
              if(Auth::check($currentRoute)){
                  $role = $auth->getUserRole();
                  if($role == 'Teacher' || $role == 'Learner'){
                      return Response::redirect('/' . strtolower($role) . '/feeds');
                  }   
              }
          }  
      }  
      return $result;   
  });

  System::middleware('redirectIfGuest', function($currentRoute, $auth){
      $result = TRUE;
      if(Request::method() == 'GET'){
         if(!starts_with($currentRoute,'/login')){
              if(!Auth::check($currentRoute)){ // route premissions (Access Control)
                  $auth->setReturnUrl($currentRoute);
                  return Response::redirect('/login/' . '?return_to=' . $currentRoute);      
              }    
         }
      }
      return $result;   
  });

  System::middleware('csrf', function($currentRoute){
      $result = TRUE;
      if(Request::method() == 'POST'){
         if(starts_with($currentRoute, '/register')){
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

  System::middleware('responseHeaders', function($currentRoute, $auth){
        
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

?>