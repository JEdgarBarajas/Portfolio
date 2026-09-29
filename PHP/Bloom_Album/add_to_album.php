<?php
	require_once('db_init.php');
	//$db is the database established in db_init.php
	//$command is just the text that goes into the query.
	
	//Page is being used to load error.
	if(isset($_GET["album"])) {
		if($_GET["album"] != "") {
			require_once("add_album_image_form.php");
		}
	}
	
	//Normal Form Handling.
	else {
		$imgName = htmlspecialchars($_POST["name"]);
		$imgName = str_replace('"', '\"', $imgName);
		$imgName = str_replace("'", "\'", $imgName);
		$id = htmlspecialchars($_POST["album_id"]);
		$imgDescrip = htmlspecialchars($_POST["description"]);
		$imgDescrip = str_replace('"', '\"', $imgDescrip);
		$imgDescrip = str_replace("'", "\'", $imgDescrip);	
		$imgTags = htmlspecialchars($_POST["albumTags"]);
		
		$nameErr = ""; //Will store name error message
		$fileErr = ""; //Will store file error message
		//$imageFile = $_FILES['file']['name'];
		
		if(isset($_FILES["file"]["name"])) {
			//Checks if the newly uploaded image name exists in the gallery
			$command = "SELECT * FROM gallery WHERE name = '$imgName'";
			$row = $db->query($command);
			if($row->num_rows != 0) {
				$nameErr .= "$imgName already exists in the gallery. Please use a different name.<br>";
			}
		}
		
		$command = "SELECT * FROM album_$id WHERE name = '$imgName'";
		$row = $db->query($command);
		if($row->num_rows != 0) {
			$nameErr .= "$imgName already exists in this album. Please use a different name.<br>";
		}
		
		if(isset($_FILES["file"]["name"])) {
			//Checks if the newly uploaded image file already exists in the gallery
			$imgFile = htmlspecialchars($_FILES["file"]["name"]);
			$imgFile = str_replace('"', '', $imgFile);
			$imgFile = str_replace("'", "", $imgFile);
			$command = "SELECT * FROM gallery WHERE file_name = '$imgFile'";
			$row = $db->query($command);
			if($row->num_rows != 0) {
				$fileErr .= "This file name has already been used in the gallery. Please use a different file.<br>";
			}
		}
		
		else if(!isset($_FILES["file"]["name"])) {
			//Sets file to selected gallery image. 
			$imgFile = htmlspecialchars($_POST["file"]);
			$imgFile = str_replace('"', '', $imgFile);
			$imgFile = str_replace("'", "", $imgFile);
		}
		
		
		$command = "SELECT * FROM album_$id WHERE file = '$imgFile'";
		$row = $db->query($command);
		if($row->num_rows != 0) {
			$fileErr .= "This file name has already been used in this album. Please use a different file.<br>";
		}
		
		if($fileErr == "" && $nameErr == "") {
			//No errors. Image can be added to album
			if(isset($_FILES["file"]["name"])) {
				//Upload to gallery first.
				$command = "INSERT IGNORE INTO gallery(name, description, file_name, tags) 
							VALUES ('$imgName', '$imgDescrip', '$imgFile', '$imgTags')";
				$db->query($command);
				move_uploaded_file($_FILES["file"]["tmp_name"], "image_storage/".$_FILES["file"]["name"]);
			}
			
			//Add image to album
			$command = "INSERT IGNORE INTO album_$id(name, description, file, tags) 
						VALUES ('$imgName', '$imgDescrip', '$imgFile', '$imgTags')";
			$db->query($command);
			
			header("location: album_details.php?album=$id");
		}
		
		else {
			//Error information stored in session variables. 
			$_SESSION["fileErr"] = $fileErr;
			$_SESSION["nameErr"] = $nameErr;
			$_SESSION["savedName"] = $imgName;
			$_SESSION["savedDescription"] = $imgDescrip; 
			$_SESSION["savedTags"] = $imgTags;
			header("location: add_album_image_form.php?album=$id");
		}
	}
	
	
	
	
	
?>