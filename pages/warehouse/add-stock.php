<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');

    $sess = array();
    $msg = array();
	$success = array();
     
    if (!isset ($_SESSION['warehouse']))
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

	  $user_check = $_SESSION['warehouse']; // Stored Session for current logged user

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

        if (isset($_POST['submit'])){

            $itemNumber = mysqli_real_escape_string($connection, $_POST['itemNumber']);
            $itemName = mysqli_real_escape_string($connection, $_POST['itemName']);
            $unit = mysqli_real_escape_string($connection, $_POST['unit']);
            $type = mysqli_real_escape_string($connection, $_POST['type']);
            $category = mysqli_real_escape_string($connection, $_POST['category']);
            $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
            $teamCode = mysqli_real_escape_string($connection, $_POST['teamCode']);
            $price = mysqli_real_escape_string($connection, $_POST['price']);
			$totalPrice = mysqli_real_escape_string($connection, $_POST['totalPrice']);
            $batchNo = mysqli_real_escape_string($connection, $_POST['batchNo']);
            $expiryDate = mysqli_real_escape_string($connection, $_POST['expiryDate']);
            $grn = mysqli_real_escape_string($connection, $_POST['grn']);
            $description = mysqli_real_escape_string($connection, $_POST['description']);
            
             if(!$msg){
            
                  $insert = "INSERT INTO item(item_code, item_name, specification, type, category, unit, quantity, expiry_date, date_received, price, total_price, batch, grn, team_code) 
                             VALUES ('$itemNumber', '$itemName', '$description',  '$type', '$category', '$unit', '$quantity',  '$expiryDate', NOW(), '$price', '$totalPrice', '$batchNo', '$grn', '$teamCode')";
                   mysqli_query($connection, $insert)or die(mysqli_error($connection));
                   $lastInsertId = mysqli_insert_id($connection);
				   $insert2 = "INSERT INTO stock_take(opening_stock, item_id)
				                VALUES('$quantity','$lastInsertId')";
				   mysqli_query($connection, $insert2)or die(mysqli_error($connection));
				   if (!$insert && !$insert2){
                       $msg[] = 'Failed to add stock details';
                   }
                   else{
                         $success[] = "Stock details added successfully";
                   }
             }
         }	 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Warehouse | Add Stock</title>

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
   <script type="text/javascript">
           function getItem(val) {
            $.ajax({
                url: 'fetch-item.php',
                type: 'POST',
                data: 'itemNumber='+val,
                dataType: 'json',
                success:function(data){
                    var len = data.length;
                    if(len > 0){
						var itemCode = data[0]['product_code'];
                        var itemName = data[0]['product_name'];
                        var specification = data[0]['specification'];
                        var type = data[0]['type'];
						var category = data[0]['category'];

                        document.getElementById('itemNumber').value = itemCode;
						document.getElementById('itemName').value = itemName;
						document.getElementById('description').value = specification;
                        document.getElementById('type').value = type;
                        document.getElementById('category').value = category;
                        console.log(data);						
                    }
					else{
						alert('The entered product code does not exist');
						return false;
					}
                }
            });
        }
   </script>
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
               $query = mysqli_query($connection, "SELECT * FROM requisition WHERE reply_status='Replied' AND view_status=0 AND team_code=$teamCode ORDER BY date_replied DESC LIMIT 4"); 
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
              $queryRequisitions = mysqli_query($connection, "SELECT * FROM requisition WHERE reply_status='Replied' AND view_status=0 AND team_code=$teamCode ORDER BY date_replied DESC");
              $countRequisition = mysqli_num_rows( $queryRequisitions);

              $queryExpiredDate = mysqli_query($connection, "SELECT * FROM item WHERE expiry_date < DATE_ADD(NOW(), INTERVAL 90 DAY) AND team_code=$teamCode");
              $countExpiryDate= mysqli_num_rows($queryExpiredDate);
			  
			  $queryStockLevel = mysqli_query($connection, "SELECT * FROM item WHERE quantity <= minimum_stock_level OR maximum_stock_level > quantity AND team_code=$teamCode ORDER BY quantity ASC");
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
          <a href="item-expiry-date.php" class="dropdown-item">
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
    <a href="warehouse-dash.php" class="brand-link">
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
        <?php include 'nav.php';?>
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
            <h1 class="m-0">Add Stock</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="warehouse-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Add Stock</li>
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
                <h3 class="card-title">Add Stock</h3>

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
                <form id="orderForm" name="orderForm" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" >
                <div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="itemNumber">Item Number/Code</label>
                    <input type="text" name="itemNumber" class="form-control" id="itemNumber" onblur="getItem(this.value);" placeholder="Enter item code" data-rule-required="true" data-msg-required="Please enter item number" />
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" name="itemName" class="form-control error" id="itemName" placeholder="Enter item name" data-rule-required="true" data-msg-required="Please enter item name" />
                  </div>
				  <div class="col-md-4 form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" class="form-control" id="unit" placeholder="Enter unit" data-rule-required="true" data-msg-required="Please specify the unit" />
                  </div>
                  </div>
				  <div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="type">Type</label>
                   <select class="form-control select2" id="type" name="type" data-rule-required="true" data-msg-required="Please select type">
                         <option value="">Choose a type...</option>
						 <option>Ampoule</option>
                         <option>Bottle</option>
                         <option>Box</option>
                         <option>Coarse</option>
                         <option>Cycle</option>
                         <option>Dose</option>
                         <option>Each</option>
                         <option>IV Bottle</option>
                         <option>Jar/Pot</option>
                         <option>Kit</option>
                         <option>Mls</option>
                         <option>Pack</option>
						 <option>Pack 30</option>
                         <option>Pessary</option>
                         <option>Piece</option>
                         <option>Roll</option>
                         <option>Sachet</option>
                         <option>Set of 10</option>
                         <option>Strip</option>
                         <option>Suppository</option>
                         <option>Tablet/Capsule</option>
                         <option>Test</option>
                         <option>Tube</option>
						 <option>Vial</option>
                      </select>
                  </div>
				      <div class=" col-md-4 form-group">
                      <label for="category">Category</label>
                      <select class="form-control select2" id="category" name="category"  data-rule-required="true" data-msg-required="Please select category">
					     <option value="">Choose a category...</option>
						 <option>Ampoule</option>
                         <option>Bottle</option>
                         <option>Box</option>
                         <option>Coarse</option>
                         <option>Cycle</option>
						 <option>Disposable Surgical Equipments - Surgical Dressings</option>
                         <option>Dose</option>
                         <option>Each</option>
                         <option>Eye/Ear Preparation</option>
                         <option>Family Planning Products</option>
                         <option>Inhalers</option>
                         <option>Injectables</option>
                         <option>IV Fluids</option>
						 <option>IV Bottle</option>
                         <option>Jar/Pot</option>
						 <option>Kit</option>
                         <option>Liquid(Oral)Preparations</option>
						 <option>Mls</option>
                         <option>Other Laboratory Suppliers</option>
						 <option>Pack</option>
						 <option>Pack 30</option>
                         <option>Pessary</option>
						 <option>Pessaries and Suppositories</option>
                         <option>Piece</option>
                         <option>Roll</option>
                         <option>Sachet</option>
                         <option>Set of 10</option>
                         <option>Strip</option>
                         <option>Suppository</option>
                         <option>Test</option>
                         <option>Tube</option>
                         <option>Solutions</option>
                         <option>Tablet/Capsule</option>
                         <option>Topical Preparations</option>
						 <option>Vial</option>
                      </select>
                     </div>
					   <div class="col-md-4 form-group">
                         <label for="teamCode">Team Code</label>
                         <input type="number" name="teamCode" class="form-control disabled" value="<?php echo $teamCode; ?>" id="teamCode" placeholder="Enter team code" data-rule-required="true" data-msg-required="Please provide team code" readonly />
                        </div>
                  </div>
                  <div class="form-row">
                      <div class="col-md-4 form-group">
                       <label for="Quantity">Quantity</label>
                       <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Enter quantity" data-rule-required="true" data-msg-required="Please provide the quantity" />
                      </div>
					  <div class="col-md-4 form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" class="form-control" id="price" placeholder="Enter price"  data-rule-required="true" data-msg-required="Please provide the price" />
                       </div>
					   <div class="col-md-4 form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="number" name="totalPrice" class="form-control disabled" id="totalPrice" placeholder="Total price" readonly />
                       </div>
                    </div>
				  <div class="form-row"> 
				    <div class="col-md-4 form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="date" name="expiryDate" class="form-control" id="expiryDate" placeholder="Enter Expiry date"  data-rule-required="true" data-msg-required="Please enter the expirly date" />
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="batchNo">Batch Number</label>
                    <input type="text" name="batchNo" class="form-control" id="batchNo" placeholder="Enter Batch" data-rule-required="true" data-msg-required="Please provide the batch number" />
                  </div> 
                   <div class="col-md-4 form-group">
                    <label for="grn">GRN</label>
                    <input type="text" name="grn" class="form-control" id="grn" placeholder="Enter GRN" data-rule-required="true" data-msg-required="Please enter GRN" />
                  </div>
				  </div>
				  <div class="form-row">
                    <div class="col-md-12 form-group">
                      <label for="description">Description/Specification</label>
                      <textarea class="form-control" name="description" id="description" rows="1" placeholder="Enter description"  data-rule-required="true" data-msg-required="Please provide description"></textarea>
                    </div>
                  </div>
				  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Add stock</button>
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
<!-- fetch matched item details from database into form fields -->
<!--<script src="../../dist/js/fetch-item.js"></script>-->
<!-- Calculating total price based on quantity and price entered -->
<script src="../../dist/js/calculateOrder.js"></script>
</body>
</html>