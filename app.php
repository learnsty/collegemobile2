<?php
   
    define('LEARNSTYPHP_EXEC_ID', mt_rand(1, time());
    
   /*!------------------------------------------------------
    ! 
    ! In the begining, We need to include the Composer class loader to
    ! allow us use Composer packages/modules only if at least one of the
    ! packages/modules is available
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
    
    if(file_exists(__DIR__ . '/packages/vendor/autoload.php'))
         require __DIR__ . '/packages/vendor/autoload.php';

   /*!------------------------------------------------------
    ! 
    ! Next, We have to boot up the system and then
    ! load up all class files needed to get started using the
    ! class loader
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/

    require_once __DIR__ . '/system/boot.php'; 

   /*!------------------------------------------------------
    ! 
    ! Create the most important object in this framework. Here
    ! We are initializing the core of the system.
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

    $app = new Providers\Core\App();

   /*!------------------------------------------------------
    ! 
    ! Create the 2 necessary services we need to get our
    ! application to function properly
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

    $app->installDBService(require __DIR__.'/configs/db.php');
    $app->installENVService(require __DIR__.'/configs/env.php');

    /*!------------------------------------------------------
    ! 
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

    $app->registerAllComponents();

    /*!------------------------------------------------------
    ! 
    ! Ermmm... this is jsut some commented indigested code 
    ! Hehehehe (just having fun with this ;)
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

    # token_name(token);
    # token_get_all(source);
    # T_INLINE_HTML


?>