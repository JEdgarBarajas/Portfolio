<?php 
	require_once('db_init.php');
	require_once('php_functions.php');
	require_once('js_functions.php');
	clearstatcache();
	
	$command = "SELECT * FROM albums ORDER BY table_id DESC";
	$pageNumber = 1;
	if(isset($_GET["page"])) {
		$pageNumber = $_GET["page"];
	}
	
	if(isset($_GET["search"])) {
		$command = base64_decode($_GET["search"]);
	}
?>
<!Doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Allbums - Bloom Album</title>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/x-icon" href="assets/icons/bloom_logo.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body id="album_page">
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
			<img src="assets/images/left_rose_bg.png" alt="purple colored roses">
			<div><h1>Albums</h1></div>
			<img src="assets/images/right_rose_bg.png" alt="purple colored roses">
		</header>
		<main>
			<div id="create_album_form" class='add_form'>
				<button class='close_button' id='close_album_form'></button>
				<form action="create_album.php" method="post" enctype="multipart/form-data">
					<div class="row">
						<label for='name'>Album Name:</label>
						<input type='text' name='name' id="add_album_name" maxlength="50" required>
					</div>
					<p class="error" id="name_error"></p>
					<div class="row">
						<label for='thumbnail'>Thumbnail:</label>
						<input type='file' name='thumbnail' accept=".jpg, .png" id='add_thumbnail'>
						<div id="special_info"><img id="special_thumbnail"></div>
						<input type="hidden" name="thumbnailPosition" id="thumbnail_position" value="">
					</div>
					<p class="error" id="file_error"></p>
					<div id="thumbnail_preview_container"></div>
					<label for='description'>Description:</label>
					<textarea name='description' id="add_album_description" maxlength="250" rows="5" col="50"></textarea>
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
					<input type="submit" value="Create Album">
				</form>
			</div>
			<ul class="mobile_buttons">
				<li><button id="search_button" alt="search button"></button></li>
				<li><button id="open_album_form" alt="create album button"></button></li>
				<li><button id="delete_albums" alt="delete album button"></button></li>
			</ul>
			<?php
				applySearchType("album search");
				require_once('search_module.php');
			?>
			<section id="results">
				<?php
					//Function defined in PHP functions. $db is the bloom db database initialized in db_init.PHP
					//$command is the string used for the SQL query in $db. 
					loadAlbums($db, $command, $pageNumber, 16);
				?>
			</section>
			<section id="page_num_row">
				<?php 
					#function defined in php_functions.php. Creates page numbers for results. 
					createPageButtons($db, $command, $pageNumber);
				?>
			</section>
			<?php 
				importJsInitialization();
				importJsFunctions();	
				restoreSearch("albumSearch", "albumFilter", "albumTags");
			?>
			<script>
				let thumbnails = document.getElementsByClassName(`thumbnail`);
				//let albumTitle = document.querySelectorAll(`.preview_album h2`);
				for(var j = 0; j < thumbnails.length; j++) {
					document.getElementsByClassName(`thumbnail`)[j].style.objectPosition = thumbnails[j].id;
					document.querySelectorAll(`#album_page .preview_album h2`)[j].height = "200px";
					document.querySelectorAll(`#album_page .preview_album h2`)[j].width = "200px";
				}
			
				//Open Create Album Form
				document.getElementById(`open_album_form`).addEventListener(`click`, function () {
					document.getElementById(`open_album_form`).style.display = `none`;
					document.getElementById(`create_album_form`).style.display = `block`;
				});
				
				//Close Create Album Form
				document.getElementById(`close_album_form`).addEventListener(`click`, function () {
					document.getElementById(`open_album_form`).style.display = `block`;
					document.getElementById(`create_album_form`).style.display = `none`;
				});
				
				//Thumbnail image is chosen
				document.getElementById(`add_thumbnail`).addEventListener(`change`, function() {
					let src = URL.createObjectURL(document.getElementById(`add_thumbnail`).files[0]);
					document.getElementById(`special_thumbnail`).src = src;
					document.getElementById(`special_thumbnail`).value = src;
					
				});
				
				//Create Thumbnail Previews
				document.getElementById(`special_thumbnail`).addEventListener(`load`, function (){
					let src = URL.createObjectURL(document.getElementById(`add_thumbnail`).files[0]);
					document.getElementById(`thumbnail_preview_container`).innerHTML = ``;
					let objPos = ""; //Used for Object Position Styling.
					
					let specialInfo = document.getElementById(`special_info`);
					for(var i = 0; i <= 100; i += 12.75) {
						const tempThumbnail = document.createElement(`img`);
						if(specialInfo.offsetWidth > specialInfo.offsetHeight) {
							objPos = `${i}% center`;
						}
						else if (specialInfo.offsetWidth < specialInfo.offsetHeight) {
							objPos = `center ${i}%`;
						}
						else {
							i = 100; 
							objPos = `center`;
						}
						if(i == 0) {
							document.getElementById('thumbnail_position').value = objPos;
							tempThumbnail.classList.add(`selected_thumb`);
						}
						tempThumbnail.src = src;
						tempThumbnail.style.objectPosition = objPos;
						tempThumbnail.classList.add(`thumbnail_preview`);
						tempThumbnail.id = `pre_thumbnail_${Math.floor(i)}`;
						tempThumbnail.alt = `thumbnail preview ${Math.floor(i)}%`;
						
						//Thumbnail Selector Code
						tempThumbnail.addEventListener(`click`, function() {
							let len = document.getElementsByClassName(`thumbnail_preview`).length;
							for(var j = 0; j < len; j++) {
								document.getElementsByClassName(`thumbnail_preview`)[j].classList.remove(`selected_thumb`);
							}
							document.getElementById(tempThumbnail.id).classList.add(`selected_thumb`);
							//Storing Object Position of Thumbnail
							document.getElementById(`thumbnail_position`).value = tempThumbnail.style.objectPosition;
						});
						document.getElementById(`thumbnail_preview_container`).appendChild(tempThumbnail);
					}
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
			</script>
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