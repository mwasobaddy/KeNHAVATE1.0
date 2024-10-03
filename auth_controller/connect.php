<?php

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "kenha innovation portal";

    if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
    {
        die("Connection to the database has failed !");
    }

?>