<?php

class AppActivity extends Model {

      protected $table = 'tbl_app_activity';

      protected $primaryKey = 'activity_id';

      protected $relations = array(

      );

      public function __construct(){

          parent::__construct();
      }

      public function getOccurence(array $columns, array $clause, $size){

         return $this->get($columns, $clause)->exec(FALSE, $size);
      }

      public function setOccurence(array $columns){

         return $this->set($columns)->exec();
      }

}

?>