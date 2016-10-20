<?php

class User extends Model /* implements AuthService */ {

    protected $table = 'tbl_user';

    protected $primaryKey = 'user_id';

    protected $relations = array(
       'Student' => 'student_id',
       'ContentProvider' => 'provider_id'
    );

    public function __construct(){

        parent::__construct();
    }    

}


?>