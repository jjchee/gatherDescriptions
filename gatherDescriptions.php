<?php   
	/*
	 *Jason Chee 6/29/2015
	 */
        $t = getdate();
        $date = date('Y-m-d', $t[0]);
	//base URL for YouTube API
	$baseURL = "https://www.googleapis.com/youtube/v3/";
	//snippet filter
	$filter = "channels?part=snippet&forUsername=";
	//Your Google developer key
	$devKey = "AIzaSyDwLY4-S1r1Yv6gr0ZjKNUBMmMLdswcAVo";
	$keyString = "&key=".$devKey;

	require_once("connect.php");
	$handle = fopen("profileNames.txt", "r");
	
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
		// process the line read.
		$userName = str_replace(array("\r", "\n"), "", $line);
		echo "Processing user $userName\n";
		$url = $baseURL.$filter.$userName.$keyString;
		$json = file_get_contents($url);
		$result = json_decode($json, true);
		$description = $result[items][0][snippet][description];
		//make string compatible with MySQL
		$description = mysqli_real_escape_string($mysqli, $description);
		$sql = "INSERT INTO $ytProfilesTable(userName, description)
			VALUES('$userName', '$description')";
		if ($mysqli->query($sql) === TRUE) {
		    echo "New record created successfully\r\n";
		} else {
		    echo "Error: " . $sql . "\r\n" . $mysqli->error;
		}
	    }
	    fclose($handle);
	} else {
	    // error opening the file.
	    echo "Could not open file";
	}
?>
