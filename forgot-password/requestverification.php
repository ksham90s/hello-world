<?php 
  $email=$_GET['email'];
  // check if user authorised
if (empty($email)) {
  header('Location: ./login.php?error=notauthorised');
  exit();
}
 ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KUIS E-voting System - Verification</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-6 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
                
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">

                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Verification Code</h1>
                  </div>
                  <?php 
                  include '../alertfunction.php';
                  // error handling
                    if (isset($_GET['success'])) {
                      if ($_GET['success'] == "emailsended") {
                        successwithclose("We have send an verification code to your student email");
                      } 
                    }
                    elseif (isset($_GET['error'])) {
                      if ($_GET['error']=="wrongcode") {
                        alertwithclose("Your Verification is not match. Please Try again");
                      }
                    }
                ?>
                  <form class="user" method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
                    <div class="form-group">
                      <input type="text" name="txt_verification_code" class="form-control form-control-user" placeholder="Insert Verification Code Here...">
                      <input type="hidden" name="txt_email" class="form-control form-control-user" value="<?=$email?>">
                    </div>
                    <input type="submit" name="btn_verify" class="btn btn-primary btn-user btn-block" value="Verify"> 

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</body>

</html>
<?php
if (isset($_POST['btn_verify']) ) {
    // get token and hash using md5
    $verifypin=md5($_POST['txt_verification_code']);
    $email=$_POST['email'];

    require '../connection.php';

    $sql= "SELECT * FROM resetpassword WHERE u_email = '$email' ";
    $qr = mysqli_query($db,$sql);
    if($qr==false){
      echo "Failed to verify request<br>";
      echo "SQL error :".mysqli_error($db);
      exit();
    }
    if (mysqli_num_rows($qr)==0) {
    echo "$email";

      echo "something when wrong please reset password ";
      exit();
    }
    $record= mysqli_fetch_array($qr);

    if ($verifypin == $record['reset_token']) {
      header('Location: ../newpasswordpage.php?email='.$email);
    }
    else
      header('Location: requestverification.php?error=wrongcode');
      exit();
}

?>