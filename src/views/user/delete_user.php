<?php
@include 'config.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $select_query = "SELECT * FROM user_form WHERE id = $delete_id";
    $result = mysqli_query($conn_user, $select_query);
    if (mysqli_num_rows($result) > 0) {

        $delete_query = "DELETE FROM user_form WHERE id = $delete_id";
        mysqli_query($conn_user, $delete_query);
    }
}

header('Location: admin_page.php');
exit();
?>
