<?php

class LearnerData extends Model {

    protected $table = 'tbl_user';

    protected $primaryKey = 'id';

    protected $relations = array(
       '<model_class_name>' => '<foreign_key>'
    );

    public function __construct(){

        parent::__construct();
    }

    public function getCMIJSON(){
 
              
    }
    

}