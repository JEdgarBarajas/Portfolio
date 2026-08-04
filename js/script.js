document.getElementById("nav-button").addEventListener("click", toggleMenu);

//Toggle menu replaces the image once clicked.
function toggleMenu() {
	//We use include since the offline URL is pretty long.
	if(document.getElementById("nav-button").src.includes("assets/images/menu_icon.png")) {
		document.getElementById("nav-button").src = "assets/images/close_icon.png";
		document.getElementById("nav-button").alt = "Close Navigation Menu Button";
		document.querySelector("nav ul").style.display = "flex";
	}
	else {
		document.getElementById("nav-button").src = "assets/images/menu_icon.png";
		document.getElementById("nav-button").alt = "Open Navigation Menu Button";
		document.querySelector("nav ul").style.display = "none";
	}
}

if(document.querySelector("form")) {
	document.getElementById("submit").addEventListener("click", function(event) {
		event.preventDefault();
		document.getElementById("error").innerHTML = "";
		var errMessage = "";
		if(!document.getElementById("name").value.match(/[a-z]/)) {
			errMessage = "Name must have letters.<br>";
		}
		if(!document.getElementById("email").value.match(/[a-zA-Z0-9._%+-]+@[a-zA-z0-9.-]+\.[a-zA-Z]{2,}$/)) {
			errMessage += "Your email must contain a some characters, an @, and a domain.<br>";
		}
		if(!document.getElementById("message").value.match(/[a-z]/)) {
			errMessage += "Message must contain some letters.";
		}
		if(errMessage == "") {
			document.getElementById("modal").style.display = "block";
		}
		document.getElementById("error").innerHTML = errMessage;
	});
}

document.getElementById("close-button").addEventListener("click", function() {
	document.getElementById("modal").style.display = "none";
});

document.getElementById("donate-button").addEventListener("click", function() {
	document.getElementById("modal").style.display = "flex";
});