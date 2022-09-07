<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	        
			$userId = mysqli_real_escape_string($connection, $_POST['userId']);
			$firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
            $surname = mysqli_real_escape_string($connection, $_POST['surname']);
            $username = mysqli_real_escape_string($connection, $_POST['username']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
            $userType = mysqli_real_escape_string($connection, $_POST['userType']);
            $teamCode = mysqli_real_escape_string($connection, $_POST['teamCode']);

				
	$update = "UPDATE user SET firstname='$firstname', surname='$surname', username='$username', password='$password', user_type='$userType', team_code='$teamCode'
               WHERE id='$userId'";
	if(mysqli_query($connection, $update)) {
		$valid['success'] = true;
		$valid['messages'] = "User details updated successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating user details";
	}

} // /$_POST
	 
echo json_encode($valid);
?>