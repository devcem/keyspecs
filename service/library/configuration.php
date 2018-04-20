<?php

	$db = new db("mysql:host=localhost;dbname=features", "root", "root");
	$db->setErrorCallbackFunction("error");

	$level = array(
		0 => 'Envato Purchase',
		1 => 'Patreon Purchase'
	);