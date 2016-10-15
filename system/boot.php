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
    ! Instantiate the class loader and register all classes
    ! via their corresponding class paths
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

	if(!array_key_exists('loader', get_defined_vars())) {

    	   $loader = new ClassLoader(json_decode(file_get_contents(__DIR__ . "/load.json"), TRUE));
    	   $loader->addClassMap(require __DIR__ . '/base/class_maps.php');
    	   $loader->register(true);
	}

    /*!--------------------------------------------------------
    ! 
    ! Otherwise, if something goes awry, kill the process... 
    ! ^Naija Style^ 
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

	else { 
	
        die('ClassLoader processing failed - LearnstyPHP terminated abruptly');
		
    }    
	
?>