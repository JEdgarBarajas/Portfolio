<?php
	require_once('db_init.php');
	require_once('php_functions.php');
	
	$searchType = "none";
	$destination = ""; //The PHP file that the search is sent to.
	$table = "";
	$primaryKey = "";
	$sessionSearch = ""; //Key used to store a session variable at the end.
	$sessionFilter = ""; //Key used to store a session variable at the end.
	$sessionTags = ""; //Key used to store a session variable at the end.
	
	//Search type determine where the search query goes.
	if(isset($_POST['search_type'])) {
		$searchType = htmlspecialchars($_POST['search_type']);
		switch ($searchType) {
			case "gallery search": 
				$destination = "gallery.php?";
				$table = "gallery";
				$sessionSearch = "gallerySearch";
				$sessionFilter = "galleryFilter";
				$sessionTags = "galleryTags";
				$primaryKey = "image_id";
				break;
			case "album search":
				$destination = "album.php?";
				$table = "albums";
				$sessionSearch = "albumSearch";
				$sessionFilter = "albumFilter";
				$sessionTags = "albumTags";
				$primaryKey = "table_id";
				break;
			case "album image form":
				$destination = "add_album_image_form.php?album=".$_POST['album_id'];
				$table = "gallery";
				$sessionSearch = "selectSearch";
				$sessionFilter = "selectFilter";
				$sessionTags = "selectTags";
				$primaryKey = "image_id";
				break;
			case "album image search":
				$destination = "album_details.php?album=".$_POST['album_id'];
				$table = "album_".$_POST['album_id'];
				$sessionSearch = $table."Search";
				$sessionFilter = $table."Filter";
				$sessionTags = $table."Tags";
				$primaryKey = "id";
				break;
			default: 
				$searchType = "none";
				break;
		}
	}
	
	if($searchType != "none") {
		$command = "SELECT * FROM $table";
		
		if($searchType == "album search") {
			$command .= " WHERE name != 'albums' AND name != 'gallery'";
		}
		
		//Function defined in PHP_functions.php. Creates a search query based on data.
		$command = createSearchQuery($command, $primaryKey, $_POST['search'], $_POST['filter'], $_POST['applied_tags']);
		
		/* $_POST['search'], $_POST['filter'], $_POST['applied_tags'] are part of every search module. */
		$_SESSION[$sessionSearch] = preg_replace("/[\'\"]/", "", htmlspecialchars($_POST['search']));
		$_SESSION[$sessionFilter] = htmlspecialchars($_POST['filter']);
		$_SESSION[$sessionTags] = htmlspecialchars($_POST['applied_tags']);
		
		$encodedCommand = base64_encode($command);
		header("location: $destination"."&search=".$encodedCommand);
	}


?>