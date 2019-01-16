<?php

/**
 * Connect to the database through PHP Data Objects (PDO).
 *
 * Written in PHP 7.2.4 / Windows 10
 * Author: Alvin Tang
 * Date Created: 2018-05-17
 * Date Last Modified: 2018-05-18
 *
*/

// Connect to the database
try {
	$db_connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
	$db_connection->setAttribute(PDO:: ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}

?>