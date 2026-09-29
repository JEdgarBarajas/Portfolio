<?php
	require_once("db_init.php");
	//$db is the database established in db_init.php
	//$command is just the text that goes into the query.
	
	$fileKey = "file";  //Default File Column Name
	$idKey = "table_id";  //Default ID Column Name

	$id = htmlspecialchars($_POST['table']);
	$albumName = htmlspecialchars($_POST["name"]);
	$albumDescrip = htmlspecialchars($_POST["description"]);
	$albumTags = htmlspecialchars($_POST["albumTags"]);
	$albumThumbPos = htmlspecialchars($_POST["thumbnailPosition"]);
	
	$albumName = str_replace("'", "\'", $albumName);
	$albumName = str_replace('"', '\"', $albumName);
	$albumDescrip = str_replace("'", "\'", $albumDescrip);
	$albumDescrip = str_replace('"', '\"', $albumDescrip);
	
	//Form Validation
	$result = $db->query($command);
	$nameErr = ""; //Will store name error message
	
	$command = "SELECT name FROM albums WHERE name = '$albumName' AND table_id != '$id'";
	$rows = $db->query($command)->num_rows;
	if($rows != 0) {
		$nameErr = "\"$albumName\" is already taken. Please choose a different album name. ";
	}
	
	if($nameErr == "") {
		//No errors! Image is added to the gallery table
		$command = "UPDATE albums SET name = '$albumName', description = '$albumDescrip', tags = '$albumTags', thumbnail_position = '$albumThumbPos'";
		if($_FILES["thumbnail"]["name"] != "") {
			$albumThumbnail = htmlspecialchars($_FILES["thumbnail"]["name"]);
			$command .= ", thumbnail = '$albumThumbnail'";
			move_uploaded_file($_FILES['thumbnail']['tmp_name'], "thumbnails/".$albumThumbnail);
		}
		$command .= " WHERE $idKey = '$id'";
		$db->query($command);
		//echo $command;
		
		header("Location: album_details.php?album=$id");
	}
	else {
		//Validation failed! 
		$aID = preg_replace("/[\D]/", "", $id);
		
		//Error handling is handled here through session variables.
		echo $nameErr;
		$_SESSION['albumErr'] = $nameErr;
		//$_SESSION['savedName'] = $name;
		//$_SESSION['savedDescription'] = $descrip;
		header("Location: album_details.php?album=$id");		
		
	}
?>