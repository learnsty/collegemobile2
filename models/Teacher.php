<?php

class Teacher extends Model {

     protected $table = 'tbl_teacher';

     protected $primaryKey = 'teacher_id';

     protected $relations = array(
        'User' => 'user_id'
     );

     public function __construct(){

         parent::__construct();
     }

}

?>