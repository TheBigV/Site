<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['expiry']);
session_destroy();
session_regenerate_id(true);

header('Location: .');
?>