<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');

    $sess = array();
    $msg = array();
     
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
          OR item.item_name LIKE '$keywords%' AND where expiry_date >= NOW() 
          ORDER BY item.date_received DESC LIMIT $start, $limit");
          $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

          $result1 = mysqli_query($connection, "SELECT COUNT(item_id) AS id FROM item");
          $itemCount = mysqli_fetch_all($result1, MYSQLI_ASSOC);
          $total = $itemCount[0]['id'];
          $pages = ceil($total / $limit);
          $previous = $page - 1;
          $next = $page + 1;
      }
      else if(isset($_POST['start']) && isset($_POST['end'])){
          $start=$_POST['start'];
          $end=$_POST['end'];
          $result = mysqli_query($connection, "SELECT *  
                                    FROM item
                                    JOIN team 
                                    ON item.team_code = team.team_code
									AND item.team_code=$teamCode where  expiry_date >= NOW()  AND date_received BETWEEN '$start' AND '$end'
                                    ORDER BY item.date_received");
          $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
      }
      else
      {
         $result = mysqli_query($connection, "SELECT *  
                                    FROM item
                                    JOIN team 
                                    ON item.team_code = team.team_code
									AND item.team_code=$teamCode where  expiry_date >= NOW() 
                                    ORDER BY item.date_received  DESC LIMIT $start, $limit");
         $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
      }

     $result1 = mysqli_query($connection, "SELECT COUNT(item_id) AS id FROM item WHERE team_code=$teamCode");
     $itemCount = mysqli_fetch_all($result1, MYSQLI_ASSOC);
     $total = $itemCount[0]['id'];
     $pages = ceil($total / $limit);
     $previous = $page - 1;
     $next = $page + 1;

      
      if(mysqli_num_rows($result) >0)
      { 
        $flag = TRUE; 
      }
      else
      {
        $flag = FALSE;
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Warehouse | Stock Transfer</title>

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
            <h1 class="m-0">Transfer Stock</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="warehouse-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Transfer Stock</li>
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
                <h3 class="card-title">Transfer Stock</h3>

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
                <?php include '../includes/file.php';?>
            </div>
            <div class="table-responsive">
                <!-- table start -->
               <table class="table table-striped table-advance table-bordered" id="stock_transfer">
                <thead class="text-nowrap">
                  <tr>
                    <th>Item Code</th>
                    <th>Name</th>
                    <th>Specification</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price</th>
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
                     <td><?= $item['item_code']; ?></td>
                     <td><?= $item['item_name']; ?></td>
                     <td><?= $item['specification']; ?></td>
                     <td><?= $item['category']; ?></td>
                     <td><?= $item['type']; ?></td>
                     <td><?= $item['unit']; ?></td>
                     <td><?= $item['quantity']; ?></td>
                     <td><?= $item['price']; ?></td>
                     <td><?= $item['date_received']; ?></td>
                     <td><?= $item['expiry_date']; ?></td>
                     <td><?= $item['batch']; ?></td>
                     <td><?= $item['GRN']; ?></td>
                     <td><a href="team-detail.php?team_code=<?= $item['team_code']; ?>"><?= $item['team_code']; ?></a></td>
                     <td>
                        <div class="btn-group">
                          <a class="btn btn-sm btn-primary" href="request-stock-transfer.php?item_id=<?= $item['item_id']; ?>&item_code=<?= $item['item_code']; ?>&item_name=<?= $item['item_name']; ?>&quantity=<?= $item['quantity']; ?>"><i class="fa fa-paper-plane">&nbsp;Transfer</i></a>
                        </div>
                     </td>
                  </tr>
                    <?php } ?>
                      <?php 
                   }
                   else if ($flag == FALSE)
                   { ?>
                       <tr><td colspan="14" class="text text-danger font-weight-bold">No search results found</td></tr>
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
                        <a class="page-link" href="transfer-stock.php?page=<?= $previous; ?>">Previous</a>
                      </li>
                      <?php 
                       } else { ?>
                      <li class="page-item disabled">
                        <span class="page-link" href="javascript:avoid(0)">Previous</span>
                      </li>
                      <?php } ?>
                      
                      <?php for($i = 1; $i <= $pages; $i++) : ?>

                      <?php if($flag == FALSE) {?>
                        <li class="page-item disabled">
                            <a class="page-link" href="javascript:avoid(0)">0</a>
                        </li>
                      <?php 
                      }else{ ?>
                        <li class="page-item <?= $page == $i ? 'active':'' ?>" aria-current="page">
                            <a class="page-link" href="transfer-stock.php?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                      <?php } ?>

                      <?php endfor; ?>
                       
                      <?php if(($i > $page) && ($page < $pages)){ ?>
                      <li class="page-item">
                        <a class="page-link" href="transfer-stock.php?page=<?= $next; ?>">Next</a>
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
          <!--/.col (right) -->
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
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!---Show date/tine on dashboard-->
<script src="../../dist/js/datetime.js"></script>
<!---Generates random numbers-->
<script src="../../dist/js/random_numbers.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<?php include '../includes/includes_footer.php';?>
<script>
    $(document).ready(function() {
        $('#stock_transfer').DataTable({
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
</body>
</html>