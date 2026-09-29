<?php
	function importJsInitialization() {
		$jsScript = "<script>
						/*
						  SAVED_TAGS is a 2 dimensional associative array. The first key accesses which set 
						  of tags have been created so far. The second key, accesses the specific tag name
						  using the tag_id as a key. EX. SAVED_TAGS['uploadForm']['tag_id5'] = 'Photo'
						  
						  LEFTOVER_TAG_IDS stores all remaining tag_ids that haven't been used. The first key
						  accesses a certain set of created tags while the second index accesses a specific 
						  tag_id. Ex. LEFTOVER_TAG_IDS['uploadForm'][0] = 'tag_id5'
						*/
						const SAVED_TAGS = new Map();
						SAVED_TAGS.set('uploadForm', new Map());
						SAVED_TAGS.set('searchModule', new Map());
						SAVED_TAGS.set('editForm', new Map());
						SAVED_TAGS.set('albumForm', new Map());
						
						const LEFTOVER_TAG_IDS = new Map();
						LEFTOVER_TAG_IDS.set('uploadForm', []);
						LEFTOVER_TAG_IDS.set('searchModule', []);
						LEFTOVER_TAG_IDS.set('editForm', []);
						LEFTOVER_TAG_IDS.set('albumForm', []);
						for(var i = 1; i <= 20; i++) {
							LEFTOVER_TAG_IDS.get('uploadForm').push(`tag_id_\${i}`);
							LEFTOVER_TAG_IDS.get('searchModule').push(`tag_id_\${i}`);
							LEFTOVER_TAG_IDS.get('editForm').push(`tag_id_\${i}`);
							LEFTOVER_TAG_IDS.get('albumForm').push(`tag_id_\${i}`);
						}
				
					</script>";
		echo $jsScript;
	}
	
	function importJsListeners() {
		$jsScript = "<script>
						//Opening the Upload Form
						document.getElementById('upload_button').addEventListener('click', function() {
							document.getElementById('upload_form').style.display = 'block';
							document.getElementById('upload_button').style.display = 'none';
						});
				
						//Closing the Upload Form
						document.getElementById('close_upload').addEventListener('click', function() {
							document.getElementById('upload_form').style.display = 'none';
							document.getElementById('upload_button').style.display = 'block';
						});
						
						//Add a tag to upload form
						document.getElementById('create_tag_button').addEventListener('click', function() {
							
							let tagValue = document.getElementById('tag_input').value;
							let totalTags = document.getElementById('new_tags_container').childNodes.length;
							let errMessage = ``;
							
							//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
							let prevTags = valuesToString(SAVED_TAGS.get('uploadForm')).toLowerCase().replace(' ', '');
							
							if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\\n\\t]/) &&  !prevTags.includes((`\${tagValue},`).toLowerCase().replace(' ', ''))){
								createRemovableTag(tagValue, 'uploadForm', 'tag_input', 'new_tags_container', 'new_tag_storage');
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
							
							document.getElementById('tag_error').innerHTML = errMessage;
						});
						
						//Add tag to search module
						document.getElementById('apply_tag_button').addEventListener('click', function() {
							
							let tagValue = document.getElementById('tag_search').value;
							let totalTags = document.getElementById('applied_tags_container').childNodes.length;
							let errMessage = ``;
							
							//Gets all tags previous tags in a string. Used later to test if the current tag is a duplicate.
							let prevTags = valuesToString(SAVED_TAGS.get('searchModule')).toLowerCase().replace(' ', '');
							
							if(tagValue != '' && totalTags < 20 && !tagValue.match(/[^0-9a-zA-Z\.\s]/) && !tagValue.match(/[\\n\\t]/) &&  !prevTags.includes((`\${tagValue},`).toLowerCase().replace(' ', ''))){
								
								createRemovableTag(tagValue, 'searchModule', 'tag_search', 'applied_tags_container', 'applied_tags_storage');

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
							
							document.getElementById('tag_error').innerHTML = errMessage;
						});
						
						
						//Add Delete Form EventListeners
						let imageCheckcircles = document.getElementsByClassName('checkcircle');
						for(var i = 0; i < imageCheckcircles.length; i++) {
							const circleId = imageCheckcircles[i].id;
							const imageId = `image_id\${circleId.replace('_checkcircle', '')}`;
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
						document.getElementById('delete_images').addEventListener('click', function() {
							for(var i = 0; i < imageCheckcircles.length; i++) {
								document.getElementById(imageCheckcircles[i].id).style.display = 'block';
							}
							document.getElementById('delete_form').style.display = 'block';
							document.getElementById('delete_images').style.display = 'none';
						});
						
						//Delete Confirmation Event Listeners
						document.getElementById('delete_button').addEventListener('click', function() {document.getElementById('delete_confirmation').style.display = 'block';});
						document.getElementById('soft_cancel').addEventListener('click', function() {document.getElementById('delete_confirmation').style.display = 'none';});
						
					</script>";
		echo $jsScript;
	}
	
	function importJsFunctions() {
		$jsScript = "<script>
						//Converts a map's values into a string.
						function valuesToString(assoc_arr, separator=`,`) {
							let str = ``;
							for(const x of assoc_arr.values()) {
								str += `` + x + separator;
							}
							return str;
						}
						
						//Function for creating removeable tags in a container
						/****************************************************************
							tagTxt is the actual text that goes into the tag.
							idKey is unique key to access the SAVED_TAG_IDS and LEFTOVER_TAG_IDS. 
							Both are used to make tags removable
							inputID is id of the input used to create the tag name, tagsContainerID 
							is the id of the container containing all the tags, and valueStorageID is
							the id of the input actually storing the value of the tags for the form. 
						****************************************************************/
						function createRemovableTag(tagTxt, idKey, inputID, tagsContainerID, valueStorageID) {
							//Create new Tag
							let newTag = document.createElement('div');
							newTag.classList.add('search_tag');
							const UNIQUE_ID = LEFTOVER_TAG_IDS.get(idKey).shift();
							newTag.id = UNIQUE_ID;
							SAVED_TAGS.get(idKey).set(`\${UNIQUE_ID}`, tagTxt);

							//Add Tag text
							let newTagTxt = document.createElement('p');
							newTagTxt.innerText = tagTxt;
							newTag.appendChild(newTagTxt);
							
							//Add remove button
							let tagXBtn = document.createElement('button');
							tagXBtn.innerText = 'X';
							tagXBtn.classList.add('remove_tag');
							tagXBtn.addEventListener('click', function() {
								LEFTOVER_TAG_IDS.get(idKey).push(UNIQUE_ID);
								SAVED_TAGS.get(idKey).delete(`\${UNIQUE_ID}`);
								document.getElementById(UNIQUE_ID).remove();
								document.getElementById(valueStorageID).value = `\${valuesToString(SAVED_TAGS.get(idKey))}`;
							});
							newTag.appendChild(tagXBtn);
							
							document.getElementById(tagsContainerID).appendChild(newTag);
							document.getElementById(inputID).value = '';
							
							//Saves current tags
							document.getElementById(valueStorageID).value = `\${valuesToString(SAVED_TAGS.get(idKey))}`;
							
						}
						
						function createThumbnailPreviews(src) {
							document.getElementById(`thumbnail_preview_container`).innerHTML = ``;
							let objPos = ``; //Used for Object Position Styling.
							let specialInfo = document.getElementById(`special_info`);
							for(var i = 0; i <= 100; i += 12.5) {
								const tempThumbnail = document.createElement(`img`);
								if(specialInfo.offsetWidth > specialInfo.offsetHeight) {
									objPos = `\${i}% center`;
								}
								else if (specialInfo.offsetWidth < specialInfo.offsetHeight) {
									objPos = `center \${i}%`;
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
								tempThumbnail.id = `pre_thumbnail_\${Math.floor(i)}`;
								tempThumbnail.alt = `thumbnail preview \${Math.floor(i)}%`;
								
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
						}
						
					</script>";
		echo $jsScript;
	}
	
	
	function restoreSearch($sessionSearch, $sessionFilter, $sessionTags) {
		$sesSearchVal = isset($_SESSION[$sessionSearch])? $_SESSION[$sessionSearch]: "";
		$sesFilterVal = isset($_SESSION[$sessionFilter])? $_SESSION[$sessionFilter]: "";
		$sesTags = isset($_SESSION[$sessionTags])? $_SESSION[$sessionTags]: "";
		$jsScript = "
			<script>
				//Fills in current search values
				let searchVal = '$sesSearchVal';
				let filterVal = '$sesFilterVal';
				let tagVal = '$sesTags';
				document.getElementById('search').value = searchVal;
				
				switch(filterVal) {
					case 'a-z':
						document.getElementById('a-z').selected = true;
						break;
					case 'z-a':
						document.getElementById('z-a').selected = true;
						break;
					case 'oldest first':
						document.getElementById('oldest_first').selected = true;
						break;
					default:
						document.getElementById('newest_first').selected = true;
						break;
				}	
				
				if(tagVal != '') {
					let appliedTags = tagVal.split(',');
					appliedTags.pop(); //Removes extra value created with an extra ','
					for(var i = 0; i < appliedTags.length; i++) {
						createRemovableTag(appliedTags[i], 'searchModule', 'tag_search', 'applied_tags_container', 'applied_tags_storage');
					}
				}
				
				sessionStorage.setItem('$sessionSearch', document.getElementById('search').value);
				sessionStorage.setItem('$sessionFilter', '$sesFilterVal');
				sessionStorage.setItem('$sessionTags', document.getElementById('applied_tags_storage').value);
			</script>
		";
		
		echo $jsScript;
	}
	
?>