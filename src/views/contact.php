<?php

@include 'config.php';
@include './user/tokens.php';

session_start();

$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['token'])) {
  header('Location: ./user/login_form.php');
  exit();
}

$token = $_SESSION['token'];

if (!validateToken($token)) {
  header('Location: ./user/login_form.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actors Smart Visualizer</title>
  <!-- Iconscout cdn -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../static/css/style.css">
  <link rel="stylesheet" href="../static/css/contact.css">
  <style>
    body {
      background-image: url("../static/images/glitterpic.jpg");
    }
  </style>
</head>

<body>

  <div id="navbar"></div>

  <!-- ============== END OF NAVBAR =============== -->
  <section class="contact">
    <div class="container contact__container">
      <aside class="contact__aside">
        <div class="aside__image">
          <img src="../static/images/contact.svg">
        </div>
        <h2>Contact Us</h2>
        <p>
          Don't hesitate to reach out to us for any questions or suggestions.
          We want to hear your thoughts!
        </p>
        <ul class="contact__details">
          <li>
            <h5>valigrigore944@gmail.com</h5>
          </li>
          <li>
            <h5>andrieseibeniamin@gmail.com</h5>
          </li>
          <li>
            <i class="uil uil-phone"></i>
            <h5>0764******</h5>
          </li>
        </ul>
        <ul class="contact__socials">
          <li><a href="https:://facebook.com"><i class="uil uil-facebook"></i></a></li>
          <li><a href="https:://instagram.com"><i class="uil uil-instagram"></i></a></li>
          <li><a href="https:://twitter.com"><i class="uil uil-twitter"></i></a></li>
        </ul>
      </aside>

      <div class="thank-you-message">
        <h2>Thank You for Visiting!</h2>
        <p>We appreciate your interest in our website. If you have any questions or suggestions, feel free to contact us through the provided email or phone number. Have a great day!</p>
      </div>
    </div>
  </section>

  <!-- ============== END OF CONTACT =============== -->

  <?php include('mod/footer.php'); ?>

  <script src="../static/main.js"></script>

  <script>
    function loadContent() {
      var width = window.innerWidth;

      if (width > 1023) {
        <?php if (isset($_SESSION['user_name'])) { ?>
          fetch('mod/nav_login.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navbar').innerHTML = data;
              attachEventListeners();
            });
        <?php } else { ?>
          fetch('mod/nav_logout.php')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navbar').innerHTML = data;
              attachEventListeners();
            });
        <?php } ?>
      } else {
        fetch('mod/nav_logout.php')
          .then(response => response.text())
          .then(data => {
            document.getElementById('navbar').innerHTML = data;
            attachEventListeners();
          });
      }
    }

    window.addEventListener('load', loadContent);
    window.addEventListener('resize', loadContent);
  </script>

</body>

</html>