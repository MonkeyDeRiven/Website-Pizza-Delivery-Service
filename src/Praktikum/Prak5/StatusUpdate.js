function process(jsonString){

    let orderArray = JSON.parse(jsonString);
    let orderStatusSection = document.getElementById("orderStatusSection");

    while(orderStatusSection.firstChild){
        orderStatusSection.removeChild(orderStatusSection.firstChild);
    }

    for(let i = 0; i<orderArray.length; i++){
        let name = orderArray[i]["name"];
        let articleId = orderArray[i]["ordered_article_id"];
        let orderId = orderArray[i]["ordering_id"];
        let status = orderArray[i]["status"];

        let tag = document.createElement("p");

        tag.innerHTML = name + ": ";
        if(status == 0){
            tag.innerHTML += "bestellt";
        }
       if(status == 1){
            tag.innerHTML += "im Ofen";
        }
        if(status == 2){
            tag.innerHTML += "fertig";
        }
        if(status == 3){
            tag.innerHTML += "unterwegs";
        }

        orderStatusSection.appendChild(tag);
    }
}


let request = new XMLHttpRequest();

function requestData() {
    request.open("GET", "CustomerStatus.php");
    request.onreadystatechange = processData;
    request.send(null);
}

function processData() {
    if(request.readyState === 4) {
        if (request.status === 200) {
            if(request.responseText != null)
                process(request.responseText);
            else console.error ("Dokument ist leer");
        }
        else console.error ("Uebertragung fehlgeschlagen");
    }
}

document.body.onload = function () {
    requestData();
    let jobId = window.setInterval(requestData, 2000);
    window.onunload = function (){
        window.clearInterval(jobId);
    }
}