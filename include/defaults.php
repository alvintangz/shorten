<?php

/**
 * Contains all the defaults that could be changed depending on preferences and/or environment circumstances.
 *
 * Written in PHP 7.2.4 / Windows 10
 * Author: Alvin Tang
 * Date Created: 2018-05-22
 * Date Last Modified: 2018-05-22
 *
*/

/*
BASE URL BEFORE SHORTENED NAME
Example 1: https://bit.ly/123456 - https://bit.ly is the BASE URL
*/
define("BASE_URL", "");

/*
DEFAULT TIMEZONE
Removing expired shortened links out of the database depends on the expired time set during this specified timezone.
Default set to America/Toronto where this Shorten program was developed.
Learn more about timezones at http://php.net/manual/en/timezones.php.
*/
date_default_timezone_set('America/Toronto');

/*
DATABASE CONSTANTS
Information used to connect to the database in the db-connect.php file.
*/
define("DB_HOST", "");
define("DB_NAME", "");
define("DB_USERNAME", "");
define("DB_PASSWORD", "");

/*
TODAY'S DATE AS A DATETIME OBJECT
*/
$today = new DateTime("now");

/*
DEFAULT EXPIRY DATE AS A DATETIME OBJECT
This is the default expiry of a new shortened link.
*/
$default_expiry = clone $today;
$default_expiry->modify('+1 year');

/*
DEFAULT LIMIT EXPIRY DATE AS A DATETIME OBJECT
This is the default expiry of a new shortened link.
*/
$default_ex_limit = clone $today;
$default_ex_limit->modify('+5 year');

/*
CHARACTERS OF THE SHORTENED NAMES
The characters that will be used for the shortened names.
Default set to all alphanumeric characters, '-', and '_'. There are 64 characters in total.
With the default size of shortened names as 6, you have 68719476736 (64^6) total shortened names to use.
64 characters
*/
define("NAME_CHARS", array_merge(range('A', 'Z'), range('a', 'z'), array('-', '_', '0', '1', '2', '3', '4', '5', '6', '7','8','9')));

/*
SIZE OF SHORTENED NAMES
The number of characters in the shortened names.
Default set to 6.
Example 1: https://bit.ly/123456 - size is 6
Example 2: https://goo.gl/a8_A9 - size is 5
*/
define("NAME_SIZE", 6);

?>