<?php

	foreach (glob(__DIR__ . "/../app/controllers/*.php") as $filename){
    	include_once $filename;
	}

	/**
	*	Handles all the routing in the application
	*/
	class Router{

		private $router;

		public function __construct(){
			$this->router = require(__DIR__ . "/../bundle/FatFree/lib/base.php");
			
			$this->router->set("ONERROR",function($router){
				$this->assign("BaseController#on_error", array());  	
			});
		}

		public function get($path, $controller){
			$this->router->route("GET " . $path,
			    function($router, $params) use($controller) {
			        $this->assign($controller, $params);
			    }
			);
		}

		public function delete($path, $controller){
			$this->router->route("DELETE " . $path,
			    function($router, $params) use($controller) {
			        $this->assign($controller, $params);
			    }
			);
		}

		public function post($path, $controller){
			$this->router->route("POST " . $path,
				function($router, $params) use($controller){
					$params_set = array();
					
					foreach($_POST as $key => $value){
						$params_set[$key] = $value;
					}
					foreach ($params as $key => $value) {
						$params_set[$key] = $value;
					}

					$this->assign($controller, $params_set);
				}
			);
		}

		public function route(){
			$this->router->run();
		}

		private function assign($assignment, $params){
			$controller = explode("#",$assignment)[0];
			$method = explode("#",$assignment)[1];
			
			$class = new $controller;
			$class->set_params($params);
			$class->{$method}();
		}
	}

?>