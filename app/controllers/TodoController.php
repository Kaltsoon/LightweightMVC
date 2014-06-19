<?php

	class TodoController extends BaseController{

		public function index(){
			$this->render("todos/index", array("todos" => Todo::all()));
		}

		public function show(){
			$todo = Todo::find($this->params["id"]);
			$this->render("todos/show", array("todo" => $todo));
		}

		public function creation(){
			$this->render("todos/create_form");
		}

		public function create(){
			$todo = new Todo;
			$todo->name = $this->params["name"];
			$todo->description = $this->params["description"];
			$todo->save();

			$this->redirect_to("/todos/" . $todo->id);
		}

		public function destroy(){
			$todo = Todo::find($this->params["id"]);
			$todo->destroy();

			$this->redirect_to("/todos");
		}

		public function edit(){
			$todo = Todo::find($this->params["id"]);
			$this->render("todos/edit_form", array("todo" => $todo));
		}

		public function update(){
			$todo = Todo::find($this->params["id"]);
			$todo->name = $this->params["name"];
			$todo->description = $this->params["description"];
			$todo->save();

			$this->redirect_to("/todos/" . $this->params["id"]);
		}

	}

?>