<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if(isset($_POST['approve'])){
	        
	$requisitionId = mysqli_real_escape_string($connection, $_POST['requisitionId']);
	$comment = mysqli_real_escape_string($connection, $_POST['comment']);
            	
	$update = "UPDATE requisition 
	           JOIN requisition_details
			   ON requisition.requisition_id=requisition_details.requisition_id 
			   SET requisition.status='Approved', requisition.view_status=2, 
			   requisition.date_replied=NOW(), requisition.comment2='$comment', 
			   requisition_details.reply_status=2 
	           WHERE requisition.requisition_id='$requisitionId'";
	if(mysqli_query($connection, $update)) {
		$valid['success'] = true;
		$valid['messages'] = "Stock requisition has been approved successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while approving stock requisition";
	}
} // /$_POST	 
echo json_encode($valid);
?>