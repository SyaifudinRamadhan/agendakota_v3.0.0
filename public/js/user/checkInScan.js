var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
        fps: 10,
        qrbox: 250
    });

var scanMode = 1;
// Mode scan : 1 -> camera, 2 -> usb laser scanner

function verifyCheckin(decodedText, camScan = true) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    // Note : -1 => pause, 1 => next / lanjut
    // Note : Atur ulang barcode / qr code isi dengan 'orderID-userID'
    var paramList = decodedText.split("-");
    console.log("1/event/1/ticketSelling/qr-scanning/" + paramList[0] + "/" + paramList[1], paramList.length);

    console.log(">2");
        $.ajax({
        url: "qr-scanning/" + paramList[0] + "/" + paramList[1],
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if(data.error == 1){
                Swal.fire({
                    title: 'Error!',
                    text: data.msg,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function (){
                    /*code to execute after alert*/
                    // html5QrcodeScanner.resume();
                    if(camScan == true){
                        html5QrcodeScanner.resume();
                    }
                });
            }else{
                Swal.fire({
                    title: 'Success!',
                    text: data.msg,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function (){
                    /*code to execute after alert*/
                    // html5QrcodeScanner.resume();
                    if(camScan == true){
                        html5QrcodeScanner.resume();
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error!',
                text: 'QR Code tidak sesuai dengan sistem',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then(function (){
                /*code to execute after alert*/
                // html5QrcodeScanner.resume();
                if(camScan == true){
                    html5QrcodeScanner.resume();
                }
            });
        }
    }); 
}

function verifyCheckinByUser(decodedText, camScan = true) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    // Note : -1 => pause, 1 => next / lanjut
    // Note : Atur ulang barcode / qr code isi dengan 'orderID-userID'
    
        $.ajax({
        url: "userSelfCheckin/" + decodedText + "/" + 'checkin',
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if(data.error == 1){
                Swal.fire({
                    title: 'Error!',
                    text: data.msg,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function (){
                    /*code to execute after alert*/
                    // html5QrcodeScanner.resume();
                    if(camScan == true){
                        html5QrcodeScanner.resume();
                    }
                });
            }else{
                Swal.fire({
                    title: 'Success!',
                    text: data.msg,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function (){
                    /*code to execute after alert*/
                    // html5QrcodeScanner.resume();
                    if(camScan == true){
                        html5QrcodeScanner.resume();
                    }
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error!',
                text: 'QR Code tidak sesuai dengan sistem',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then(function (){
                /*code to execute after alert*/
                // html5QrcodeScanner.resume();
                if(camScan == true){
                    html5QrcodeScanner.resume();
                }
            });
        }
    }); 
}

function onScanSuccess(decodedText, decodedResult) {
    var pageMode = undefined;
    try {
        pageMode = document.getElementById('position').value;
    } catch (error) {
        console.log(error);
    }
    console.log(pageMode);
    console.log(`Scan result: ${decodedText}`, decodedResult);
    if(pageMode == undefined){
        if(scanMode == 1){
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
            // var paramCmd = 1;
            var paramList = decodedText.split("-");
            if(paramList.length < 2){
                console.log("<2");
                Swal.fire({
                    title: 'Error!',
                    text: 'QR Code tidak sesuai dengan sistem',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function (){
                    /*code to execute after alert*/
                    // html5QrcodeScanner.resume();https://qrco.de/bbuNnk
                    html5QrcodeScanner.resume();
                });
            }else{
                verifyCheckin(decodedText);
            }
    
            html5QrcodeScanner.pause();
            // ^ this will stop the scanner (video feed) and clear the scan area.
        }
    }else{
        verifyCheckinByUser(decodedText);
        html5QrcodeScanner.pause();
        // ^ this will stop the scanner (video feed) and clear the scan area.
    }
}

html5QrcodeScanner.render(onScanSuccess);

// Scanning dengan alat scanner barcode
var strKeyDown = "";
window.addEventListener('keydown', function (event) {
    // console.log(event.keyCode);
    if(scanMode == 2){
        if(event.keyCode == 13){
            console.log(strKeyDown);
            runCheck = verifyCheckin(strKeyDown, camScan = false);
            strKeyDown = "";
        }else{
            strKeyDown += event.key;
        }
    }
})

// Control / Navigator untuk merubah mode scan
try {
    var btnNav = document.getElementById('btn-ch-scan');
    btnNav.addEventListener('click', function (event) {
        if(scanMode == 1){
            scanMode = 2;
            // ubah text btn => laser scanner
            btnNav.innerHTML = "Ubah Mode : Camera Scan";
            document.getElementById('reader').classList.add("d-none");
            try {
                document.querySelectorAll('#reader__dashboard_section_csr span button')[1].click();
            } catch (error) {
                console.log("request kamera belum dapat");
            }
            document.getElementById('reader-usb').classList.remove("d-none");
        }else{
            scanMode = 1;
            // ubah text btn => cam scanner
            btnNav.innerHTML = "Ubah Mode : USB QR Scan";
            document.getElementById('reader-usb').classList.add("d-none");
            document.getElementById('reader').classList.remove("d-none");
        }
    })
} catch (error) {
    console.log(error);
}