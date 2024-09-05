<?php

@include 'config.php';
@include './user/tokens.php';


session_start();


//Daca nu sunt logat si vreau sa merg direct pe news aici 
//se va retine ca previous_page e news si daca ma loghez o sa ma duca pe news

$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['token'])) {
  header('Location: ./user/login_form.php');
  exit();
}


//Trebuie login ca sa intri pe news
$token = $_SESSION['token'];

if (!validateToken($token)) {
  header('Location: ./user/login_form.php');
  exit();
}


?>
<script>
  function fetchNews(query, apiKey) {
    var url = 'https://newsapi.org/v2/everything?' +
      'q=' + encodeURIComponent(query) + '&' +
      'apiKey=' + apiKey;

    var req = new Request(url);

    return fetch(req)
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        return data.articles;
      })
      .catch(function(error) {
        console.log(error);
        return null;
      });
  }

  function fetchUpdates(query, apiKey) {
    var url = 'https://newsapi.org/v2/everything?' +
      'q=' + encodeURIComponent(query) + '&' +
      'apiKey=' + apiKey;

    var req = new Request(url);

    return fetch(req)
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        return data.articles;
      })
      .catch(function(error) {
        console.log(error);
        return null;
      });
  }

  function displayNews(articles, containerId) {
    if (articles && articles.length > 0) {
      var newsContainer = document.getElementById(containerId);
      newsContainer.innerHTML = '';

      articles.forEach(function(article) {
        var articleElement = createArticleElement(article);
        newsContainer.appendChild(articleElement);
      });
    }
  }

  function displayNews2(articles, containerId) {
    var container = document.getElementById(containerId);
    container.innerHTML = '';

    if (articles && articles.length > 0) {
      var article = articles[0];

      var articleElement = createArticleElement(article);
      container.appendChild(articleElement);
    } else {
      container.textContent = 'Nu s-au găsit rezultate.';
    }
  }

  function createArticleElement(article) {
    var articleElement = document.createElement('article');
    articleElement.classList.add('new');

    var imageElement = document.createElement('div');
    imageElement.classList.add('new__image');
    var image = document.createElement('img');
    image.src = article.urlToImage;
    image.alt = '';
    imageElement.appendChild(image);

    var titleElement = document.createElement('h4');
    var linkElement = document.createElement('a');
    linkElement.href = article.url;
    linkElement.textContent = article.title;
    titleElement.appendChild(linkElement);

    articleElement.appendChild(imageElement);
    articleElement.appendChild(titleElement);

    return articleElement;
  }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actors Smart Visualizer</title>

  <!-- Iconscount cdn -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../static/css/style.css">
  <link rel="stylesheet" href="../static/css/news.css">

  <style>
    body {
      background-image: url("../static/images/glitterpic.jpg");
    }
  </style>
</head>

<body>

  <div id="navbar"></div>


  <!-- CSS PENTRU CAUTARE NUME -->

  <body>
    <title>Căutare Nume</title>
    <style>
      body {
        background-image: url("../static/images/glitterpic.jpg");
        text-align: center;
      }

      .search-container {
        margin-top: 10rem;
        max-width: 600px;
        align-items: center;
      }

      .search-input {
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 5px;
        border-radius: 4px;
        margin-right: 10px;
      }

      button {
        padding: 20px 20px;
        background-color: var(--color-bg3);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 20px;
      }

      button:hover {
        background-color: var(--color-primary);
      }

      .error {
        color: red;
        font-weight: bold;
      }

      /* CAUTARE NUME SI AFISARE STIRE DESPRE ACTORU PE CARE L-AM CAUTAT */
    </style>
    </head>

    <div class="search-container">
      <input type="text" id="firstNameInput" class="search-input" placeholder="Prenume">
      <br>
      <input type="text" id="lastNameInput" class="search-input" placeholder="Nume">
      <br>
      <button onclick="search()">Căutare</button>
      <div id="results" class="results"></div>
    </div>

    <script>
      function search() {
        var firstName = document.getElementById('firstNameInput').value;
        var lastName = document.getElementById('lastNameInput').value;

        if (firstName && lastName) {
          afisareNews(firstName, lastName);
        } else {
          displayError('Vă rugăm să completați ambele câmpuri.');
        }
      }

      function resetResults() {
        var resultsContainer = document.getElementById('results');
        resultsContainer.innerHTML = '';
      }

      function createArticleElement(article) {
        var articleElement = document.createElement('article');
        articleElement.classList.add('news');

        var imageElement = document.createElement('div');
        imageElement.classList.add('news__image');
        var image = document.createElement('img');
        image.src = article.urlToImage;
        image.alt = '';
        imageElement.appendChild(image);

        var titleElement = document.createElement('h4');
        var linkElement = document.createElement('a');
        linkElement.href = article.url;
        linkElement.textContent = article.title;
        titleElement.appendChild(linkElement);

        articleElement.appendChild(imageElement);
        articleElement.appendChild(titleElement);

        return articleElement;
      }

      function afisareNews(firstName, lastName) {
        resetResults();
        var actorName = firstName + ' ' + lastName;
        var apiKey = '4557335ecb3e4a8ca099d2d693e25aab';

        fetchNews(actorName, apiKey)
          .then(function(articles) {
            displayNews2(articles, 'results');
          })
          .catch(function(error) {
            console.log(error);
            displayError('A apărut o eroare în timpul căutării.');
          });
      }
    </script>


    <!-- PARTEA CU TRENDING NEWS SI LATEST UPDATES -->

    <script>
      function loadContent() {
        var trendingActorNames = ["Florin Piersic", "Leonardo DiCaprio", "Brad Pitt", "Jennifer Aniston"];
        var updatesTitles = ["Adrian Mutu", "ANDREW SCOTT", "MICHAEL B. JORDAN", "LAURA HARRIER"];
        var apiKey = "4557335ecb3e4a8ca099d2d693e25aab";

        var trendingPromises = trendingActorNames.map(function(actorName) {
          return fetchNews(actorName, apiKey);
        });

        var updatesPromises = updatesTitles.map(function(title) {
          return fetchNews(title, apiKey);
        });

        Promise.all([...trendingPromises, ...updatesPromises])
          .then(function(results) {
            var trendingNews = results.slice(0, trendingActorNames.length);
            var updates = results.slice(trendingActorNames.length);

            var trendingArticles = trendingNews.map(function(actorNews) {
              return actorNews[0];
            });

            var updateArticles = updates.map(function(update) {
              return update[0];
            });

            displayNews(trendingArticles, 'trending-container');
            displayNews(updateArticles, 'updates-container');
          });
      }

      window.addEventListener('load', loadContent);

      window.addEventListener('load', function() {
        loadContent();
        setInterval(loadContent, 24 * 60 * 60 * 1000);
      });
    </script>

    <section class="container trending__container">
      <h3 class="heading"> Trending News </h3>
      <div class="trending" id="trending-container"></div>
    </section>
    <section class="container latest-updates__container">
      <h3 class="heading"> Latest Updates </h3>
      <div class="latest-updates" id="updates-container"></div>
    </section>

    <!-- FOOTER -->
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