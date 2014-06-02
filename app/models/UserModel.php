<?php

	class User extends BaseModel{

		public $id;
		public $name;
		public $description;

		public function __construct($attributes){
			self::construct_attributes($this, $attributes);
		}

		public function update_attributes($new_attributes){
			self::update_rows_where("Users", array("ID" => $this->id), $new_attributes);
		}

		public function destroy(){
			self::delete_rows_where("Users", array("ID" => $this->id));
		}

		public static function all(){
			$obj = self::fetch_all_rows("Users");

			$users = array();
			while($user = $obj->fetch()){
				array_push($users, new User(array("name" => $user["name"], "id" => $user["ID"], "description" => $user["description"])));
			}

			return $users;
		}

		public static function create($attributes){
			$id = self::insert_row("Users", $attributes);
			$attributes["id"] = $id;

			return new User($attributes);
		}

		public static function find($id){
			$obj = self::fetch_rows_where("Users", array("ID" => $id), true);
			$user = $obj->fetch();
			return new User(array("name" => $user["name"], "id" => $user["ID"], "description" => $user["description"]));
		}

		public static function where($attributes){
			$obj = self::fetch_rows_where("Users", $attributes);

			$users = array();
			while($user = $obj->fetch()){
				array_push($users, new User(array("name" => $user["name"], "id" => $user["ID"], "description" => $user["description"])));
			}

			return $users;
		}

	}

?>