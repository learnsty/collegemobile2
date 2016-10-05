<?php

    require_once __DIR__ . '/base/functions.php';

   /*!------------------------------------------------------
    ! 
    ! Lets start out by including the class loader from its
    ! location
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

    require_once __DIR__ . '/base/ClassLoader.php';

    /*!------------------------------------------------------
    ! 
    ! Instantiate the class loader and register all class via
    ! their corresponding class paths
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

	if(!array_key_exists('loader', get_defined_vars())){

    	   $loader = new ClassLoader(json_decode(file_get_contents("./load.json"), TRUE));
    	   $loader->addClassMap(require __DIR__.'/base/class_maps.php');
    	   $loader->register(true);

    	   exit(0);
	}

    /*!--------------------------------------------------------
    ! 
    ! If something goes wrong, kill the process... Naija Style!
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
    ----------------------------------------------------------*/

	die('ClassLoader processing failed');

?>