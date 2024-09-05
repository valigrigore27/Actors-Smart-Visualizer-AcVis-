<?php
@include './user/config.php';
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

$actors_per_page = 8;

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $actors_per_page;

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

    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/actors.css">

    <style>
        body {
            background-image: url("../static/images/glitterpic.jpg");
        }
    </style>

    <!-- Aici folosim DOM -->
    <!-- un fel de fetch -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('reset-btn').addEventListener('click', function() {
                document.getElementById('sort-form').reset();
                var resetUrl = this.value;
                window.location.href = resetUrl;
            });
        });
    </script>
</head>

<body>

    <div id="navbar"></div>

    <!-- ============== END OF NAVBAR =============== -->

    <section class="container photo-grid">
        <div class="search">
            <form action="actors.php" method="get" class="search-bar">
                <input type="text" placeholder="Search" name="q">
                <button type="submit" class="search"><i class="uil uil-search"></i></button>
            </form>
        </div>
        <div class="actors__container">
            <div class="sidebar">
                <form id="sort-form" action="actors.php" method="get">
                    <div class="sidebar-groups">
                        <h3 class="sg-title">Alfabetic</h3>
                        <input type="radio" id="alphabet_asc" name="sort_by_alphabet" value="asc">
                        <label for="alphabet_asc">A-Z</label><br>
                        <input type="radio" id="alphabet_desc" name="sort_by_alphabet" value="desc">
                        <label for="alphabet_desc">Z-A</label><br>
                    </div>
                    <div class="sidebar-groups">
                        <h3 class="sg-title">An</h3>
                        <input type="radio" id="year_asc" name="sort_by_year" value="asc">
                        <label for="year_asc">Ascendent</label><br>
                        <input type="radio" id="year_desc" name="sort_by_year" value="desc">
                        <label for="year_desc">Descendent</label><br>
                    </div>
                    <div class="sidebar-groups">
                        <h3 class="sg-title">Rank</h3>
                        <input type="radio" id="rank_asc" name="sort_by_rank" value="asc">
                        <label for="rank_asc">Ascendent</label><br>
                        <input type="radio" id="rank_desc" name="sort_by_rank" value="desc">
                        <label for="rank_desc">Descendent</label><br>
                    </div>
                    <button type="submit" class="sort">Sort</button>
                    <button type="reset" id="reset-btn" class="reset" value="actors.php">Reset</button>
                </form>
            </div>
            <div class="actors">
                <?php
                try {
                    // $sort_by_alphabet = isset($_GET['sort_by_alphabet']) ? $_GET['sort_by_alphabet'] : '';
                    // $sort_by_year = isset($_GET['sort_by_year']) ? $_GET['sort_by_year'] : '';
                    // $sort_by_rank = isset($_GET['sort_by_rank']) ? $_GET['sort_by_rank'] : '';

                    // if (isset($_GET['q'])) {
                    //     $search_query = $_GET['q'];
                    //     $select = "SELECT DISTINCT full_name FROM screen_actor_guild_awards WHERE full_name !='' AND full_name LIKE '%$search_query%'";
                    // } else {
                    //     $select = "SELECT DISTINCT full_name FROM screen_actor_guild_awards WHERE full_name !=''";
                    // }

                    // if ($sort_by_alphabet) {
                    //     $select .= " ORDER BY full_name $sort_by_alphabet";
                    // } elseif ($sort_by_year) {
                    //     $select .= " ORDER BY year $sort_by_year";
                    // } elseif ($sort_by_rank) {
                    //     $select .= " ORDER BY rank $sort_by_rank";
                    // }

                    // $select .= " LIMIT $start_from, $actors_per_page";

                    // $all_actors = mysqli_query($conn_actors, $select);

                    function getActorProfilePicture($actorName)
                    {
                        $api_key = '85594ca84bea519df6022c77c3435931';


                        $url = "https://api.themoviedb.org/3/search/person?api_key={$api_key}&query=" . urlencode($actorName);

                        $response = file_get_contents($url);

                        $data = json_decode($response, true);

                        $actors = $data['results'];

                        foreach ($actors as $actor) {
                            if (strtolower($actor['name']) == strtolower($actorName)) {
                                if (!empty($actor['profile_path'])) {
                                    return 'https://image.tmdb.org/t/p/w500' . $actor['profile_path'];
                                }
                            }
                        }

                        return null; //  null if no picture found
                    }

                    // actors from the database
                    try {
                        $sort_by_alphabet = isset($_GET['sort_by_alphabet']) ? $_GET['sort_by_alphabet'] : '';
                        $sort_by_year = isset($_GET['sort_by_year']) ? $_GET['sort_by_year'] : '';
                        $sort_by_rank = isset($_GET['sort_by_rank']) ? $_GET['sort_by_rank'] : '';

                        if (isset($_GET['q'])) {
                            $search_query = $_GET['q'];
                            $select = "SELECT DISTINCT full_name FROM screen_actor_guild_awards WHERE full_name !='' AND full_name LIKE '%$search_query%'";
                        } else {
                            $select = "SELECT DISTINCT full_name FROM screen_actor_guild_awards WHERE full_name !=''";
                        }

                        if ($sort_by_alphabet) {
                            $select .= " ORDER BY full_name $sort_by_alphabet";
                        } elseif ($sort_by_year) {
                            $select .= " ORDER BY year $sort_by_year";
                        } elseif ($sort_by_rank) {
                            $select .= " ORDER BY rank $sort_by_rank";
                        }

                        $select .= " LIMIT $start_from, $actors_per_page";

                        $all_actors = mysqli_query($conn_actors, $select);

                        if (mysqli_num_rows($all_actors) > 0) {
                            while ($row = mysqli_fetch_array($all_actors)) {
                                $actorName = $row['full_name'];
                                $actorNameFormatted = ucwords(strtolower($actorName));
                                $actorNameLower = strtolower($actorNameFormatted);

                                $profilePicture = getActorProfilePicture($actorName);

                                if ($profilePicture) {
                                    echo '<div class="photo"><img src="' . $profilePicture . '" alt="actor\'s name"><p>' . $row['full_name'] . '</p></div>';
                                } else {
                                    echo '<div class=\'photo\'>
                            <img src=\'../static/images/blank-user.jpg\' alt=\'actor\'s name\'><p>' . $row['full_name'] . ' </p>
                            </div>';
                                }
                            }
                        } else {
                            echo '<div class="not-found"><h2>No actors found!</h2></div>';
                        }
                    } catch (Exception $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </section>

    <?php
    $sql = "SELECT DISTINCT full_name FROM screen_actor_guild_awards WHERE full_name !=''";
    $result = mysqli_query($conn_actors, $sql);
    $total_records = mysqli_num_rows($result);
    $total_pages = ceil($total_records / $actors_per_page);
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    echo '<nav class=\'data-pagination\'>';
    if ($current_page >= 2) {
        echo '<a href="actors.php?page=' . ($current_page - 1) . buildQueryString() . '"><i class="uil uil-angle-left"></i></a>';
    }
    echo '<ul>';

    for ($i = 1; $i <= 10; $i++) {
        echo '<li' . ($i == $current_page ? ' class="current"' : '') . '><a href="actors.php?page=' . $i . buildQueryString() . '">' . $i . '</a></li>';
    }

    echo '<li><a href="actors.php?page=100' . buildQueryString() . '">...</a></li>';
    echo '<li><a href="actors.php?page=' . $total_pages . buildQueryString() . '">' . $total_pages . '</a></li>';
    echo '</ul>';
    if ($current_page < $total_pages) {
        echo '<a href="actors.php?page=' . ($current_page + 1) . buildQueryString() . '"><i class="uil uil-angle-right"></i></a>';
    }
    echo '</nav>';

    function buildQueryString()
    {
        $queryString = '';
        if (isset($_GET['q'])) {
            $queryString .= '&q=' . urlencode($_GET['q']);
        }
        if (isset($_GET['sort_by_alphabet'])) {
            $queryString .= '&sort_by_alphabet=' . $_GET['sort_by_alphabet'];
        }
        if (isset($_GET['sort_by_year'])) {
            $queryString .= '&sort_by_year=' . $_GET['sort_by_year'];
        }
        if (isset($_GET['sort_by_rank'])) {
            $queryString .= '&sort_by_rank=' . $_GET['sort_by_rank'];
        }
        return $queryString;
    }
    ?>

    <!-- ============== END OF ACTORS  =============== -->

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
                            //iau din nav_login si imi pun in navbar de la linia 71
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