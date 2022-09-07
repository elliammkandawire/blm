<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
     require_once('../connection/db-connection.php'); 
	 include'send-mail.php';
     include('../misc/functions.php');

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

        $ret = "SELECT user.team_code FROM 
	          user 
			  JOIN team 
			  ON team.team_code = user.team_code
			  WHERE user.username = '$user_check'";
	    $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
        $getTeamCode = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $teamCode = $getTeamCode['team_code'];
		
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
		
		$ret = "SELECT email FROM orderDetails WHERE order_detail_id=$orderId";
        $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
	    $getemail = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $email= $getemail['email'];
		
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
		
		$ret = "SELECT email FROM orderDetails WHERE order_detail_id=$orderId";
        $result=mysqli_query($connection, $ret)or die(mysqli_error($connection));
	    $getemail = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $email= $getemail['email'];
		
		if(isset($_POST['submit']))
		{
			
		    if(!$msg)
	        {    
				$to = $email;
				
                $subject = "Purchase Order from Banja La Mtsogolo";
				
                $message = '<html lang="en">
                            <head>
                            <title>Purchase Order</title>
						     <style>
							       table{
									  border-collapse:collapse;
									  width:100%;
									  text-align:left;
									  padding: 8px;
								    }
								    tr, td, th{
									  border: 1px solid #000000;
								    }
								    h2{
									  text-transform:uppercase;
									  text-decoration:underline;
								    }
								    #totals{
									  text-align: right;
								    }
									caption{
										font-size:15px;
									}
									#header-image{
										float:left;
									}
									#blm-address{
										float:right;
									}
									#supplier-address{
										clear:right;
									}
									#terms-conditions{
										background-color:#c0c0c0;
										
									}
									#terms, #terms-head{
										margin: 10px;
									}

						    </style>
                            </head>
                            <body>';
			    $message .= '<div id="header-image">
				             <img src="cid:brand-logo" width="120" height"115" alt="logo">
				             </div>';
			    $message .= '<div id="blm-address">
                             From:
                            <address>
                            <strong>Banja La Mtsogolo.</strong><br>
					         Off Paul Kagame Rd,<br>
					         Area4, Opposite CFAO<br>
                             P.O. Box 1854,<br>
                             LILONGWE<br>
                             <strong>Tel:</strong> 01 772 511/ 505  
                             </address>
                             </div><br>';
							 
                $message .= '<div id="supplier-address">
                             To:
                             <address>
                             <strong>'. $order['supplier_name'].'</strong><br>
					        '. $order['supplier_address'] = str_replace("\n", "<br>", $order['supplier_address']) .'';
                $message .= '</address></div>';
				
				$message .= '<p>Dear sir/Madam,</p>';
				
				$message .= '<h2>Banja La Mtsogolo Purchase Order</h2>';
				
				$message .= '<p>Please find the details of the purchase order in the table below, also included in this email are the terms and conditions.</p>';
                              						 
				$message .= '<div>
                             <table>
			                 <caption><strong>Order Number:</strong>&nbsp;'. $order['order_number'] .'&nbsp;|&nbsp;<strong>Order ID:</strong>&nbsp;'. $order['order_detail_id'] .'&nbsp;|&nbsp;<strong>Order Date:</strong>&nbsp;'. $order['order_date'].'</caption>
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
                             <tbody>';
					   foreach ($orders as $order)
                       { 
				$message .= '<tr>
                             <td>'.$order['quantity'].'</td>
                             <td>'.$order['item_name'].'</td>
					         <td>'.$order['specification'].'</td>
                             <td>'.$order['unit'].'</td>
                             <td>'. number_format($order['price'], 2).'</td>
                             <td>'. number_format($order['total_price'], 2).'</td>
                             </tr>';
                      }
			    $message .= '<tr>
				              <th>General description:</th>
							  <td colspan="5">'. $order['description'].'</td>
				             </tr>';
				$message .= '<tr>
				              <th id="totals" colspan="5">Sub Total:</th>
							  <td>'. number_format($order['sub_total'], 2) .'</td>
				             </tr>';
				$message .= '<tr>
				              <th id="totals" colspan="5">Tax rate (%):</th>
							  <td>'. number_format($order['tax'], 2) .'</td>
				             </tr>';
		        $message .= '<tr>
				              <th id="totals" colspan="5">Tax Amount:</th>
							  <td>'. number_format($order['tax_amount'], 2) .'</td>
				             </tr>';
				$message .= '<tr>
				              <th id="totals" colspan="5">Grand Total:</th>
							  <td>'. number_format($order['grand_total'], 2) .'</td>
				             </tr>';
                $message .= '</tbody></table></div>';
               
				$message .= '<p>Regards,</p>';
				
				$message .= '<p><strong>Procurement Officer<strong></p>';
				
				$message .= '<div id="terms-conditions">
				             <h3 id="terms-head">Terms and Conditions</h3>
							 <p id="terms">The terms will be placed here</p>
							 </div>';
				
                $message .= '</body></html>';
				
                $name = "Banja La Mtsogolo";
                $mailsend = sendmail($to, $subject, $message, $name);
               if($mailsend==1)
			   {
			      $update = "UPDATE orderdetails SET status='sent' WHERE order_detail_id=$orderId";
                  mysqli_query($connection, $update)or die(mysqli_error($connection));
                  if(!$update)
			     {
                   $msg[] = 'Failed send a purchase order';
                 }
                   $success[]= 'Purchase Order was sent successfully';
               }
               else
			   {
                  $msg[] = 'Failed to send a purchase order due to network problem or the email is not reacheable';
               } 
            }
        }
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Procurement | Order Invoice</title>

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

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
  <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  <span class="sr-only">Loading...</span>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" title="Menu" href="#" role="button"><i class="fas fa-bars"></i>&nbsp;</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a title="Search" class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <!-- Messages Dropdown Menu -->
     <li class="nav-item dropdown">
        <a title="Messages" class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-envelope"></i>
          <?php
               $query = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Replied' AND view_status!=1 AND team_code=$teamCode ORDER BY date_replied DESC LIMIT 4"); 
               $count= mysqli_num_rows($query);
               
               if(mysqli_num_rows($query)>0){
          
            ?>
            <span class="badge badge-danger navbar-badge"><?php echo $count; ?></span>
          <?php }
          else{
            echo '';
          } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php
               if(mysqli_num_rows($query)>0){
  
                   while($result = mysqli_fetch_assoc($query))
                   {
                      $requisition_id = ''.$result['requisition_id'].'';
                      $link = "view-replied-requisition.php?requisition_id=" . base64_encode(json_encode($requisition_id));
                      
                      echo'<a href="'.$link.'" class="dropdown-item">';
                      echo'<!-- Message Start -->';
                      echo'<div class="media"><span><i class="fas fa-envelope mr-2">&nbsp;</i></span>';
                      echo'<div class="media-body"><h3 class="dropdown-item-title">Requisition Reply</h3>';
                      echo'<p class="text-sm">For:&nbsp;'.$result['title'].'</p>';
                      echo'<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>'. date('j F, Y, g:i a', strtotime($result['date_replied'])).'</p>';
                      echo'</div>
                        </div>
                       <!-- Message End -->
                      </a>
                       <div class="dropdown-divider"></div>';
                   }
                    echo'<a href="replied-requisition.php" class="dropdown-item dropdown-footer">See All Requisitions</a>';
                }
                else{
                  echo'
                  <a href="#" class="dropdown-item">
                   <div class="media">
                     <div class="media-body">
                       <h3 class="dropdown-item-title text-danger font-weight-bold">No New Requisition Reply</h3> 
                     </div>
                   </div>
                 </a>';
                }
              ?>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
       <li class="nav-item dropdown">
      <?php
              $queryRequisitions = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Replied' AND view_status!=1 AND team_code=$teamCode ORDER BY date_replied DESC");
              $countRequisition = mysqli_num_rows( $queryRequisitions);

              $queryExpiredDate = mysqli_query($connection, "SELECT * FROM item WHERE expiry_date < DATE_ADD(NOW(), INTERVAL 90 DAY) AND team_code=$teamCode");
              $countExpiryDate= mysqli_num_rows($queryExpiredDate);
			  
			  $queryStockLevel = mysqli_query($connection, "SELECT * FROM item WHERE quantity <= 20 AND team_code=$teamCode ORDER BY quantity ASC");
              $countStockLevel = mysqli_num_rows($queryStockLevel);
               
              $countAllNotification = $countRequisition + $countExpiryDate +  $countStockLevel;
        ?>

        <a title="Notifications" class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php 
               if($countAllNotification == 0)
               {
                  echo "";
               }
               else
               {
          ?>
          <span class="badge badge-warning navbar-badge"><?php echo  $countAllNotification; ?></span>
          <?php
               }
          ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php 
              if($countAllNotification == 0)
              {
          ?>
            <span class="dropdown-item dropdown-header text-danger font-weight-bold">No New Notification</span>
        <?php     
               }
               else
               {
          ?>
              <span class="dropdown-item dropdown-header font-weight-bold"><?php echo  $countAllNotification; ?>&nbsp;Notification(s)</span>
              <?php 
               }
               ?>
          <div class="dropdown-divider"></div>
          <a href="requisition-summary.php" class="dropdown-item">
            <?php 
                if(mysqli_num_rows($queryRequisitions) > 0)
                {
            ?>
            <i class="fas fa-envelope mr-2"></i><?php echo $countRequisition; ?>&nbsp;Requisition(s)
            <span class="float-right text-muted text-sm"></span>
          </a>
          <?php }
               else{
          ?>
            <i class="fas fa-envelope mr-2"></i><span class="text-danger font-weight-bold"><?php echo 'No New Requisition Reply'; ?></span>
            <?php 
               }
               ?>
          <div class="dropdown-divider"></div>
          <a href="expiry-items.php" class="dropdown-item">
            <?php

               if(mysqli_num_rows($queryExpiredDate)>0)
                {
               ?>
               <i class="fas fa-bell mr-2"></i><?php echo $countExpiryDate; ?>&nbsp;Expiry Reminder(s)
               <span class="float-right text-muted text-sm"></span>
               <?php
                  }
                  else
                  { ?>
                    <i class="fas fa-bell mr-2"></i><span class="text-danger font-weight-bold"><?php echo 'No Expiry Reminder'; ?></span>
               <?php
                  }
               ?>
          </a>
          <div class="dropdown-divider"></div>
          <a href="stock-level.php" class="dropdown-item">
		        <?php

               if(mysqli_num_rows($queryStockLevel)>0)
                {
               ?> 
            <i class="fas fa-file mr-2"></i><?php echo $countStockLevel; ?>&nbsp;Stock level Reminder(s)
            <span class="float-right text-muted text-sm"></span>
			  <?php
                  }
                  else
                  { ?>
                    <i class="fas fa-bell mr-2"></i><span class="text-danger font-weight-bold"><?php echo 'No Stock Level Reminder'; ?></span>
               <?php
                  }
               ?>
          </a>
          <div class="dropdown-divider"></div>
         
        </div>
      </li>
      <li class="nav-item">
        <a title="Fullscreen" class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="home" class="brand-link">
      <img src="../../dist/img/blm_logo.png" alt="Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Banja La Mtsogolo</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/avatar.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo "". $getfirstname['firstname']; 
                                       echo "&nbsp;" . $getsurname['surname'];?></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="../misc/logout.php" class="nav-link">
                  <i class="fa fa-key nav-icon"></i>
                  <p>Logout</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-upload"></i>
              <p>
                Upload documents
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="upload-files.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Upload RFQ/RTD</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="uploaded-files.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Uploads</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item menu">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-shopping-bag"></i>
              <p>
                Purchase requisition
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
			        <li class="nav-item">
                <a href="purchase-requisition" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending requisition</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="requisition-summary.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Requisition Summary</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fa fa-shopping-bag"></i>
              <p>
                Purchase Order
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="purchase.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generate an order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="order-summary.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order Details</p>
                </a>
              </li>
            </ul>
          </li>
            
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>
                Invoices
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vew Invoice</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Purchase</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Purchase Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="procurement-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Purchase Details</li>
            </ol>
          </div>
        </div><!-- /.row -->
        <?php if($sess)
                    { 
				            showSessError($sess); 
		                }
			         ?>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info no-print">
              <h5><i class="fas fa-info"></i> Note:</h5>
              To print this Purchase Order. Click the print button at the bottom of purchase order.
            </div>
			<?php if ($success){ 
		                 showSuccess($success);?>	
                        <?php 
			                }else{  
			                      if ($msg){ 
				                        showerror($msg); 
				                    }?>
				        <?php 
				            } ?>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
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

                       if(mysqli_num_rows($results) >0)
                       {				 

                   ?>
				 <div class="clearfic">  
                  <h5>
                    <img src="../../dist/img/blm_logo.png" class="brand-image img-circle img-fluid elevation-2" height="115" width="110" alt="Banja logo">&nbsp;<strong>Banja La Mtsogolo Purchase Order</strong> 
                    <small class="float-right"><strong>Order Date:</strong>&nbsp;<?php echo''. date('j F, Y, g:i a', strtotime( $order['order_date'])).''; ?></small>
                  </h4>
				 </div>
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
						<strong>Email:</strong>&nbsp;<?php echo $order['email']; ?><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <strong>Order Number:</strong>&nbsp;<?php echo $order['order_number']; ?><br>
                  <strong>Order ID:</strong>&nbsp;<?php echo $order['order_detail_id']; ?><br>
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
                 
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead class="text-nowrap">
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
              <br>
              <!-- this row will not appear when printing -->
              <div class="row no-print clearfic">
                <div class="col-12">
				 <form action="" method="POST">
                  <a href="order-details-print.php?order_detail_id=<?= $order['order_detail_id']; ?>" rel="noopener" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i> Print</a>
                  <button type="submit" name="submit" class="btn btn-success"><i class="far fa-credit-card"></i> Send Order</button>
                 </form>
				</div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer no-print">
    <strong>Copyright &copy; 2021 <a target="_blank" href="https://xpartsmw.com">XParts IT Solutions</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- jquery-validation -->
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- jquery-for validating order form -->
<script src="../../dist/js/order-form-validation.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!---Generates random numbers-->
<script src="../../dist/js/random_numbers.js"></script>
<!----Calculates total price for an order---->
<script src="../../dist/js/calculateOrder.js"></script>
<script src="../../dist/js/invoice.js"></script>
</body>
</html>