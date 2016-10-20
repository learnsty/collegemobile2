<?php

class Student extends Model {

     protected $table = 'tbl_student';

     protected $primaryKey = 'student_id';

     protected $relations = array(
        'User' => 'user_id'
     );

     public function __construct(){

         parent::__construct();
     }

}

?>