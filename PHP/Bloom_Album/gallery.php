<?php 
	clearstatcache();
	require_once('db_init.php');
	require_once('php_functions.php');
	require_once('js_functions.php');
	
	//Default search command
	$command = "SELECT name, file_name, image_id FROM gallery ORDER BY image_id DESC";
	
	$pageNumber = 1;
	if(isset($_GET["page"])) {
		$pageNumber = $_GET["page"];
	}
	$currImage = "none";
	if(isset($_GET["currImage"])) {
		$currImage = $_GET["currImage"];
	}
	if(isset($_GET["search"])) {
		$command = base64_decode($_GET["search"]);
	}
	
	//Form Validation errors are returned as session variables.
	$imgNameErr = ""; 
	$editName = "";
	$editDescrip = "";
	
	if(isset($_SESSION["nameErr"])) {
		//Sets variables if they exist
		$imgNameErr = isset($_SESSION["nameErr"])? $_SESSION["nameErr"]: "";
		$editName = isset($_SESSION["savedName"])? $_SESSION["savedName"]: "";
		$editDescrip = isset($_SESSION["savedDescription"])? $_SESSION["savedDescription"]: "";
		
		//Removes variables afterwards.
		unset($_SESSION["nameErr"]);
		unset($_SESSION["savedName"]);
		unset($_SESSION["savedDescription"]);
		
	}
	
	
?>
<!Doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Gallery - Bloom Album</title>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/x-icon" href="assets/icons/bloom_logo.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body id="gallery_page">
		<nav>
			<button id="nav_menu_button" alt="open nav button">
			</button>
			<div id="bloom_logo">
				<a href="index.php"><img src="assets/icons/photo_bloom.png" alt="home button/multi-colored rose"></a>
			</div>
			<ul>
				<button class="close_button" id="close_nav_button"></button>
				<li><a href="index.php" class="home_link">Home</a></li>
				<li><a href="gallery.php" class="gallery_link">Gallery</a></li>
				<li><a href="album.php" class="album_link">Albums</a></li>
				<li><a href="about.html" class="about_link">About</a></li>
			</ul>
		</nav>
		<script>			
			document.getElementById("nav_menu_button").addEventListener(`click`, function () {
				document.getElementById("nav_menu_button").style.display = "none";
				document.querySelector("nav ul").style.display = "flex";
				document.getElementById(`bloom_logo`).style.position = `fixed`;
			});
			
			document.getElementById("close_nav_button").addEventListener(`click`, function () {
				document.getElementById("nav_menu_button").style.display = "block";
				document.querySelector("nav ul").style.display = "none";
					document.getElementById(`bloom_logo`).style.position = `absolute`;
			});
			
			document.getElementById(`bloom_logo`).style.position = `absolute`;
			if(window.outerWidth < 1210) {
				document.querySelector(`nav ul`).style.display = `none`;
				document.getElementById(`nav_menu_button`).style.display = `block`;
				document.getElementById(`close_nav_button`).style.display = `block`;
				if(window.outerWidth < 700) {
					//Viewport doesn't affect JS, but does affect styling. Makes pixels easier to read. 
					document.querySelectorAll(`meta`)[1].setAttribute(`content`, `width=device-width, initial-scale=0.75`);
				}
			}
			else {
				document.querySelector(`nav ul`).style.display = `flex`;
				document.getElementById(`close_nav_button`).style.display = `none`;
				document.getElementById(`nav_menu_button`).style.display = `none`;
				document.querySelectorAll(`meta`)[1].setAttribute(`content`, `width=device-width, initial-scale=1.0`);
			}
			
			
			window.addEventListener(`resize`, function() {
				document.getElementById(`bloom_logo`).style.position = `absolute`;
				if(window.outerWidth < 1210) {
					document.querySelector(`nav ul`).style.display = `none`;
					document.getElementById(`nav_menu_button`).style.display = `block`;
					document.getElementById(`close_nav_button`).style.display = `block`;
					if(window.outerWidth < 700) {
						//Viewport doesn't affect JS, but does affect styling. Makes pixels easier to read. 
						document.querySelectorAll(`meta`)[1].setAttribute(`content`, `width=device-width, initial-scale=0.75`);
					}
				}
				else {
					document.querySelector(`nav ul`).style.display = `flex`;
					document.getElementById(`close_nav_button`).style.display = `none`;
					document.getElementById(`nav_menu_button`).style.display = `none`;
					document.querySelectorAll(`meta`)[1].setAttribute(`content`, `width=device-width, initial-scale=1.0`);
				}
			});
		</script>
		<header>
			<img src="assets/images/left_rose_bg.png" alt="blue colored roses">
			<div><h1>Gallery</h1></div>
			<img src="assets/images/right_rose_bg.png" alt="blue colored roses">
		</header>
		<main>
		
			<div id="upload_form" class='add_form'>
				<!-- Close Button -->
				<button class="close_button" id="close_upload" alt="close_button"></button>
				<form method="post" action="add_image.php" enctype="multipart/form-data">
					<!--Name, File, and Description-->
					<label for="name">Name:</label>
					<input type="text" name="name" id="name" size="50" maxlength="50" required>
					<p class="error" id="name_err"></p> <!-- Error Message for Name -->
					<input type="file" name="file" id="file" required>
					<p class="error" id="file_err"></p> <!-- Error Message for File -->
		
					<label for="description">Description:</label>
					<textarea cols="50" rows="5" name="description" id="description" maxlength="250"></textarea>
					
					<!--Tags-->
					<fieldset>
						<label for="tags">Tags:</label>
						<input type="text" size="25" id="tag_input" maxlength="25"> 
						<input type="button" value="Add Tag" id="create_tag_button">
						<p class="error" id="tag_error"></p>
						<p class="caption">
							You can only use up to 20 tags per image. Tags are case-insensitive. 
							Tags are also space insensitive. Tag names must be alpha-numeric. 
							You may include a "." in your tag name.
							<br>Ex. PHOTO = Photo |	Bad Days = BadDays 
						</p>
						<input type="hidden" name="tags" id="new_tag_storage" value="">
						<div id="new_tags_container" class='form_tags'></div>
					</fieldset>
					<input type="submit" id="submit">
				</form>
			</div>
			<!-- If Image is Selected -->
			<ul class='mobile_buttons'>
				<li><button id="search_button" alt="search button"></button></li>
				<li><button id="upload_button" alt="upload button"></button></li>
				<li><button id="delete_images" alt="delete images button"></button></li>
			</ul>
			<?php
				applySearchType("gallery search");
				require_once('search_module.php');
			?>
			<section id="results">
				<?php
					#loadImages is a function from the php_functions.php to load the first 16 images.
					#$db is the database established in db_init.php.
					loadImages($db, $command, $pageNumber);
					
				?>
			</section>
			<section id="page_num_row">
				<?php 
					#function defined in php_functions.php. Creates page numbers for results. 
					createPageButtons($db, $command, $pageNumber);
				?>
			</section>
			<?php 
				#addGalleryJs is a function from js_functions.php to add js script into a page.
				importJsInitialization();
				importJsListeners();
				importJsFunctions();
				restoreSearch("gallerySearch", "galleryFilter", "galleryTags");
			?>
			<?php 
				if($currImage != "none") {
					addDetailsModule($db, "gallery", "image_id", $currImage, "file_name");
					
					//If there is a error in the edit form
					if($imgNameErr != "") {
						echo "
							<script>
								
								//Restores all edited information, except for tags.
								document.getElementById('new_name_err').innerHTML = '$imgNameErr';
								document.getElementById('edit_name').value = '$editName';
								document.getElementById('edit_description').value = '$editDescrip';
								document.getElementById('edit_form').style.display = 'block';
								
							</script>
						 ";
					}
				}
			?>
		</main>
		<footer>
			<div class="disclaimer">
				<p>
					All icon assets were created by the developer using Photopea using
					basic shapes. Any featured images that does not belong to the 
					developer will have the proper attribution next to it. The developer
					is not responsible for images, trademarks, or copyright infringements
					posted by users.
				</p>
			</div>
			<div>
				<address>
					<p>
						Created by: Josiah E. Barajas<br>
						Email: <a href="mailto:jedbar11@gmail.com">Jedbar11@gmail.com</a><br>
						Other works: <a href="https://github.com/JEdgarBarajas/Portfolio">GitHub Portfolio</a><br>
						LinkedIn: <a href="https://www.linkedin.com/in/josiahbarajas/">Josiah Barajas</a><br>
					</p>
				</address>
			</div>
		</footer>
	</body>
</html>
<?php 
	$db->close();
?>