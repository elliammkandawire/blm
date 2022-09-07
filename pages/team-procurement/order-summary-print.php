<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');
	 //include("export-stock.php");

    $sess = array();
    $msg = array();
    $success = array();
	
    if (!isset ($_SESSION['team-procurement']))
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

	  $user_check = $_SESSION['team-procurement']; // Stored Session for current logged user

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

      $ret = "SELECT user.team_code FROM user 
              JOIN team 
              ON team.team_code = user.team_code
              WHERE user.username = '$user_check'";
      $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
      $getTeamCode = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $teamCode = $getTeamCode['team_code'];

      $results = $connection->query("SELECT *  
                                      FROM orderDetails 
                                      WHERE team_code=$teamCode
                                      ORDER BY order_date DESC");
      $orders = $results->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Procurement | Order Summary</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Main content -->
      <section class="content">
		<div class="container-fluid"> 
		   <div class="row">
              <div style="font-weight: bold; margin-bottom: 15px;" class="col-md-12 col-sm-12">  
                All Stock Details
              </div>
			</div>
			<div class="row">
			  <div class="col-md-12">
                <!-- table start -->
               <table id="stockTable" class="table table-striped table-bordered">
                <thead class="text-nowrap">
                  <tr>
					<th>Order ID</th>
					<th>Order Number</th>
                    <th>Supplier Name</th>
                    <th>Status</th>
					<th>Tax (%)</th>
					<th>Tax Amount</th>
                    <th>Sub Total</th>
                    <th>Grand Total</th>
                    <th>Order Date</th>
                  </tr>
                </thead>
                <tbody>
                    <?php 		 
                        foreach ($orders as $order)
                        {
                   ?>
                  <tr>
                    <td><?= $order['order_detail_id']; ?></td>
                    <td><?= $order['order_number']; ?></td>
					<td><?= $order['supplier_name']; ?></td>
                    <td><?= $order['status']; ?></td>
                    <td><?= $order['tax']; ?></td>
                    <td><?= number_format($order['tax_amount'], 2); ?></td>
                    <td><?= number_format($order['sub_total'], 2); ?></td>
					<td><?= number_format($order['grand_total'], 2); ?></td>
                    <td class="text-nowrap"><?= $order['order_date']; ?></td>
				   </tr>
				  <?php
					  }
					  ?>
                </tbody>
              </table>
              <!-- /.end table -->
			   </div>
			</div>
		  </div>
            <!-- /.container fluid -->
        </section>
	  <!-- /.section -->
</div>
<!-- ./wrapper -->
<script>
    window.addEventListener("load", window.print());
	//window.close();
</script>
</body>
</html>                                                                                                          