<?php

/*!------------------------------------------------
 ! Class Loader for this Appliction
 !
 !
 !
 !
 !
 !
 !
 !
 !
 !
 -------------------------------------------------*/



class ClassLoader {

    private $classMapSuper;

    private $classMap;

    private $options;

    private $registered = false;

    const ROOT = __DIR__; // dirname(dirname(__FILE__));


    public function __construct(array $options){

        $this->options = $options;
        
        $this->classMapSuper = true;

        $this->classMap = array();

    }

    /**
     *
     *
     *
     */

     public function addClassMap($maps)
     {

         foreach($maps as $key => $val){

              if(array_key_exists($key, $this->classMap)){
                
                    continue;

              }

              $this->classMap[$key] = $val;

         }

     }

    /**
     *
     *
     *
     */

    private function getHostVersion($splitNumber){

         $version = phpversion();

         if($splitNumber){

              return explode('.', $version);

         }else{

              return $version;
         }  
    } 

    /**
     *
     *
     *
     *
     */

     public function isRegistered(){

         return $this->registered;
     } 

    /**
     *
     *
     *
     *
     */

     public function getClassMap()
    {
        return $this->classMap;
    }

    /**
     * Should class lookup fail if not found in the current class map?
     *
     *
     * @return bool
     * @param void
     */
    public function isClassMapSuper()
    {
        return $this->classMapSuper;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
      if(function_exists('spl_autoload_register')){ // PHP 5.3+

           $this->registered = spl_autoload_register(array($this, 'loadClass'), true, $prepend);


        }else{ // PHP 5.0 - 5.2
         
               include self::ROOT . '/_autoload.php';

        }
    }

    /**
     * Unregisters this instance as an autoloader.
     */
    public function unregister()
    {

      if(function_exists('spl_autoload_register')){

           spl_autoload_unregister(array($this, 'loadClass'));

           $this->registered = false;

      }else{

           ;
      }
    }

    /**
     * Loads the given class or interface.
     *
     * @param  string    $class The name of the class
     * @return bool|null True if loaded, null otherwise
     */
    public function loadClass($class)
    {
        if (($file = $this->findFile($class)) !== NULL) {

            includeFile($file);

            return true;
        }

        return false;
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     * @return string|false The path if found, false otherwise
     */
    public function findFile($class)
    {
        // for PHP 5.3.0 - 5.3.2, we need not add a leading backward slash
        if(!(substr($this->getHostVersion(false), 0, 3) === '5.3')){
            $class = "\\" . $class;
        }   
        // class map lookup
        if (isset($this->classMap[$class])) {
            return $this->classMap[$class];
        }
        if ($this->classMapSuper) {
            return NULL;
        }

        return NULL;
    }

}

function includeFile($file){
 
     include $file . ".php";
  
}

?>