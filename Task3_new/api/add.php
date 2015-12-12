<?php
require_once '../system/database.php';
require_once '../system/utilities.php';

authenticate();

if(!$user) {
	header('Content-Type: application/json');
	die('{"logged_in":false}');
}

if(!isset($_POST['text'], $_POST['done']) || $_POST['text'] === '' || ($_POST['done'] !== '1' && $_POST['done'] !== '0')) {
	header('HTTP/1.1 400 Bad Request');
	die('Invalid parameters.');
}

$query = $link->prepare('INSERT INTO todo(`done`, `user`, `text`) VALUES(:done, :user, :text);');
$query->execute(array(
		':done' => (int)$_POST['done'],
		':user' => $user,
		':text' => $_POST['text']
	));

echo json_encode(array('id' => $link->lastInsertId()));
?>