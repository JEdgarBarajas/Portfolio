<?php
	#MySQL login information.
	$server = "localhost";
	$user = "username"; //Replace Username with your MySQL username
	$pw = "password"; //Replace Password with MySQL password

	#Database and Table innitiliazation
	$db = new mysqli($server, $user, $pw);
	$db->query("CREATE DATABASE IF NOT EXISTS bloomdb");
	$db = new mysqli($server, $user, $pw, "bloomdb");
	$command = "CREATE TABLE IF NOT EXISTS gallery (
		image_id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) UNIQUE NOT NULL,
		description VARCHAR(250),
		file_name VARCHAR(250) UNIQUE NOT NULL,
		tags VARCHAR(550))";
	#Note: Tags are 25 characters, meaning there can be a max of 20 tags per image.	
	$db->query($command);
	
	//Create table for album names
	$command = "CREATE TABLE IF NOT EXISTS albums (
		table_id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) UNIQUE NOT NULL,
		description VARCHAR(250),
		thumbnail VARCHAR(250),
		thumbnail_position VARCHAR(250),
		tags VARCHAR(550)
	)";
	$db->query($command);
	
	//Insert albums and gallery tables into albums
	$command = "INSERT IGNORE INTO albums(name, description) VALUES('gallery', 'The gallery of bloom album.')";
	$db->query($command);
	$command = "INSERT IGNORE INTO albums(name, description) VALUES('albums', 'table of existing albums.')";
	$db->query($command);
	
	session_start();
	 
?>