<?php
session_start();

$is_admin = isset($_SESSION['admin']);

session_unset();
session_destroy();

if ($is_admin) {
    header("Location: admin_login.php");
} else {
    header("Location: login.php");
}

exit();
?>