<?php
session_start();
session_unset();

session_destroy();
// Logged out, return to Login page.
Header("Location: ../../index.php");
?>
