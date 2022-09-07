<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php'); 
include('../misc/functions.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST){
	$orderId = mysqli_real_escape_string($connection, $_POST['orderId']);
	$amountToPay = mysqli_real_escape_string($connection, $_POST['amountToPay']);
	$amountPaid = mysqli_real_escape_string($connection, $_POST['amountPaid']);
            
	
	$update = "UPDATE orderdetails SET amount_paid=$amountPaid+$amountToPay, payment_status='Paid'
               WHERE order_detail_id=$orderId";
	
    $insert ="INSERT INTO payment(amount_paid, date_paid, order_detail_id)
	           VALUES($amountToPay, NOW(), $orderId)";
	
	if(mysqli_query($connection, $update)&& mysqli_query($connection, $insert)) {
		$valid['success'] = true;
		$valid['messages'] = "You have made payment to this order successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while making payment to the order details";
	}

} // /$_POST	 
echo json_encode($valid);
?>