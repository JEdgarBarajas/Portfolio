<?php
	require_once("db_init.php");
	
	$albumName = htmlspecialchars($_POST["name"]);
	$albumThumbnail = htmlspecialchars($_FILES["thumbnail"]["name"]);
	$albumDescrip = htmlspecialchars($_POST["description"]);
	$albumTags = htmlspecialchars($_POST["albumTags"]);
	$albumThumbPos = htmlspecialchars($_POST["thumbnailPosition"]);
	
	$albumName = str_replace("'", "\'", $albumName);
	$albumName = str_replace('"', '\"', $albumName);
	$albumDescrip = str_replace("'", "\'", $albumDescrip);
	$albumDescrip = str_replace('"', '\"', $albumDescrip);
	
	//Error messages
	$nameErr = "";
	$fileErr = "";
	
	$command = "SELECT name FROM albums WHERE name = '$albumName'";
	$rows = $db->query($command)->num_rows;
	if($rows != 0) {
		$nameErr = "\"$albumName\" is already taken. Please choose a different album name. ";
	}
	
	$thumbnailType = strtolower(pathinfo($albumThumbnail, PATHINFO_EXTENSION));
	if($thumbnailType != "png" && $thumbnailType != "jpg" && $albumThumbnail != "") {
		$fileErr = "Thumbnails must either be a .png or .jpg file.";
	}
	
	if($nameErr == "" && $fileErr == "") {
		//Form Validation Succeeded
		
		//Adds album information to album table
		$command = "INSERT IGNORE INTO albums(name, description, thumbnail, thumbnail_position, tags) 
					VALUES ('$albumName', '$albumDescrip', '$albumThumbnail', '$albumThumbPos', '$albumTags')";
		$db->query($command);
		
		//Creates new Album table
		$command = "SELECT table_id FROM albums WHERE name = '$albumName'";
		$result = $db->query($command)->fetch_assoc();
		$albumId = $result['table_id'];
		//gallery_id is the id of the image in the gallery. 
		//Album images can have unique names and descriptions in the album.
		$command = "CREATE TABLE IF NOT EXISTS album_$albumId (
			id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			file VARCHAR(250) UNIQUE NOT NULL, 
			name VARCHAR (50) UNIQUE,
			description VARCHAR(250),
			tags VARCHAR(500)
		)";
		$db -> query($command);
		
		move_uploaded_file($_FILES['thumbnail']['tmp_name'], "thumbnails/".$albumThumbnail);
		
		header("Location: album.php");
	}
	else {
		//Form Validation Failed.
		require_once('album.php');
		
		//Restores information with error messages.
		echo "
				<script>
					document.getElementById('add_album_name').value = '$albumName';
					document.getElementById('name_error').innerText = '$nameErr';
					document.getElementById('add_album_description').value = '$albumDescrip';
					document.getElementById('file_error').innerText = '$fileErr';
					document.getElementById('create_album_form').style.display = 'block';
					document.getElementById('open_album_form').style.display = 'none';
				</script>
			 ";
	}
	
	
?>