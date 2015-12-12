<?php
require_once '../system/database.php';
require_once '../system/utilities.php';

authenticate();

if(!$user) {
	header('Content-Type: application/json');
	die('{"logged_in":false}');
}

if(!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
	header('HTTP/1.1 400 Bad Request');
	die('Invalid parameters.');
}

$query = $link->prepare('DELETE FROM todo WHERE `id` = :id AND `user` = :user LIMIT 1;');
$query->execute(array(
		':id'   => (int)$_POST['id'],
		':user' => $user
	));

echo json_encode(array('success' => $query->rowCount() === 1));
?>