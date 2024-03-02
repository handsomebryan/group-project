<!doctype html>
<html lang="en">
  <?php

  include 'dbconnect.php';
  
  session_start();
  
  
  $data=mysqli_connect($servername,$username,$password,$dbname);
  
  if($data===false)
  {
    die("connection error");
  }
  
  
  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
    $username=$_POST["username"];
    $password=$_POST["password"];
  
  
    $sql="select * from 業務員資料 where username='".$username."' AND password='".$password."' ";
  
    $result=mysqli_query($data,$sql);
  
    $row=mysqli_fetch_array($result);
  
    if($row["是否為主管"]=="0")
    {	
  
      $_SESSION["username"]=$username;
  
      header("location:user/index21.php");
    }
  
    elseif($row["是否為主管"]=="1")
    {
  
      $_SESSION["username"]=$username;
      
      header("location:admin/index21.php");
    }
  
    else
    {
      echo "username or password incorrect";
    }

  } 
  ?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>業務一把罩</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo-sm.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <p><center><img src="../assets/images/logos/logo.png" width="180" alt=""></center></p>
                <form action="#" method="post">
                  <div class="mb-3">
                    <label for="id" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username"  id="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="pw" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                  </div>
                  <input type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" value="Login">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>