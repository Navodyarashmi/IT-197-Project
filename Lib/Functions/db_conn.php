<?php

function Connection() {
    $host = 'localhost';   // Your database host
    $user = 'root';        // Your database username
    $pass = '';            // Your database password
    $dbname = 'ums';  // Your database name

    // Create a connection
    $db_conn = mysqli_connect($host, $user, $pass, $dbname);

    // Check if the connection was successful
    if (!$db_conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $db_conn;
}


?>
