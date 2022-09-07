<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php');

$query = "SELECT * FROM item";
$resultset = mysqli_query($connection, $query) or die(mysqli_error($connection));
$stock_records = array();
while($rows = mysqli_fetch_assoc($resultset) ) {
	$stock_records[] = $rows;
}	
if(isset($_POST["export-stock"])) {	
	$filename = "Warehouse-stock-on-hand-".date('d-m-y'). ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($stock_records)) {
	  foreach($stock_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}
?>