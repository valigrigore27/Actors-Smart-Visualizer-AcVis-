<?php

@include 'config.php';
@include './user/tokens.php';

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
    <!-- Swiper Js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <link rel="stylesheet" href="../static/css/style.css">

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
        <div class="container header__container">
            <div class="header__left">
                <h1> Learn more about your favourite actors</h1>
                <p>
                    Check out our Statistics page to get insights of the Hollywood's most famous names that we ranked,
                    in order to provide for our users a better visual perspective.
                </p>
                <a href="statistics.php" class="btn btn-primary"> Get Started</a>

            </div>
    </header>
    <!-- ============== END OF HEADER =============== -->

    <section class="actors">
        <div class="container actors__container">
            <div class="actors__left">
                <h1>Discover the most Popular Actors</h1>
                <p>
                    We present you a few of the most popular nominees and winners of the Screen Actors Guid (SAG) Awards over the years. In the section below
                    you can read quotes from their most iconic SAG award-accepting speeches. Check out the full list in the Actors section of our page.
                </p>

            </div>
            <div class="actors__right">
                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/emmastone.png"></div>
                    <h5> Emma Stone </h5>
                </article>

                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/jenniferaniston.png"></div>
                    <h5> Jennifer Aniston </h5>
                </article>

                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/davidharbour.png"></div>
                    <h5> David Harbour </h5>
                </article>

                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/leodicaprio.png"></div>
                    <h5> Leonardo DiCaprio </h5>
                </article>

                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/viola-davis.png"></div>
                    <h5> Viola Davis </h5>
                </article>
                <article class="actor">
                    <div class="actor_pic"><img src="../static/images/margotrobbie.png"></div>
                    <h5> Margot Robbie </h5>
                </article>
            </div>
        </div>
    </section>
    <!-- ======================================= END OF ACTORS =================================-->

    <section class="container quotes__container mySwiper">
        <h2> SAG Winners' Most Famous Speeches </h2>
        <div class="swiper-wrapper">
            <article class="quote swiper-slide">
                <div class="pic">
                    <img src="../static/images/emmastone.png">
                </div>
                <div class="quote__info">
                    <h5>Emma Stone</h5>
                </div>
                <div class="quote__body">
                    <p id="quote-text">
                        "To be an actor, playing an actor, and receiving an actor by a guild of actors, it’s pretty exceptional.
                        Oh, they’re counting me down. I have forgotten everything I have ever thought in my life."
                    </p>
                    <p>
                        -SAG 2017-
                    </p>
                </div>
            </article>

            <article class="quote swiper-slide">
                <div class="pic">
                    <img src="../static/images/jenniferaniston.png">
                </div>
                <div class="quote__info">
                    <h5>Jennifer Aniston</h5>
                </div>
                <div class="quote__body">
                    <p id="quote-text">
                        “Boy, did we get to dive deep into our own experiences and our own history and really be able to breathe life into these extraordinary characters.”
                    </p>
                    <p> -SAG 2020- </p>
                </div>
            </article>

            <article class="quote swiper-slide">
                <div class="pic">
                    <img src="../static/images/leodicaprio.png">
                </div>
                <div class="quote__info">
                    <h5>Leonardo DiCaprio</h5>
                </div>
                <div class="quote__body">
                    <p id="quote-text">
                        "So for any young actors out there, I encourage you to watch the history of cinema because as the history of cinema unfolds,
                        you realize that we all stand on the shoulders of giants."
                    </p>
                    <p>- SAG 2016-</p>
                </div>
            </article>

            <article class="quote swiper-slide">
                <div class="pic">
                    <img src="../static/images/davidharbour.png">
                </div>
                <div class="quote__info">
                    <h5>David Harbour</h5>
                </div>
                <div class="quote__body">
                    <p id="quote-text">
                        "We are united in that we are all human beings and we are all together on this horrible, painful, joyous, exciting and mysterious ride that is being alive."
                    </p>
                    <p>-SAG 2017-</p>
                </div>
            </article>

            <article class="quote swiper-slide">
                <div class="pic">
                    <img src="../static/images/viola-davis.png">
                </div>
                <div class="quote__info">
                    <h5>Viola Davis</h5>
                </div>
                <div class="quote__body">
                    <p id="quote-text">
                        "My job, as an actor, is just to create a human being to the best of my ability; flawed, messy, maybe not always likable.
                        It is my job, and I get so much joy out of being an actor."
                    </p>
                    <p>-SAG 2016-</p>
                </div>
            </article>
        </div>
        <div class="swiper-pagination"></div>
    </section>
    <!-- ======================================= END OF QUOTES =================================-->

    <section class="newss">
        <h2> Discover the latest HOLLYWOOD NEWS </h2>
        <div class="container newss__container">
            <article class="news">
                <div class="news__image">
                    <img src="../static/images/news1.jpg">
                </div>
                <div class="news__info">
                    <p>
                        Steve Carell Candidly Explains Why He Left The Office
                    </p>
                    <a href="news.php" class="btn btn-primary"> Find out more</a>
                </div>
            </article>

            <article class="news">
                <div class="news__image">
                    <img src="../static/images/news2.jpg">
                </div>
                <div class="news__info">
                    <p>
                        Elle Fanning and longtime boyfriend Max Minghella break up

                    </p>
                    <a href="news.php" class="btn btn-primary"> Find out more</a>
                </div>
            </article>

            <article class="news">
                <div class="news__image">
                    <img src="../static/images/news3.jpg">
                </div>
                <div class="news__info">
                    <p>
                        Janet McTeer on Phaedra, female sexuality and avoiding fame

                    </p>
                    <a href="news.php" class="btn btn-primary"> Find out more</a>
                </div>
            </article>
        </div>
    </section>
    <!-- ======================================= END OF NEWS =================================-->
    <section class="awards">
        <h2> SAG Award Categories </h2>
        <div class="container awards__container mySwiper1">
            <div class="swiper-wrapper">
                <article class="award swiper-slide">
                    <h4> Outstanding Female Actor in a Leading Role </h4>

                </article>


                <article class="award swiper-slide">
                    <h4>Outstanding Performance by a Male Actor in a Leading Role </h4>

                </article>

                <article class="award swiper-slide">
                    <h4> Outstanding Performance by a Female Actor in a Supporting Role </h4>

                </article>

                <article class="award swiper-slide">
                    <h4>Outstanding Performance by a Male Actor in a Supporting Role </h4>

                </article>

                <article class="award swiper-slide">
                    <h4> Outstanding Performance by a Cast in a Motion Picture</h4>

                </article>

            </div>
        </div>
        <div class="swiper-pagination"></div>

    </section>
    <!-- ======================================= END OF AWARDS =================================-->

    <?php include('mod/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../static/main.js"></script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination1",
                clickable: true,
            },
            //when window width is >= 600px
            breakpoints: {
                600: {
                    slidesPerView: 2
                }
            }
        });
    </script>

    <script>
        var swiper1 = new Swiper(".mySwiper1", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            //when window width is >= 600px
            breakpoints: {
                600: {
                    slidesPerView: 3
                }
            }
        });
    </script>

    <script>
        function loadContent() {
            var width = window.innerWidth;

            if (width > 1023) {
                <?php if (isset($_SESSION['token'])) {
                    $token = $_SESSION['token'];
                    if (isset($_SESSION['user_name']) && validateToken($token)) { ?>
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
                    <?php }
                } else {
                    ?>
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