<?php

/**
 * The action page to actually shorten a link.
 *
 * Written in PHP 7.2.4 / Windows 10
 * Author: Alvin Tang
 * Date Created: 2018-05-22
 * Date Last Modified: 2018-05-22
 *
*/

// Set this as JSON file
header('Content-Type: application/json');

if(!isset($_POST['expirationOfLink'])) {
	echo json_encode(['status' => 0]);
	die();
}

// Require necessary files
require_once("include/defaults.php");
require_once("include/db-connect.php");

$shortened = '';

$insert_sql = "INSERT INTO tb_links (name, link, expire) VALUES (:name, :link, :expire)";

// Expiration date set as a DateTime object
$expiration = new DateTime($_POST['expirationOfLink']);

// If url is not valid, redirect to provide error
if (!filter_var($_POST['shortenThis'], FILTER_VALIDATE_URL)) {
	echo json_encode(['status' => 1]);
	die();
}

// If expiration date was in the past, redirect to provide error
if ($expiration <= $today) {
	echo json_encode(['status' => 2]);
	die();
}

// If expiration date is above the limit, redirect to provide error
if ($expiration >= $default_ex_limit) {
	echo json_encode(['status' => 3]);
	die();
}

// If characters in custom name is not in NAME_CHARS, redirect to provide error
if (!($_POST['customName'] == "")) {

	// Loop through each character
	for ($char_index = 0; $char_index < strlen($_POST['customName']); $char_index++) {
		if (!in_array($_POST['customName'][$char_index], NAME_CHARS, true)) {
			echo json_encode(['status' => 4]);
			die();
		} 
	}
	// CUSTOM NAME BUG
}

// If characters are more than 10 characters long, redirect to provide error
if(strlen($_POST['customName']) > 10) {
	echo json_encode(['status' => 5]);
	die();
}

// Handle custom name
if (!($_POST['customName'] == "")) {
	$check_custom_sql = "SELECT * FROM tb_links WHERE name = :name";
	$sth = $db_connection->prepare($check_custom_sql);
	$sth->execute(array(':name' => $_POST['customName']));
	if($sth->rowCount() > 0) {
		echo json_encode(['status' => 6]);
		die();
	} else {

		$db_connection->prepare($insert_sql)->execute(array(
			':name' => $_POST['customName'],
			':link' => $_POST['shortenThis'],
			':expire' => $expiration->format('Y-m-d H:i:s')
		));

		echo json_encode(['status' => 200, 'name' => BASE_URL . $_POST['customName']]);
		die();
	}
}

// Generate random name
$found = false;
while(!$found) {
	// Generate a 6 character random name with $chars
	for ($i = 0; $i < 6; $i++) {
		$shortened .= NAME_CHARS[array_rand(NAME_CHARS, 1)];
	}
	if ($db_connection->query("SELECT * FROM tb_links WHERE name = '" . $shortened . "'")->rowCount() == 0) {
		$found = true;
	}
}

$db_connection->prepare($insert_sql)->execute(array(
	':name' => $shortened,
	':link' => $_POST['shortenThis'],
	':expire' => $expiration->format('Y-m-d H:i:s')
));

echo json_encode(['status' => 200, 'name' => BASE_URL . $shortened]);