<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	        
	$itemId = mysqli_real_escape_string($connection, $_POST['itemId']);
    $closingStock = mysqli_real_escape_string($connection, $_POST['closingStock']);
    $physicalStock = mysqli_real_escape_string($connection, $_POST['physicalStock']);
    $remarks = mysqli_real_escape_string($connection, $_POST['remarks']);
	
	$update = "UPDATE stock_take SET closing_stock='$closingStock', physical_stock='$physicalStock', remarks='$remarks' 
	           WHERE stock_take_id='$itemId'";
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