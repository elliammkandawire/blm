<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	        
	$stockTakeId = mysqli_real_escape_string($connection, $_POST['stockTakeId']);
    $closingStock = mysqli_real_escape_string($connection, $_POST['closingStock']);
    $physicalStock = mysqli_real_escape_string($connection, $_POST['physicalStock']);
    $variance = mysqli_real_escape_string($connection, $_POST['variance']);
    $remarks = mysqli_real_escape_string($connection, $_POST['remarks']);
	
	$update = "UPDATE stock_take SET closing_stock='$closingStock', physical_stock='$physicalStock', variance='$variance', remarks='$remarks' WHERE stock_take_id='$stockTakeId' LIMIT 1";
	if(mysqli_query($connection, $update)) {
		$valid['success'] = true;
		$valid['messages'] = "You have updated the stock details successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating stock details";
	}

} // /$_POST
	 
echo json_encode($valid);
?>