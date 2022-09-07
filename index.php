<?php
    session_start();
    require_once('pages/connection/db-connection.php');
    include('pages/misc/functions.php');

    $msg = array();
    $proc = "procurement";
    $financ = "finance";
    $wareho = "warehouse";
    $userteam = "user_team";
	$adminUser = "admin";
	$lineManager = "line_manager";
	$teamProcurement = "team_procurement";
	$budgetHolder ="budget_holder";
	$financeDirector = "finance_director";
	$procurementOfficer = "procurement_officer";

  if(isset($_POST['submit'])){
	  $username = mysqli_real_escape_string($connection, $_POST["username"]);
	  $password = mysqli_real_escape_string($connection, $_POST["password"]);
	 //$password = sha1($password);

  if(empty($_POST["username"])){
    $msg[] = 'Please enter your Username'; 
 
   } 
  if(empty($_POST["password"])){
    $msg[] = 'Please enter your Password'; 
  }
	  
   // query rows where the username and password is equal to the username and password entered in the input boxes
   $q1 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$proc\"";
   $q2 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$financ\"";
   $q3 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$wareho\"";
   $q4 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$userteam\"";
   $q5 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$adminUser\"";
   $q6 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$lineManager\"";
   $q7 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$teamProcurement\"";
   $q8 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$budgetHolder\"";
   $q9 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$financeDirector\"";
   $q10 = "SELECT * FROM user WHERE username = \"$username\" AND password = \"$password\" AND user_type = \"$procurementOfficer\"";
	 
	$r1 = mysqli_query($connection, $q1);
    $r2 = mysqli_query($connection, $q2);
	$r3 = mysqli_query($connection, $q3);
	$r4 = mysqli_query($connection, $q4);
	$r5 = mysqli_query($connection, $q5);
	$r6 = mysqli_query($connection, $q6);
	$r7 = mysqli_query($connection, $q7);
	$r8 = mysqli_query($connection, $q8);
	$r9 = mysqli_query($connection, $q9);
	$r10 = mysqli_query($connection, $q10);
	  
	$num1 = mysqli_num_rows($r1);
    $num2 = mysqli_num_rows($r2);
	$num3 = mysqli_num_rows($r3);
	$num4 = mysqli_num_rows($r4);
	$num5 = mysqli_num_rows($r5);
	$num6 = mysqli_num_rows($r6);
	$num7 = mysqli_num_rows($r7);
	$num8 = mysqli_num_rows($r8);
	$num9 = mysqli_num_rows($r9);
	$num10 = mysqli_num_rows($r10);
	 
	$Gt1 = mysqli_fetch_assoc($r1);
	$Gt2 = mysqli_fetch_assoc($r2);
	$Gt3 = mysqli_fetch_assoc($r3);
	$Gt4 = mysqli_fetch_assoc($r4);
	$Gt5 = mysqli_fetch_assoc($r5);
	$Gt6 = mysqli_fetch_assoc($r6);
	$Gt7 = mysqli_fetch_assoc($r7);
	$Gt8 = mysqli_fetch_assoc($r8);
	$Gt9 = mysqli_fetch_assoc($r9);
	$Gt10 = mysqli_fetch_assoc($r10);
	 
	if($num1 > 0)
    {
        $_SESSION['procurement'] = $Gt1['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/procurement/home');
    }
    if($num2 > 0)
    {
        $_SESSION['finance'] = $Gt2['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/finance/finance-dash.php');
    }
    if($num3 > 0)
    {
        $_SESSION['warehouse'] = $Gt3['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/warehouse/warehouse-dash.php');
    }
    if($num4 > 0)
    { 
        $_SESSION['user-team'] = $Gt4['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/user-team/user-team-dash.php');
    }
	if($num5 > 0)
    { 
        $_SESSION['admin'] = $Gt5['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/admin/admin-dash.php');
    }
	if($num6 > 0)
    { 
        $_SESSION['line-manager'] = $Gt6['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/line-manager/line-manager-dash.php');
    }
	if ($num7 > 0)
    {
        $_SESSION['team-procurement'] = $Gt7['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/team-procurement/home');
    }
	if($num8 > 0)
    {
        $_SESSION['budget-holder'] = $Gt8['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/budget-holder/budget-holder-dash.php');
    }
	if($num9 > 0)
    {
        $_SESSION['finance-director'] = $Gt9['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/finance-director/finance-director-dash.php');
    }
	if($num10 > 0)
    {
        $_SESSION['procurement-officer'] = $Gt10['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start']+(60*60);
        header('Location: pages/procurement-officer/procurement-officer-dash.php');
    }
    else
    {
        $msg[] =  "Incorrect username or password";
    }  
    mysqli_close($connection);
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Banja | Sign In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.css">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
       <img src="dist/img/blm_logo.png" alt="Banja Logo" height="100" width="100" class="brand-image img-circle elevation-3">
    </div>
  <!-- /.login-logo -->
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Sign In</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
      <?php if ($msg)
            { 
			        showerror($msg)
		  ?>	
      <?php ;}

			?>
    <form method="POST" name="loginForm" id="loginForm" action="<?php $_SERVER['PHP_SELF']; ?>">
      <div class="card-body">
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group mb-6">
             <input type="text" name ="username" class="form-control" id="username" placeholder="Enter username" />
          <div class="input-group-append">
             <div class="input-group-text">
               <span class="fa fa-user"></span>
             </div>
          </div>

          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group mb-6">
             <input type="password" name ="password" class="form-control" id="password" placeholder="Enter password" />
          <div class="input-group-append">
            <div class="input-group-text">
              <span toggle="#password" class="fa fa-eye toggle-password"></span>
            </div>
          </div>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" name="submit" class="btn btn-primary">Sign In</button>
        <button type="clear" name="cancel" class="btn btn-default">Cancel</button>
      </div>
    </form>
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/password-viewer.js"></script>

<script>
     	 $(document).ready(function() {
		$("#loginForm").validate();
    
 	});
</script>
</body>
</html>
