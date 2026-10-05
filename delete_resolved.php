<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "DELETE FROM complaints WHERE status='Resolved'";

if (mysqli_query($conn, $sql)) {
    header("Location: admin.php?message=resolved_deleted");
    exit();
} else {
    echo "Error deleting resolved complaints: " . mysqli_error($conn);
}
?>