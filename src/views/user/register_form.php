<?php
@include 'config.php';

if (isset($_POST['submit'])) {
   $username = mysqli_real_escape_string($conn_user, $_POST['username']);
   $email = mysqli_real_escape_string($conn_user, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);

   $select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass'";
   $result = mysqli_query($conn_user, $select);

   if (mysqli_num_rows($result) > 0) {
      $error[] = 'User already exists!';
   } else {
      if ($pass != $cpass) {
         $error[] = 'Passwords do not match!';
      } else {
         $user_type = $_POST['user_type'];

         if ($user_type == 'admin') {
            $admin_password = md5($_POST['admin_password']);
            $insert = "INSERT INTO admin_form(username, email, password) VALUES('$username','$email','$pass')";
            mysqli_query($conn_user, $insert);
            header('location:login_form.php');
         } else {
            $insert = "INSERT INTO user_form(username, email, password) VALUES('$username','$email','$pass')";
            mysqli_query($conn_user, $insert);
            header('location:login_form.php');
         }
      }
   }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Actors Smart Visualizer</title>
   <!-- Iconscount cdn -->
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
   <!-- Goofle fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="../../static/css/style.css">
   <link rel="stylesheet" href="../../static/css/user.css">

   <style>
      body {
         background-image: url("../../static/images/glitterpic.jpg");
      }
   </style>
</head>

<body>

   <?php include('../mod/nav_logout_user.php'); ?>

   <!-- ============== END OF NAVBAR =============== -->
   <header class="user__forms">
      <div class="container form-container">
         <form action="" method="post">
            <h3>Register Now</h3>
            <?php
            if (isset($error)) {
               foreach ($error as $error) {
                  echo '<span class="error-msg">' . $error . '</span>';
               };
            };
            ?>
            <input type="text" name="username" required placeholder="username">
            <input type="email" name="email" required placeholder="email">
            <input type="password" name="password" required placeholder="password">
            <input type="password" name="cpassword" required placeholder="confirm password">
            <select name="user_type" onchange="showAdminPasswordInput(this)">
               <option value="user">user</option>
               <option value="admin">admin</option>
            </select>

            <input type="submit" name="submit" value="register now" class="form-btn">
            <p>Already have an account? <a class="register-now" href="login_form.php">login now</a></p>
         </form>
      </div>
   </header>
   <!-- ============== END OF HEADER =============== -->

   <?php include('../mod/footer_user.php'); ?>

   <script src="../../static/main.js"></script>

   </script>
</body>

</html>