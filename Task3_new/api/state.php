<?php
require_once '../system/database.php';
require_once '../system/utilities.php';

authenticate();

if(!$user) {
	header('Content-Type: application/json');
	die('{"logged_in":false}');
}

if(!isset($_POST['id'], $_POST['done']) || !ctype_digit($_POST['id']) || ($_POST['done'] !== '1' && $_POST['done'] !== '0')) {
	header('HTTP/1.1 400 Bad Request');
	die('Invalid parameters.');
}

$query = $link->prepare('UPDATE todo SET `done` = :done WHERE `id` = :id AND `user` = :user LIMIT 1;');
$query->execute(array(
		':id'   => (int)$_POST['id'],
		':done' => (int)$_POST['done'],
		':user' => $user
	));

header('Content-Type: application/json');
echo json_encode(array('success' => $query->rowCount() === 1));
?>