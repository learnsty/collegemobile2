<?php

$CLASSMAP = require './class_maps.php';

function __autoload($class){ // __autoload is supported in PHP 5.0.0 - 5.2.0 

       $DIR = __DIR__;
       
       foreach($CLASSMAP as $key => $val){
       	    if(substr($key, 1) == $class){ // in PHP 5.0.0 - 5.2.0, the leading backward slash is always missing!!
	       	   if(file_exists("{$DIR}{$val}.php")){
	       	       require "{$DIR}{$val}.php";
	       	       
		           return hasClassBeenAutoloaded($class);
	       	    }  
	       	}    
       }

       return hasClassBeenAutoloaded($class);
}

   
function hasClassBeenAutoloaded($class){
    	return class_exists($class);
}

?>