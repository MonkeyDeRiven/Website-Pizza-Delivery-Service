let pizzaCount = 0;

function addPizzaToCart(name, price){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");

    let newPizza = document.createElement('option');
    newPizza.optionValue = pizzaCount++;
    newPizza.innerText = name;
    shoppingCart.appendChild(newPizza);

    shoppingCart.setAttribute("style", "height:" + (shoppingCart.scrollHeight) + "px");

    let totalPrice = document.getElementById("totalPrice");
    let totalPriceString = totalPrice.innerText;
    let currentTotalArray = totalPriceString.split(" ");
    let currentTotal = parseFloat(currentTotalArray[1]) + parseFloat(price);
    currentTotal = currentTotal.toFixed(2);
    totalPrice.innerText = "Gesamtpreis: " + currentTotal.toString() + " €";

    enableSubmitButton();
}

function selectAllOptions(){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");
    let shoppingCartOptionsArray = shoppingCart.childNodes;
    console.log("ja");
    for(let i = 0; i<shoppingCart.length; i++){

        let x = shoppingCart.options[i];
        x.selected = true;
        console.log(x);
    }
}
function submitOrder(){
    "use strict";
    let form = document.forms;
    selectAllOptions();
    form.submit();

}

function resetShoppingCart(){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");
    shoppingCart.innerText = "";
    shoppingCart.setAttribute("style", "height:" + 20 + "px");

    let totalPrice = document.getElementById("totalPrice");
    totalPrice.innerText = "Gesamtpreis: 0.00 €";

    enableSubmitButton();
}

function deleteAllSelectedOptions(){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");
    for(let i = 0; i<shoppingCart.length; i++){
        let option = shoppingCart.options[i];
        if(option.selected == true){
            let pizzaName = option.innerText;
            let price = document.getElementById(pizzaName).innerText;

            let priceArray = price.split(" ");

            let totalPrice = document.getElementById("totalPrice");
            let totalPriceString = totalPrice.innerText;
            let currentTotalArray = totalPriceString.split(" ");
            let currentTotal = parseFloat(currentTotalArray[1]) - parseFloat(priceArray[1]);
            currentTotal = currentTotal.toFixed(2);
            totalPrice.innerText = "Gesamtpreis: " + currentTotal.toString() + " €";
            shoppingCart.removeChild(option);
            i--;
        }
    }
    enableSubmitButton();
}

function enableSubmitButton(){
    "use strict";
    let submitButton = document.getElementById("submitButton");
    let addressInputField = document.getElementById("address");
    let shoppingCart = document.getElementById("shoppingCart");
    if(addressInputField.value != "" &&  shoppingCart.firstChild) {
        submitButton.disabled = false;
    }
    else
        submitButton.disabled = true;
}