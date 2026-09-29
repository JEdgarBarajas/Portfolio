<?php
	require_once('db_init.php');
	$id = $_POST['id'];
	$command = "DELETE FROM albums WHERE table_id = '$id'";
	$db->query($command);
	header("location:  album.php");
?>