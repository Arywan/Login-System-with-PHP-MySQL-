<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'NewSpotify');
define('DB_USER', 'root');
define('DB_PASS', '');
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
