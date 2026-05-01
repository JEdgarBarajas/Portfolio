<?php
	require('database.php');
	$name = filter_input(INPUT_POST, 'category');
	if($name == NULL) {
		$error = "Invalid category name. Must be under 255 Characters and not another category..";
		include('error.php');
	}
	else {
		$query = "INSERT INTO categories (categoryName)";
		$query = $query . "VAlUES (:categoryName)";
		$statement = $db->prepare($query);
		$statement->bindValue(':categoryName', $name);
		$statement->execute();
		$statement->closeCursor();
		
		include('category_list.php');
	}
	
?>
