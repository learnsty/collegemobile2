<?php

   /*!------------------------------------------------------
    ! It's only natural that we rep Naija by modifiying the
    ! default timezone for this server. Feel free to change
    ! it anytime 
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/ 
    date_default_timezone_set('Africa/Lagos'); # UTC

  /*!------------------------------------------------------
    ! 
    ! Include the main application file so we can get this
    ! show on the road ;)
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
    require_once __DIR__ . '/../app.php';


    /*!------------------------------------------------------
    ! 
    ! Load up our routes file so we know what the client-side
    ! is asking for any reply accordingly
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
	require_once __DIR__ . '/../routes/setup.php';

   /*!------------------------------------------------------
    ! 
    ! Load up all the application middlewares
    !
    !
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
    require_once __DIR__ . '/../middlewares/setup.php';

   /*!------------------------------------------------------
    ! 
    ! Load up all the application event observer callbacks
    !
    !
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
    require_once __DIR__ . '/../system/events/setup.php';

    /*!------------------------------------------------------
    ! 
    ! It's now time to make all custom ENV variables available 
    ! to every part of the application by exposing the $env
    ! variable
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
	$env = $app->exposeEnvironment();

    /*!------------------------------------------------------
    ! 
    ! Start piecing together the controller action from the 
    ! activated route
    !
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
	$app->initHTTPResolver();


	/*!------------------------------------------------------
    ! 
    ! Lets' cross our fingers and have a good ride on the 
    ! application cycle.
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/

    # compact(':-)');

?>