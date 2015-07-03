# gatherDescriptions
This crawler will recursively search through related YouTube videos from a supplied seed.
The crawler is implemented using the YouTube v3 Data API, PHP and MySQL. I am filtering for
users with a subscriber count between 1k-100k, then storing their profiles in MySQL.
Using Unix command-line tools, I will extract email addresses from the data.

Recommended Setup:
1. 5.6.25 MySQL Community Server
2. PHP Version 5.5.14

Instructions:
1. Login to MySQL, then create database:
   create database 'yt'
2. Select the recently created database:
   use 'yt';
3. Create the table:
   CREATE TABLE `yt_profiles` (`id` int(11) NOT NULL auto_increment,
       `userName` varchar(100) default NULL,
       `description` varchar(1000) default NULL,
       PRIMARY KEY  (`id`));
4. Place connect.php, gatherDescriptions.php in the same directory
5. cd to directory containing the above files and run gatherDescriptions.php:
    php gatherDescriptions.php
6. Enter the following command into MySQL:
    SELECT description FROM yt_profiles INTO OUTFILE '/tmp/profiles.txt';
7. cd to /tmp/ and enter the following into the command line to extract email addresses:
    grep -o '[[:alnum:]+\.\_\-]*@[[:alnum:]+\.\_\-]*' profiles.txt | sort | uniq -i
