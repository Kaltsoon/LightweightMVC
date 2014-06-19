<?php

	/**
	*	Write all the routes of your application here.
	*	HTTP POST, GET, PUT and DELETE requests are supported.
	*	First parameter is the path and the second parameter has the controller and the method seperated with "#".
	*	F.e $router->get("/books", "BooksController#index"); calls the method "index" of the class "BookController".
	*/
	$router = new Router();

	$router->get("/", "Todo#index");
	$router->get("/todos", "Todo#index");
	$router->get("/todos/new", "Todo#creation");
	$router->get("/todos/@id", "Todo#show");
	$router->post("/todos", "Todo#create");
	$router->post("/todos/delete", "Todo#destroy");
	$router->post("/todos/edit", "Todo#update");
	$router->get("/todos/@id/edit", "Todo#edit");

	$router->route();
?>