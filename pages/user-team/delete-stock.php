<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

if($_POST['action'] == 'delete_stock' && $_POST['id']) {
	
    $delete = "DELETE FROM item WHERE item_id ='" .$_POST['id']. "' LIMIT 1";
    mysqli_query($connection, $delete)or die(mysqli_error($connection));	
	$jsonResponse = array(
		"message" => "Record Deleted",	
		"status" => 1	
	);
	echo json_encode($jsonResponse);	
}
?>

