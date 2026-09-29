<?php 
	clearstatcache();
	require_once("db_init.php");
	require_once("php_functions.php");
	require_once("js_functions.php");
	
	$albumId = "";
	$albumTitle = "Error";
	if(isset($_GET['album'])){
		$albumId = htmlspecialchars($_GET['album']);
		$command = "SELECT * FROM albums WHERE table_id = '$albumId'";
		$data = $db->query($command);
		if($data->num_rows != 0) {
			$row = $data->fetch_assoc();
			$albumTitle = $row['name'];
		}
	}
	
	//Gets Page Number
	$command = "SELECT * FROM gallery ORDER BY image_id DESC";
	$pageNumber = 1;
	if(isset($_GET["page"])) {
		$pageNumber = $_GET["page"];
	}
	
	//Decodes Search Query if exists
	$searchQuery = "";
	if(isset($_GET["search"])) {
		$command = base64_decode($_GET["search"]);
		$searchQuery = $_GET["search"];
	}
	
	/*If an form validation fails, the error will be stored in session variables and retrieved here.*/
	$imageId = "";
	$name = "";
	$descrip = "";
	$file = "";
	$tags = "";
	$fileErr = ""; 
	$nameErr = ""; 
	if(isset($_SESSION["fileErr"]) || isset($_SESSION["nameErr"])) {
		//Sets variables if they exist
		$fileErr = isset($_SESSION["fileErr"])? $_SESSION["fileErr"]: ""; 
		$nameErr = isset($_SESSION["nameErr"])? $_SESSION["nameErr"]: "";
		$name = isset($_SESSION["savedName"])? $_SESSION["savedName"]: "";
		$descrip = isset($_SESSION["savedDescription"])? $_SESSION["savedDescription"]: "";
		$tags = isset($_SESSION["savedTags"])? $_SESSION["savedTags"]: "";
		
		//Removes variables afterwards.
		unset($_SESSION["fileErr"]);
		unset($_SESSION["nameErr"]);
		unset($_SESSION["savedName"]);
		unset($_SESSION["savedDescription"]);
		unset($_SESSION["savedTags"]);
		
	}
?>
<!Doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Add to Album Form - Bloom Album</title>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/x-icon" href="assets/icons/bloom_logo.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body id="unique_album_page">
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
			<div>
				<h1><?php echo $albumTitle;?></h1>
			</div>
		</header>
		<main>
			<!-- The choice will edit the form. Add a gallery image is default -->
			<div id="choice">
				<h2>Which Type of Image is being added to Album?</h2>
				<div class="row">
					<button class='semi_circle_button current' id="add_gallery">Gallery Image</button>
					<button class='semi_circle_button' id="upload_new">New Image</button>
				</div>
			</div>
			<div id="add_album_image_form">
				<?php
					applySearchType("album image form", $albumId);
					require_once('search_module.php');
				?>
				<section class='select_form'>
					<section id="results" class='scroll_section'>
						<?php
							#loadImages is a function from the php_functions.php to load the first 16 images.
							#$db is the database established in db_init.php.
							loadImages($db, $command, $pageNumber, 24);
						
						?>
					</section>
					<div id="page_num_row">
						<?php 
							#function defined in php_functions.php. Creates page numbers for results. 
							createPageButtons($db, $command, $pageNumber);	
						?>
					</div>
				</section>
				<?php
				
					if(isset($_GET['currImage'])){
						$command = "SELECT * FROM gallery WHERE image_id = '".$_GET['currImage']."'";
						$row = $db->query($command);
						if($row->num_rows != 0) {
							$row = $row->fetch_assoc();
							$imageId = $row["image_id"];
							$name = $row["name"];
							$descrip = $row["description"];
							$file = $row["file_name"];
							$tags = $row["tags"];
						}
					}
				?>
				<section class="web_module" id="album_image_details">
					<form action="add_to_album.php" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="album_id" value="<?php echo $albumId;?>">
						<p class="caption">Images added to an album can have an album-specific version of its name, description and tags unique to that to album.</p>
						<label for="name">Album Version Name:</label>
						<input type="text" name="name" id="name" size="50" maxlength="50" value="<?php echo $name;?>" required>
						<p class="error" id="name_err"><?php echo $nameErr;?></p>
						<div id="special_info" class='float'><img id="special_thumbnail" src="image_storage/<?php echo $file;?>"><input type='hidden' name='file' id='file' value='<?php echo $file;?>'></div>
						<p class="error" id="file_err"><?php echo $fileErr;?></p>
						<label for="description">Album Version Description:</label>
						<textarea cols="50" rows="5" name="description" id="description" maxlength="250"><?php echo $descrip;?></textarea>
						<div class="web_row">
							<label for='tags'>Album Version Tag:</label>
							<div class="row">
								<input type='text' id='album_tag_input' maxlength="25">
								<input type='button' value='Add Tag' id="add_album_image_tag">
							</div>
						</div>
						<input type='hidden' name='albumTags' id='album_image_tags_storage' value="">
						<p class="error" id="tag_error"></p>
						<fieldset id='album_image_tags_container' class='form_tags'>
							<legend>Album Version Tags: </legend>
						</fieldset>
						<input type="submit" id="submit">
					</form>
				</section>
			</div>
		</main>
		<?php //echo "add_album_image_form.php?search=".$_GET["search"]."&album=$albumId&page=$pageNumber&currImage=".$_GET["currImage"];?>
		<?php
			importJsInitialization();
			importJsFunctions();
			restoreSearch("selectSearch", "selectFilter", "selectTags");
		?>
		<script>
			function removeSelectedClass() {
				let imageSelection = document.querySelectorAll("#unique_album_page .preview_image img");
				for(var i = 0; i < imageSelection.length; i++) {
					document.querySelectorAll("#unique_album_page .preview_image img")[i].classList.remove("selected");
				}
			}
			
			function addSelectedClass() {
				let imageSelection = document.querySelectorAll("#unique_album_page .preview_image img");
				for(var i = 0; i < imageSelection.length; i++) {
					let path = imageSelection[i].src.split("/");
					let selectionSrc = path[path.length - 2] + "/" + path[path.length - 1];
					selectionSrc = selectionSrc.replaceAll("%20", " ");
					if(selectionSrc == "image_storage/<?php echo $file;?>") {
						document.querySelectorAll("#unique_album_page .preview_image img")[i].classList.add("selected");
					}
				}
			}
			
			//Restoring tags to the image_details
			let baseTxt = `<?php if(isset($tags)) {echo $tags;} ?>`;
			let tagList = baseTxt.split(',');
			tagList.pop();
			
			for(var j = 0; j < tagList.length; j++) {
				createRemovableTag(tagList[j], 'editForm', 'album_tag_input', 'album_image_tags_container', 'album_image_tags_storage');
			}
			
			//Adding Select Class to image selected
			removeSelectedClass();
			addSelectedClass();
			
			//Add tag to search module
			document.getElementById('apply_tag_button').addEventListener('click', function() {
				
				let tagValue = document.getElementById('tag_search').value;
				let totalTags = document.getElementById('applied_tags_container').childNodes.length;
				let errMessage = ``;
				
				//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
				let prevTags = valuesToString(SAVED_TAGS.get('searchModule')).toLowerCase().replace(' ', '');
				
				if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\n\t]/) &&  !prevTags.includes((`${tagValue},`).toLowerCase().replace(' ', ''))){
					
					createRemovableTag(tagValue, 'searchModule', 'tag_search', 'applied_tags_container', 'applied_tags_storage');

				}
				else {
					if(tagValue == '') {
						errMessage += `Tag names cannot be empty<br>`;
					}
					if(totalTags >= 20) {
						errMessage += `You can only have 20 tags per image<br>`;
					}
					if(tagValue.match(/[^0-9a-zA-Z\.\s]/) || tagValue.match(/[\n\t]/)) {
						errMessage += `Tag names only accepts alpha-numeric, space, and "." characters .<br>`;
					}
					if(prevTags.includes((`${tagValue},`).toLowerCase().replace(' ', ''))) {
						errMessage += `You already have a tag with this name. Tags are case and space insensitive. Ex. PHOTO = photo | Bad Day = BadDay`;
					}
					
				}
				
				document.getElementById('tag_error').innerHTML = errMessage;
			});
			
			//Upload Image Button EventListener
			document.getElementById(`upload_new`).addEventListener('click', function () {
				document.getElementById(`add_gallery`).classList.remove('current');
				document.getElementById(`upload_new`).classList.add('current');
				document.getElementsByClassName(`select_form`)[0].style.display = "none";
				document.getElementById(`special_info`).innerHTML = "<input type='file' name='file' id='file' accept='.jpg, .png, .gif' required><input type='hidden' name='file' id='file' value=''>";
				document.getElementById(`special_info`).classList.remove(`float`);
				removeSelectedClass();
				document.getElementById(`name`).value = "";
				document.getElementById(`description`).innerText = "";
				document.getElementById(`album_image_tags_container`).innerHTML = "<legend>Album Version Tags: </legend>";
				document.getElementById(`album_image_tags_storage`).value = "";
				document.getElementById(`search_module`).style.display = "none";
			});
			
			//Add Gallery Image Button EventListener
			document.getElementById(`add_gallery`).addEventListener('click', function () {
				document.getElementById(`add_gallery`).classList.add('current');
				document.getElementById(`upload_new`).classList.remove('current');
				document.getElementsByClassName(`select_form`)[0].style.display = "block";
				document.getElementById(`special_info`).innerHTML = "<img id='special_thumbnail' src='image_storage/'";
				document.getElementById(`special_info`).classList.add(`float`);
				document.getElementById(`search_module`).style.display = "block";
			});
			
			//Add a tag to add form
			document.getElementById('add_album_image_tag').addEventListener('click', function() {
				
				let tagValue = document.getElementById('album_tag_input').value;
				let totalTags = document.getElementById('album_image_tags_container').childNodes.length;
				let errMessage = ``;
							
				//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
				let prevTags = valuesToString(SAVED_TAGS.get('uploadForm')).toLowerCase().replace(' ', '');
							
				if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\n\t]/) &&  !prevTags.includes((`\${tagValue},`).toLowerCase().replace(' ', ''))){
					createRemovableTag(tagValue, 'uploadForm', 'album_tag_input', 'album_image_tags_container', 'album_image_tags_storage');
				}
				else {
					if(tagValue == '') {
						errMessage += `Tag names cannot be empty<br>`;
					}
					if(totalTags >= 20) {
						errMessage += `You can only have 20 tags per image<br>`;
					}
					if(tagValue.match(/[^0-9a-zA-Z\.\s]/) || tagValue.match(/[\n\t]/)) {
						errMessage += `Tag names only accepts alpha-numeric, space, and "." characters .<br>`;
					}
					if(prevTags.includes((`${tagValue},`).toLowerCase().replace(' ', ''))) {
						errMessage += `You already have a tag with this name. Tags are case and space insensitive. Ex. PHOTO = photo | Bad Day = BadDay`;
					}
					
				}
				
				document.getElementById('tag_error').innerHTML = errMessage;
			});
				
			document.getElementById("submit").addEventListener("click", function() {
				if(document.getElementById("special_thumbnail") && document.getElementById("special_thumbnail").src == "image_storage/") {
					preventDefault();
				}
			});
				
		</script>
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