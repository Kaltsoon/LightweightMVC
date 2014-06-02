<?php

	/**
	*	Parent class for all the models.
	*	Offers basic services like executing a custom database query and multiple database query abstractations
	*/

	class BaseModel{

		/**
		*	Executes a database query and returns the results
		*/
		protected static function db_query($query, $params = null){
			$connection = Utils::db_connection();
			$q = $connection->prepare($query);
			if($params != null){
				$q->execute($params);
			}else{
				$q->execute();
			}

			return $q;
		}

		/**
		*	Fetches rows from the given table where attributes match given attributes.
		*	$only_one_row parameter limits query to one row when set to true
		*/
		protected static function fetch_rows_where($table, $attributes, $only_one_row = false){
			$query = "SELECT * FROM " . $table . " WHERE";
			foreach ($attributes as $key => $value) {
				$query .= (" " . $key . "=:" . $key . "#");
			}
			$query = str_replace("#", " AND ", substr($query, 0, -1));
			if($only_one_row){
				$query .= " LIMIT 1";
			}

			return self::db_query($query, $attributes);
		}

		/**
		*	Fetches all the rows from the given table
		*/
		protected static function fetch_all_rows($table){
			$query = "SELECT * FROM " . $table;

			return self::db_query($query);
		}

		/**
		*	Inserts a row to the table with the given attributes.
		*   Returns the ID of the inserted object
		*/
		protected static function insert_row($table, $attributes){
			$query = "INSERT INTO " . $table . " (";
			foreach($attributes as $key => $value){
				$query .= ($key . ",");
			}
			$query = (substr($query, 0, -1) . ") VALUES (");
			foreach($attributes as $key => $value){
				$query .= (":" . $key . ",");
			}
			$query = (substr($query, 0, -1) . ")");

			$connection = Utils::db_connection();
			$q = $connection->prepare($query);
			$q->execute($attributes);

			return $connection->lastInsertId();
		}

		/**
		*	Deletes the rows from the table which match the given attributes
		*/
		protected static function delete_rows_where($table, $attributes){
			$query = "DELETE FROM " . $table . " WHERE";
			foreach ($attributes as $key => $value) {
				$query .= (" " . $key . "=:" . $key . "#");
			}
			$query = str_replace("#", " AND ", substr($query, 0, -1));

			self::db_query($query, $attributes);
		}

		/**
		*	Updates rows from the table with the given new attributes where attributes match the given attributes
		*/
		protected static function update_rows_where($table, $attributes, $new_attributes){
			$query = "UPDATE " . $table . " SET";
			$attribute_set = array();
			foreach ($new_attributes as $key => $value) {
				$attribute_set[$key . "__set"] = $value;
				$query .= (" " . $key . "=:" . $key . "__set" . ",");
			}
			$query = substr($query, 0, -1);
			$query .= " WHERE";
			foreach ($attributes as $key => $value) {
				$attribute_set[$key . "__where"] = $value;
				$query .= (" " . $key . "=:" . $key . "__where" . "#");
			}
			$query = str_replace("#", " AND ", substr($query, 0, -1));

			self::db_query($query, $attribute_set);
		}

		/**
		*	Sets public public variable values of an instance by reading them from the given array 
		*/
		protected static function construct_attributes($instance, $attributes){
			foreach($attributes as $key => $value){
				$instance->{strtolower($key)} = $value;
			}
		}

	}

?>