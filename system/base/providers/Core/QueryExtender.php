<?php

namespace Providers\Core;

class QueryExtender {
     
     protected $queryString;

     protected $attribs;

     protected $relations;

     protected $paramValues;

     protected $paramTypes;

     protected $connection;

     public function __construct(array $schemaAttribs, array $relations, $connection, $paramTypes){

         $this->queryString = '';

         $this->attribs = $schemaAttribs;

         $this->relations = $relations;

         $this->paramValues = NULL;

         $this->paramTypes = $paramTypes;

         $this->connection = $connection;

     }

     
     public function get($columns, $clauseProps, $conjunction){
     	$conjunction = " ".strtoupper($conjunction) ." ";
        $this->queryString .= "SELECT " . $columns . " FROM " . $this->attribs['table'] . (count($clauseProps) > 0? " WHERE " . implode($conjunction, $this->prepareSelectPlaceholder($clauseProps)) : "");

        return $this;
     }

     public function set($columns, $values){

        $this->queryString .= "INSERT INTO " . $this->attribs['table'] . "(" . $columns . ") VALUES (" .  $this->prepareInsertPlaceholder($values) . ")";

        return $this;
     }

     public function let($columns, $conjunction){

        $this->queryString .= "UPDATE " . $this->attribs['table'] . " SET " . implode($conjunction, $this->prepareUpdatePlaceholder($columns));

        return $this;
     }

     public function del($columns){

     	$this->queryString .= "DELETE " . $this->attribs['table'];

        return $this;
     }

     public function join($jointype){

         $jointype = strtoupper($jointype);

     	 $this->queryString .= " $jointype JOIN";

     	 return $this;
     }

     public function orderby($column){

     	 $this->queryString .= " ORDER BY $column";

     	 return $this;

     }

     public function distinct(){

        return $this;
     }

     public function exec($ascending = false, $limit = 0){

     	  $type = substr($this->queryString, 0, index_of($this->queryString, " ", 0));
     	  $result = NULL;
        
          switch (strtolower($type)){
          	case 'select':
          		$this->queryString .= ($ascending? " ASC" : " DESC");
                $this->queryString .= ($limit == 0? "" : " LIMIT $limit");
                $result = db_get($this->connection, $this->paramTypes, $this->queryString, $this->parmeterizeValues($this->paramValues));
          	break;
          	case 'insert':
                 $result = db_put($this->connection, $this->paramTypes, $this->queryString, $this->parmeterizeValues($this->paramValues));
          	break;
          	case 'update':
                 $result = db_post($this->connection, $this->paramTypes, $this->queryString, $this->parmeterizeValues($this->paramValues));
          	break;
          	case 'delete':
                 $result = db_del($this->connection, $this->paramTypes, $this->queryString, $this->parmeterizeValues($this->paramValues));
          	break;
          	default:
          		# code...
          	break;
          }
          return $result;   
     }

     private function prepareInsertPlaceholder($props){

     	$this->paramValues = $props;
       
        return array_fill(0, count($props), "?");
     }

     private function prepareSelectPlaceholder($props){
         
         $this->paramValues = array_values($props); 

         return array_map('update_in_keys', array_keys($props));
     }

     private function prepareUpdatePlaceholder($props){

        $this->paramValues = array_values($props); 

        return array_map('update_in_keys', array_keys($props));
     }

     private function parmeterizeValues($params){
     	  $values = array();
          foreach ($params as $value) {
          	  $type = substr(gettype($value), 0, 3);
          	  $values[$type] = $value;
          } 
          return $values;
     }

}

?>