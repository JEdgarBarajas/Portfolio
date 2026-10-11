
document.addEventListener("DOMContentLoaded", async function () {
    //loadImages();

    let url = "http://localhost:8000/retrieve_images";
    let htmlText = "";
    let content = "";

    await fetch(url).then( async (response)=>{
        if(response.ok) {
            if(response.status == 200) {
                let data = await response.json();
                htmlText = "Connected to Server";
                content = `<div id="image_details">`;
                content += `<h2>${data.objects[0].name}</h2>`;
                content += `<img src="images/${data.objects[0].file}" alt="Artwork created by Josiah Barajas.">`;
                content += `<p>${data.objects[0].descrip}</p>`;
                content += "</div>";
            }
            else {
                htmlText = "Error: " + response.status;
                alert(htmlText);
            }

        }
        else {
            htmlText = "Error, but Connected.";
            alert(htmlText);
        }

    }).catch((error) => {
        htmlText = "Error: Could not connect to server. "+error.message;
        alert(htmlText);
    });
    document.getElementById(`results`).innerHTML = content;

});

function loadImages() {

}
