<?php

namespace Providers\Core;

use Providers\Core\QueryExtender;

/*
 HOW TO USE THE BELOW FUNCTIONS
 
 db_get("SELECT * FROM tbl_client_testimonies WHERE client = ?", array("str" => "Alexis"), 3);
 db_put("INSERT/UPDATE INTO tbl_news_subscribers (email, location, created_at) SET logged = ? / VALUES (?, ?, ?)", array("str" => "xyz@gmail.com", "str" => "Abuja", "int" => time(), TRUE);
 db_delete("DELETE * FROM tbl_saas_pricing WHERE admin_id = ?", array("str" => ""), TRUE);
 
 @TODO: THINKING OF USING db_let("UPDATE tbl_saas_pricing SET =  WHERE admin_id = ?"); FOR UPDATE QUERIES ??
 
 */

class QueryBuilder {

	private static $modelInstances = NULL;

    protected $extender;

    protected $schemaAtrribs;

	public function __construct($table, $key, array $relations, $connection, $paramTypes){

       $this->schemaAttribs = compact('table', 'key');

       $this->extender = new QueryExtender($this->schemaAttribs, $relations, $connection, $paramTypes);

       // $this->extender->bindModels(static::$modelInstances);

       // QueryBuilder::bindModels($models);

	}

	public function select(array $columns, array $clauseProps, $conjunction){

        return $this->extender->get(implode(', ', $columns), $clauseProps, $conjunction);
	}

	public function insert(array $values){

       return $this->extender->set(implode(', ', array_keys($values)), array_values($values));
	}

	public function update(array $columns){

        return $this->extender->let($columns);
	}

	public function delete(array $columns){

       return $this->extender->del($columns);
	}

	public static function bindModels(array $models){
 
        static::$modelInstances = $models;
	}
	
}