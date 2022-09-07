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

        if(isset($_POST['submit'])){
			
		  $supplierName = mysqli_real_escape_string($connection, $_POST['supplierName']);
		  $supplierAddress = mysqli_real_escape_string($connection, $_POST['supplierAddress']);
		  $email= mysqli_real_escape_string($connection, $_POST['email']);
		  $orderNumber = mysqli_real_escape_string($connection, $_POST['orderNumber1']);
          $taxRate = mysqli_real_escape_string($connection, $_POST['taxRate']);
		  $taxAmount = mysqli_real_escape_string($connection, $_POST['taxAmount']);
          $subTotal = mysqli_real_escape_string($connection, $_POST['subTotal']);
          $grandTotal = mysqli_real_escape_string($connection, $_POST['totalAftertax']);
          $description = mysqli_real_escape_string($connection, $_POST['description']);
		  
          
		  /*if(empty($_POST['orderNumber']))
		  {
			  $msg[] = "Please generate an order Number";
		  }*/
           if(!$msg)
		   {
			 $sqlInsertOrderdetails = "INSERT INTO orderDetails(order_number, description, supplier_name, supplier_address, email, tax, tax_amount, sub_total, grand_total, team_code, order_date)
			                            VALUES($orderNumber, '$description', '$supplierName', '$supplierAddress', '$email', '$taxRate', '$taxAmount', '$subTotal', '$grandTotal','$teamCode', NOW())";
			  mysqli_query($connection, $sqlInsertOrderdetails)or die(mysqli_error());
			 $lastInsertId = mysqli_insert_id($connection);
			 $sqlInsertItem = "";
			 for($i = 0; $i < count($_POST['productName']); $i++) 
			 {
			    $sqlInsertItem = "INSERT INTO orders(item_name, specification, category, unit, quantity, price, total_price, order_detail_id)
				                  VALUES ('".$_POST['productName'][$i]."', '".$_POST['specification'][$i]."', '".$_POST['category'][$i]."', '".$_POST['unit'][$i]."', '".$_POST['quantity'][$i]."', '".$_POST['price'][$i]."', '".$_POST['total'][$i]."', '$lastInsertId')";
			    mysqli_query($connection, $sqlInsertItem)or die(mysqli_error());
		       
			 }
             if (!$sqlInsertItem && !$sqlInsertOrderdetails)
			 {
                $msg[] = 'Failed to place an order';
             }
             else
		     {
                $success[] = "Order Details Saved Successfully";
             }
		   }
       }
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
                <a href="purchase.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generate an order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="order-summary.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order Summary</p>
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
            <h1 class="m-0">Generate Purchase Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="procurement-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Generate Purchase Order</li>
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
        <!-- Main row -->
        <div class="row">
            <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Purchase Order</h3>

                <div class="card-tools">
                   <button type="button" class="btn btn-tool" data-card-widget="collapse">
                     <i class="fas fa-minus"></i>
                   </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                     <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <?php if ($success){ 
		                 showSuccess($success);?>	
                        <?php 
			                }else{  
			                      if ($msg){ 
				                        showerror($msg); 
				                    }?>
				        <?php 
				            } ?>
               <!-- Card start -->
        <div class="card-body">
                 <!-- form start -->
          <form action="" id="orderForm" method="post" class="invoice-form" role="form">
         <div class="load-animate animated fadeInUp">
         <div class="row">
		     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
               <h3>From:</h3>
               <strong>Banja La Mtsogolo.</strong><br>
					Off Paul Kagame Rd,<br>
					Area4, Opposite CFAO<br>
                    P.O. Box 1854,<br>
                    LILONGWE<br>
                <strong>Tel:</strong> 01 772 511/ 505  
            </div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
               <h3>To:</h3>
               <div class="form-group">
                  <input type="text" class="form-control" name="supplierName" id="supplierName" placeholder="Enter supplier name" data-rule-required="true" data-msg-required="Please enter supplier name" autocomplete="off">
               </div>
			   <div class="form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Enter supplier's email address" data-rule-required="true" data-msg-required="Please enter supplier's email address" autocomplete="off">
               </div>
               <div class="form-group">
                  <textarea class="form-control" rows="3" name="supplierAddress" id="supplierAddress" placeholder="Enter supplier's address and contact details" data-rule-required="true" data-msg-required="Please enter supplier's address"></textarea>
               </div>
            </div>
		 </div>
         <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
               <table class="table table-bordered" id="invoiceItem">
                <thead class="text-nowrap">
				  <tr>
                     <th>
					    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkAll" name="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                        </div>
                    </th>
                     <th>Item Name</th>
					 <th>Specification</th>
					 <th>Category</th>
					 <th>Unit</th>
                     <th>Quantity</th>
                     <th>Price</th>
                     <th>Total</th>
                  </tr>
				 </thead> 
				<tbody>
                  <tr class="text-nowrap">
                    <td>
					   <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="itemRow custom-control-input" id="itemRow_1">
                        <label class="custom-control-label" for="itemRow_1"></label>
                       </div>
					 </td>
                     <td><input type="text" name="productName[]" id="productName_1" class="form-control" data-rule-required="true" data-msg-required="Please enter item name"></td>
					 <td><input type="text" name="specification[]" id="specification_1" class="form-control" data-rule-required="true" data-msg-required="Please specify product details"></td>
					 <td>
                       <select class="form-control" name="category[]" id="category_1" data-rule-required="true" data-msg-required="Please select a category">
                         <option value="">Choose a category...</option>
                         <option>Disposable Surgical Equipments - Surgical Dressings</option>
                         <option>Eye/Ear Preparation</option>
                         <option>Family Planning Products</option>
                         <option>Inhalers</option>
                         <option>Injectables</option>
                         <option>IV Fluids</option>
                         <option>Liquid (Oral) Preparations</option>
                         <option>Other Laboratory Suppliers</option>
                         <option>Pessaries and Suppositories</option>
                         <option>Solutions</option>
                         <option>Tablets/Capsules</option>
                         <option>Topical Preparations</option>
                      </select>
					  </td>
					   <td><input type="text" name="unit[]" id="unit" class="form-control unit" data-rule-required="true" data-msg-required="Please enter unit"></td>
                     <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" data-rule-required="true" data-msg-required="Please enter quantity"></td>
                     <td><input type="number" step="any" value="" name="price[]" id="price_1" class="form-control price" data-rule-required="true" data-msg-required="Please enter a price"></td>
                     <td><input type="number" step="any" value="" name="total[]" id="total_1" class="form-control total disabled" readonly></td>
                  </tr>
				</tbody>  
               </table>
            </div>
         </div>
		 <br>
		 <div class="row">
            <div class="col-md-12">
               <button class="btn btn-success" id="addRows" type="button">+ Add Record</button>
                   <button class="btn btn-danger delete" id="removeRows" type="button">- Delete Record</button>
            </div>
         </div>
		 <br />
		    <div class="row justify-content-center">
			<div class="col-xs-6 col-md-6 align-items-start">
			  <div class="col">
                <label>Description: &nbsp;</label>
                <textarea class="form-control description" rows="4" name="description" id="description" placeholder="provide order item description" data-rule-required="true" data-msg-required="Please enter a description of the items being ordered"></textarea>
               </div>
			 </div> 
			 
             <div class="col-xs-6 col-md-3 align-items-start">			 
			  <div class="col form-group">
                <label>Order No: &nbsp;</label>
			    <input type="text" name="orderNumber1" id="orderNumber1" readonly class="form-control orderNumber disabled" /> 
              </div>
			  <div class="col form-group">
                <label>Sub Total: &nbsp;</label>
                <input value="" step="any" type="number" class="form-control disabled" name="subTotal" id="subTotal" readonly />
              </div>
			</div>
			 <div class="col-xs-6 col-md-3 align-items-start">
			<div class="col form-group">
              <label>Tax rate(%): &nbsp;</label> 
              <input value="" step="any" type="number" class="form-control" name="taxRate" id="taxRate" data-rule-required="true" data-msg-required="Please enter tax rate" />
            </div>
			<div class="col form-group">
              <label>Tax Amount: &nbsp;</label> 
              <input value="" step="any" type="number" readonly class="form-control disabled" name="taxAmount" id="taxAmount" />
            </div>
            <div class="col form-group">
              <label>Grand Total: &nbsp;</label>
              <input value="" step="any" type="number" class="form-control disabled" name="totalAftertax" id="totalAftertax" readonly />
            </div>
           </div>			
           </div>
          </div>
        </div>
                <!-- /.card-body -->
              <div class="card-footer">
                <div class="row">
				<div class="col-md-12">
                  <input type="submit" id="submitOrder" name="submit" value="Save Invoice" class="btn btn-primary">
				</div>
               </div>
              </div>
			</form> 
            </div>
            <!-- /.card -->
            </div>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
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