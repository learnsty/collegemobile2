<?php

# https://www.collegemobile.net/webhook/git-payload

/*!------------------------------------------------------
! 
! This is the routes file for configuring all route urls
! and their corresponding HTTP verb, models and parameters
!
!
!
! *
! *
! *
! *
! *
! *
----------------------------------------------------------*/

 /* Master Views */
 
 Router::bind('/', array());
 
 Router::bind('/login', array());

 Router::bind('/account/forgotpassword', array('models' => array('User', 'UserRole')));

 Router::bind('/account/resetpassword', array('models' => array('User', 'UserRole')));

 Router::bind('/account/profile', array('models' => array('User', 'UserRole')));

 Router::bind('/account/activate', array('models' => array('User')));
 
 Router::bind('/register/@category', array('models' => array(), 'params' => array('category' => '/^[a-z]+/i')));
 
 Router::bind('/teach', array('models' => array('Teacher')));
 
 Router::bind('/learn', array('models' => array('Student')));
 
 Router::bind('/scorm/runtime/', array('models' => array()));
 
 
 
 
 
 
 /* Partial Views (AngularJS Templates) */

 Router::bind('/teach/', array('models' => array('Project'), 'ajax' => true));
 
 Router::bind('/learn/', array('models' => array('Project'), 'ajax' => true));
 
 
 
 
 
 
 /* JSON APIs */

 Router::bind('/login/authenticate/@fingerprint', array('verb' => 'post', 'models'=> array('User', 'UserThrottle', 'UserRole'), 'ajax' => true, 'params' => array('fingerprint' => '/[a-z0-9_]+/i')));
 
 Router::bind('/register/createuser/@category', array('verb' => 'post', 'models' => array('User', 'UserRole'), 'ajax' => true, 'params' => array('category' => '/^[a-z]+/i')));

 Router::bind('/activity/streams', array('models' => array('AppActivity')));

 Router::bind('/webhook/git-payload', array('verb' => 'post', 'models' => array()));
 
 Router::bind('/scorm/tracking/@type', array('models' => array('LeanerData'), 'ajax' => true, 'params' => array('type' => '/^get$/i'))); 
 
 Router::bind('/scorm/tracking/@type', array('verb' => 'post', 'models' => array('LearnerData'), 'ajax' => true, 'params' => array('type' => '/^set$/i'))); // make sure to include CSRF-Token here

?>