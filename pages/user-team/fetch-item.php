<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once('../connection/db-connection.php');

      $itemNumber = mysqli_real_escape_string($connection, $_POST['itemNumber']); 
      $query = "SELECT * FROM product WHERE product_code ='$itemNumber'";
	  
	  $result= mysqli_query($connection, $query)or die(mysqli_error($connection));
	  
	  $data=array();
      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$itemCode = $row['product_code']; 
        $itemName = $row['product_name'];
        $specification = $row['specification'];
        $type = $row['type'];
		$category = $row['category'];
        $data[] = array("product_code" => $itemCode, "product_name" => $itemName, "specification" => $specification, "type" => $type, "category" => $category);
     }
	  // encoding array to json format
      echo json_encode($data);
      exit;
 ?>