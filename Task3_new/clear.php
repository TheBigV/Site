
<?php
require_once 'system/database.php';
require_once 'system/utilities.php';

authenticate();

if(!$user) {
	header('Location: login');
	exit();
}

if(isset($_POST['confirm'])) {
	$query = $link->prepare('DELETE FROM todo WHERE `user` = :user');
	$query->execute(array(':user' => $user));
	
	header('Location: .');
	exit();
}
?><!DOCTYPE html>

<html>
	<head>
		<!-- Page title -->
		<title>Clear all items &middot; TODO:</title>

		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="stylesheets/default.css" />
	</head>
	<body>
		<div id="content">
			<form method="POST">
				<p>Are you sure you want to clear <em>your entire todo list</em>?</p>
	
				<input type="submit" name="confirm" value="Yes, clear it." class="destructive button" />
				<a href="." class="button">No, go back.</a>
			</form>
		</div>
	</body>
</html>
