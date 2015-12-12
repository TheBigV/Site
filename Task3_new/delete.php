<?php
require_once 'system/database.php';
require_once 'system/utilities.php';

authenticate();

if(!$user) {
	header('Location: login');
	exit();
}

if(!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	header('HTTP/1.1 400 Bad Request');
	die('Invalid item ID.');
}

$id = (int)$_GET['id'];

if(isset($_POST['confirm'])) {
	$query = $link->prepare('DELETE FROM todo WHERE `id` = :id AND `user` = :user LIMIT 1;');
	$query->execute(array(':id' => $id, ':user' => $user));

	header('Location: .');

	exit();
} else {
	$query = $link->prepare('SELECT * FROM todo WHERE `id` = :id AND `user` = :user LIMIT 1;');
	$query->execute(array(':id' => $id, ':user' => $user));
?><!DOCTYPE html>

<html>
	<head>
		<!-- Page title -->
		<title>Delete item &middot; TODO:</title>

		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="stylesheets/default.css" />
	</head>
	<body>
		<div id="content">
			<?php if($row = $query->fetch()): ?>
				<form method="POST">
					<p>Are you sure you want to delete <b><?= htmlspecialchars($row->text) ?></b>?</p>

					<input type="submit" name="confirm" value="Yes, delete this item." class="destructive button" />
					<a href="." class="button">No, go back.</a>
				</form>
			<?php else: ?>
				<p>That item doesn’t exist.</p>

				<a href="." class="button">Go back</a>
			<?php endif; ?>
		</div>
	</body>
</html>
<?php
}
?>