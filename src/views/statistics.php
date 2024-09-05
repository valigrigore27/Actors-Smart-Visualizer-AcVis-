<?php

@include './user/config.php';
@include './user/tokens.php';

session_start();

//initializam previous_page cu pagina curenta pt a putea reveni la ea
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


$exportCount = 0;

function exportButtonClicked()
{

    include './user/config.php';
    $userId = $_SESSION['id'];
    global $exportCount;

    $exportCountResult = mysqli_query($conn_user, "SELECT export_count, last_export_reset FROM user_form WHERE id = $userId");
    $exportData = mysqli_fetch_assoc($exportCountResult);
    $exportCount = $exportData['export_count'];
    $lastExportReset = $exportData['last_export_reset'];

    // interval de 24 de ore
    if (time() - strtotime($lastExportReset) >= 24 * 60 * 60) {
        mysqli_query($conn_user, "UPDATE user_form SET export_count = 0, last_export_reset = NOW() WHERE id = $userId");
    }

    $exportCountResult = mysqli_query($conn_user, "SELECT export_count FROM user_form WHERE id = $userId");
    $exportCount = mysqli_fetch_assoc($exportCountResult)['export_count'];

    if ($exportCount < 100) {
        mysqli_query($conn_user, "UPDATE user_form SET export_count = export_count + 1 WHERE id = $userId");
    } else {
        // mesaj de eroare 
        echo "<div id='error-container'>
            <h3>Mesaj de eroare</h3>
            <p>Ati atins limita maxima de 3 export-uri pe zi.</p>
            <i class='uil uil-times' id='close-error'></i>
        </div>";
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/statistics.css">

    <style>
        body {
            background-image: url("../static/images/glitterpic.jpg");
        }
    </style>
</head>

<body>

    <div id="navbar"></div>

    <!-- ============== END OF NAVBAR =============== -->

    <header>
        <div class="container statistics__container">
            <div class="statistics">
                <h3> Actors ranking </h3>
                <div class="table-wrapper">
                    <table class="table-sortable">
                        <thead>
                            <th>ID</th>
                            <th>Year</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Show</th>
                            <th>Won</th>
                            <th>Rank</th>
                        </thead>
                        <tbody>
                            <?php

                            $select = "SELECT * FROM screen_actor_guild_awards LIMIT 100";
                            $result = mysqli_query($conn_actors, $select);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['year'] . "</td>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['full_name'] . "</td>
                        <td>" . $row['show'] . "</td>
                        <td>" . $row['won'] . "</td>
                        <td>" . $row['rank'] . "</td>
                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </header>
    <!-- ============== END OF STATISTICS =============== -->

    <section class="container views__container" id="charts">
        <div class="views">
            <div class="views__buttons">
                <a href="<?php echo '?action=view1' . '#charts'; ?>" class="btn btn-primary"> View 1 </a>
                <a href="<?php echo '?action=view2' . '#charts'; ?>" class="btn btn-primary"> View 2 </a>
                <a href="<?php echo '?action=view3' . '#charts'; ?>" class="btn btn-primary"> View 3 </a>
            </div>

            <div class="generated__views">
                <?php
                @include 'charts.php';
                ?>
            </div>
        </div>
    </section>

    <!-- ============== END OF VIEWS  =============== -->

    <div class="export__buttons">
        <h4>Export</h4>

        <?php
        // Obtinem actiunea din URL
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        // Verificam daca este selectata o actiune de view
        if (isset($_GET['action'])) {

            $viewAction = $_GET['action'];

            // Construim URL-urile butoanelor de export cu actiunea de export specificata
            $currentUrl = $_SERVER['REQUEST_URI'];
            $parts = parse_url($currentUrl);
            $query = isset($parts['query']) ? $parts['query'] : '';
            if ($query !== '') {
                $currentUrl .= '&';
            } else {
                $currentUrl .= '?';
            }
            $csvUrl = $currentUrl . 'export=csv#charts';
            $webpUrl = $currentUrl . 'export=webp#charts';
            $svgUrl = $currentUrl . 'export=svg#charts';

            switch ($viewAction) {
                case 'view1':
                    if (isset($_GET['export'])) {
                        exportButtonClicked();
                        $exportType = $_GET['export'];
                        if ($exportType === 'csv') {
                            $csvUrl = exportToCSV13($chartData1, 'chart_data');
                        } else if ($exportType === 'webp') {
                            $webpUrl = exportToWebP($chartConfig1, 'chart_data');
                        } else if ($exportType === 'svg') {
                            $svgUrl = exportToSVG($chartConfig1, 'chart_data');
                        }
                    }
                    break;
                case 'view2':
                    if (isset($_GET['export'])) {
                        exportButtonClicked();
                        $exportType = $_GET['export'];
                        if ($exportType === 'csv') {
                            $csvUrl = exportToCSV2($chartData2, 'chart_data');
                        } else if ($exportType === 'webp') {
                            $webpUrl = exportToWebP($chartConfig2, 'chart_data');
                        } else if ($exportType === 'svg') {
                            $svgUrl = exportToSVG($chartConfig2, 'chart_data');
                        }
                    }
                    break;
                case 'view3':
                    if (isset($_GET['export'])) {
                        exportButtonClicked();
                        $exportType = $_GET['export'];
                        if ($exportType === 'csv') {
                            $csvUrl = exportToCSV13($chartData3, 'chart_data');
                        } else if ($exportType === 'webp') {
                            $webpUrl = exportToWebP($chartConfig3, 'chart_data');
                        } else if ($exportType === 'svg') {
                            $svgUrl = exportToSVG($chartConfig3, 'chart_data');
                        }
                    }
                    break;
                default:
                    break;
            }
        } else if (isset($_GET['export'])) {
            $csvUrl = "";
            $webpUrl = "";
            $svgUrl = "";
            echo "<div id='error-container'>
            <h3>Mesaj de eroare</h3>
            <p>Selectati mai intai o actiune de view.</p>
            <i class='uil uil-times' id='close-error'></i>
        </div>";
        } else {
            $csvUrl = "?export=csv";
            $webpUrl = "?export=webp";
            $svgUrl = "?export=svg";
        }

        if ($exportCount < 100) {
            echo '<a href="' . $csvUrl . '" class="btn-to export-to1">';
            echo '<span> CSV </span>';
            echo '</a>';

            echo '<a href="' . $webpUrl . '" class="btn-to export-to2">';
            echo '<span> WebP </span>';
            echo '</a>';

            echo '<a href="' . $svgUrl . '" class="btn-to export-to3">';
            echo '<span> SVG </span>';
            echo '</a>';
        } else {
            //blocam butoanele de export
            echo "<a href='#' class='btn-to export-to1'>
            <span> CSV </span>
        </a>";

            echo "<a href='#' class='btn-to export-to2'>
            <span> WebP </span>
        </a>";

            echo "<a href='#' class='btn-to export-to3'>
            <span> SVG </span>
        </a>";
        }

        ?>

    </div>

    <!-- ============== END OF BUTTONS =============== -->

    <?php include('mod/footer.php'); ?>

    <script src="../static/main.js"></script>
    <script src="../static/tablesort.js"></script>

    <script>
        //inchiderea mesajului de eroare
        var closeButton = document.getElementById('close-error');
        var errorContainer = document.getElementById('error-container');

        if (closeButton) {
            closeButton.addEventListener('click', function() {
                errorContainer.style.display = 'none';
            });
        }

        //vizualizarea butonului de view selectat 
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const buttons = document.querySelectorAll('.views__buttons a');

            buttons.forEach(function(button) {
                const buttonHref = button.getAttribute('href');
                const url = new URL(buttonHref, window.location.href);
                const buttonAction = url.searchParams.get('action');

                if (action === buttonAction) {
                    button.classList.add('selected');
                } else {
                    button.classList.remove('selected');
                }
            });
        });
    </script>
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