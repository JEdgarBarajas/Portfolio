//MongoDB Database Initiliazation
let MongoClient = require('mongodb').MongoClient;



async function mongoConnect() {
    console.log("Connecting to server...");
    const url = "mongodb://127.0.0.1:27017/";
    let client = new MongoClient(url);
    await client.connect();
    let db= client.db("bloomDB");
    console.log("BloomDB created");
    let images = [];
    images.push({
        name: "Pikmin 3 Promo Art",
        descrip: "Official Promotional Artwork for Pikmin 3 created by Nintendo. Uses digitals models to mimic a diorama.",
        tags: ["artwork", "3d", "nintendo", "pikmin", "promo art"],
        file: "800px-Pikmin3Promo2.png"
    });
    images.push({
        name: "Comfy Sweet Date - Hagetapo",
        descrip: "Fanart featuring the protagonists of Pokemon Black and White, Hilda and Hilbert. Created by Hageta/Hagetapo.",
        tags: ["artwork", "fan art", "pokemon", "pokemon bw", "couple", "hilda", "hilbert", "hageta", "hagetapo"],
        file: "cute_snacks.png"
    });
    images.push({
        name: "Loremaster's Newest Abomination",
        descrip: "Artwork of Loremaster from Helltaker. Created by Vanripper, the Developer of Helltaker and Awaria.",
        tags: ["artwork", "comic", "meme", "helltaker", "vanripper", "cat", "azazel", "loremaster"],
        file: "E-mg18HWUAUl0tF.jpg"
    });
    images.push({
        name: "Oil Pastel Imperial Knight",
        descrip: "Artwork created by me, Josiah Barajas. Features a Knight named Zaesha in front of a large pyre.",
        tags: ["artwork", "fantasy", "josiah barajas", "oil pastel", "knight", "fire", "zaesha"],
        file: "CaptainOfTheOrder.jpg"
    });
    images.push({
        name: "Twin Wardens Custom Card Artwork",
        descrip: "A Photoshop of Caroline and Justine from Persona Q2. Used as custom card artwork.",
        tags: ["persona", "persona q2", "photoshop", "custom card", "caroline", "justine"],
        file: "RebelHeartsCarolineAndJustine.png"
    });
    images.push({
        name: "Twin Wardens Custom Card Artwork",
        descrip: "A Photoshop of Caroline and Justine from Persona Q2. Used as custom card artwork.",
        tags: ["persona", "persona q2", "photoshop", "custom card", "caroline", "justine"],
        file: "RebelHeartsCarolineAndJustine.png"
    });
    images.push({
        name: "Sharp Glasses Yusaku and Aoi",
        descrip: "Fan artwork of the characters Yusaku, and Aoi from Yu-Gi-Oh!: VRAINS. Created by Nanashibax.",
        tags: ["artwork", "fan art", "yugioh", "ygo vrains", "aoi", "yusaku", "ai", "nanashibax", "glasses"],
        file: "65239914_p15_master1200.jpg"
    });
    images.push({
        name: "Austin Capital Building",
        descrip: "A photo of the Austin capital building I took while at a debate competition.",
        tags: ["photo"],
        file: "Austin.jpg"
    });

    await db.collection("gallery").insertMany(images);
    console.log("Images Uploaded to BloomDB");
    client.close();
    console.log("Connected to MongoDB.");
}
mongoConnect();

/*
images.push({
        name: "Pikmin 3 Promo Art",
        descrip: "Official Promotional Artwork for Pikmin 3 created by Nintendo. Uses digitals models to mimic a diorama.",
        tags: ["artwork", "3d", "nintendo", "pikmin", "promo art"],
        file: "800px-Pikmin3Promo2.png"
    });
    images.push({
        name: "Comfy Sweet Date - Hagetapo",
        descrip: "Fanart featuring the protagonists of Pokemon Black and White, Hilda and Hilbert. Created by Hageta/Hagetapo.",
        tags: ["artwork", "fan art", "pokemon", "pokemon bw", "couple", "hilda", "hilbert", "hageta", "hagetapo"],
        file: "cute_snacks.png"
    });
    images.push({
        name: "Loremaster's Newest Abomination",
        descrip: "Artwork of Loremaster from Helltaker. Created by Vanripper, the Developer of Helltaker and Awaria.",
        tags: ["artwork", "comic", "meme", "helltaker", "vanripper", "cat", "azazel", "loremaster"],
        file: "E-mg18HWUAUl0tF.jpg"
    });
    images.push({
        name: "Oil Pastel Imperial Knight",
        descrip: "Artwork created by me, Josiah Barajas. Features a Knight named Zaesha in front of a large pyre.",
        tags: ["artwork", "fantasy", "josiah barajas", "oil pastel", "knight", "fire", "zaesha"],
        file: "E-mg18HWUAUl0tF.jpg"
    });
    images.push({
        name: "Twin Wardens Custom Card Artwork",
        descrip: "A Photoshop of Caroline and Justine from Persona Q2. Used as custom card artwork.",
        tags: ["persona", "persona q2", "photoshop", "custom card", "caroline", "justine"],
        file: "RebelHeartsCarolineAndJustine.png"
    });
    images.push({
        name: "Twin Wardens Custom Card Artwork",
        descrip: "A Photoshop of Caroline and Justine from Persona Q2. Used as custom card artwork.",
        tags: ["persona", "persona q2", "photoshop", "custom card", "caroline", "justine"],
        file: "RebelHeartsCarolineAndJustine.png"
    });
    images.push({
        name: "Sharp Glasses Yusaku and Aoi",
        descrip: "Fan artwork of the characters Yusaku, and Aoi from Yu-Gi-Oh!: VRAINS. Created by Nanashibax.",
        tags: ["artwork", "fan art", "yugioh", "ygo vrains", "aoi", "yusaku", "ai", "nanashibax", "glasses"],
        file: "65239914_p15_master1200.jpg"
    });
    images.push({
        name: "Austin Capital Building",
        descrip: "A photo of the Austin capital building I took while at a debate competition.",
        tags: ["photo"],
        file: "Austin.jpg"
    });
*/