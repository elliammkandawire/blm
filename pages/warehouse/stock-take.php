<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');
	 //include("export-stock.php");

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

      $limit = 25;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $start = ($page -1) * $limit;
      $results = $connection->query("SELECT *
                                    FROM item
                                    JOIN stock_take
                                    ON item.item_id=stock_take.item_id
								    WHERE item.team_code=$teamCode
									ORDER BY item.date_received DESC");

  if(isset($_POST['start']) && isset($_POST['end'])){
    $start=$_POST['start'];
    $end=$_POST['end']  ;
    $results = mysqli_query($connection, "SELECT *  
                                    FROM item
                                    JOIN stock_take 
                                    ON item.item_id = stock_take.item_id
									WHERE item.team_code=$teamCode AND date_received BETWEEN '$start' AND '$end'
                                    ORDER BY item.date_received DESC");
   }
      $items = $results->fetch_all(MYSQLI_ASSOC);

      $result1 = $connection->query("SELECT COUNT(item.item_id) AS id
	                                 FROM item
                                     JOIN stock_take
                                     ON item.item_id=stock_take.item_id
								     WHERE item.team_code=$teamCode");
      $itemCount = $result1->fetch_all(MYSQLI_ASSOC);
      $total = $itemCount[0]['id'];
      $pages = ceil($total / $limit);
      $previous = $page - 1;
      $next = $page + 1;
	 
	  $query = "SELECT *
                FROM item
                JOIN stock_take
                ON item.item_id=stock_take.item_id
				WHERE item.team_code=$teamCode
                ORDER BY item.date_received DESC";
      $resultset = mysqli_query($connection, $query) or die(mysqli_error($connection));
      $stock_records = array();
      while($rows = mysqli_fetch_assoc($resultset)) 
	  {
	     $stock_records[] = $rows;
      }	
      if(isset($_POST["export-stock"])) 
	  {	
	      $filename = "Stock-stake-report-".date('d-m-y'). ".xls";			
	      header("Content-Type: application/vnd.ms-excel");
	      header("Content-Disposition: attachment; filename=\"$filename\"");	
	      $show_coloumn = false;
	    if(!empty($stock_records)) 
	    {
	      foreach($stock_records as $record) 
	      {
		     if(!$show_coloumn)
			 {
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Warehouse | Stock Details</title>

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

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
              <input class="form-control form-control-navbar" type="search" placeholder="Search by item code" aria-label="Search">
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
            <h1 class="m-0">Stock Take</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="warehouse-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Stock Take</li>
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
                <div id="print">
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
                        <?php include '../includes/file.php';?>
                    </div>
			<div id="alert-message"></div>
            <div class="table-responsive">
                <!-- table start -->
               <table id="stockTable" class="table table-striped table-advance table-bordered">
                <thead class="text-nowrap">
                  <tr>
                    <th>ID</th>
					<th>Item Code</th>
                    <th>Name</th>
                    <th>Specification</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Date Recieved</th>
                    <th>Expiry Date</th>
                    <th>Batch</th>
                    <th>GRN</th>
					<th>Remaining Stock</th>
					<th>Opening Stock</th>
					<th>Closing Stock</th>
					<th>Used stock</th>
					<th>Physical Stock</th>
					<th>Variance</th>
					<th>Remarks</th>
                    <th>Team Code</th>
                    <th><i class="fa fa-tools"></i>&nbsp;Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                    if(mysqli_num_rows($results) >0)
                    {				 
                        foreach ($items as $item)
                        {

                ?>
                  <tr>
                     <td><?= $item['stock_take_id']; ?></td>
					 <td><?= $item['item_code']; ?></td>
                     <td><?= $item['item_name']; ?></td>
                     <td><?= $item['specification']; ?></td>
                     <td><?= $item['category']; ?></td>
                     <td><?= $item['type']; ?></td>
                     <td><?= $item['unit']; ?></td>
                     <td><?= number_format($item['price'], 2); ?></td>
                     <td><?= $item['date_received']; ?></td>
                     <td><?= $item['expiry_date']; ?></td>
                     <td><?= $item['batch']; ?></td>
                     <td><?= $item['GRN']; ?></td>
					 <td><?= $item['quantity']; ?></td>
					 <td><?= $item['opening_stock']; ?></td>
					 <td><?= $item['closing_stock']; ?></td>
					 <td><?= $item['stock_used']; ?></td>
					 <td><?= $item['physical_stock']; ?></td>
					 <td><?= $item['variance']; ?></td>
					 <td><?= $item['remarks']; ?></td>
                     <td><a href="team-detail.php?team_code=<?= $item['team_code']; ?>"><?= $item['team_code']; ?></a></td>
                     <td class="text-nowrap">
                        <div class="btn-group">
						  <a class="btn btn-primary btn-sm stockTake" href="#" id="<?= $item['stock_take_id']; ?>"><i class="fa fa-edit">&nbsp;Stock Take</i></a>
                        </div>
                     </td>
                  </tr>
                  <?php 
                     }?>
                     <?php 
                    }
                    else{
                      echo '<tr><td colspan="21" class="text text-danger font-weight-bold">There are currently no item details to display</td></tr>';
                    }?>
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
                        <a class="page-link" href="stock-take.php?page=<?= $previous; ?>">Previous</a>
                      </li>
                      <?php 
                       } else { ?>
                      <li class="page-item disabled">
                        <span class="page-link" href="javascript:avoid(0)">Previous</span>
                      </li>
                      <?php } ?>
                      
                      <?php for($i = 1; $i <= $pages; $i++) : ?>
                      <li class="page-item <?= $page == $i ? 'active':'' ?>" aria-current="page">
                            <a class="page-link" href="stock-take.php?page=<?= $i; ?>"><?= $i; ?></a>
                      </li>
                      <?php endfor; ?>
                       
                      <?php if(($i > $page) && ($page < $pages)){ ?>
                      <li class="page-item">
                        <a class="page-link" href="stock-take.php?page=<?= $next; ?>">Next</a>
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
<!--				  <a href="stock-details-print.php" rel="noopener" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i>&nbsp;Print Stock Details</a>-->
<!--			    -->
                </form>
			  </div>
			 </div>
			
			<!-- /. Modal -->
		    <div class="modal fade" id="stockTake" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Stock Take</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                <div id="success-message"></div>
            <div class="modal-body">  
                <!-- form start -->
             <form id="orderForm" name="orderForm" method="POST" action="update-stock-take.php">
                <div class="form-row">
                  <div class="col-md-12 form-group">
                    <input type="hidden" name="itemId" class="form-control" id="stockTakeId" />
                  </div>
			    </div>  
				<div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="itemNumber">Item Number/Code</label>
                    <input type="text" name="itemNumber" class="form-control disabled" id="itemNumber" data-rule-required="true" data-msg-required="Please enter item number" readonly />
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" name="itemName" class="form-control disabled" id="itemName" data-rule-required="true" data-msg-required="Please enter item name" readonly />
                  </div>
				  <div class="col-md-4 form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" class="form-control disabled" id="unit" data-rule-required="true" data-msg-required="Please specify the unit" readonly />
                  </div>
                </div>
				<div class="form-row">
                  <div class="col-md-4 form-group">
                    <label for="type">Type</label>
                     <input type="text" name="type" class="form-control disabled" id="type" data-rule-required="true" data-msg-required="Please specify the type" readonly />
                  </div>
				   <div class=" col-md-4 form-group">
                      <label for="category">Category</label>
                      <input type="text" name="category1" class="form-control disabled" id="category" data-rule-required="true" data-msg-required="Please specify the category" readonly />
                    </div>
					<div class="col-md-4 form-group">
                    <label for="type">Expiry date</label>
                     <input type="text" name="expiry" class="form-control disabled" id="expiry" data-rule-required="true" data-msg-required="Please specify expiry date" readonly />
                  </div>
                  </div>
                <div class="form-row"> 
                  <div class="col-md-4 form-group">
                    <label for="batchNo">Opening Stock</label>
                    <input type="text" name="openingStock" class="form-control disabled" id="openingStock" data-rule-required="true" data-msg-required="Please provide Opening Stock" readonly />
                  </div>
				  <div class="col-md-4 form-group">
                       <label for="quantity">Remaining Stock</label>
                       <input type="number" name="quantity" class="form-control" id="quantity" data-rule-required="true" data-msg-required="Please provide the quantity" readonly />
                     </div>
                  <div class="col-md-4 form-group">
                    <label for="price">Stock Transfered/used</label>
                    <input type="number" name="quantityUsed" class="form-control" id="quantityUsed" readonly />
                  </div>				  
				 </div>
				 <div class="form-row">
				 <div class="col-md-6 form-group">
                    <label for="price">Closing Stock</label>
                    <input type="number" name="closingStock" class="form-control" id="closingStock" placeholder="Enter closing stock amount"  data-rule-required="true" data-msg-required="Please Specify closing stock amount" />
                  </div>
				  <div class="col-md-6 form-group">
                    <label for="price">Physical Stock</label>
                    <input type="number" name="physicalStock" class="form-control" id="physicalStock" placeholder="Enter physical stock amount"  data-rule-required="true" data-msg-required="Please Specify physical stock amount" />
                  </div>
                  			 
                </div> 
                 <div class="form-row">	 
                  <div class="col-md-12 form-group">
                     <label for="description">Remarks</label>
                     <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Enter Remarks"></textarea>
                  </div>
                </div> 					
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" id="stock-take" class="btn btn-primary">Update Details</button>
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
<!---Stock Take table -->
<script src="../../dist/js/stock-take-modal.js"></script>
<script src="../../dist/js/update-stock-take.js"></script>
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>-->
<!--<script>-->
<!--    $(document).ready(function() {-->
<!--        $('#stockTable').DataTable({-->
<!--            "paging":   false,-->
<!--            "ordering": false,-->
<!--            "info":     false-->
<!--        });-->
<!--    });-->
<!--</script>-->
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