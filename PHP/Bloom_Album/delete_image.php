<?php
	require_once('db_init.php');
	#$db is the database initialized in db_init.php
	#$command is just string used for the SQL query in $db
	
	if(isset($_POST['deletedImages'])) {
		$imgIds = $_POST["deletedImages"];
		
		if($_POST['table'] == "gallery"){
			//Setting keys to gallery column names and table name
			$table = "gallery";
			$idKey = "image_id";
			$fileKey = "file_name";
		}
		
		else{
			//Setting keys to album specific column names and table name
			$table = $_POST['table'];
			$idKey = "id";
			$fileKey = "file";
		}
		
		$command = "DELETE FROM $table WHERE";
	
	
		for($i = 0; $i < count($imgIds); $i++) {
			if($i != 0) {
				$command .= " OR"; 
			}
			$command .= " $idKey = ".$imgIds[$i];
			if($table == "gallery") {
				deleteFromAllTables($db, $imgIds[$i]);
				$row = $db->query("SELECT $fileKey FROM $table WHERE $idKey = ".$imgIds[$i])->fetch_assoc();
				unlink("image_storage/".$row[$fileKey]);
			}
		}
		
		$db->query($command);
	}
	
	if($_POST['table'] == "gallery") {
		header("Location: gallery.php");
	}
	else {
		$aID = $_POST['table'];
		$aID = preg_replace("/[\D]/", "", $aID); //Getting Album ID number.
		header("Location: album_details.php?album=$aID");
	}
	
	//Accepts a database to remove data from, and a list of ids of images to remove.
	function deleteFromAllTables($bloomDB, $selectedId) {
		$data = $bloomDB->query("SELECT table_id FROM albums WHERE name != 'gallery' AND name != 'albums'");
		$queryTxt = "";
		$albumIds = [];
		//Creates array of all albums aside from gallery and albums
		while($row = $data->fetch_assoc()) {
			array_push($albumIds, $row['table_id']);
		}
		
		//Removing the file image from every album
		$queryTxt = "SELECT file_name FROM gallery WHERE image_id = '$selectedId'";
		$file = $bloomDB->query($queryTxt)->fetch_assoc();
		$file = $file['file_name']; //Sets file to the file name of the current image.
		
		for($j = 0; $j < count($albumIds); $j++) {
			$queryTxt = "SELECT * FROM album_".$albumIds[$j]." WHERE file = '$file'";
			$data = $bloomDB->query($queryTxt);
			if($data->num_rows != 0) {
				$queryTxt = "DELETE FROM album_".$albumIds[$j]." WHERE file = '$file'";
				$bloomDB->query($queryTxt);
			}
		}
	}
	
	
	
?>