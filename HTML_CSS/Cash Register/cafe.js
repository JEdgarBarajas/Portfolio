let total = 0.0;
let orderStr = "";

document.addEventListener("DOMContentLoaded", function() {
	
	let biscotti = document.getElementById("images/biscotti_info.jpg");
	let cappuccino = document.getElementById("images/cappuccino_info.jpg");
	let coffee = document.getElementById("images/coffee_info.jpg");
	let espresso = document.getElementById("images/espresso_info.jpg");
	let latte = document.getElementById("images/latte_info.jpg");
	let scone = document.getElementById("images/scone_info.jpg");
	
	biscotti.src = "images/biscotti.jpg";
	cappuccino.src = "images/cappuccino.jpg";
	coffee.src = "images/coffee.jpg";
	espresso.src = "images/espresso.jpg";
	latte.src = "images/latte.jpg";
	scone.src = "images/scone.jpg";
	
	biscotti.addEventListener("click", addBiscotti);
	cappuccino.addEventListener("click", addCappuccino);
	coffee.addEventListener("click", addCoffee);
	espresso.addEventListener("click", addEspresso);
	latte.addEventListener("click", addLatte);
	scone.addEventListener("click", addScone);
	
	biscotti.addEventListener("mouseout", function(){
		biscotti.src = "images/biscotti.jpg";
	});
	cappuccino.addEventListener("mouseout", function(){
		cappuccino.src = "images/cappuccino.jpg";
	});
	coffee.addEventListener("mouseout", function(){
		coffee.src = "images/coffee.jpg";
	});
	espresso.addEventListener("mouseout", function(){
		espresso.src = "images/espresso.jpg";
	});
	latte.addEventListener("mouseout", function(){
		latte.src = "images/latte.jpg";
	});
	scone.addEventListener("mouseout", function(){
		scone.src = "images/scone.jpg";
	});
	
	
	biscotti.addEventListener("mouseover", function(){
		biscotti.src = "images/biscotti_info.jpg";
	});
	cappuccino.addEventListener("mouseover", function(){
		cappuccino.src = "images/cappuccino_info.jpg";
	});
	coffee.addEventListener("mouseover", function(){
		coffee.src = "images/coffee_info.jpg";
	});
	espresso.addEventListener("mouseover", function(){
		espresso.src = "images/espresso_info.jpg";
	});
	latte.addEventListener("mouseover", function(){
		latte.src = "images/latte_info.jpg";
	});
	scone.addEventListener("mouseover", function(){
		scone.src = "images/scone_info.jpg";
	});
	
	document.getElementById("clear_order").addEventListener("click", clearOrder);	
	document.getElementById("place_order").addEventListener("click", placedOrder);
	
	
});

function placedOrder() {
	window.open("checkout.html", "_self");
}

function addBiscotti() {
	orderStr += "<option> 1.95 - Biscotti </option>"; 
	$("#order").html(orderStr);
	updateTotal(1.95);
}

function addCappuccino() {
	orderStr += "<option> 3.45 - Cappuccino </option>"; 
	$("#order").html(orderStr);
	updateTotal(3.45);
}

function addCoffee() {
	orderStr += "<option> 1.75 - Coffee </option>"; 
	$("#order").html(orderStr);
	updateTotal(1.75);
}

function addEspresso() {
	orderStr += "<option> 1.95 - Espresso </option>"; 
	$("#order").html(orderStr);
	updateTotal(1.95);
}

function addLatte() {
	orderStr += "<option> 2.95 - Latte </option>"; 
	$("#order").html(orderStr);
	updateTotal(2.95);
}

function addScone() {
	orderStr += "<option> 2.95 - Scone </option>"; 
	$("#order").html(orderStr);
	updateTotal(2.95);
}

function clearOrder() {
	
	orderStr = "";
	$("#order").html(orderStr);
	total = 0;
	updateTotal(0.0);
	
}


function updateTotal(price) {
	total += price;
	document.getElementById("total").innerHTML = "Total: $" + total;
	
}

