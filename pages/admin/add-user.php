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
      $results = $connection->query("SELECT * FROM user ORDER BY date_created DESC LIMIT $start, $limit");
      $users = $results->fetch_all(MYSQLI_ASSOC);

      $result1 = $connection->query("SELECT COUNT(id) AS user_id FROM user");
      $userCount = $result1->fetch_all(MYSQLI_ASSOC);
      $total =  $userCount[0]['user_id'];
      $pages = ceil($total / $limit);
      $previous = $page - 1;
      $next = $page + 1;

        if (isset($_POST['submit'])){

          $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
          $surname = mysqli_real_escape_string($connection, $_POST['surname']);
          $username = mysqli_real_escape_string($connection, $_POST['username']);
          $userType = mysqli_real_escape_string($connection, $_POST['userType']);
          $teamCode = mysqli_real_escape_string($connection, $_POST['teamCode']);
          $username = mysqli_real_escape_string($connection, $_POST['username']);
          $password = mysqli_real_escape_string($connection, $_POST['password']);
          
		  if(!empty($_POST['teamCode'])){
            $sql = "SELECT team_id FROM team WHERE team_code = '$teamCode'";
            $results = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            if (mysqli_num_rows($results)==0){
              $msg[] = "The entered team code does not exist, please refer to team details table under manage teams for all the required team code";
            }
          }
		  if(!empty($_POST['username'])){
              $sql = "SELECT id FROM user WHERE username = '$username'";
              $results = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            if (mysqli_num_rows($results)>0){
              $msg[] = "The entered username already exist";
            }
          }
		  if(!$msg){
          
                $insert = "INSERT INTO user(firstname, surname, username, password, user_type, date_created, team_code) 
                           VALUES ('$firstname', '$surname', '$username', '$password', '$userType', NOW(), '$teamCode')";
                 mysqli_query($connection, $insert)or die(mysqli_error());
                 if (!$insert){
                     $msg[] = 'Failed to register user';
                 }
                 else{
                       $success[] = "User registered successfully";
                 }
           }
       }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Admin | Manage user</title>

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
              $queryRequisitions = mysqli_query($connection, "SELECT * FROM requisition WHERE status='Pending' ORDER BY date_requested DESC"); 
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
            <i class="fas fa-envelope mr-2"></i><?php echo $countRequisition; ?>&nbsp;Requisition(s)
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
                <a href="add-user.php" class="nav-link active">
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
                <a href="manage-team.php" class="nav-link">
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
            <h1 class="m-0">Register User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="admin-dash.php">Home</a></li>
              <li class="breadcrumb-item active">Register User</li>
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
                <h3 class="card-title">Register User</h3>

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
                <div class="col-md-6 form-group">
				  <label for="firstname">Firstname:</label>
                  <input type="text" name="firstname" class="form-control" id="name" placeholder="User Firstname" data-rule-required="true" data-msg-required="Please enter user firstname" />
                </div>
				<div class="col-md-6 form-group">
				  <label for="surname">Surname:</label>
                  <input type="text" name="surname" class="form-control" id="surname" placeholder="User Surname" data-rule-required="true" data-msg-required="Please enter User surname" />
			    </div>
			  </div>
                <div class="form-row">
			       <div class="col-md-6 form-group">
				     <label for="userType">User Type:</label>
                      <select class="form-control" name="userType" data-rule-required="true" data-msg-required="Please select user type">
                         <option value="">Choose user type...</option>
                         <option>admin</option>
						 <option>budget_holder</option>
						 <option>finance</option>
						  <option>finance_director</option>
						 <option>line_manager</option>
						 <option>procurement</option>
						 <option>procurement_officer</option>
                         <option>user_team</option>
                         <option>team_finance</option>
						 <option>team_procurement</option>
						 <option>warehouse</option>
                      </select>
                   </div>
				  <div class="col-md-6 form-group">
				    <label for="teamCode">Team Code:</label>
                    <input type="number" class="form-control" name="teamCode" id="teamCode" placeholder="User team code" data-rule-required="true" data-msg-required="Please enter user team code"/>
                  </div>
                </div>
                <div class="form-row">
				   <div class="col-md-6 form-group">
				      <label for="username">Username:</label>
                      <input type="text" name="username" class="form-control" id="username" placeholder="User username" data-rule-required="true" data-msg-required="Please enter username" />
                </div>
				<div class="col-md-6 form-group">
				  <label for="password">Password:</label>
                  <input type="password" name="password" class="form-control" id="password" placeholder="User password" onkeyup='check();' data-rule-required="true" data-msg-required="Please enter user password" />
                  <span toggle="#password" class="icofont-eye-alt field-icon toggle-password"></span>
                </div>
				<div class="col-md-6 form-group">
				  <label for="password2">Confirm Password:</label>
                  <input type="password" name="password2" class="form-control" id="password2" onkeyup='check();' data-rule-required="true" data-msg-required="Please enter confirmation password" />
                  <span toggle="#password2" class="icofont-eye-alt field-icon toggle-password2"></span>
				  <div id="message"></div>
                </div>
			  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Register User</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row (main row) -->
		      
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
</body>
</html>