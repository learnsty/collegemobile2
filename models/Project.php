<?php

class Project extends Model {

    protected $table = 'tbl_project';

    protected $primaryKey = 'project_id';

    protected $relations = array(

    );

    public function __construct(){

        parent::__construct();
    }    

}


?>