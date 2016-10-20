<?php 

class Login extends Controller {

        protected $params;

        public function __construct($params){

             parent::__construct($params);
             
        }

        // @Override 

        public function index($models){

            Logger::info('Hello There! from ' . get_class($this) . '::index method.');

             // $models['User']->set(array('id'=>'355252', 'url'=>'hhhhhhh')); // SQL INSERT

             // $models['User']->let(); // SQL UPDATE

             // $models['User']->del(); // SQL DELETE           

            Response::view('site/login/index', array('errors' => array('po', 'au'), 'flack' => 'lp' , 'open' => 'Hello'));
        }

        public function authenticate($models){

             $uploadErrors = array('write_error' => 'E get as e be oo!');

             $ua_fingerprint = $this->params['fingerprint'];

             $redirect = '/';

             $json = array();

             Logger::info('Hello There! from ' . get_class($this) . '::authenticate method.');
            
             $fields_list = Request::input()->getFields();

             # validation [fields] ...

             if(array_key_exists('return_to', $fields_list)){ // client reads querystring and puts it in POST vars
                 $url = $fields_list['return_to'];
                 unset($fields_list['return_to']);
             }

             Auth::setThrottleId($ua_fingerprint);

             $loggedIn = Auth::logUser($models['User'], $models['UserRole'], $models['UserThrottle'], $fields_list);


             /*$file_list1 = Request::input()->uploadFiles(array('courseware_file'=>'courseware'), $uploadErrors);*/

             /*$file_list2 = Request::upload('display_photos', $uploadErrors);*/              

             if($loggedIn){
                 if(Auth::willBeReturnedToURL($url)){
                     $redirect = Auth::getReturnToURL();
                 }else{
                     $redirect = $redirect . ""; /* user role here */
                 }   
                 $json['status'] = 'ok';  
                 $json['redirect'] = $redirect;
             }else{
                 $json['status'] = 'error';
                 $json['errors']= $uploadErrors;
             }

             return Response::json($json);

        }
}

?>