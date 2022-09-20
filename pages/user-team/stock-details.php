<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');
	 //include("export-stock.php");

    $sess = array();
    $msg = array();
    $success = array();
	
    if (!isset ($_SESSION['user-team']))
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

	  $user_check = $_SESSION['user-team']; // Stored Session for current logged user

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

      $flag = FALSE; 
      $limit = 25;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $start = ($page -1) * $limit; 
      if (isset($_POST['search']) and !empty($_POST['search']))
      {
          $keywords = $_POST['search'];
          $result = mysqli_query($connection, "SELECT *  
          FROM item
          JOIN team 
          ON item.team_code = team.team_code
          WHERE item.item_code LIKE '$keywords%'
          OR item.item_name LIKE '$keywords%'
		  AND item.team_code=$teamCode
          ORDER BY item.date_received DESC LIMIT $start, $limit");
          $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

          $result1 = mysqli_query($connection, "SELECT COUNT(item_id) AS id FROM item");
          $itemCount = mysqli_fetch_all($result1, MYSQLI_ASSOC);
          $total = $itemCount[0]['id'];
          $pages = ceil($total / $limit);
          $previous = $page - 1;
          $next = $page + 1;
      }else if(isset($_POST['start']) && isset($_POST['end'])){
          $start=$_POST['start'];
          $end=$_POST['end']  ;
          $result = mysqli_query($connection, "SELECT *  
                                    FROM item
                                    JOIN team 
                                    ON item.team_code = team.team_code
									AND item.team_code=$teamCode WHERE date_received BETWEEN '$start' AND '$end'
                                    ORDER BY item.date_received DESC");

          $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
//          echo var_dump($items);
//          echo var_dump($start);
//          echo var_dump($end);
//          echo var_dump($teamCode);
//          exit;

          $result1 = mysqli_query($connection, "SELECT COUNT(item_id) AS id FROM item WHERE team_code=$teamCode");
          $itemCount = mysqli_fetch_all($result1, MYSQLI_ASSOC);
          $total = $itemCount[0]['id'];
          $pages = ceil($total / $limit);
          $previous = $page - 1;
          $next = $page + 1;
      }
      else
      {
          $result = mysqli_query($connection, "SELECT *  
                                    FROM item
                                    JOIN team 
                                    ON item.team_code = team.team_code
									AND item.team_code=$teamCode
                                    ORDER BY item.date_received DESC LIMIT $start, $limit");
         $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

         $result1 = mysqli_query($connection, "SELECT COUNT(item_id) AS id FROM item WHERE team_code=$teamCode");
         $itemCount = mysqli_fetch_all($result1, MYSQLI_ASSOC);
         $total = $itemCount[0]['id'];
         $pages = ceil($total / $limit);
         $previous = $page - 1;
         $next = $page + 1;
        
      }
      if(mysqli_num_rows($result) >0)
      { 
        $flag = TRUE; 
      }
      else
      {
        $flag = FALSE;
      }
	 
	  if(isset($_POST["export-stock"])) 
	  {	
	      $filename = "stock-details-".date('d-m-y'). ".xls";			
	      header("Content-Type: application/vnd.ms-excel");
	      header("Content-Disposition: attachment; filename=\"$filename\"");
		  
          $query = "SELECT * FROM item WHERE team_code=$teamCode";
          $resultset = mysqli_query($connection, $query);
	  
	      $flags = false;
	      while($row = mysqli_fetch_assoc($resultset)){
            if(!$flags){
              // display field/column names as first row
              echo implode("\t", array_keys($row)) . "\r\n";
              $flags = true;
            }
              echo implode("\t", array_values($row)) . "\r\n";
         }
		 exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | user-team | Stock Details</title>

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
          <form class="form-inline" method="post" action="">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search by item code or item name" aria-label="Search">
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
               $query = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Replied' AND view_status=0 AND team_code=$teamCode ORDER BY date_replied DESC LIMIT 4"); 
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
              $queryRequisitions = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Replied' AND view_status =0 AND team_code=$teamCode ORDER BY date_replied DESC");
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
    <a href="user-team-dash.php" class="brand-link">
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
              <i class="nav-icon fa fa-shopping-bag"></i>
              <p>
                 Requisitions
                 <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="raise-requisition.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Raise Requisition</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pending-requisition.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Requisitions</p>
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
          
          <li class="nav-item  menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Manage stock
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add-stock.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="stock-details.php" class="nav-link  active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="stock-usage-details.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Usage</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="stock-take.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Take</p>
                </a>
              </li>
            </ul>
          </li>
         
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-server nav-icon"></i>
              <p>
                Transfers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="transfer-stock.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transfer stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pending-transfer.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Transfers</p>
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
                <a href="stock-usage-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Usage</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="stock-onhand-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock on hand</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="stock-transfer-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Transfers</p>
                </a>
              </li>
			  
              <li class="nav-item">
                <a href="item-expiry-date.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Expiry</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="stock-valuation-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Valuation</p>
                </a>
              </li>
			  
              <li class="nav-item">
                <a href="stock-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Report</p>
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
            <h1 class="m-0">Stock Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="user-team-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Stock Deatils</li>
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
                <h3 class="card-title">All stock Details</h3>

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
                   
            <!-- /.card-body -->
            <div class="card-body">
			   <div class="row">
                 <span style="font-weight: bold; margin-bottom: 15px;" class="col-md-12 col-sm-12">  
                   Showing results of
                    <span style="color: #008000;">
                        <?php  if(isset($keywords)){ 
                                    echo $keywords; 
                                }else{ 
                                    echo'all items in stock'; 
                        } ?>
                    </span>
                 </span>
                   <div style="font-weight: bold; margin-bottom: 15px;" class="col-md-6 col-sm-6">
                       <form action="#" method="post">
                           <span>Start Date:</span>
                           <input type="date" name="start">

                           <span>End Date:</span>
                           <input type="date" name="end">

                           <button class="btn btn-primary btn-sm">Search</button>
                       </form>
                   </div>
              </div>
            <div class="table-responsive">
                <!-- table start -->
               <table id="stockTable" class="table table-striped table-bordered">
                <thead class="text-nowrap">
                  <tr>
                    <th>ID</th>
					<th>Item Code</th>
                    <th>Name</th>
                    <th>Specification</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Quantity</th>
					<th>Stock used</th>
                    <th>Price</th>
					<th>Total Price</th>
                    <th>Date Recieved</th>
                    <th>Expiry Date</th>
                    <th>Batch</th>
                    <th>GRN</th>
                    <th>Team Code</th>
                    <th><i class="fa fa-tools"></i>&nbsp;Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                   if ($flag == TRUE)
                   {
                     ?>
                    <?php
                      foreach ($items as $item){ ?>
                  <tr>
                     <td><?= $item['item_id']; ?></td>
					 <td><?= $item['item_code']; ?></td>
                     <td><?= $item['item_name']; ?></td>
                     <td><?= $item['specification']; ?></td>
                     <td><?= $item['category']; ?></td>
                     <td><?= $item['type']; ?></td>
                     <td><?= $item['unit']; ?></td>
                     <td><?= $item['quantity']; ?></td>
					 <td><?= $item['stock_used']; ?></td>
                     <td><?= $item['price']; ?></td>
					 <td><?= number_format($item['total_price'], 2); ?></td>
                     <td><?= $item['date_received']; ?></td>
                     <td><?= $item['expiry_date']; ?></td>
                     <td><?= $item['batch']; ?></td>
                     <td><?= $item['GRN']; ?></td>
                     <td><a href="team-detail.php?team_code=<?= $item['team_code']; ?>"><?= $item['team_code']; ?></a></td>
                     <td class="no-print">
                        <div class="btn-group">
						  <a class="btn btn-success btn-sm stockUsage" href="#" id="<?= $item['item_id']; ?>"><i class="fa fa-minus">&nbsp;Usage</i></a>
                          <a class="btn btn-primary btn-sm editStock" href="#" id="<?= $item['item_id']; ?>"><i class="fa fa-edit">&nbsp;Edit</i></a>
<!--                          <a class="btn btn-danger btn-sm deleteStock" href="#" id="--><?//= $item['item_id']; ?><!--" data-id="--><?php //echo $item['item_id']; ?><!--"><i class="fa fa-times">&nbsp;Delete</i></a>-->
                        </div>
                     </td>
                  </tr>
                  <?php } ?>
                      <?php 
                   }
                   else if ($flag == FALSE)
                   { ?>
                       <tr><td colspan="17" class="text text-danger font-weight-bold">No search results found</td></tr>
                  <?php }  ?>
                </tbody>
              </table>
              <!-- /.end table -->
              </div>
                <!-- .card-footer -->
                <div class="card-footer">
                   <nav aria-label="...">
                    <ul class="pagination pagination-sm">
                    <?php if($page > 1){ ?>
                    <li class="page-item">
                        <a class="page-link" href="stock-details.php?page=<?= $previous; ?>">Previous</a>
                      </li>
                      <?php 
                       } else { ?>
                      <li class="page-item disabled">
                        <span class="page-link" href="javascript:avoid(0)">Previous</span>
                      </li>
                      <?php } ?>
                      
                      <?php for($i = 1; $i <= $pages; $i++) : ?>
                      <li class="page-item <?= $page == $i ? 'active':'' ?>" aria-current="page">
                            <a class="page-link" href="stock-details.php?page=<?= $i; ?>"><?= $i; ?></a>
                      </li>
                      <?php endfor; ?>
                       
                      <?php if(($i > $page) && ($page < $pages)){ ?>
                      <li class="page-item">
                        <a class="page-link" href="stock-details.php?page=<?= $next; ?>">Next</a>
                      </li>
                      <?php 
                       } else { ?>
                      <li class="page-item disabled">
                        <span class="page-link" href="javascript:avoid(0)">Next</span>
                      </li>
                      <?php } ?>
                    </ul>
                  </nav>
				  
                </div>
				<!-- /.card footer -->
                 
            </div>
            <!-- /.card body -->
           </div>
		   <!-- /.card -->
		  
		     <div class="row">
		      <div class="col-sm-6">
			    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<!--				  <button type="submit" class="btn btn-success" name="export-stock" id="export-stock"><i class="fa fa-file-excel">&nbsp;Export to Excel</i></button>-->
			    </form>
			  </div>
			 </div>
			 <!-- /. Modal -->
		    <div class="modal fade" id="stockUsage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Stock Usage</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                <div id="success-message"></div>
            <div class="modal-body">  
                <!-- form start -->
             <form id="stockUsageForm" name="stockUsageForm" method="POST" action="stock-usage.php">
                <div class="form-row">
                  <div class="col-md-6 form-group">
                    <input type="hidden" name="itemId1" class="form-control" id="itemId1" data-rule-required="true" data-msg-required="Please enter item number" />
                  </div>
				  <div class="col-md-6 form-group">
                    <input type="hidden" name="stockUsed" id="stockUsed" class="form-control" />
                  </div>
			    </div>  
				<div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="itemNumber">Item Number/Code</label>
                    <input type="text" name="itemNumber1" class="form-control disabled" id="itemNumber1" data-rule-required="true" data-msg-required="Please enter item number" readonly />
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" name="itemName1" class="form-control disabled" id="itemName1" data-rule-required="true" data-msg-required="Please enter item name" readonly />
                  </div>
				  <div class="col-md-4 form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit1" class="form-control disabled" id="unit1" data-rule-required="true" data-msg-required="Please specify the unit" readonly />
                  </div>
                </div>
				<div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="type">Type</label>
                     <input type="text" name="type1" class="form-control disabled" id="type1" data-rule-required="true" data-msg-required="Please specify the type" readonly />
                  </div>
				   <div class=" col-md-4 form-group">
                      <label for="category">Category</label>
                      <input type="text" name="category1" class="form-control disabled" id="category1" data-rule-required="true" data-msg-required="Please specify the category" readonly />
                    </div>
					<div class="col-md-4 form-group">
                       <label for="quantity">Quantity</label>
                       <input type="number" name="quantity1" class="form-control disabled" id="quantity1" data-rule-required="true" data-msg-required="Please provide the quantity" readonly />
                     </div>
                  </div>
                <div class="form-row"> 
                  <div class="col-md-4 form-group">
                    <label for="batchNo">Batch Number</label>
                    <input type="text" name="batchNo1" class="form-control disabled" id="batchNo1" data-rule-required="true" data-msg-required="Please provide the batch number" readonly />
                  </div> 
                  <div class="col-md-8 form-group">
                    <label for="price">Specify the Quantity to use</label>
                    <input type="number" name="quantity2" class="form-control" id="quantity2" placeholder="Enter Quantity to use"  data-rule-required="true" data-msg-required="Please specify the quantity to use" />
                  </div>
				 </div> 
				 <div class="form-row"> 
                  <div class="col-md-12 form-group">
                     <label for="description">Description</label>
                     <textarea class="form-control" name="description1" id="description1" rows="2" placeholder="Enter description"  data-rule-required="true" data-msg-required="Please provide description"></textarea>
                  </div>
                </div> 					
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" id="stock-usage" data-loading-text="Loading..." class="btn btn-primary">Save Details</button>
                 </div>
		    </form>
                </div>
              </div>
            </div>
			<!-- /.row (modal) -->
			<br>
		     <!-- /. Modal -->
		    <div class="modal fade" id="editStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                           <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Update Stock Details</h3>
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            <div id="update-success-message"></div>
                    <div class="modal-body">
					        
                      <!-- form start -->
                <form id="orderForm" name="orderForm" method="POST" action="update-stock.php">
                <div class="form-row">
                  <div class="col-md-12 form-group">
                    <input type="hidden" name="itemId" class="form-control" id="itemId" data-rule-required="true" data-msg-required="Please enter item number" />
                  </div>
			    </div>  
				    <div class="form-row">
                  <div class="col-md-6 form-group">
                    <label for="itemNumber">Item Number/Code</label>
                    <input type="text" name="itemNumber" class="form-control" id="itemNumber"  placeholder="Enter item number" data-rule-required="true" data-msg-required="Please enter item number" />
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" name="itemName" class="form-control" id="itemName" placeholder="Enter item name" data-rule-required="true" data-msg-required="Please enter item name" />
                  </div>
                  </div>
				          <div class="form-row">
                  <div class="col-md-6 form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" class="form-control" id="unit" placeholder="Enter unit" data-rule-required="true" data-msg-required="Please specify the unit" />
                  </div>
                  <div class="col-md-6 form-group">
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
                  </div>
                  <div class="form-row">
                    <div class=" col-md-6 form-group">
                      <label for="category">Category</label>
                      <select class="form-control select2" id="category" name="category" data-rule-required="true" data-msg-required="Please select category">
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
                      <div class="col-md-6 form-group">
                       <label for="quantity">Quantity</label>
                       <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Enter quantity" data-rule-required="true" data-msg-required="Please provide the quantity" />
                      </div>
                    </div>
                  <div class="form-row">
                   <div class="col-md-6 form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control" id="price" placeholder="Enter price"  data-rule-required="true" data-msg-required="Please provide the price" />
                  </div>
				  <div class="col-md-6 form-group">
                    <label for="batchNo">Batch Number</label>
                    <input type="text" name="batchNo" class="form-control" id="batchNo" placeholder="Enter Batch" data-rule-required="true" data-msg-required="Please provide the batch number" />
                  </div> 
				  </div>
				  <div class="form-row"> 
				   <div class="col-md-6 form-group">
                    <label for="grn">GRN</label>
                    <input type="number" name="grn" class="form-control" id="grn" placeholder="Enter GRN" data-rule-required="true" data-msg-required="Please enter GRN" />
                   </div>
                   <div class="col-md-6 form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="date" name="expiryDate" class="form-control" id="expiryDate" placeholder="Enter Expiry date"  data-rule-required="true" data-msg-required="Please enter the expirly date" />
                  </div>
				  </div>
				  <div class="form-row">
                    <div class="col-md-12 form-group">
                      <label for="description">Description/Specification</label>
                      <textarea class="form-control" name="description" id="description" rows="2" placeholder="Enter description"  data-rule-required="true" data-msg-required="Please provide description"></textarea>
                    </div>
                  </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" id="update-stock" data-loading-text="Loading..." class="btn btn-primary">Update Stock</button>
                    </div>
				    	</form>
                </div>
              </div>
            </div>
			<!-- /.row (modal) -->
			
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
<script>
    $(document).ready(function() {
	   $("#stockUsageForm").validate();
		
 	});
</script>
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- jquery-validation -->
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- jquery-for validating order form -->
<script src="../../dist/js/order-form-validation.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!---Deletes stock in Item table-->
<script src="../../dist/js/delete-stock.js"></script>
<!---Edits Stock in Item table-->
<script src="../../dist/js/edit-stock.js"></script>
<!---Updates Stock in Item table-->
<script src="../../dist/js/update-stock.js"></script>
<!---Stock Usage -->
<script src="../../dist/js/stock-usage.js"></script>
<script src="../../dist/js/update-stock-usage.js"></script>

</body>
</html>
<?php include '../includes/includes_footer.php';?>
<script>
    $(document).ready(function() {
        $('#stockTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'print',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },  {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                'colvis'
            ],
            "paging":   false,
            "ordering": false,
            "info":     false
        });
    });
</script>