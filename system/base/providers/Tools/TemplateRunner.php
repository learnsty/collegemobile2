<?php 

namespace Providers\Tools;

use Session;
use Router;

class TemplateRunner {

    private $viewRoot = NULL;	

    private $compiledViewRoot = NULL;

    private $compileFileName = NULL;

    public function __construct(){

        $this->viewRoot = $GLOBALS['env']['app.path.view'];

        $this->compiledViewRoot = str_replace('/views', '', $this->viewRoot) . '/storage/compiled/';

    }

    private function getCompiledFileName($name){
       
       if($this->compileFileName === NULL){ 
          
           $this->compileFileName = $this->compiledViewRoot . preg_replace('/\//', '_', $name) . '.php';
       }
       
       return $this->compileFileName;   

    }

    private function compile(\string $name__){

    	$view__string;

		if(!isset($name__)){

		      throw new \Exception("No View File For Compilation");	
		}
		try{ 

		   $view__string  = file_get_contents($this->viewRoot . (index_of($name__, '/') > -1? substr($name__, 1) : $name__) . '.view');

		}catch(\Exception $e){

		     throw new \Exception("View File Not Found >> [" . $name__ . "] Compilation Not Successful");

		}

    	$templateTokens = array('/\[\s+?(if|elseif)\:([\w\S]+)?\@(\w+)(.+)\s+?\]/i', '/(?<!\[)\=\@(\w+)(?!\])/i', '/\[\@(\w+)\]/', '/\[\@(\w+)\=(\w+)\]/i', '/\[\s+?loop\:\@(\w+)\s+?\]/i', '/\[\s+?\/if\s+?\]/i', '/\[\s+?\/loop\s+?\]/i'); 
        $templateTokensReplace  = array('<?php ${1}(${2}$${3}${4}): ?>', '<?php echo $${1}; ?>', '<?php echo $${1}; ?>', '<?php echo $${1}[\'${2}\']; ?>', '<?php foreach($${1} as $${1}_index => $${1}_value): ?>', '<?php endif; ?>', '<?php endforeach; ?>');
		$templateRendered = preg_replace($templateTokens, $templateTokensReplace, $view__string);

		file_put_contents($this->getCompiledFileName($name__), $templateRendered);

		return $this->getCompiledFileName($name__);

    }

    public function render(\string $view__name, array $data__array = array()){

		
        $__file0 = $this->getCompiledFileName($view__name);

        $__viewss = Session::get('__views');

        $__routee = Router::currentRoute();

        $__sett = NULL;

        if(!isset($__viewss) || $__viewss === FALSE){

            $__viewss = array();
        }

        if(array_key_exists($__routee, $__viewss)){

             $__sett = $__viewss[$__routee];
        }

        if(isset($__sett) && file_exists($__file0)){

        	if($__sett['compiled'] === $__file0){

                if($__sett['size'] !== filesize($__file0)){ // detect change in the view file '.view'

                     $__file0 = $this->compile($view__name); // comiple the view
                }
            }     
        }else{

        	$__file0 = $this->compile($view__name); // compile the view

        }

        $__viewss[$__routee] = array('compiled' => $__file0, 'size' => filesize($__file0));

        Session::put('__views', $__viewss);

        $data__array['csrftoken'] = Session::token();

		return $this->draw($__file0, $view__name, $data__array);

	}

	private function draw(\string $__file, \string $__name, array $vars){

	   // variables created by 'extract()' are not visible in outer or global scope	
       // so this is a safe operation within this function method (__FUNCTION__)
	   extract($vars); 

       try{

         // 'require_once()' uses variables defined in only both the immediate outer and global scope
         require_once($__file);

       }catch(\Exception $e){

		   throw new \Exception("Error in View File >> [" . $__name . "] => " . $e->getMesage());

 	   }

        /*if('<=PHP5'){
            $read = file_get_contents('viewsey.php', true);
       /* }else{
            $read = file_get_contents('viewsey.php', FILE_USE_INCLUDE_PATH);
        } */

        /*
           // Create a stream
            $opts = array(
              'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                          "Cookie: foo=bar\r\n"
              )
            );

            $context = stream_context_create($opts);

            // Open the file using the HTTP headers set above
            $file = file_get_contents('http://www.example.com/', false, $context);
        */

        $___drawn =  ob_get_contents();  

        return $___drawn;

	}

}

?>