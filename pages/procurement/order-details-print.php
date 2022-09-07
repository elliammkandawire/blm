<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');

     $sess = array();
     $msg = array();
     $success = array();
     
    if (!isset ($_SESSION['procurement']))
        {
        // User not logged in, redirected to login page
        Header("Location: ../../index.php");
        }
		    else
        {
			    $now = time();
		    }
		    if ($now > $_SESSION['expire'])
        {
			     session_destroy();
			     $sess[] = 'Your session has expired please login again';
		    }
	      $user_check = $_SESSION['procurement']; // Stored Session for current logged user

        $ret = "SELECT id FROM user WHERE username = '$user_check'";
        $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
	    $getid = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $userid = $getid['id'];

        $ret = "SELECT firstname FROM user WHERE username = '$user_check'";
	    $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
        $getfirstname= mysqli_fetch_array($result, MYSQLI_ASSOC);
        $firstname = $getfirstname['firstname'];

        $ret = "SELECT surname FROM user WHERE username = '$user_check'";
	    $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
        $getsurname = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $surname = $getsurname['surname'];

        $ret = "SELECT user.team_code FROM 
	          user 
			  JOIN team 
			  ON team.team_code = user.team_code
			  WHERE user.username = '$user_check'";
	    $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
        $getTeamCode = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $teamCode = $getTeamCode['team_code'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Procurement | Purchase Order</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <!-- title row -->
              <div class="row">
                <div class="col-12">
                 <?php
                     if(isset($_GET['order_detail_id']))
                     {
                        $orderId =  $_GET['order_detail_id'];
    
                        $results = $connection->query("SELECT *  
                              FROM orders
                              JOIN orderDetails 
                              ON orders.order_detail_id =  orderDetails.order_detail_id
                              WHERE orderDetails.order_detail_id = $orderId
                              ORDER BY orderDetails.order_date DESC");
                        $orders = $results->fetch_all(MYSQLI_ASSOC);

                       $results = $connection->query("SELECT *  
                              FROM orders
                              JOIN orderDetails 
                              ON orders.order_detail_id =  orderDetails.order_detail_id
                              WHERE orderDetails.order_detail_id = $orderId
                              ORDER BY orderDetails.order_date DESC");
                       $order = $results->fetch_array(MYSQLI_ASSOC);
               
                        }
                   ?>
                  <h5>
                    <img src="../../dist/img/blm_logo.png" width="115" height="110" class="brand-image img-circle img-fluid elevation-2" alt="Banja logo">&nbsp;<strong>Banja La Mtsogolo Purchase Order</strong>
                    <small class="float-right">Order Date:&nbsp;<?php echo''. date('j F, Y, g:i a', strtotime( $order['order_date'])).''; ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From:
                  <address>
                    <strong>Banja La Mtsogolo.</strong><br>
					Off Paul Kagame Rd,<br>
					Area 4, Opposite CFAO<br>
                    P.O. Box 1854,<br>
                    LILONGWE<br>
                   <strong>Tel:</strong> 01 772 511/ 505  
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To:
                  <address>
                    <strong><?php echo $order['supplier_name']; ?></strong><br>
                    <?php 
					    $order['supplier_address'] = str_replace("\n", "<br>", $order['supplier_address']);
					    echo $order['supplier_address']; 
						?>
					    <br>
						<strong>Email:</strong>&nbsp;<?php echo $order['email']; ?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Order Number:</b>&nbsp;<?php echo $order['order_number']; ?><br>
                  <b>Order ID:</b>&nbsp;<?php echo $order['order_detail_id']; ?><br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
                 
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Quantity</th>
                      <th>Product</th>
					  <th>Specifications</th>
                      <th>Unit</th>
                      <th>Price</th>
                      <th>Total Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                       foreach ($orders as $order)
                       {
                   ?>  
                    <tr>
                      <td><?= $order['quantity']; ?></td>
                      <td><?= $order['item_name']; ?></td>
					   <td><?= $order['specification']; ?></td>
                      <td><?= $order['unit']; ?></td>
                      <td><?= number_format($order['price'], 2); ?></td>
                      <td><?= number_format($order['total_price'], 2); ?></td>
                    </tr>
                    <?php 
                      }
                    ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                
                <div class="col-6">
				  <strong>General description:</strong>
                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                     <?= $order['description']; ?>
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?= number_format($order['sub_total'], 2); ?></td>
                      </tr>
                      <tr>
                        <th>Tax rate (%)</th>
                        <td><?= $order['tax']; ?></td>
                      </tr>
					   <tr>
                        <th>Tax Amount</th>
                        <td><?= number_format($order['tax_amount'], 2); ?></td>
                      </tr>
                      <tr>
                        <th>Grand Total:</th>
                        <td><?= number_format($order['grand_total'], 2); ?></td>
                      </tr>
                    </table>
                   
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
  window.close();
</script>
</body>
</html>
