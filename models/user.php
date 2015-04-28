<?php

class User extends Model {
	protected $name = "user";

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

		return parent::schema( $schema );
	}

	// events
	// - placeholders
	function onRegister(){
		// replace with your own method...
	}

}

?>