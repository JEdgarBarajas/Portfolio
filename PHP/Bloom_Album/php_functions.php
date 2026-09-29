<?php
	//List of functions that can be called upon in any PHP file.
	require_once('db_init.php');
	#$db is the MySQL database innitialized in db_init.php 
	#$command is the SQL query used in mysqli $db object
	
	
	//Keeps URL information to be reference when needed. 
	//Structured base -> search -> page -> currImage
	//currImage is not kept since it is meant to disappear
	$urlArray = array("base"=> basename($_SERVER["SCRIPT_NAME"])."?", "search"=> "", "page"=>"", "album"=>"");
	
	if(isset($_GET["search"])) { 
		$urlArray["search"] = "search=".$_GET["search"]; 
	}
	
	if(isset($_GET["page"])) {
		$urlArray['page'] = "page=".$_GET["page"];
	}

	if(isset($_GET["album"])) {
		$urlArray['album'] = "album=".$_GET["album"];
	} 
	
	//Add an extra "&" symbol if needed. 
	if($urlArray['page'] != "" && $urlArray["search"] != "") {
		$urlArray['search'] .= "&"; 
	}
	
	
	/*********************************************************
		$bloomDB refers to the bloomDB database created in db_init.php
		$queryTxt is an optional parameter. By default it will load the newest 
		images first, but can be changed based on the user's search preferences.
		$currPage is the current page.
		$img_per_page is the amount of images per page.
	*********************************************************/
	function loadImages($bloomDB, $queryTxt, $currPage=1, $img_per_page=16) {
		global $urlArray;
		$table = explode(" ", $queryTxt);
		$table = $table[3];
		$html = "";
		$queryTxt = "$queryTxt LIMIT $img_per_page";
		if($currPage > 1) {
			$offset = ($currPage - 1) * $img_per_page;
			$queryTxt = "$queryTxt OFFSET $offset";
		}
		
		//Setting the column names depending on the table
		//Gallery column names
		if(preg_match("/gallery/", $queryTxt)) {
			$table = "gallery";
			$nameKey = 'name';
			$fileKey = 'file_name';
			$idKey = 'image_id';
		}
		
		//Album Image column names
		else if(!preg_match("/gallery/", $queryTxt)) {
			$table = explode(" ", $queryTxt);
			$table = $table[3]; //Gets the table name
			$nameKey = 'name';
			$fileKey = 'file';
			$idKey = 'id';
		}
		
		//Creating the images
		$data = $bloomDB->query($queryTxt); 
		while($row = $data->fetch_assoc()) {
			$f = $row[$fileKey];
			$n = $row[$nameKey];
			$id = $row[$idKey];
			$html .= "<div class='preview_image'><button id='".$id."_checkcircle' class='checkcircle'></button><a href='".$urlArray["base"].$urlArray["search"]."&".$urlArray["album"]."&".$urlArray["page"]."&currImage=$id'><img src='image_storage/$f' alt='$n'></a></div>";
		}
		
		//Creating delete form
		$data = $bloomDB->query($queryTxt); 
		$deleteForm = "";
		$deleteForm .= "<form action='delete_image.php' method='post' id='delete_form'>";
		$deleteForm .= "<input type='hidden' name='table' value='$table'>";
		$deleteForm .= "<select multiple name='deletedImages[]'>";
		while($row = $data->fetch_assoc()){
			$id = $row[$idKey];
			$deleteForm .= "<option value='$id' id='image_id$id'>$id</option>";
		}
		$deleteForm .= "</select>";
		
		$deleteForm .= "<div class='row' id='delete_row'>
							<input type='button' value='Delete' id='delete_button' class='delete_button'>
							<a href='".$urlArray['base'].$urlArray["album"]."&".$urlArray['search']."&".$urlArray['page']."'>
								<input type='button' value='Cancel'>
							</a>
						</div>";
				
		$deleteForm .= "<div id='delete_confirmation' class='delete_confirmation'>
					<h2>Are you Sure?</h2>";
		if($table == "gallery") {
			$deleteForm .= "<p class='error'>This will delete this image from all albums.<p>";
		}
		$deleteForm .= "<input type='submit' value='Delete' id='true_delete_button' class='true_delete_button'>
					<input type='button' value='Cancel' id='soft_cancel'>
				  </div>";
		
		$deleteForm .= "</form>";
		$deleteJs = "";
		$html = $html.$deleteForm.$deleteJs;
		
		echo $html;
	}
	
	
	function createPageButtons($bloomDB, $queryTxt, $page, $img_per_page=16) {
		global $urlArray;
		$results = $bloomDB->query($queryTxt);
		$imgLeft = $results->num_rows;
		$html = "";
		if($imgLeft > $img_per_page) {
			$j = 1;
			for($i = $imgLeft; $i > 0; $i -= $img_per_page) {
				$class = "page_button";
				if($j == $page) {
					$class .= " current";
				}
				$html .= "<a href=\"".$urlArray["base"].$urlArray["album"]."&".$urlArray["search"]."&"."page=$j\"><button class=\"$class\">$j</button></a>";
				$j++;
			}
		}
		echo $html;
	}
	
	//Sister function to loadImages()
	function loadAlbums($bloomDB, $queryTxt, $currPage=1, $albums_per_page=16) {
		$html = "";
		$queryTxt = "$queryTxt LIMIT $albums_per_page";
		if($currPage > 1) {
			$offset = ($currPage - 1) * $albums_per_page;
			$queryTxt = "$queryTxt OFFSET $offset";
		}
		
		//Creating the images
		$data = $bloomDB->query($queryTxt); 
		while($row = $data->fetch_assoc()) {
			$n = $row['name'];
			$id = $row['table_id'];
			$src = $row['thumbnail'];
			$pos = $row['thumbnail_position'];
			if($n != 'gallery' && $n != 'albums') {
				$html .= "
					<a href='album_details.php?album=$id'>
					<div class='preview_album' id='album_id$id'>
						<div class='preview_title'><h2>$n</h2></div>
						<button id='".$id."_checkcircle' class='checkcircle'></button>
						<img src='thumbnails/$src' class='thumbnail' id='$pos'>
					</div>
					</a>";
			}
		}
		
		echo $html;
	}
	
	function createSearchQuery($queryTxt, $id, $search, $filter, $tags) {
		$search = htmlspecialchars($_POST["search"]);
		$search = str_replace("'", "\'", $search);
		$search = str_replace('"', '\"', $search);
		$filter = htmlspecialchars($_POST["filter"]);
		$tags = htmlspecialchars($_POST["applied_tags"]);
		
		if($search != "") {
			if(preg_match('/WHERE/', $queryTxt)) {
				$queryTxt .= " AND name LIKE '%$search%'";
			}
			else {
				$queryTxt .= " WHERE name LIKE '%$search%'";
			}
		}
		
		//Query to check tags
		if($tags != "") {
			$tagArray = explode(",", $tags);
			array_pop($tagArray); //Removes extraneous value at end.
			if(preg_match('/WHERE/', $queryTxt)) {
				$queryTxt .= " AND tags LIKE "; //If there is a search value.
				
			}
			else {
				$queryTxt .= " WHERE tags LIKE "; //If there is no other search value.
			}
			
			for($i = 0; $i < count($tagArray); $i++) {
				if($i != 0) {
					$queryTxt .= " AND tags LIKE "; //If not first. Include comma to space out values.
				}
				$queryTxt .= "'%".$tagArray[$i].",%'";
			}
			$queryTxt .= "";
			
		}
		
		switch($filter) {
			case "a-z":
				$queryTxt .= " ORDER BY name ASC";
				break;
			case "z-a":
				$queryTxt .= " ORDER BY name DESC";
				break;
			case "oldest first":
				$queryTxt .= " ORDER BY $id ASC";
				break;
			default :
				$queryTxt .= " ORDER BY $id DESC";
				break;
		}
		
		return $queryTxt;
	}
	
	//Search module html that is made to be easily inserted and reused.
	//Contains all functions, script relating to search forms.
	$searchType = "";
	$specialAlbumId = "";
	$sessionSearch = ""; //Special Keys used to access the specific search session variable.
	$sessionFilter = ""; //Special Keys used to access the specific filter session variable.
	$sessionTags = ""; //Special Keys used to access the specific tags session variable.
	
	/*
		There are currently four types of search types:
		 - gallery search
		 - album search
		 - album image form
	     - album image search
	*/
	
	function applySearchType($type, $searchId = "none") {
		global $searchSearch, $sessionFilter, $sessionTags, $specialAlbumId, $searchType;
		$searchType = $type; 
		$specialAlbumId = $searchId;
		
		switch($searchType) {
			case "gallery search":
				$sessionSearch = "gallerySearch";
				$sessionFilter = "galleryFilter";
				$sessionTags = "galleryTags";
				break;
			case "album search":
				$sessionSearch = "albumSearch";
				$sessionFilter = "albumFilter";
				$sessionTags = "albumTags";
				break;
			case "album image search":
				$sessionSearch = "album_".$searchId."Search";
				$sessionFilter = "album_".$searchId."Filter";
				$sessionTags = "album_".$searchId."Tags";
				break;
			case "album image form":
				$sessionSearch = "selectSearch";
				$sessionFilter = "selectFilter";
				$sessionTags = "selectTags";
				break;
			default: 
				break;
		}
	} 
	
	
	//Accepts database, column names, table, and id to get image information
	function addDetailsModule($bloomDB, $table, $idKey, $currId, $fileKey) {
		
		//Gets the image information with the image_id
		$imageDetails = ($bloomDB->query("SELECT * FROM $table WHERE $idKey = $currId"))->fetch_assoc();
	
		$imgName = $imageDetails["name"];
		$fileName = $imageDetails[$fileKey];
		$imgDescrip = $imageDetails["description"];
		
		$assocTags = [];  //Stands for Associated Tags.
		if($imageDetails["tags"] != "") {
			$assocTags = explode(",", $imageDetails["tags"]);
			array_pop($assocTags); //Gets rid of extra empty tag created by the last ",".
		}
		$tagsHtml = "";
		for($i = 0; $i < count($assocTags); $i++) {
			$tagsHtml .= "<div class='search_tag'><p>".$assocTags[$i]."</p></div>"; 	
		}
		
		//tags for edit form. 
		$editTagJs = "";
		for($j = 0; $j < count($assocTags); $j++) {
			$editTagJs .= "createRemovableTag('".$assocTags[$j]."', 'editForm', 'edit_tag_input', 'edited_tags_container', 'edited_tag_storage');\n";
		}
		
		$detailsHtml = "
			<div id='edit_form' class='add_form'>
				<!-- Close Button -->
				<button class='close_button' id='close_edit_form' alt='close_button'></button>
				<form method='post' action='edit_image.php'>
					<!--Name, File, and Description-->
					<input type='hidden' value='$table' name='table'>
					<input type='hidden' value='$currId' name='id'>
					<label for='name'>Name:</label>
					<input type='text' name='name' id='edit_name' size='50' maxlength='50' required>
					<p class='error' id='new_name_err'></p> <!-- Error Message for Name -->
					<img src='image_storage/$fileName' alt='$imgName' class='image'>
		
					<label for='description'>Description:</label>
					<textarea cols='50' rows='5' name='description' id='edit_description' maxlength='250'></textarea>
					
					<!--Tags-->
					<fieldset>
						<label for='tags'>Tags:</label>
						<input type='text' size='25' id='edit_tag_input' maxlength='25'> 
						<input type='button' value='Add Tag' id='add_edited_tag'>
						<p class='error' id='edited_tag_error'></p>
						<p class='caption'>
							You can only use up to 20 tags per image. Tags are case-insensitive. 
							Tags are also space insensitive. Tag names must be alpha-numeric. 
							You may include a "." in your tag name.
							<br>Ex. PHOTO = Photo |	Bad Days = BadDays 
						</p>
						<input type='hidden' name='tags' id='edited_tag_storage' value=''>
						<div id='edited_tags_container' class='form_tags'></div>
					</fieldset>
					<input type='submit' id='submit'>
				</form>
			</div>
			<figure id='image_details'>
				<button class='edit_button' id='edit_image' alt='edit image button'></button>
				<button class='close_button' id='close_details' alt='close image details button'></button>
				<h3>$imgName</h3>
				<img src='image_storage/$fileName' alt='$imgName'>
				<figcaption>
					<p>$imgDescrip</p>
				</figcaption>
				<h4>Tags:</h4>
				<div id='associated_tags'><p>$tagsHtml</p></div>
			</figure>
			<!-- Blocks upload button and upload form -->
			<script>
				//Close upload form and prevents upload button from being used.
				//document.getElementById('upload_form').style.display = 'none';
				
				document.getElementById('close_details').addEventListener('click', function() {
					document.getElementById('image_details').style.display = 'none';
					//document.getElementById('upload_button').style.display = 'block';
				});
			</script>
			<script>
				
				//Restores file information
				document.getElementById('edit_name').value = '$imgName';
				document.getElementById('edit_description').value = '$imgDescrip';
				$editTagJs
				
				//Add a tag to edit form
				document.getElementById('add_edited_tag').addEventListener('click', function() {
					
					let tagValue = document.getElementById('edit_tag_input').value;
					let totalTags = document.getElementById('edited_tags_container').childNodes.length;
					let errMessage = ``;
					
					//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
					let prevTags = valuesToString(SAVED_TAGS.get('editForm')).toLowerCase().replace(' ', '');
					
					if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\\n\\t]/) &&  !prevTags.includes((`\${tagValue},`).toLowerCase().replace(' ', ''))){
						createRemovableTag(tagValue, 'editForm', 'edit_tag_input', 'edited_tags_container', 'edited_tag_storage');
					}
					else {
						if(tagValue == '') {
							errMessage += `Tag names cannot be empty<br>`;
						}
						if(totalTags >= 20) {
							errMessage += `You can only have 20 tags per image<br>`;
						}
						if(tagValue.match(/[^0-9a-zA-Z\.\s]/) || tagValue.match(/[\\n\\t]/)) {
							errMessage += `Tag names only accepts alpha-numeric, space, and \".\" characters .<br>`;
						}
						if(prevTags.includes((`\${tagValue},`).toLowerCase().replace(' ', ''))) {
							errMessage += `You already have a tag with this name. Tags are case and space insensitive. Ex. PHOTO = photo | Bad Day = BadDay`;
						}
						
					}
					
					document.getElementById('edited_tag_error').innerHTML = errMessage;
				});
				
				//Close edit form
				document.getElementById('close_edit_form').addEventListener('click', function() {
					document.getElementById('edit_form').style.display = 'none';
				});
				
				
				//Open edit form
				document.getElementById('edit_image').addEventListener('click', function() {
					document.getElementById('edit_form').style.display = 'block';
				});
				
				
			</script>
		";
		echo $detailsHtml;
	}
	
	
?>