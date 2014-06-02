<?php

	class UserController extends BaseController{

		public function __construct(){}

		public function index(){
			$this->render("users/index", array("users" => User::all()));
		}

		public function show(){
			$user = User::find($this->params["id"]);
			$this->render("users/show", array("user" => $user));
		}

		public function creation(){
			$this->render("users/form");
		}

		public function create(){
			$user = User::create(array("name" => $this->params["name"], "description" => $this->params["description"]));
			$this->redirect_to("/users/" . $user->id);
		}

		public function destroy(){
			$user = User::find($this->params["id"]);
			$user->destroy();

			$this->redirect_to("/users");
		}

		public function edit(){
			$user = User::find($this->params["id"]);
			$this->render("users/edit", array("user" => $user));
		}

		public function update(){
			$user = User::find($this->params["id"]);
			$user->update_attributes(array("name" => $this->params["name"]));
			$this->redirect_to("/users/" . $this->params["id"]);
		}

	}

?>