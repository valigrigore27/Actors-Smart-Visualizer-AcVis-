<?php

@include 'config.php';
@include 'tokens.php';

session_start();

if (isset($_POST['submit'])) {

    $user_type = $_POST['user_type'];
    //    scapa de caracterele speciale din email
    $email = mysqli_real_escape_string($conn_user, $_POST['email']);
    $pass = md5($_POST['password']);

    if ($user_type == 'user') {

        $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";
        $location = 'location:user_page.php';
    } else {
        $select = " SELECT * FROM admin_form WHERE email = '$email' && password = '$pass' ";
        $location = 'location:admin_page.php';
    }


    $result = mysqli_query($conn_user, $select);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result);
        $_SESSION['user_name'] = $row['username'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_type'] = $user_type;

        $username = $row['username'];
        $id =  $row['id'];



        //token
        $token = generateToken($username, $user_type, $id);
        $_SESSION['token'] = $token;
        setcookie('token', $token, time() + (60 * 1000), '/');


        if (isset($_SESSION['previous_page'])) {
            header('location: ' . $_SESSION['previous_page']);
        } else {
            header($location);
        }
    } else {
        $error[] = 'Incorrect email or password!';
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
                <h3>Login Now</h3>
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    };
                };
                ?>
                <select name="user_type" onchange="showAdminPasswordInput(this)">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                </select>
                <input type="email" name="email" required placeholder="email">
                <input type="password" name="password" required placeholder="password">
                <input type="submit" name="submit" value="login now" class="form-btn">
                <p>Don't have an account? <a class="register-now" href="register_form.php">register now</a></p>
            </form>
        </div>
    </header>
    <!-- ============== END OF HEADER =============== -->

    <?php include('../mod/footer_user.php'); ?>

    <script src="../../static/main.js"></script>

    </script>
</body>

</html>