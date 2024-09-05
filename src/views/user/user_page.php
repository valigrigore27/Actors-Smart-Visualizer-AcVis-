<?php

@include 'config.php';

session_start();

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

  <div id="navbar"></div>

  <!-- ============== END OF HEADER =============== -->

  <header class="container-user">
    <div class="content">
      <h3>Hi, <span>user</span></h3>
      <h1>Welcome <span><?php echo $_SESSION['user_name'] ?></span></h1>
      <p>to this wonderful page</p>
      <a href="change_username.php" class="btn buton-mobile">Change Username</a>
      <a href="change_password_user.php" class="btn buton-mobile">Change Password</a>
      <a href="logout.php" class="btn buton-mobile">logout</a>
    </div>
  </header>

  <!-- ============== END OF HEADER =============== -->

  <?php include('../mod/footer_user.php'); ?>

  <script src="../../static/main.js"></script>
  <script>
    function loadContent() {
      var width = window.innerWidth;

      if (width > 1023) {
        <?php if (isset($_SESSION['user_name'])) { ?>
          fetch('../mod/nav_login_user.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navbar').innerHTML = data;
              attachEventListeners();
            });
        <?php } else { ?>
          <?php header('location:login_form.php'); ?>
        <?php } ?>
      } else {
        <?php if (isset($_SESSION['user_name'])) { ?>
          fetch('../mod/nav_logout_user.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navbar').innerHTML = data;
              attachEventListeners();
            });
        <?php } else { ?>
          <?php header('location:login_form.php'); ?>
        <?php } ?>
      }
    }

    window.addEventListener('load', loadContent);
    window.addEventListener('resize', loadContent);
  </script>

  </script>
</body>

</html>