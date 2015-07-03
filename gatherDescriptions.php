<?php   
	/*
	 *Jason Chee 6/29/2015
	 */
        $t = getdate();
        $date = date('Y-m-d', $t[0]);
	//base URL for YouTube API
	$baseURL = "https://www.googleapis.com/youtube/v3/";
	//snippet filter
	$filter = "channels?part=snippet&id=";
	//related video filters
	$filter2 = "search?relatedToVideoId=";
	$filter3 = "&part=snippet&type=video&maxResults=50";
	//num subscribers filter
	$filter4 = "channels?part=statistics&id=";
	//Your Google developer key
	$devKey = "AIzaSyDwLY4-S1r1Yv6gr0ZjKNUBMmMLdswcAVo";
	$keyString = "&key=".$devKey;
	
	$searchLimit = 3000;
	$seedVid = "cHi3NICopjE";
	$seedID = "UC6wtWmyCNFW9fc_7v1biLnA";
	$userArray = array($seedID);
	
	require_once("connect.php");
	addRelated($seedID, $seedVid);
	
	function addRelated($relatedUser, $relatedVid)
	{
	    global $baseURL, $filter, $filter2, $filter3, $filter4, $devKey, $keyString,
	        $searchLimit, $userArray, $mysqli, $ytProfilesTable;
	    //First, store  the desription of the user in mySql
	    $url = $baseURL.$filter.$relatedUser.$keyString;
	    $json = file_get_contents($url);
	    $result = json_decode($json, true);
	    $description = $result[items][0][snippet][description];
	    //make string compatible with MySQL
	    $description = mysqli_real_escape_string($mysqli, $description);
	    $sql = "INSERT INTO $ytProfilesTable(userName, description)
		    VALUES('$relatedUser', '$description')";
	    if ($mysqli->query($sql) === TRUE) {
		echo "Success " . count($userArray) . "/" . $searchLimit. "\r\n";
	    } else {
		echo "Error: " . $sql . "\r\n" . $mysqli->error;
	    }
	    
	    //then, search for related videos
	    $url1 = $baseURL.$filter2.$relatedVid.$filter3.$keyString;
	    $json1 = file_get_contents($url1);
	    $result1 = json_decode($json1, true); //array of related videos details
	    //navigate down related videos list
	    $found = FALSE;
	    $count = count($result1[items]);
	    while(!$found)
	    {
		for($j = 0; $j < $count && !$found; $j++)
		{
		    $relatedUser = $result1[items][$j][snippet][channelId];
		    $relatedVid = $result1[items][$j][id][videoId];
		    
		    $url2 = $baseURL.$filter4.$relatedUser.$keyString;
		    $json2 = file_get_contents($url2);
		    $result2 = json_decode($json2, true);
		    
		    $subCount = $result2[items][0][statistics][subscriberCount]; //get sub count
		    //if user does not exist and is in range, recurse
		    if (!in_array($relatedUser, $userArray)
			&& $subCount > 1000 && $subCount < 100000)
		    {
			array_push($userArray, $relatedUser);
			$found = TRUE;
			//deallocate unused variables to save memory
			unset($url);
			unset($json);
			unset($result);
			unset($description);
			unset($sql);
			unset($url1);
			unset($json1);
			unset($result1);
			unset($url2);
			unset($json2);
			unset($result2);
			unset($subCount);
			unset($token);
			unset($count);
			
			if(count($userArray) < $searchLimit)
			{
			    addRelated($relatedUser, $relatedVid);
			}
		    }
		    if($j == $count - 1)
		    {
			$token = $result1[nextPageToken];
			if($token == null)
			{
			    //if no more related videos, start again at seed (will cause one duplicate)
			    addRelated($seedID, $seedVid);
			}
			$url1 = $baseURL.$filter2.$relatedVid."&part=snippet&pageToken=" . $token . "&type=video&maxResults=50".$keyString;
			$json1 = file_get_contents($url1);
			$result1 = json_decode($json1, true);
		    }
		}
	    }
	}
	var_dump($userArray);
	
?>
