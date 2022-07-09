let pizzaCount = 0;

function addPizzaToCart(name, price){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");

    let newPizza = document.createElement('option');
    newPizza.optionValue = pizzaCount++;
    newPizza.innerHTML = name;
    shoppingCart.appendChild(newPizza);

    shoppingCart.setAttribute("style", "height:" + (shoppingCart.scrollHeight) + "px");

    let totalPrice = document.getElementById("totalPrice");
    let totalPriceString = totalPrice.innerHTML;
    let currentTotalArray = totalPriceString.split(" ");
    let currentTotal = parseFloat(currentTotalArray[1]) + parseFloat(price);
    currentTotal = currentTotal.toFixed(2);
    totalPrice.innerHTML = "Gesamtpreis: " + currentTotal.toString() + " €";

    enableSubmitButton();
}

function selectAllOptions(){
    let shoppingCart = document.getElementById("shoppingCart");
    let shoppingCartOptionsArray = shoppingCart.childNodes;
    console.log("ja");
    for(let option in shoppingCartOptionsArray){
        option.selected = true;
    }
}

function submitOrder(){
    let form = document.forms;
    selectAllOptions();
    form.submit();

}

function resetShoppingCart(){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");
    shoppingCart.innerHTML = "";
    shoppingCart.setAttribute("style", "height:" + 20 + "px");

    let totalPrice = document.getElementById("totalPrice");
    totalPrice.innerHTML = "Gesamtpreis: 0.00 €";

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