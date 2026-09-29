<?php
	clearstatcache();
 ?>
<!Doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Home - Bloom Album</title>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/x-icon" href="assets/icons/bloom_logo.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body id="home_page">
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
			<img src="assets/images/left_rose_bg.png" alt="multi-colored roses">
			<div><h1>Bloom Album</h1></div>
			<img src="assets/images/right_rose_bg.png" alt="multi-colored roses">
		</header>
		<main>
			<section id="welcome">
				<h2>Welcome to Bloom Album!</h2>
				<div class="web_row">
					<p>
						Welcome to Bloom Album, an online personal photo album, and
						portfolio designed to add an aesthetic touch to photo folders,
						allowing you tag photos, create thumbnails for folders, and a
						custom description! 
					</p>
					<img src="assets/icons/photo_bloom.png" alt="bloom album logo" class="image">
				</div>
			</section>
			<div id="sliding_gallery">
				<div class="row">
					<img src="assets/images/Sticker_BlackBG.png" alt="bloom album logo" class="slide_image">
					<img src="assets/images/BloomAlbum.gif" alt="bloom album title gif" class="slide_image">
					<img src="assets/images/FlowerColors.png" alt="white, purple, and hot pink flower" class="slide_image">
					<img src="assets/images/Sticker_BlackBG.png" alt="bloom album logo" class="slide_image">
					<img src="assets/images/BloomAlbum.gif" alt="bloom album title gif" class="slide_image">
					<img src="assets/images/FlowerColors.png" alt="white, purple, and hot pink flower" class="slide_image">
					<img src="assets/images/Sticker_BlackBG.png" alt="bloom album logo" class="slide_image">
					<img src="assets/images/BloomAlbum.gif" alt="bloom album title gif" class="slide_image">
					<img src="assets/images/FlowerColors.png" alt="white, purple, and hot pink flower" class="slide_image">
				</div>
			</div>
			<section id="get_started">
				<div>
					<h2>Want to get started? Upload your first image!</h2>
					<a href="gallery.php" alt="gallery button"><button>Get Started</button></a>
				</div>
				<div>
					<h2>Want to start your first Collection or Album?</h2>
					<a href="album.php" alt="album button"><button>Create</button></a>
				</div>
			</section>
		</main>
		<script>
			let pos = -600;
			document.querySelector(`#sliding_gallery div`).style.marginLeft = `${pos}px`;
			//alert(document.querySelector(`#sliding_gallery div`).style.marginLeft);
			setInterval(function() {
				pos += 2;
				document.querySelector(`#sliding_gallery div`).style.marginLeft = `${pos}px`;
				if(pos == 0) {
					pos = -892;
				}
			}, 10);
			
			
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