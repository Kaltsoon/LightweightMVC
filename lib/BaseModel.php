<?php

	/**
	*	Parent class for all the models.
	*	Offers basic services like executing a custom database query and multiple database query abstractations
	*/
	class BaseModel{

		/**
		*	Fetches all the objects from the database
		*/
		public static function all(){
			$class_name = get_called_class();
			$response = self::fetch_all_rows($class_name);
			$objects = array();

			while($row = $response->fetch()){
				$instance = new $class_name;
				foreach($row as $attribute => $value){
					$instance->{$attribute} = $value;
				}
				array_push($objects, $instance);
			}

			return $objects;
		}

		/**
		*	Fetches objects where attributes matches the given attributes
		*/
		public static function where($attributes){
			$class_name = get_called_class();
			$response = self::fetch_rows_where($class_name, $attributes);
			$objects = array();

			while($row = $response->fetch()){
				$instance = new $class_name;
				foreach($row as $attribute => $value){
					$instance->{$attribute} = $value;
				}
				array_push($objects, $instance);
			}

			return $objects;
		}

		/**
		*	Fetches object from the database with the given id
		*/
		public static function find($id){
			$class_name = get_called_class();
			$response = self::fetch_rows_where($class_name, array("id" => $id), true);

			$instance = new $class_name;

			foreach($response->fetch() as $attribute => $value){
				$instance->{$attribute} = $value;
			}

			return $instance;
		}

		/**
		*	Saves the given object to the database
		*/
		public function save(){
			$class_name = get_called_class();
			$attribute_accessor = self::get_accessable_attributes($class_name);
			$attributes = array();

			foreach($this as $attribute => $value){
				if(in_array($attribute, $attribute_accessor)){
					$attributes[$attribute] = $value;
				}
			}

			if(!isset($this->id)){
				$this->id = self::insert_row($class_name, $attributes);
			}else{
				self::update_rows_where($class_name, array("id" => $this->id), $attributes);
			}
		}

		/**
		*	Removes the given object from the database
		*/
		public function destroy(){
			$class_name = get_called_class();
			self::delete_rows_where($class_name, array("id" => $this->id));	
		}

		/**
		*	Executes the given database query with the given parameters
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
		*	Return accessable attributes of the given model
		*/
		private static function get_accessable_attributes($model_name){
			$attribute_accessor = (new $model_name)->attribute_accessor;
			array_push($attribute_accessor, "id");

			return $attribute_accessor;
		}

		/**
		*	Fetches rows from the given table where attributes match given attributes.
		*	$only_one_row parameter limits query to one row when set to true
		*/
		private static function fetch_rows_where($table, $attributes, $only_one_row = false){
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
		private static function fetch_all_rows($table){
			$query = "SELECT * FROM " . $table;

			return self::db_query($query);
		}

		/**
		*	Inserts a row to the table with the given attributes.
		*   Returns the ID of the inserted object
		*/
		private static function insert_row($table, $attributes){
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
		private static function delete_rows_where($table, $attributes){
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
		private static function update_rows_where($table, $attributes, $new_attributes){
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
		private static function construct_attributes($instance, $attributes){
			foreach($attributes as $key => $value){
				$instance->{strtolower($key)} = $value;
			}
		}

		private static function get_attributes($instance, $only = null){
			$attributes = array();
			foreach($instance as $key => $value){
				if($only == null || in_array($key, $only)){
					$attributes[$key] = $value;
				}
			}

			return $attributes;	
		}

		private static function validate($instance, $validator){
			return $instance->{$validator}();
		}

		private static function validate_all($instance, $validators){
			foreach($validators as $validator){
				if($instance->{$validator}() == false){
					return false;
				}
			}
		}

	}

?>