<?php 

class Login extends Controller {

        protected $params;

        public function __construct($params){

             parent::__construct($params);
             
        }

        // @Override 

        protected function index($models){

            Logger::info('Hello There! from ' . get_class($this) . '::index method.');

             // Auth::verifyJWT(Request::getCookie('_marker__stat_002')); --> [move this to system middleware later]

             // $models['User']->set(array('id'=>'355252', 'url'=>'hhhhhhh')); // SQL INSERT

             // $models['User']->let(); // SQL UPDATE

             // $models['User']->del(); // SQL DELETE           

            Response::view('site/login/index', array('errors' => array('po', 'au'), 'flack' => 'lp' , 'open' => 'Hello'));
        }

        public function authenticate($models){

             $uploadErrors = array('status' => 'E get as e be oo!');
            
             /*$file_list1 = Request::input()->uploadFiles(array('courseware_file'=>'courseware'), $uploadErrors);*/

             $field_list1 = Request::input()->getFields();

             /*$file_list2 = Request::upload('display_photos', $uploadErrors);*/

             Logger::info('Hello There! from ' . get_class($this) . '::register method.');

             Response::json(array('ok'=> FALSE, 'errors'=> $uploadErrors));

        }
}

?>