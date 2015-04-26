<?php

class User extends Model {

	function __construct($id=false, $table="users") {
		// class vars that may have already been set
		if( !$this->db ) $this->db = "users.sqlite";
		if( !$this->pkname ) $this->pkname = "id";
		// the model

		$this->rs = $this->schema(); // move to model class
		// initiate parent constructor
		parent::__construct($this->db, $this->pkname, $table);
		// retrieve the specific user (if available)

		if ($id){
			$this->retrieve($id);
			$this->id = $id;
		}
	}

	function schema(){

		$schema = array(
			"id" => "",
			"name" => "",
			"email" => "",
			"password" => ""
		);
		//
		if( !isset($this->rs) ) $this->rs = array();
		$this->rs = array_merge( $schema, $this->rs );

		// save schema in the global namespace
		if( !isset( $GLOBALS['db_schema'] ) ) $GLOBALS['db_schema'] = array();
		if( !isset( $GLOBALS['db_schema']['users'] ) ) $GLOBALS['db_schema']['users'] = array();

		return $this->rs;

	}

}

?>