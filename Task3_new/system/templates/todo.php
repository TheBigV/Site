<?php
if(isset($_POST['submit'])) {
	$addId = -1;

	if(isset($_POST['content'])) {
		foreach($_POST['content'] as $id => $content) {
			if(!is_int($id)) {
				header('HTTP/1.1 400 Bad Request');
				die('Invalid item ID.');
			}

			if($id === -1 && !empty($content)) {
				$query = $link->prepare('INSERT INTO todo(`done`, `user`, `text`) VALUES(0, :user, :text);');
				$query->execute(array(
						':user' => $user,
						':text' => $content
					));
				$addId = $link->lastInsertId();
			} else {
				$query = $link->prepare('UPDATE todo SET `text` = :text WHERE `id` = :id AND `user` = :user LIMIT 1;');
				$query->execute(array(
						':id' => (int)$id,
						':user' => $user,
						':text' => $content
					));
			}
		}
	}

	$query = $link->prepare('UPDATE todo SET `done` = 0 WHERE `user` = :user');
	$query->execute(array(':user' => $user));

	if(isset($_POST['done'])) {
		foreach($_POST['done'] as $done) {
			if(!ctype_digit($done) && $done !== '-1') {
				header('HTTP/1.1 400 Bad Request');
				die('Invalid item ID.');
			}

			if($done === '-1') {
				$done = $addId;
			}

			$query = $link->prepare('UPDATE todo SET `done` = 1 WHERE `id` = :id AND `user` = :user LIMIT 1;');
			$query->execute(array(':id' => (int)$done, ':user' => $user));
		}
	}
}

$query = $link->prepare('SELECT * FROM todo WHERE `user` = :user ORDER BY `done`, `id` DESC;');
$query->execute(array(':user' => $user));
?><!DOCTYPE html>

<html>
	<head>
		
		<title>My list &middot; TODO:</title>

		
		<link rel="stylesheet" type="text/css" href="stylesheets/default.css" />
		<link rel="stylesheet" type="text/css" href="stylesheets/todo.css" />

		
		<script type="text/javascript" src="scripts/json2.min.js"></script>
	</head>
	<body>
		<form method="POST" id="content">
			<h1>My todo list</h1>
<a class = 'button' href="logout.php">LogOut</a>


			<ul id="todo-items">
				<?php while($row = $query->fetch()): ?>
					<li><a href="delete?id=<?= $row->id ?>" class="delete">Delete</a> <input type="checkbox" name="done[]" value="<?= $row->id ?>" class="done-box"<?php if($row->done): ?> checked="checked"<?php endif; ?> /> <span class="content"><?= htmlspecialchars($row->text) ?></span></li>
				<?php endwhile; ?>

				<li><input type="checkbox" name="done[]" value="-1" class="done-box" /> <input type="text" name="content[-1]" value="" placeholder="New item" class="edit" /></li>
			</ul>

			<input type="submit" name="submit" value="Update" class="button" id="update-button" /> <a href="clear" class="destructive button">Clear All</a>
		</form>
                
		<script type="text/javascript" src="scripts/todo.js" async="async"></script>
	</body>
</html>
