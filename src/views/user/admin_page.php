<?php
@include 'config.php';
session_start();

$select_query = "SELECT * FROM user_form";
$result = mysqli_query($conn_user, $select_query);

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

    <section class="container-admin">
        <div class="content">
            <h3>Hi, <span>admin</span></h3>
            <h1>Welcome <span><?php echo $_SESSION['user_name'] ?></span></h1>
            <p>to this wonderful page</p>
        </div>
    </section>

    <!-- ============== END OF HEADER =============== -->

    <section class="container-table-admin">
        <div class="users">
            <h3>User Accounts</h3>
            <div class="table-wrapper">
                <table class="table-users">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><a href="delete_user.php?delete_id=<?php echo $row['id']; ?>">Delete</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <!--================SFARSITUL TABELULUI=====================================-->

    <?php include('../mod/footer_user.php'); ?>

    <script src="../../static/main.js"></script>
    <script>
        function loadContent() {
            var width = window.innerWidth;

            if (width > 1023) {
                <?php if (isset($_SESSION['user_name'])) { ?>
                    fetch('../mod/nav_login_admin.php')
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