<?php

class KISS_Account extends Controller {

	function __construct($controller_path,$web_folder,$default_controller,$default_function) {

		// DBs
		if( !isset($this->db) ) $this->db = array();
		if( !array_key_exists('user', $this->db) ) $this->db['user'] = new User();

		// continue to the default setup
		parent::__construct($controller_path,$web_folder,$default_controller,$default_function);

	}

	function login() {
		$method = $_SERVER['REQUEST_METHOD'];
		if( $method == "GET" ){
			// load the form
			return $this->render("login");
		}
		if( $method == "POST" ){
			// process creds
			$login = $this->checkLogin();
			if( $login ){
				// redirect to homepage
				header("Location: ". url() );
			} else {
				// redirect to login (with error message?)
				header("Location: ". url("account/login") );
				exit();
			}
		}
	}

	function logout() {
		unset($_SESSION['user']);
		header("Location: ". url() );
		exit();
	}

	function register() {
		$method = $_SERVER['REQUEST_METHOD'];
		if( $method == "GET" ){
			// load the form
			return $this->render("register");
		}
		if( $method == "POST" ){
			// process creds
			// validate first?
			$valid = $this->checkRegister();
			if( $valid ){
				// create account
				$this->create();
				// login
				$this->login();
			} else {
				// redirect to register (with error message?)
				header("Location: ". url("account/register") );
				exit();
			}
		}
	}

	private function checkLogin() {
		// prerequisites
		// - if already logged in redirect (without processing)
		if( array_key_exists('user', $_SESSION ) ) return true;
		if( !isset($_POST['email']) || !isset($_POST['password']) ) return false;
		// variables
		$db = $this->db['user']; // this should be already defined in the controller's contruct....
		$email=trim( $_POST['email'] );
		$password=crypt( $_POST['password'], $_POST['password'] );
		//
		$user = $db->findOne("email", $email);
		// exit now if we found no user
		if( !$user ) return false;
		if( $email == $user['email'] && $password == $user['password'] ){
			// save in session
			$_SESSION['user'] = $user;
			return true;
		} else {
			return false;
		}
	}

	private function checkRegister() {
		// prerequisites
		// - if already logged in redirect (without processing)
		if( array_key_exists('user', $_SESSION ) ) return false;
		if( !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['password_repeat']) ) return false;
		if( empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password_repeat']) ) return false;
		// variables
		$db = $this->db['user'];
		// make sure the passwords match
		if( $_POST['password'] !== $_POST['password_repeat'] ) return false;
		// make sure there's no user with that email
		$user = $db->findOne("email", $_POST['email']);
		// exit now if we found a user
		if( $user ) return false;
		//
		return true;
	}

	private function create() {
		// variables
		$db = $this->db['user'];
		$name = trim( $_POST['fullname'] );
		$email = trim( $_POST['email'] );
		$password = crypt( $_POST['password'], $_POST['password'] ); // using the password as the salt...
		$db->set( "name", $name );
		$db->set( "email", $email );
		$db->set( "password", $password );
		// save user
		$db->create();
		// trigger event
		Event::trigger('user:register');
		// save user in session
		$_SESSION['user'] = $db->getAll();
	}

}

?>