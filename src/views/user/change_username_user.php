<?php

@include 'config.php';

session_start();

if (isset($_POST['submit'])) {

    $nuser = $_POST['wuser'];

    $id = $_SESSION['id'];
    $username = $_SESSION['user_name'];

    if ($nuser == $username) {
        $error[] = 'Same username';
    } else {
        $sql = "SELECT username FROM user_form WHERE id='$id' AND username='$username'";
        $result = mysqli_query($conn_user, $sql);
        if (mysqli_num_rows($result) === 1) {

            $sql_2 = "UPDATE user_form SET username='$nuser' WHERE id='$id'";
            mysqli_query($conn_user, $sql_2);

            $_SESSION['user_name'] = $nuser;

            $success[] = 'Your username has been changed successfully';
        } else {
            $error[] = 'Username not found';
        }
    }
}

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

    <header class="user__forms">
        <div class="container form-container">
            <form action="" method="post">
                <h3>Change Username</h3>
                <h6> Your username: <span><?php echo $_SESSION['user_name'] ?></span></h6>
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    }
                }
                ?>
                <?php
                if (isset($success)) {
                    foreach ($success as $success) {
                        echo '<span class="success-msg">' . $success . '</span>';
                    }
                }
                ?>
                <input type="text" name="wuser" required placeholder="new username">
                <input type="submit" name="submit" value="Change" class="form-btn">
            </form>
        </div>
    </header>

    <!-- ============== END OF HEADER =============== -->

    <?php include('../mod/footer_user.php'); ?>

    <script src="../../static/main.js"></script>
    <script>
        function loadContent() {
            var width = window.innerWidth;

            if (width > 1023) {
                fetch('../mod/nav_login_user.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('navbar').innerHTML = data;
                        attachEventListeners();
                    });
            } else {
                fetch('../mod/nav_logout_user.php')
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