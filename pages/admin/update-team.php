<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	        
	$teamId = mysqli_real_escape_string($connection, $_POST['teamId']);
    $teamName = mysqli_real_escape_string($connection, $_POST['teamName']);
    $teamCode = mysqli_real_escape_string($connection, $_POST['teamCode']);

				
	$update = "UPDATE team SET team_code='$teamCode', name='$teamName' WHERE team_id='$teamId'";
	if(mysqli_query($connection, $update)) {
		$valid['success'] = true;
		$valid['messages'] = "Team details updated successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating team details";
	}

} // /$_POST
	 
echo json_encode($valid);
?>