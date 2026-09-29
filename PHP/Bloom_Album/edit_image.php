<?php
	require_once("db_init.php");
	//$db is the database established in db_init.php
	//$command is just the text that goes into the query.
	
	$table = htmlspecialchars($_POST["table"]);
	
	$fileKey = "file_name";  //Default File Column Name
	$idKey = "image_id";  //Default ID Column Name
	
	//Edited image is not a gallery image
	if($table != "gallery") {
		$fileKey = "file"; 
		$idKey = "id";
	}
	
	$name = htmlspecialchars($_POST["name"]);
	$descrip = htmlspecialchars($_POST["description"]); 
	$imgTags = htmlspecialchars($_POST["tags"]);
	$id = htmlspecialchars($_POST["id"]);
	
	//Removing any ' or " that would mess up the code
	$name = str_replace('"', '\"', $name);
	$name = str_replace("'", "\'", $name);
	$descrip = str_replace('"', '\"', $descrip);
	$descrip = str_replace("'", "\'", $descrip);
	$id = str_replace('"', '\"', $id);
	$id = str_replace("'", "\'", $id); 
	
	//Form Validation
	$command = "SELECT name FROM $table WHERE name = '$name' AND $idKey != '$id'";
	$result = $db->query($command);
	$nameErr = ""; //Will store name error message
	
	if($result->num_rows != 0) {
		if($table == "gallery") {
			$nameErr = "This name has already been used in the gallery. Use a different name.";
		}
		else {$nameErr = "This name has already been taken in this album. Use a different name.";}
	}
	
	if($nameErr == "") {
		//No errors! Image is added to the gallery table
		$command = "UPDATE $table SET name = '$name', description = '$descrip', tags = '$imgTags' WHERE $idKey = '$id'";
		$db->query($command);
		
		if($table == "gallery") {
			header('Location:gallery.php');
		}
		else {
			$aID = preg_replace("/[\D]/", "", $table);
			header("Location: album_details.php?album=$aID");
		}
	}
	else {
		//Validation failed! 
		if($table == "gallery") {
			$_SESSION['nameErr'] = $nameErr;
			$_SESSION['savedName'] = $name;
			$_SESSION['savedDescription'] = $descrip;
			header("Location: gallery.php?currImage=$id");
		}
		else {
			$aID = preg_replace("/[\D]/", "", $table);
			
			//Error handling is handled here through session variables.
			$_SESSION['nameErr'] = $nameErr;
			$_SESSION['savedName'] = $name;
			$_SESSION['savedDescription'] = $descrip;
			header("Location: album_details.php?album=$aID&currImage=$id");
		}
		
		
		
	}
?>