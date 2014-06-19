<?php 
	// Require database config
	require_once("config/Database.php");

	// Require lib classess
	require_once("lib/Utils.php");
	require_once("lib/BaseModel.php");
	require_once("lib/BaseController.php");
	require_once("lib/Router.php");

	// Require models
	foreach (glob("app/models/*.php") as $filename){
    	include_once $filename;
	}

	// Require routes
	require_once("config/Routes.php");
?>