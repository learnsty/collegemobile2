<?php

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
 Router::bind('/', array('models' => array('', '', '', '', '')));
 
 Router::bind('/login', array('models' => array()));
 
 Router::bind('/register/@category', array('models' => array(), 'params' => array('category' => '/^[a-z]$/i')));
 
 Router::bind('/teach', array('models' => array('')));
 
 Router::bind('/learn', array('models' => array('')));
 
 Router::bind('/scorm/runtime/', array('models' => array()));
 
 
 
 
 
 
 /* Partial Views (AngularJS Templates) */
 Router::bind('/teach/feeds/', array('models' => array(''), 'ajax' => true));
 
 Router::bind('/learn/feeds/', array('models' => array(''), 'ajax' => true));
 
 
 
 
 
 
 
 /* JSON APIs */
 Router::bind('/login/authenticate/', array('verb' => 'post', 'models'=> array('User', 'Throttle', 'UserRole'), 'ajax' => true));
 
 Router::bind('/register/createuser/@category', array('verb' => 'post', 'models' => array('User', 'UserRole'), 'ajax' => true));
 
 Router::bind('/scorm/tracking/@type', array('models' => array('LeanerData'), 'ajax' => true)); 
 Router::bind('/scorm/tracking/@type', array('verb' => 'post', 'models' => array('LearnerData'), 'ajax' => true)); // make sure to include CSRF-Token here

?>