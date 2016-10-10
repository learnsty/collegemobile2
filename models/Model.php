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

              $this->builder = new Builder($this->table, $this->primaryKey, $this->relations, $this->db->getConnection(), $this->db->getParamTypes());

          }else{

               throw new \Exception("Cannot create Model Instance >> Database Settings Not Found");
               
          }    
     }

     protected function get(array $columns = array(), array $clauseProps = array(), $conjunction = 'and'){

         return $this->builder->select($columns, $clauseProps, $conjunction);
     }

     protected function set(array $values = array()){
        
        return $this->builder->insert($values);
     }

     protected function let(array $columns = array(), $conjunction = 'and'){

        return $this->builder->update($columns, $conjunction);
     }

     protected function del(array $columns = array()){

        return $this->builder->delete($columns);
     }

     public function findById($id){
          
     }

}


?>