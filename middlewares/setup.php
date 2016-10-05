<?php

 /*--------------------------------------------------------
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
  !-------------------------------------------------------*/

  System::middleware('csrf', function($currentRoute){
      $result = TRUE;
      if(Request::method() == 'POST'){
         if($currentRoute == 'login/authenticate' 
             || $urrentRoute == 'register/createuser'){
             $token = Request::input()->getFields(array('_token'));
             if($token !== Session::token()){
                  $result = FALSE;
             }
         }    
      }
      return $result;
  });

  System::middleware('permissions', function($currentRoute){
      $signedCookie = NULL;
      if(Request::hasCookie('_marker__stat_002')){
          $signedCookie = Request::getCookie('_marker__stat_002');
      }
      // validate signed cookie (JWT) 

  });

?>