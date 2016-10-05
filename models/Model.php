<?php

use \Contracts\Policies\DBAccessInterface as DBInterface;
use \Providers\Core\QueryBuilder as Builder;

class Model implements DBInterface {

     protected $db;

     protected $builder;

     protected $table = '';

     protected $primaryKey = '';

     protected $relations = array(

     );

     public function __construct(){

          $this->db = $GLOBALS['app']->getDBService();

          if(file_exists($GLOBALS['env']['app.path.base'] . '.env')){

              $this->db->connect($GLOBALS['env']['app.path.base'] . '.env');

              $this->builder = new Builder($this->table, $this->relations);

              $this->builder->setIndex($this->primaryKey);

          }else{

               throw new Exception("Cannot create Model Instance >> Database Settings Not Found");
               
          }    
     }

     protected function get(array $columns = array('*')){

        // $models = new \stdClass();

        $this->builder->select($columns);
     }

     protected function set(array $columns = array()){
        
        $this->builder->insert();
     }

     protected function let(array $columns = array()){

        $this->builder->update();
     }

     protected function del(array $columns = array()){

        $this->builder->delete();
     }

     public function findBy($id){
          
     }

}


?>