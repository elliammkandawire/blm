<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	        
	$itemId1 = mysqli_real_escape_string($connection, $_POST['itemId1']);
    $quantity1 = mysqli_real_escape_string($connection, $_POST['quantity1']);
    $quantity2 = mysqli_real_escape_string($connection, $_POST['quantity2']);
	$stockUsed = mysqli_real_escape_string($connection, $_POST['stockUsed']);
    $description1 = mysqli_real_escape_string($connection, $_POST['description1']);

	$update = (mysqli_query($connection,"UPDATE item SET quantity=$quantity1-$quantity2, stock_used=$stockUsed+$quantity2 WHERE item_id='$itemId1'"));
	$insert = (mysqli_query($connection,"INSERT INTO stock_usage(quantity_taken, description, item_id, date_taken)
                                         VALUES($quantity2, '$description1', '$itemId1', NOW())"));		   
	if($update && $insert) {
		$valid['success'] = true;
		$valid['messages'] = "You have taken stock successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating stock details";
	}

} // /$_POST	 
echo json_encode($valid);
?>