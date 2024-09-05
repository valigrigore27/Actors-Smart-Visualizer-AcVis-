<?php

$servername = 'localhost';
$username = 'root';
$password = '';

// connect 
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
  die('Conectarea la MySQL a eșuat: ' . mysqli_connect_error());
}

$dbName = 'user_db';
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbName";
mysqli_query($conn, $createDbQuery);

// connect to user_db 
$conn_user = mysqli_connect($servername, $username, $password, $dbName);

// create user_form table
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS user_form (
  id INT(255) AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) COLLATE utf8mb4_general_ci,
  email VARCHAR(255) COLLATE utf8mb4_general_ci,
  password VARCHAR(255) COLLATE utf8mb4_general_ci,
  export_count INT(255) NOT NULL DEFAULT 0,
  last_export_reset TIMESTAMP
)";
mysqli_query($conn_user, $sqlCreateTable);

// create admin_form table
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS admin_form (
  id INT(255) AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) COLLATE utf8mb4_general_ci,
  email VARCHAR(255) COLLATE utf8mb4_general_ci,
  password VARCHAR(255) COLLATE utf8mb4_general_ci
)";
mysqli_query($conn_user, $sqlCreateTable);

$dbName = 'actors';
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbName";
mysqli_query($conn, $createDbQuery);

// connect to actors db 
$conn_actors = mysqli_connect($servername, $username, $password, $dbName);

$tableName = 'screen_actor_guild_awards';
$result = mysqli_query($conn_actors, "SHOW TABLES LIKE '$tableName'");
$tableExists = mysqli_num_rows($result) > 0;

if (!$tableExists) {
  // create screen_actor_guild_awards table
  $sqlCreateTable = "CREATE TABLE IF NOT EXISTS screen_actor_guild_awards(
    `year` VARCHAR(255) COLLATE utf8mb4_general_ci,
    `category` VARCHAR(255) COLLATE utf8mb4_general_ci,
    `full_name` VARCHAR(255) COLLATE utf8mb4_general_ci,
    `show` VARCHAR(255) COLLATE utf8mb4_general_ci,
    `won` VARCHAR(255) COLLATE utf8mb4_general_ci
  )";
  mysqli_query($conn_actors, $sqlCreateTable);

  $csvFilePath = 'C:/Users/Tuf/OneDrive/Desktop/Tehnologii Web-2024/xampp/htdocs/ActorsSmartVisualizer-main/src/static/screen_actor_guild_awards.csv';
  $csvFile = fopen($csvFilePath, 'r');

  // ignoara randul de antet din CSV
  fgetcsv($csvFile);

  while (($data = fgetcsv($csvFile)) !== false) {
    // extragem valorile din randul curent
    $year = $data[0];
    $category = $data[1];
    $full_name = $data[2];
    $show = $data[3];
    $won = $data[4];

    // Escapare speciala pentru valorile care vor fi introduse în interogari SQL
    $year = mysqli_real_escape_string($conn_actors, $year);
    $category = mysqli_real_escape_string($conn_actors, $category);
    $full_name = mysqli_real_escape_string($conn_actors, $full_name);
    $show = mysqli_real_escape_string($conn_actors, $show);
    $won = mysqli_real_escape_string($conn_actors, $won);

    $insertQuery = "INSERT INTO screen_actor_guild_awards (`year`, `category`, `full_name`, `show`, `won`) 
                      VALUES ('$year', '$category', '$full_name', '$show', '$won')";
    mysqli_query($conn_actors, $insertQuery);
  }

  $sqlAddColumns = "ALTER TABLE screen_actor_guild_awards
  ADD COLUMN id INT(11) AUTO_INCREMENT PRIMARY KEY FIRST,
  ADD COLUMN `rank` INT(11) AFTER won";
  mysqli_query($conn_actors, $sqlAddColumns);

  $sqlUpdateRank = "UPDATE screen_actor_guild_awards s
  SET s.rank = (
    SELECT COUNT(*) FROM screen_actor_guild_awards a
    WHERE (a.full_name = s.full_name AND (a.full_name!='' AND s.full_name!=''))
    AND a.won = 'True'
  )";
  mysqli_query($conn_actors, $sqlUpdateRank);

  fclose($csvFile);
}
