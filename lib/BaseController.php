<?php
	
	/**
	*	Parent class for all the controllers.
	*	Offers basic services like session handling, rendering and redirecting
	*/

	class BaseController{

		protected $params;

		public function __construct(){}

		public function __call($method, $args){
			session_start();
		}

		/**
		*	Sets controller parameters
		*/
		public function set_params($params){
			$this->params = $params;
		}

		/**
		*	Action called on error
		*/
		public function on_error(){
			$this->render("error");
		}

		/**
		*	Renders the view with the given content
		*/
		protected function render($view, $content = array()){
			Utils::render($view, $content);
		}

		/**
		*	Redirects to the given path 
		*/
		protected function redirect_to($path){
			header("Location: " . $path);
		}

		/**
		*	Binds a session key to the given value
		*/
		protected function set_session($key, $value){
			$_SESSION[$key] = $value;
		}

		/**
		*	Returns the session value of the given key
		*/
		protected function get_session($key){
			return $_SESSION[$key];
		}
	}

?>