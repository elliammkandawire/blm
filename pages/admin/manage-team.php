<?php
     session_start();
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
     require_once('../connection/db-connection.php'); 
     include('../misc/functions.php');

     $sess = array();
     $msg = array();
     $success = array();
     
    if (!isset ($_SESSION['admin']))
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
	      $user_check = $_SESSION['admin']; // Stored Session for current logged user

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

      $limit = 25;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $start = ($page -1) * $limit;
      $results = $connection->query("SELECT * FROM team ORDER BY team_code ASC LIMIT $start, $limit");
      $teams = $results->fetch_all(MYSQLI_ASSOC);

      $result1 = $connection->query("SELECT COUNT(team_id) AS id FROM team");
      $teamCount = $result1->fetch_all(MYSQLI_ASSOC);
      $total =  $teamCount[0]['id'];
      $pages = ceil($total / $limit);
      $previous = $page - 1;
      $next = $page + 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Admin | Manage Teams</title>

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
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">

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
               $query = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Pending' ORDER BY date_requested DESC LIMIT 4"); 
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
                      $link = "#=" . base64_encode(json_encode($requisition_id));

                      echo '<a href="'.$link.'" class="dropdown-item">';
                      echo '<!-- Message Start -->';
                      echo'<div class="media"><span><i class="fas fa-envelope mr-2">&nbsp;</i></span>';
                      echo'<div class="media-body"><h3 class="dropdown-item-title">'.$result['title'].'</h3>';
                      echo'<p class="text-sm">From:&nbsp;'.$result['team_code'].'</p>';
                      echo'<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>'. date('j F, Y, g:i a', strtotime($result['date_requested'])).'</p>';
                      echo'</div>
                        </div>
                       <!-- Message End -->
                      </a>
                       <div class="dropdown-divider"></div>';
                   }
                   echo '<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>';
                }
                else{
                  echo '
                  <a href="#" class="dropdown-item">
                   <div class="media">
                     <div class="media-body">
                       <h3 class="dropdown-item-title text-danger font-weight-bold">No New Message</h3> 
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
              $queryRequisitions = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Pending' ORDER BY date_requested DESC LIMIT 4"); 
               $countRequisition = mysqli_num_rows( $queryRequisitions);

              $queryExpiredDate = mysqli_query($connection, "SELECT * FROM item WHERE expiry_date < DATE_ADD(NOW(), INTERVAL 90 DAY)");
              $countExpiryDate= mysqli_num_rows($queryExpiredDate);
               
              $countAllNotification = $countRequisition + $countExpiryDate
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
          <a href="#" class="dropdown-item">
            <?php 
                if(mysqli_num_rows($queryRequisitions) > 0)
                {
            ?>
            <i class="fas fa-envelope mr-2"></i><?php echo $countRequisition; ?>&nbsp;Requsition(s)
            <span class="float-right text-muted text-sm"></span>
          </a>
          <?php }
               else{
          ?>
            <i class="fas fa-envelope mr-2"></i><span class="text-danger font-weight-bold"><?php echo 'No New Requisition'; ?></span>
            <?php 
               }
               ?>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
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
    <a href="admin-dash.php" class="brand-link">
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
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add-user.php" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Create Users</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="view-users.php" class="nav-link">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manage Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="manage-team.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Teams</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../misc/logout.php" class="nav-link">
                  <i class="fa fa-key nav-icon"></i>
                  <p>Logout</p>
                </a>
              </li>
            </ul>
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
            <h1 class="m-0">Team Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="admin-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Team Details</li>
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
                <h3 class="card-title">Team Details</h3>
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
              
              <!-- Card start -->
            <div class="card-body">
            
                 <div class="table-responsive">
                <!-- table start -->
               <table class="table table-striped table-advance table-hover table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Team Code</th>
                    <th>Team</th>
                   <th><i class="fa fa-tools"></i>&nbsp;Action</th>
                  </tr>
                </thead>
                <tbody> 
                    <?php 
                    if(mysqli_num_rows($results) >0)
                    {
                     foreach ($teams as $team)
                      {
                        ?>
                  <tr>
                     <td><?= $team['team_id']; ?></td>
                     <td><?= $team['team_code']; ?></td>
					 <td><?= $team['name']; ?></td>
                     <td>
					    <div class="btn-group">
                              <a class="btn btn-primary btn-sm editTeam" href="#" id="<?= $team['team_id']; ?>"><i class="fa fa-edit">&nbsp;Edit</i></a>
							  <a class="btn btn-danger btn-sm deleteTeam" href="#" id="<?= $team['team_id']; ?>"><i class="fa fa-times">&nbsp;Delete</i></a>
					    </div>
					</td>
                  </tr>
                      <?php 
                     }?>
                     <?php 
                    }
                    else{
                      echo '<tr><td colspan="4" class="text text-danger font-weight-bold">There are currently no user records to display</td></tr>';
                    }?>
                </tbody>
              </table>
              <!-- /.end table -->
              </div>
                
                <div class="card-footer">
                     <nav aria-label="...">
                    <ul class="pagination pagination-sm"> 
                    <?php if($page > 1){ ?>
                    <li class="page-item">
                        <a class="page-link" href="manage-team.php?page=<?= $previous; ?>">Previous</a>
                      </li>
                      <?php 
                       } else { ?>
                      <li class="page-item disabled">
                        <span class="page-link" href="javascript:avoid(0)">Previous</span>
                      </li>
                      <?php } ?>
                      
                      <?php for($i = 1; $i <= $pages; $i++) : ?>
                      <li class="page-item <?= $page == $i ? 'active':'' ?>" aria-current="page">
                            <a class="page-link" href="manage-team.php?page=<?= $i; ?>"><?= $i; ?></a>
                      </li>
                      <?php endfor; ?>
                       
                      <?php if(($i > $page) && ($page < $pages)){ ?>
                      <li class="page-item">
                        <a class="page-link" href="manage-team.php?page=<?= $next; ?>">Next</a>
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
				<!-- / .card footer -->
              
            </div>
            <!-- /.card body -->
            
            </div>
			<!-- /.card -->
        </div>
		 <!-- /.row (col md12) -->
		   <!-- /. Modal -->
		    <div class="modal fade" id="editTeam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                           <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Update Team Details</h3>
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                   </button>
                            </div>
                            <div id="update-success-message"></div>
               <div class="modal-body">     
                       <!-- form start -->
               <form id="orderForm" name="orderForm" method="POST" action="update-team.php" >
               <div class="form-row">
                  <div class="col-md-12 form-group">
                    <input type="hidden" name="teamId" class="form-control" id="teamId" data-rule-required="true" data-msg-required="Please enter team id" />
                  </div>
			    </div>
			   <div class="form-row">
                <div class="col-md-12 form-group">
				  <label for="firstname">Team Code:</label>
                  <input type="number" name="teamCode" class="form-control" id="teamCode" placeholder="Specify Team Code" data-rule-required="true" data-msg-required="Please enter team code" />
                </div>
			  </div>
                <div class="form-row">
				  <div class="col-md-12 form-group">
				    <label for="teamCode">Team Name:</label>
                    <input type="text" class="form-control" name="teamName" id="teamName" placeholder="enter team name" data-rule-required="true" data-msg-required="Please enter team name"/>
                  </div>
                </div>
               </div> 
                <div class="modal-footer">
				  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" name="submit" id="submit" class="btn btn-primary">Update team details</button>
                </div>
              </form>
                
               </div>
              </div>
            </div>
			<!-- /.row (modal) -->
	  </div>
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
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!---Edits teams-->
<script src="../../dist/js/edit-team.js"></script>
<!---Updates teams in team table-->
<script src="../../dist/js/update-team.js"></script>
<!---Deletes user in team table-->
<script src="../../dist/js/delete-team.js"></script>
</body>
</html>