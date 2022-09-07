<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

if($_POST['action'] == 'delete_uploads' && $_POST['id']) {
	$query="SELECT file_name FROM upload WHERE file_id='" .$_POST['id']. "'";
	$result= mysqli_query($connection, $query)or die(mysqli_error($connection));
	$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
	
    $delete = "DELETE FROM upload WHERE file_id='" .$_POST['id']. "' LIMIT 1";
    mysqli_query($connection, $delete)or die(mysqli_error($connection));
    unlink("uploads/".$row['file_name']);	
	$jsonResponse = array(
		"message" => "File Deleted Successfully",	
		"status" => 1	
	);
	echo json_encode($jsonResponse);	
}
?>

