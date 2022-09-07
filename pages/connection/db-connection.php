<?php
	$server="localhost";
	$username="root";
	$password="";
	$db_name="blm";

	$connection=mysqli_connect($server, $username, $password) or die(mysqli_connect_error());
	mysqli_select_db($connection, $db_name);
?>