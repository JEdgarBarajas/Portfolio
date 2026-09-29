<?php
	require_once("php_functions.php");
?>
<section id="search_module" class="web_module">
	<button class="close_button" id="close_search"></button>
	<form action="search_handler.php" method="post"> 
		<!-- special input that indentifies where the search is coming from -->
		<input type="hidden" value="<?php echo $searchType;?>" name="search_type">
		<input type="hidden" value="<?php echo $specialAlbumId;?>" name="album_id">
		<div class="web_row">
			<div class="row">
				<label for="search">Search:</label>
				<input type="search" name="search" id="search" maxlength="50">
				<input type="submit" value="Search" id="submit_search">
			</div>
			<div class="row">
				<label for="filter">Filter:</label>
				<select name="filter">
					<option value="a-z" id="a-z">A-Z</option>
					<option value="z-a" id="z-a">Z-A</option>
					<option value="oldest first" id="oldest_first">Oldest First</option>
					<option value="newest first" id="newest_first">Newest First</option>
				</select>
			</div>
		</div>
		<div class="row">
			<label for="tag_search">Tags:</label>
			<input type="text" id="tag_search" name="tag_search" maxlength="25">
			<div id="tag_suggestions">
			</div>
			<input type="button" id="apply_tag_button" value="Add Tags">
			<!-- tag_suggestions is a four tags that match the search. It can be ignored if chosen. -->
			<input type="hidden" name="applied_tags" id="applied_tags_storage" value="">
		</div>
		<fieldset id="applied_tags_container"  class='form_tags'>
			<legend>Applied Tags: </legend>
		</fieldset>
	</form>
</section>
<?php
	//Mobile version of search module. Does not apply to add album image form.
	if(basename($_SERVER["SCRIPT_NAME"]) != "add_album_image_form.php") {
		$searchJs = "<script>
			
			if(window.outerWidth < 1210) {
				document.getElementById(`search_module`).style.display = `none`;
				document.getElementById(`search_module`).style.position = `fixed`;
				document.getElementById(`close_search`).style.display = `block`;
				document.getElementById(`search_button`).style.display = `block`;
			}
			else {
				document.getElementById(`search_module`).style.display = `block`;
				document.getElementById(`search_module`).style.position = `static`;
				document.getElementById(`search_button`).style.display = `none`;
				document.getElementById(`close_search`).style.display = `none`;
			}
			window.addEventListener(`resize`, function() {
				if(window.outerWidth < 1210) {
					document.getElementById(`search_module`).style.display = `none`;
					document.getElementById(`search_button`).style.display = `block`;
					document.getElementById(`close_search`).style.display = `block`;
					document.getElementById(`search_module`).style.position = `fixed`;
				}
				else {
					document.getElementById(`search_module`).style.display = `block`;
					document.getElementById(`search_button`).style.display = `none`;
					document.getElementById(`close_search`).style.display = `none`;
					document.getElementById(`search_module`).style.position = `static`;
				}
			});
			
			document.getElementById(`search_button`).addEventListener(`click`, function () {
				document.getElementById(`search_module`).style.display = `block`;
			});
			
			document.getElementById(`close_search`).addEventListener(`click`, function () {
				document.getElementById(`search_module`).style.display = `none`;
			});
		</script>";
		
		echo $searchJs;
	}
?>
