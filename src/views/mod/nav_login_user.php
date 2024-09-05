<head>
    <link rel="stylesheet" href="../static/css/user.css">
</head>

<nav>
    <div class="container nav__container">
        <a href="../index.php"><h4>AcVis</h4></a>
        <ul class="nav__menu">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../actors.php">Actors</a></li>
            <li><a href="../statistics.php">Statistics</a></li>
            <li><a href="../news.php">News</a></li>
            <li><a href="../contact.php">Contact</a></li>
            <li>
                <a href="#" class="uil uil-user"></a>
                <ul class="profile__menu">
                    <li class="sub-item">
                        <span class="uil uil-text"></span> 
                        <a href="change_username_user.php">Change Username</a>
                    </li>
                    <li class="sub-item">
                        <span class="uil uil-key-skeleton"></span>
                        <a href="change_password_user.php">Change Password</a>
                    </li>
                    <li class="sub-item">
                        <span class="uil uil-signout"></span>
                        <a href="logout.php">Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
        <button id="open-menu-btn"><i class="uil uil-list-ul"></i></button>
        <button id="close-menu-btn"><i class="uil uil-multiply"></i></button>
    </div>
</nav>