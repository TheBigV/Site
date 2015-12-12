<?php
try {
	$link = new PDO('mysql:host=localhost;charset=utf8;dbname='
.	'todo' # Database
,	'root' # Username
,	'' # Password
);
	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$link->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch(Exception $ex) {
	header('HTTP/1.1 500 Internal Server Error');
	die('Failed to connect to database.');
}
?>
