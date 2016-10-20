<?php 

class Register extends Controller {

      protected $params;

      public function __construct($params){

             parent::__construct($params);
             
      }

      // @Override 

      public function index($models){

           switch($this->params['category']){
               case "contentprovider":
                   $arrayInputs = array('owner' => $this->params['category']);
               break;
               case "basicuser":
                   $arrayInputs = array('owner' => $this->params['category']);
               break;
           }

           Response::view('site/register/index', $arrayInputs);     
      }

      public function createuser($models){ # '/register/createuser/(basicuser|contentprovider)'

          $json = array();

          $sanitizedInputs = Validator::checkAndSanitize(Request::input()->getFields(), array(
                   'email' => 'email|required',
                   'password' => 'password|required|/^(?:[^\t\r\n\f\b\~\"\'\<\>]+)$/i',
                   'gender' => array('allowed'=>'male, female', 'rule'=>'required'),
                   'full_name' => 'full_name|required|/^(?:[^\S\d\t\r\n]+)$/i',
                   'mobile_number' => 'mobile_number|required|/^(?:070|071|081|080|090|091)(?:\d{8})$/'
          ));

          $inputCheckErrors = Validator::getErrors(); 

          if(count($inputCheckErrors) > 0){
              $json['status'] = 'error';
              $json['erorrs'] = $inputCheckErrors;
          }else{
              $json['status'] = 'ok';
          
              switch($this->params['category']){
                   case "contentprovider":
                       $arrayInputs = array();
                   break;
                   case "basicuser":
                       $arrayInputs = array();
                   break;
               }

               Auth::create($models['User'], $sanitizedInputs);
               System::fire('newUserRegistered', $sanitizedInputs, $models['AppActivity']);

               sleep(2);
          }     

          Response::json($json); 
      }

  }

?>