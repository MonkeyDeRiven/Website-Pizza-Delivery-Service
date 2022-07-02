function addPizzaToCart(name, price){
    "use strict";
    let shoppingCart = document.getElementById("shoppingCart");
    shoppingCart.innerHTML += name + "\n"
    shoppingCart.setAttribute("style", "height:" + (shoppingCart.scrollHeight) + "px");

    let totalPrice = document.getElementById("totalPrice");
    let totalPriceString = totalPrice.innerHTML;
    let currentTotalArray = totalPriceString.split(" ");
    let currentTotal = parseFloat(currentTotalArray[1]) + parseFloat(price);
    currentTotal = currentTotal.toFixed(2);
    totalPrice.innerHTML = "Gesamtpreis: " + currentTotal.toString() + " €";
}

function resetShoppingCart(){
    let shoppingCart = document.getElementById("shoppingCart");
    shoppingCart.innerHTML = "";
    shoppingCart.setAttribute("style", "height:" + 20 + "px");

    let totalPrice = document.getElementById("totalPrice");
    totalPrice.innerHTML = "Gesamtpreis: 0.00 €";
}