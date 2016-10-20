<?php
 
   /*!------------------------------------------------------
    ! 
    ! In the begining, We need to include the Composer class
    ! loader to allow us use Composer packages/modules only 
    ! if at least one of the packages/modules is available.
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/
    
    if(file_exists(__DIR__ . '/packages/vendor/autoload.php')){
         require __DIR__ . '/packages/vendor/autoload.php';
    }     

   /*!---------------------------------------------------------
    ! 
    ! Next, We have to boot up the system and then
    ! load up all class files needed to get started using
    ! the class loader
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    -----------------------------------------------------------*/

    require_once __DIR__ . '/system/boot.php'; 

   /*!---------------------------------------------------------
    ! 
    ! Create the most important object in this framework. Here
    ! we are initializing the core of the framework where all
    ! functionality resides {$app}.
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    ----------------------------------------------------------*/

    $app = new \Providers\Core\App;

   /*!--------------------------------------------------------
    ! 
    ! Create the 2 necessary services we need to get our
    ! application to function properly. The Database and
    ! Environment settings are loaded and processed here.
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/

    $app->installDBService(require __DIR__ . '/configs/db.php');
    $app->installENVService(require __DIR__ . '/configs/env.php');

   /*!------------------------------------------------------
    ! 
    ! It's now time to make all custom ENV variables available 
    ! to every part of the application by exposing the {$env}
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
    $env = $app->exposeEnvironment(basename(__DIR__));

   /*!------------------------------------------------------
    ! 
    ! Instantiate all necessary components needed by app
    ! internals e.g Controllers, Models and Views
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

    # token_name(token); token_get_all(source);  T_INLINE_HTML

?>