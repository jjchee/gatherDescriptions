# gatherDescriptions
Store YouTube channel descriptions in MySQL

Recommended Setup:
5.6.25 MySQL Community Server
PHP Version 5.5.14

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
4. Create profileNames.txt: It contains the list of targetted userNames, formatted with one username per line.
5. Place connect.php and profileNames.txt in the same directory as this file
