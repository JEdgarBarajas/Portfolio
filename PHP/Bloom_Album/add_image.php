<?php 
	require_once('db_init.php');
	//$db is the database established in db_init.php
	//$command is just the text that goes into the query.
	
	//*****Note to self make sure to add when the date was created to gallery table*********//
	
	$name = htmlspecialchars($_POST["name"]);
	$file = htmlspecialchars($_FILES["file"]["name"]);
	$descrip = htmlspecialchars($_POST["description"]); 
	$imgTags = htmlspecialchars($_POST["tags"]);
	
	//Removing any ' or " that would mess up the code
	$name = str_replace('"', '\"', $name);
	$name = str_replace("'", "\'", $name);
	$descrip = str_replace('"', '\"', $descrip);
	$descrip = str_replace("'", "\'", $descrip);
	$file = str_replace('"', '', $file);
	$file = str_replace("'", "", $file);
	
	//Form Validation
	$nameErr = ""; //Will store name error message
	$fileErr = ""; //Will store file error message
	
	$command = "SELECT name FROM gallery WHERE name = '$name'";
	$result = $db->query($command);
	
	if($result->num_rows != 0) {
		$nameErr = "This name has already been used in the gallery. Use a different name.";
	}
	
	//Checks if this file name is already taken.
	$command = "SELECT file_name FROM gallery WHERE file_name = '$file'";
	$result = $db->query($command);
	if($result->num_rows != 0) {
		$fileErr = "A $file is already uploaded. You cannot use this file name.";
	}
	
	//Checks if image is either a jpg, png, or gif
	$filetype = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	if($filetype != "png" && $filetype != "jpg" && $filetype != "gif") {
		$fileErr += "<br>Bloom Album only accepts .png, .jpg, or .gif files at the moment.";
	}
	
	if($fileErr == "" && $nameErr == "") {
		//No errors! Image is added to the gallery table
		$command = "INSERT INTO gallery(name, description, file_name, tags) VALUES('$name', '$descrip', '$file', '$imgTags')";
		$db->query($command);
		
		//Adds image to image_storage folder
		move_uploaded_file($_FILES["file"]["tmp_name"], "image_storage/".$file);
		header('Location:gallery.php');
	}
	else {
		//Validation failed! 
		require_once('gallery.php?');
		
		//Session variables will be used to store errors and information
		unset($_SESSION["savedName"]);
		unset($_SESSION["savedDescription"]);
		unset($_SESSION["savedName"]);
		unset($_SESSION["savedTags"]);
	}
?>