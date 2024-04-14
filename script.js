let xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
        useXML(this);
    }
};

xhttp.open("GET", "porazo_joyce.xml", true);
xhttp.send();

function useXML(xml) {
    let doc = xml.responseXML;
    let capple = 0;
    let cbanana = 0;
    let cgrapes = 0;
    let corange = 0;

    if (!doc) {
        console.error("Failed to parse the XML file.");
        return;
    }
    let root = doc.documentElement;

    if (!root) {
        console.error("No root element found in the XML file.");
        return;
    }


    let baskets = root.getElementsByTagName("basket");

    var tbl = document.getElementById("basketTable").getElementsByTagName("tbody")[0];
    
    if (!tbl) {
        tbl = document.createElement("tbody");
        document.getElementById("basketTable").appendChild(tbl);
    }

    let highestTotalFruits = 0;
    let highestRow = null;

    for (var i = 0; i < baskets.length; i++) {
        var basket = baskets[i];
        
        // Get the basket details
        var basketID = basket.getElementsByTagName("number")[0].textContent;
        var ownerName = basket.getElementsByTagName("owner")[0].textContent;

        // Get the fruits element and each fruit count
        var fruits = basket.getElementsByTagName("fruits")[0];
        var appleCount = parseInt(fruits.getElementsByTagName("Apple")[0].textContent);
        var bananaCount = parseInt(fruits.getElementsByTagName("Banana")[0].textContent);
        var grapesCount = parseInt(fruits.getElementsByTagName("Grapes")[0].textContent);
        var orangeCount = parseInt(fruits.getElementsByTagName("Orange")[0].textContent);
        
        // Calculate total fruits in the basket
        var totalFruits = appleCount + bananaCount + grapesCount + orangeCount;
        capple = capple + appleCount;
        cbanana = cbanana + bananaCount;
        cgrapes = cgrapes + grapesCount;
        corange = corange + orangeCount;
        
        var tr = document.createElement("tr");

        appendCell(tr, basketID);
        appendCell(tr, ownerName);
        appendCell(tr, appleCount);
        appendCell(tr, bananaCount);
        appendCell(tr, grapesCount);
        appendCell(tr, orangeCount);
        appendCell(tr, totalFruits);

        
        if (totalFruits > 5) {
            tr.classList.add('blue-background');
        } else {
            tr.classList.add('red-background');
        }

        
        if (totalFruits > highestTotalFruits) {
            highestTotalFruits = totalFruits;
            highestRow = tr;
        }

        tbl.appendChild(tr);
    }
    
    if (highestRow) {
        highestRow.classList.add('yellow-background');
    }

    var tr1 = document.createElement("tr");

        appendCell(tr1, null);
        appendCell(tr1, "Total Fruits");
        appendCell(tr1, capple);
        appendCell(tr1, cbanana);
        appendCell(tr1, cgrapes);
        appendCell(tr1, corange);
        appendCell(tr1, null);

        tbl.appendChild(tr1);

}

function appendCell(row, value) {
    let td = document.createElement("td");
    td.textContent = value;
    row.appendChild(td);
}


