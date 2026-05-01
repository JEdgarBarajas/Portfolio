<?php
	require('database.php');
	$name = filter_input(INPUT_POST, 'category');
	
	$query = "DELETE FROM categories WHERE categoryName = :category";
	$statement = $db->prepare($query);
	$statement->bindValue(':category', $name);
	$statement->execute();
	$statement->closeCursor();
	include("category_list.php");
	
?>
