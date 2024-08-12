<!--Making a connection to the database-->

<?php
    //starting the session
    session_start();
    //including the error handler and logging
    include_once "logging/ErrorHandling.php";

    //Database connection parameters
    $user =  'Webuser';
    $password = 'Lab2021';
    $database = 'MaterialDB';
    $host = 'localhost';

    //Creating the connection
    $conn = new mysqli($host, $user, $password, $database);

    //Checking the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>