<?php
require_once('database.php');

// Get all categories
$query = 'SELECT * FROM categories
          ORDER BY categoryID';
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" href="main.css" />
</head>

<!-- the body section -->
<body>
<header><h1>Product Manager</h1></header>
<main>
    <h1>Category List</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>&nbsp;</th>
        </tr>
        
        <!-- add code for the rest of the table here -->
		<?php 
			foreach($categories as $row) {
				$innerHtml = "<tr>";
				$innerHtml = $innerHtml . "<td>$row[1]</td>";
				$innerHtml = $innerHtml . "<td><form action='delete_category.php' method='post'>";
				$innerHtml = $innerHtml . "<input type='hidden' name='category' value='$row[1]'><input type='submit' value='Delete'></form></td>";
				$innerHtml = $innerHtml . "</tr>";
				echo "$innerHtml";
			}
		?>
    
    </table>

    <h2>Add Category</h2>
    
    <!-- add code for the form here -->
	<?php
		$addCategory = "<form id='add_product_form' action='add_category.php' method='post'>";
		$addCategory = $addCategory . "<label>Name:</label>";
		$addCategory = $addCategory . "<input type='text' name='category'><br>";
		$addCategory = $addCategory . "<label>&nbsp;</label>";
		$addCategory = $addCategory . "<input type='submit' value='Add'>";
		$addCategory = $addCategory . "</form>";
		echo $addCategory;
	?>
    
    <br>
    <p><a href="index-6.php">List Products</a></p>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>