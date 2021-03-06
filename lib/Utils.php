<?php 

	require_once(__DIR__ . "/../bundle/Twig/lib/Twig/Autoloader.php");

	/**
	*	Offers basic util services
	*/
	class Utils{

		/**
		*	Establishes a database connection
		*/
		public static function db_connection(){
			$connection = null;
			$db_conf = database_config();

			try {
			    $connection = new PDO("mysql:host=" . $db_conf["host"] . ";dbname=" . $db_conf["database"], $db_conf["username"], $db_conf["password"]);
			} catch (PDOException $e) { }
			return $connection;
		}

		/**
		*	Renders the given view found at app/views/
		*/
		public static function render($view, $content = array()){

			Twig_Autoloader::register();

			$twig_loader = new Twig_Loader_Filesystem(__DIR__ . "/../app/views");
			$twig = new Twig_Environment($twig_loader);

			echo $twig->render($view . ".html", $content);

		}

	}

?>