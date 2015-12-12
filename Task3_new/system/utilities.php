<?php
define('SALT_CHARACTERS', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./');
define('KEY_CHARACTERS', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');

# Generates salt for bcrypt.
function generateSalt() {
	return generateRandom(SALT_CHARACTERS, 22);
}



function generateRandom($characters, $length) {
	$str = '';
	$setmax = strlen($characters) - 1;

	for($i = 0; $i < $length; $i++) {
		$str .= $characters[mt_rand(0, $setmax)];
	}

	return $str;
}

# Starts the session and makes sure the login hasn't expired:
function authenticate() {
	global $user;
	global $link;

	session_start();

	if(isset($_SESSION['id'])) {
		if($_SESSION['expiry'] >= time()) {
			# Make sure the user still exists:
			$query = $link->prepare('SELECT COUNT(*) AS count FROM users WHERE `id` = :id LIMIT 1;');
			$query->execute(array(':id' => $_SESSION['id']));

			if($query->fetch()->count === 1) {
				# The user is already logged in.
				$user = $_SESSION['id'];
				return;
			}
		}

		# The user's session has expired, or the user was deleted, or something is very wrong.
		unset($_SESSION['id']);
		unset($_SESSION['expiry']);
		session_destroy();
		session_regenerate_id(true);
		session_start();
	}

	$user = null;
}

$user = null;
?>
