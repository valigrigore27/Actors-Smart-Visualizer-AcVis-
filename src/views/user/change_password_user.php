<?php

@include 'config.php';

session_start();

if (isset($_POST['submit'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $op = validate($_POST['op']);
    $np = validate($_POST['np']);
    $c_np = validate($_POST['c_np']);

    if ($np !== $c_np) {
        $error[] = 'The confirmation password does not match';
    } else {
        $op = md5($op);
        $np = md5($np);
        $id = $_SESSION['id'];

        $sql = "SELECT password FROM user_form WHERE id='$id' AND password='$op'";
        $result = mysqli_query($conn_user, $sql);
        if (mysqli_num_rows($result) === 1) {

            $sql_2 = "UPDATE user_form SET password='$np' WHERE id='$id'";
            mysqli_query($conn_user, $sql_2);

            $success[] = 'Your password has been changed successfully';
        } else {
            $error[] = 'Incorrect password';
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
                <h3>Change Password</h3>
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
                <input type="password" name="op" required placeholder="old password">
                <input type="password" name="np" required placeholder="new password">
                <input type="password" name="c_np" required placeholder="confirm new password">
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