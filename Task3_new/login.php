<?php
require_once 'system/database.php';
require_once 'system/utilities.php';

authenticate();

$loginError = null;

if(isset($_POST['e-mail'], $_POST['password'])) {
	$query = $link->prepare('SELECT `id`, `password`, `key` FROM users WHERE `email` = :email LIMIT 1;');
	$query->execute(array(':email' => $_POST['e-mail']));

	if($row = $query->fetch() and crypt($_POST['password'], $row->password) === $row->password) {
		if($row->key == "active") {
			$_SESSION['id'] = (int)$row->id;
			$_SESSION['expiry'] = time() + 30 * 24 * 60 * 60; # Expire in one month
			header('Location: .');
			exit();
		} else {
			$loginError = 'This account hasn’t been activated yet; check your e-mail. If you didn’t get a message, please contact support.';
		}
	} else {
		$loginError = 'Wrong e-mail address or password; try again!';
	}
}
?><!DOCTYPE html>

<html>
	<head>
		<!-- Page title -->
		<title>Log in &middot; TODO:</title>

		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="stylesheets/default.css" />

		<!-- Scripts -->

		<!-- Compatibility -->
	</head>
	<body>
		<div id="content">	
			<h1>Log In</h1>

			<form method="POST">
				<?php if($loginError !== null): ?>
					<p><?= $loginError ?></p>
				<?php endif; ?>

				<label for="e-mail" class="label">E-mail address</label>
				<input type="email" name="e-mail" id="e-mail" value="<?= htmlspecialchars(@$_POST['e-mail']) ?>" maxlength="254" placeholder="mary@example.com" class="text" />

				<label for="password" class="label">Password</label>
				<input type="password" name="password" id="password" value="" placeholder="Whatever you picked!" class="text" />

				<input type="submit" value="Log In" class="button" />
			</form>
		</div>
	</body>
</html>
