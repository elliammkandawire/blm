<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
			$itemId = mysqli_real_escape_string($connection, $_POST['itemId']);
			$itemNumber = mysqli_real_escape_string($connection, $_POST['itemNumber']);
            $itemName = mysqli_real_escape_string($connection, $_POST['itemName']);
            $unit = mysqli_real_escape_string($connection, $_POST['unit']);
            $type = mysqli_real_escape_string($connection, $_POST['type']);
            $category = mysqli_real_escape_string($connection, $_POST['category']);
            $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
            $price = mysqli_real_escape_string($connection, $_POST['price']);
            $batchNo = mysqli_real_escape_string($connection, $_POST['batchNo']);
            $expiryDate = mysqli_real_escape_string($connection, $_POST['expiryDate']);
            $grn = mysqli_real_escape_string($connection, $_POST['grn']);
            $description = mysqli_real_escape_string($connection, $_POST['description']);
	
	$update = "UPDATE item SET item_code='$itemNumber', item_name='$itemName', specification='$description', type='$type', category='$category', unit='$unit',  quantity=$quantity, expiry_date='$expiryDate', price=$price, total_price=$quantity*$price, batch='$batchNo', grn='$grn'
               WHERE item_id='$itemId'";
	if(mysqli_query($connection, $update)) {
		$valid['success'] = true;
		$valid['messages'] = "Stock details updated successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating stock details";
	}

} // /$_POST	 
echo json_encode($valid);
?>