<?php
	clearstatcache();
	require_once('db_init.php');
	require_once('php_functions.php');
	require_once('js_functions.php');
	$albumId = htmlspecialchars($_GET['album']);
	$command = "SELECT * FROM albums WHERE table_id = '$albumId'";
	$data = $db->query($command);
	$albumTitle = "Error";
	$albumDescrip = "";
	$albumSrc = ""; //Similar to banner, but album is an image
	$bannerSrc = ""; //Similar to banner, but banner is background image
	$bannerPos = "";
	$albumTags = [];
	
	if($data->num_rows != 0) {
		$row = $data->fetch_assoc();
		$albumTitle = $row["name"];
		$albumDescrip = $row["description"];
		$albumSrc = "thumbnails/".$row["thumbnail"];
		$bannerSrc = "url('thumbnails/".$row["thumbnail"]."')";
		$bannerPos = $row["thumbnail_position"];
		$albumTags = $row["tags"];
		$albumTags = explode(",", $albumTags);
		array_pop($albumTags);  //Removes extra tag created at the end by the final ","
	}
	
	$pageNumber = 1;
	if(isset($_GET["page"])) {
		$pageNumber = $_GET["page"];
	}
	
	$currImage = "none";
	if(isset($_GET["currImage"])) {
		$currImage = $_GET["currImage"];
	}
	
	$command = "SELECT * FROM album_$albumId ORDER BY id DESC";
	if(isset($_GET["search"])) {
		$command = base64_decode($_GET["search"]);
	}
	
	//Form Validation errors are returned as session variables.
	$nameErr = ""; 
	$name = "";
	$descrip = "";
	$albumErr = "";
	
	if(isset($_SESSION["nameErr"])) {
		//Sets variables if they exist
		$nameErr = isset($_SESSION["nameErr"])? $_SESSION["nameErr"]: "";
		$name = isset($_SESSION["savedName"])? $_SESSION["savedName"]: "";
		$descrip = isset($_SESSION["savedDescription"])? $_SESSION["savedDescription"]: "";
		
		//Removes variables afterwards.
		unset($_SESSION["nameErr"]);
		unset($_SESSION["savedName"]);
		unset($_SESSION["savedDescription"]);
		
	}
	
	if(isset($_SESSION["albumErr"])) {
		$albumErr = $_SESSION["albumErr"];
		unset($_SESSION["albumErr"]);
	}
	
	
?>
<!Doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $albumTitle; ?> - Bloom Album</title>
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
				<div><h1><?php echo $albumTitle; ?></h1></div>
			</div>
		</header>
		<main>
			<div class='delete_confirmation' id="delete_album_confirmation">
				<form method="POST" action="delete_album.php" >
					<h2>Are you Sure?</h2>
					<p class='error'>All images will be saved in the gallery.<p>
					<input type="hidden" name='id' value="<?php echo $albumId; ?>">
					<div class='row'>
						<input type='submit' value='Delete' class='true_delete_button' id='terminate_album'>
						<input type='button' value='Cancel' id='hard_cancel'>
					</div>
				</form>
			</div>
			<button class='edit_button' id='edit_album' alt='edit image button'></button>
			<div class='add_form' id='edit_album_form'>
				<button class='close_button' id='close_album_form'></button>
				<form action="edit_album.php" method="post" enctype="multipart/form-data">
					<input type="hidden" value="<?php echo $albumId;?>" name="table">
					<div class="row">
						<label for='name'>Album Name:</label>
						<input type='text' name='name' id="add_album_name" maxlength="50" value="<?php echo $albumTitle;?>"required>
					</div>
					<p class="error" id="album_err"><?php echo $albumErr;?></p>
					<div class="row">
						<label for='thumbnail'>Thumbnail:</label>
						<input type='file' name='thumbnail' accept=".jpg, .png" id='add_thumbnail'>
						<div id="special_info"><img id="special_thumbnail"></div>
						<input type="hidden" name="thumbnailPosition" id="thumbnail_position" value="<?php echo $bannerPos;?>">
					</div>
					<p class="error" id="file_error"></p>
					<div id="thumbnail_preview_container"></div>
					<label for='description'>Description:</label>
					<textarea name='description' id="add_album_description" maxlength="250" rows="5" col="50"><?php echo $albumDescrip;?></textarea>
					<div class="row">
						<label for='tags'>Album Tags:</label>
						<input type='text' id='album_tag_input' maxlength="25">
						<input type='button' value='Add Tag' id="create_album_tag">
					</div>
					<input type='hidden' name='albumTags' id='new_album_tags_storage'>
					<p class="error" id="tag_error"></p>
					<fieldset id='new_album_tags_container' class='form_tags'>
						<legend>Album Tags: </legend>
					</fieldset>
					<input type="submit" value="Update Album">
					<input type="button" value="Delete Album" id="delete_album_button" class="delete_button">
				</form>
			</div>
			<section>
				<p><?php echo $albumDescrip;?></p>
				<div id="associated_tags">
					<?php
						$albumTagHTML = "";
						for($i = 0; $i < count($albumTags); $i++) {
							$albumTagHTML .= "<div class='search_tag'><p>".$albumTags[$i]."</p></div>"; 	
						}
						echo $albumTagHTML;
					?>
				</div>
			</section>
			<ul class="mobile_buttons">
				<li><button id="search_button" alt="search button"></button></li>
				<li><a href="add_album_image_form.php?album=<?php echo $albumId;?>"><button id="add_album_image"></button></a></li>
				<li><button id="remove_album_image"></button></li>
			</ul>
			<?php 
				applySearchType("album image search", $albumId);
				require_once('search_module.php');
			?>
			<section id="results">
				<?php
					loadImages($db, $command, $pageNumber);
				?>
			</section>
			<div id="page_num_row">
				<?php	
					createPageButtons($db, $command, $pageNumber);
				?>
			</div>
			<?php
				importJsInitialization();
				importJsFunctions();
				restoreSearch("album_".$albumId."Search", "album_".$albumId."Filter", "album_".$albumId."Tags");
			?>
			<?php 
				if($currImage != "none") {
					addDetailsModule($db, "album_".$albumId, "id", $currImage, "file");
				}
			?>
		</main>
		<script>
			
			document.querySelector('#unique_album_page').style.backgroundImage = `<?php echo $bannerSrc;?>`;
			document.querySelector('#unique_album_page').style.backgroundSize = `cover`;
			document.querySelector('#unique_album_page').style.backgroundPosition = `<?php echo $bannerPos;?>`;
			document.getElementById("special_thumbnail").src = `<?php echo $albumSrc;?>`;
			//Add Delete Form EventListeners
			
			let imageCheckcircles = document.getElementsByClassName('checkcircle');
			for(var i = 0; i < imageCheckcircles.length; i++) {
				const circleId = imageCheckcircles[i].id;
				const imageId = `image_id${circleId.replace('_checkcircle', '')}`;
				document.getElementById(circleId).addEventListener('click', function() {
					if(document.getElementById(circleId).style.backgroundColor == 'white') {
						document.getElementById(circleId).style.backgroundColor = '#F88379';
						document.getElementById(imageId).selected = true;
					}
					else {
						document.getElementById(circleId).style.backgroundColor = 'white';
						document.getElementById(imageId).selected = false;
					}
				});
			}
			
			//Delete Images 
			document.getElementById('remove_album_image').addEventListener('click', function() {
				for(var i = 0; i < imageCheckcircles.length; i++) {
					document.getElementById(imageCheckcircles[i].id).style.display = 'block';
				}
				document.getElementById('delete_form').style.display = 'block';
				document.getElementById('remove_album_image').style.display = 'none';
			});
			
			//Delete Confirmation Event Listeners
			document.getElementById('delete_album_button').addEventListener('click', function() {document.getElementById('delete_album_confirmation').style.display = 'flex';});
			document.getElementById('hard_cancel').addEventListener('click', function() {document.getElementById('delete_album_confirmation').style.display = 'none';});
		</script>
		<?php
			if($nameErr != "") {
				echo "<script>
						
						//Restores all edited information, except for tags.
						document.getElementById('new_name_err').innerText = '$nameErr';
						document.getElementById('edit_name').value = '$name';
						document.getElementById('edit_description').value = '$descrip';
						document.getElementById('edit_form').style.display = 'block';
						
					  </script>";
			}
			
			if($albumErr != "") {
				echo "<script>
						  document.getElementById('edit_album_form').style.display = 'block';
					  </script>";
			}
		?>
		<script>
			//Open Create Album Form
			document.getElementById(`edit_album`).addEventListener(`click`, function () {
				document.getElementById(`edit_album_form`).style.display = `block`;
			});
			
			//Close Create Album Form
			document.getElementById(`close_album_form`).addEventListener(`click`, function () {
				document.getElementById(`edit_album_form`).style.display = `none`;
			});
			
			//Thumbnail image is chosen
			document.getElementById(`add_thumbnail`).addEventListener(`change`, function() {
				let src = URL.createObjectURL(document.getElementById(`add_thumbnail`).files[0]);
				document.getElementById(`special_thumbnail`).src = src;
				document.getElementById(`special_thumbnail`).value = src;
				
			});
			
			//Create Thumbnail Previews
			document.getElementById(`special_thumbnail`).addEventListener(`load`, function (){
				let imageSrc = "<?php echo $albumSrc; ?>";
				if(document.getElementById(`add_thumbnail`).files[0]) {
					imageSrc = URL.createObjectURL(document.getElementById(`add_thumbnail`).files[0]);
				}
				createThumbnailPreviews(imageSrc);
			});
			
			//Add Album Tag for Create Album Form
			document.getElementById('create_album_tag').addEventListener('click', function() {					
				let tagValue = document.getElementById('album_tag_input').value;
				let totalTags = document.getElementById('new_album_tags_container').childNodes.length;
				let errMessage = ``;
				
				//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
				let prevTags = valuesToString(SAVED_TAGS.get('albumForm')).toLowerCase().replace(' ', '');
				
				if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\n\t]/) &&  !prevTags.includes((`${tagValue},`).toLowerCase().replace(' ', ''))){
					createRemovableTag(tagValue, 'albumForm', 'album_tag_input', 'new_album_tags_container', 'new_album_tags_storage');
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
			
			
			
			let restoredTags = `<?php echo implode(",", $albumTags);?>`;
			restoredTags = restoredTags.split(",");
			for(var i = 0; i < restoredTags.length; i++) {
				createRemovableTag(restoredTags[i], 'albumForm', 'album_tag_input', 'new_album_tags_container', 'new_album_tags_storage');
			}
			
			//Delete Confirmation Event Listeners
			document.getElementById('delete_button').addEventListener('click', function() {document.getElementById('delete_confirmation').style.display = 'block';});
			document.getElementById('soft_cancel').addEventListener('click', function() {document.getElementById('delete_confirmation').style.display = 'none';});
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