<?php
require_once 'system/database.php';
require_once 'system/utilities.php';

$ajax = isset($_POST['ajax']);

if(isset($_POST['e-mail'], $_POST['password'], $_POST['repassword'])) {
	$errors = array();

	# Check information validity:
	if(!filter_var($_POST['e-mail'], FILTER_VALIDATE_EMAIL)) {
		$errors[] = array(
				'relatedElement' => 'e-mail',
				'message' => 'That’s not an e-mail address.'
			);
	}

	if(strlen($_POST['password']) < 8) {
		$errors[] = array(
				'relatedElement' => 'password',
				'message' => 'Your password needs to be at least 8 characters long.'
			);
	}

	if($_POST['password'] !== $_POST['repassword']) {
		$errors[] = array(
				'relatedElement' => 'repassword',
				'message' => 'The passwords didn’t match; try again.'
			);
	}

	# Make sure the e-mail isn't already in use:
	$query = $link->prepare('SELECT COUNT(*) AS count FROM users WHERE `email` = :email LIMIT 1;');
	$query->execute(array(':email' => $_POST['e-mail']));

	if($query->fetch()->count !== 0) {
		$errors[] = array(
				'relatedElement' => 'e-mail',
				'message' => 'That e-mail address is already taken. Did you <a href="#">forget your password</a>?'
			);
	}

	# Is everything in order?
	if(count($errors) === 0) {
		# Create a password hash:
		$salt = generateSalt();
		$hash = crypt($_POST['password'], '$2y$11$' . $salt);

		#Set key: future feache
		$key = "active";

		# Insert the user into the database:
		$query = $link->prepare('INSERT INTO users(`email`, `password`, `key`) VALUES(:email, :password, :key);');
		$query->execute(array(':email' => $_POST['e-mail'], ':password' => $hash, ':key' => $key));

		# All right!
		if($ajax) {
			echo '{"success":true}';
			exit();
		}
	} elseif($ajax) {
		# Report some errors through JSON:
		echo json_encode(array(
				'success' => false,
				'errors'  => $errors
			));
		exit();
	}
}
?>
