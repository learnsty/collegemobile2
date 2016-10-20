<?php 

class Scorm extends Controller {

        protected $params;

        public function __construct($params){

             parent::__construct($params);
             
        }

        // @Override

        public function index($models){

             
        }

        public function tracking($models){

             $inputs = Request::input()->getFields(); // $_GET | $_POST

             $payload;

             $sanitizedInputs = Validator::checkAndSanitize($inputs, array(
                 'cmi_activity' => 'cmi_activity|required|/^(?:[\w_]+)$/i',
                 'adl_course' => 'adl_course|required|/^(?:[^\t\r\n\f\b\~\"\']+)$/',
                 'cmi_learner' => 'cmi_learner|required|/^(?:[a-z\,]+)$/i'
             ));

             switch($this->params['type']){
                 case "get":
                   $payload = $models['LearnerData']->get(array('cmi_json'), $sanitizedInputs);
                 break;
                 case "set":
                   try{
                      $x = json_decode($inputs['cmi_json'], TRUE);
                   }catch(\Exception $e){
                      $x = array();
                   }
                   $sanitizedInputs['cmi_json'] = json_encode($x);
                   $payload = $models['LearnerData']->set(array_keys($sanitizedInputs), $sanitizedInputs);
                 break;
              }

              Response::json($payload);
        }

        public function runtime($models){
            
           $inputs = Request::input()->getFields(); // $_GET
 
           $sanitizedInputs = Validator::checkAndSanitze($inputs);

           $x = $models['Courses']->findBy($sanitizedInputs['course_id']);
           
           Response::view('scorm/runtime', array('item' => $x));
        }

 }

?>    