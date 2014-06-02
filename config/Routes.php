<?php

	/**
	*	Write all the routes of your application here.
	*	HTTP POST, GET, PUT and DELETE requests are supported.
	*	First parameter is the path and the second parameter has the controller and the method seperated with "#".
	*	F.e $router->get("/books", "BooksController#index"); calls the method "index" of the class "BookController".
	*/
	$router = new Router();

	$router->get("/", "UserController#index");
	$router->get("/users", "UserController#index");
	$router->get("/users/new", "UserController#creation");
	$router->get("/users/@id", "UserController#show");
	$router->post("/users", "UserController#create");
	$router->post("/users/delete", "UserController#destroy");
	$router->post("/users/edit", "UserController#update");
	$router->get("/users/@id/edit", "UserController#edit");

	$router->route();
?>