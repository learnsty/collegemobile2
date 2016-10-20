<?php

class LearnerData extends Model {

    protected $table = 'tbl_scorm_data';

    protected $primaryKey = 'data_id';

    protected $relations = array(
       'Student' => 'student_id',
       'Courseware' => 'courseware_id'
    );

    public function __construct(){

        parent::__construct();
    }

    public function getCMIJSON(){
 
        // return $this->get()->exec();      
    }
    

}