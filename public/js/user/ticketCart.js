function addCount(evt) {
    var idBtn = evt.id;
    var targetID = "countTicket" + idBtn;
    var targetID2 = "inBuy" + idBtn;
    var oldCount = parseInt(document.getElementById(targetID).innerHTML);
    var max = parseInt(document.getElementById("limiter" + idBtn).value);
    oldCount += 1;
    if (oldCount > max) {
        oldCount = max;
    } else {
        var totalPay = parseInt(document.getElementById("totalPayInput").value);
        var price = parseInt(document.getElementById("price" + idBtn).value);
        console.log(document.getElementById("price" + idBtn));
        document.getElementById("totalPayInput").value = totalPay + price;
        document.getElementById("totalPay").innerHTML =
            "Total Pay : " + (totalPay + price);
    }
    document.getElementById(targetID).innerHTML = oldCount;
    document.getElementById(targetID2).value = oldCount;
}

function minCount(evt) {
    var idBtn = evt.id;
    var targetID = "countTicket" + idBtn;
    var targetID2 = "inBuy" + idBtn;
    var oldCount = parseInt(document.getElementById(targetID).innerHTML);
    oldCount -= 1;
    if (oldCount < 0) {
        oldCount = 0;
    } else {
        var totalPay = parseInt(document.getElementById("totalPayInput").value);
        var price = parseInt(document.getElementById("price" + idBtn).value);
        document.getElementById("totalPayInput").value = totalPay - price;
        document.getElementById("totalPay").innerHTML =
            "Total Pay : " + (totalPay - price);
    }
    document.getElementById(targetID).innerHTML = oldCount;
    document.getElementById(targetID2).value = oldCount;
}

function setCustomPrice(evt, jsonData) {
    // var dataDB = JSON.parse(jsonData);
    console.log(jsonData);
    var totalPayHidd = document.getElementById("totalPayInput");
    var totalPayView = document.getElementById("totalPay");
    var qty = document.getElementById("inBuy" + jsonData.id);
    var priceHidd = document.getElementById("price" + jsonData.id);
    // Proses mengubah nilai form custom price
    var customPrice = parseInt(evt.value);
    // Cek apakah kurang dari minimum
    if (customPrice < jsonData.ticket.price) {
        evt.value = "";
        evt.value = jsonData.ticket.price;
    } else {
        var total =
            parseInt(totalPayHidd.value) -
            parseInt(priceHidd.value) * parseInt(qty.value);
        total = total + parseInt(evt.value) * parseInt(qty.value);
        totalPayHidd.value = total;
        totalPayView.innerHTML = "Total Pay : " + total;
        priceHidd.value = evt.value;
    }
}
