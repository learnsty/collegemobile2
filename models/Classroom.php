<?php

class Classroom extends Model {

     protected $table = 'tbl_classroom';

     protected $primaryKey = 'classroom_id';

     protected $relations = array(
        'User' => 'user_id'
     );

     public function __construct(){

         parent::__construct();
     }

}

?>