<?php
	
	/***************************************
	*									   *
	*		Deprecated File. To be 		   *
	*		 Deleted Next Commit		   *
	*									   *
	***************************************/


	require_once('db_init.php');
	require_once('php_functions.php');
	
	$command = "SELECT * FROM albums";
	
	//Function defined in PHP_functions.php. Creates a search query based on data.
	$command = createSearchQuery($command, $_POST['search'], $_POST['filter'], $_POST['applied_tags']);
	
	$encodedCommand = base64_encode($command);
	header("location: album.php?search=".$encodedCommand);
	
	$_SESSION["albumSearch"] = preg_replace("/[\'\"]/", "", htmlspecialchars($_POST['search']));
	$_SESSION["albumFilter"] = htmlspecialchars($_POST['search']);
	$_SESSION["albumTags"] = htmlspecialchars($_POST['applied_tags']);
?>