<?php
require_once 'system/database.php';
require_once 'system/utilities.php';

authenticate();

if($user) {
	require 'system/templates/todo.php';
} else {
	echo file_get_contents('system/templates/welcome.html');
}
?>