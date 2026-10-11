const express = require(`express`);
const MongoClient = require('mongodb').MongoClient;
const app = express();
const PORT = 8000;



app.get(`/test-api`, (req, res)=> {
    res.setHeader("Access-Control-Allow-Origin", "*");
    res.json({"message": "Success. You are connected to the Server."});
});

app.get(`/retrieve_images`, async (req, res)=> {
    res.setHeader("Access-Control-Allow-Origin", "*");
    let images = await retrieveImages("Oil Pastel Imperial Knight");
    //let content = `${images}`;
    //console.log(content);
    res.json({objects : images});
});

app.listen(PORT);

async function retrieveImages(name) {
    const url = "mongodb://127.0.0.1:27017/";
    let objs = [];
    let client = new MongoClient(url);
    await client.connect();
    let db = await client.db("bloomDB");
    objs = await db.collection("gallery").find({name: "Oil Pastel Imperial Knight"}).toArray();
    //console.log(objs);
    client.close()
    return objs;
}