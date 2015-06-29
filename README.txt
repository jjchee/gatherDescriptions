# gatherDescriptions
Store YouTube channel descriptions in MySQL

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
4. Create profileNames.txt which contains the list of targetted usernames, formatted with one username per line.
5. Place connect.php and profileNames.txt in the same directory as this file

To extract emails from the Profiles:

1. Enter the following command into MySQL:

    SELECT description FROM yt_profiles INTO OUTFILE '/tmp/profiles.txt';
2. cd to /tmp/ and enter the following into the command line to extract email addresses:

    grep -o '[[:alnum:]+\.\_\-]*@[[:alnum:]+\.\_\-]*' profiles.txt | sort | uniq -i
