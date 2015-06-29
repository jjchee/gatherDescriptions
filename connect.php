<?php
        /*
	 *Jason Chee 6/25/2015
	 *Established connection with the MySQL server
	 */
	$host = "127.0.0.1";
	$username = "root";
	$password = "password";
	$database = "yt";
	
	$mysqli = new mysqli($host,$username,$password,$database);
	if (mysqli_connect_error()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    
        //name of table in the database to store channel descriptions
	$ytProfilesTable = "yt_profiles";
?>