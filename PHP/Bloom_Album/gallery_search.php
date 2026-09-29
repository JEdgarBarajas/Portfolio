<?php
	/***************************************
	*									   *
	*		Deprecated File. To be 		   *
	*		 Deleted Next Commit		   *
	*									   *
	***************************************/






	require_once("db_init.php");
	require_once("php_functions.php");
	
	$command = "SELECT * FROM gallery";
	
	$command = createSearchQuery($command, $_POST['search'], $_POST['filter'], $_POST['applied_tags']);
	
	
	#$command = "Test *";
	$encodedCommand = base64_encode($command);
	header("location: gallery.php?search=".$encodedCommand);
	
	$_SESSION["gallerySearch"] = preg_replace("/[\'\"]/", "", htmlspecialchars($_POST['search']));
	$_SESSION["galleryFilter"] = htmlspecialchars($_POST['search']);
	$_SESSION["galleryTags"] = htmlspecialchars($_POST['applied_tags']);
	
?>