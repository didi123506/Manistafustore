<?php

 $host = "localhost";
 $username = "root";
 $password = "";
 $database = "phpecom";

 //Creating database connection
 $con = mysqli_connect($host, $username, $password, $database);

//check
if(!$con)
{
    die("Connection Failed: ". mysqli_connect_error());
}
 

?>