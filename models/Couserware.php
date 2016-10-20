<?php

class Courseware extends Model {

     protected $table = 'tbl_courseware';

     protected $primaryKey = 'courseware_id';

     protected $relations = array(
        'User' => 'user_id'
     );

     public function __construct(){

         parent::__construct();
     }   

}

?>