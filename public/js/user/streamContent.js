
// Get all sections that have an ID defined
const sections = document.querySelectorAll("section[id]");
var paramDeleteMsgFloat = false;

// ---------- Desain lama ----------------------------------
// Add an event listener listening for scroll
var contentStream = document.getElementById("content-stream");
// contentStream.addEventListener("scroll", navHighlighter);
// ---------------------------------------------------------

// --------------- Untuk desain baru -------------------------
// let item;
// document.querySelectorAll('.stream-menu').forEach(element => {
//     element.addEventListener('click', function(){
//         let containDesc = document.getElementsByClassName('box-desc-inner');
//         for(let i=0; i<containDesc.length; i++){
//             containDesc[i].classList.add('d-none');
//         }
//         item = this;
//         document.getElementById(this.getAttribute('value')).classList.remove('d-none');
//         navHighlighter(this.getAttribute('value'));
//     })
// })
// -----------------------------------------------------------

var streamType = document.getElementById("type-stream").value;


// function navHighlighter(valueBtn) {
//     // ------------------ Untuk desain baru ---------------------
//     try {
//         document.querySelectorAll('.tablinks-stream-menu').forEach(element => {
//             element.classList.remove('active')
//         })
//         document.getElementById('tablinks-stream-menu-'+valueBtn).classList.add('active');
//     } catch (error) {
//         //console.log(error)
//     }
    
    
// }

// function exitSessionPage() {
//     document.getElementById("right-stream").classList.remove('d-none-0');
// }

document.addEventListener(
    "DOMContentLoaded",
    function () {
        //console.log("Document ready");
        if (streamType == "zoom") {
            var elementMoved = document.getElementById("zmmtg-root");
            var elementTarget = document.getElementById("streaming");

            elementTarget.appendChild(elementMoved);
        } 
        // else if (streamType == "youtube") {
        // }

        var eventStatus = document.getElementById("event-status").value;
        // //console.log(eventStatus);
        if (eventStatus == "Acara Telah Dimulai Selama") {
            // //console.log(eventStatus);
            if (streamType == "zoom") {
                //============ Untuk Zoom ===============
                // showButtonSidebar();
                // document.getElementById('stage-btn').click();
                // $("#stage-btn").trigger("click");
                document.getElementById('stage-btn').click();
                console.log("menu streaming diklik\n\n\n")
                // opentabs(event, "stream-menu", "streaming");
                //========== End untuk Zoom ==============
            } else if (streamType == "youtube") {
                // showButtonSidebar();
                document.getElementById('stage-btn').click();
            }
        }
    },
    false
);

var paramLoop = "yes";
var paramCout = 0;

document.addEventListener(
    "DOMContentLoaded",
    function () {
        setInterval(function () {
            var eventStatus = $("#event-status").attr("value");
            if (streamType == "zoom") {
               
                $(".zm-btn-legacy").click(function () {
                    $(".ReactModal__Content").hide();
                });
                $(".zm-icon-close").click(function () {
                    $(".ReactModal__Content").hide();
                });
                //Menonaktifkan notifikiasi pop up gagal join dan end meet
                var errJoin = $("#err_code_join").attr("value");
                var meetStat = $("#meet_status").attr("value");
                // //console.log(meetStat);
                if (errJoin != "" || meetStat == "3") {
                    // //console.log("ReactMOdal deleted");
                    // $(".ReactModal__Content").remove();
                    if (
                        meetStat == "3" &&
                        errJoin == "" &&
                        eventStatus == "Acara Telah Selesai"
                    ) {
                        window.location.replace(
                            document.getElementById("url_leave").value
                        );
                    }
                    else if(meetStat == '3'){
                        // $(".ReactModal__Overlay").remove();
                    }else if(errJoin == '3622' || errJoin == 3622 || errJoin == '3633' || errJoin == 3633){
                        window.location.replace(
                            document.getElementById("url_leave").value
                        );
                    }
                }

                if (
                    errJoin != "" &&
                    eventStatus == "Acara Telah Dimulai Selama" &&
                    paramLoop == "yes"
                ) {
                    paramLoop = "no";
                    document.getElementById("zmmtg-root").style.display =
                        "none";
                    // exitSessionPage();
                    document.getElementById("stream-blank").classList.remove('d-none');
                    //console.log("Gagal mencoba join");
                }

                var btnTarget = $(
                    ".participants-section-container__participants-footer-bottom .btn"
                );
                // //console.log(btnTarget);
                for (let i = 0; i < btnTarget.length; i++) {
                    // //console.log(btnTarget[i].innerText);
                    if (
                        btnTarget[i].innerText == "Invite" ||
                        btnTarget[i].innerText == "More"
                    ) {
                        btnTarget[i].remove();
                    }
                }
            } else if (streamType == "youtube") {
            }
            //============== End untuk zoom =================

            var startStr = $("#start").attr("value");
            var endStr = $("#end").attr("value");
            var startTime = new Date(startStr);
            var endTime = new Date(endStr);
            var timeNow = new Date($.now());
            if (
                startTime.getDate() == timeNow.getDate() &&
                startTime.getHours() == timeNow.getHours() &&
                startTime.getMinutes() == timeNow.getMinutes() &&
                eventStatus == "Acara Dimulai Dalam"
            ) {
                //console.log("Streaming sudah mulai");
                try {
                    window.location.replace(
                        document.getElementById("url_leave").value
                    );
                } catch (error) {
                    location.reload();
                }
            }

            var distance = timeNow.getTime() - startTime.getTime();
            //console.log(distance);
            if (distance < 0) {
                var absDis = Math.abs(distance);
                var days = Math.floor(absDis / (1000 * 60 * 60 * 24));
                var hours = Math.floor(
                    (absDis % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                );
                var minutes = Math.floor(
                    (absDis % (1000 * 60 * 60)) / (1000 * 60)
                );
                var seconds = Math.floor((absDis % (1000 * 60)) / 1000);
                // if(timeNow.getTime() > endTime.getTime()){
                if (days != 0) {
                    //console.log(days + " Hari " + hours + " Jam");
                    $("#countdown1").html(days + " Hari " + hours + " Jam");
                    $("#countdown2").html(days + " Hari " + hours + " Jam");
                } else if (hours != 0) {
                    //console.log(hours + " Jam " + minutes + " Menit");
                    $("#countdown1").html(hours + " Jam " + minutes + " Menit");
                    $("#countdown2").html(hours + " Jam " + minutes + " Menit");
                } else if (minutes != 0) {
                    //console.log(minutes + " Menit " + seconds + " Detik");
                    $("#countdown1").html(
                        minutes + " Menit " + seconds + " Detik"
                    );
                    $("#countdown2").html(
                        minutes + " Menit " + seconds + " Detik"
                    );
                } else {
                    //console.log(minutes + " Menit " + seconds + " Detik");
                    $("#countdown1").html(
                        minutes + " Menit " + seconds + " Detik"
                    );
                    $("#countdown2").html(
                        minutes + " Menit " + seconds + " Detik"
                    );
                }
                // }
            } else {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor(
                    (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                );
                var minutes = Math.floor(
                    (distance % (1000 * 60 * 60)) / (1000 * 60)
                );
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if (timeNow.getTime() < endTime.getTime()) {
                    if (days != 0) {
                        //console.log(days + " Hari " + hours + " Jam");
                        $("#countdown1").html(days + " Hari " + hours + " Jam");
                        $("#countdown2").html(days + " Hari " + hours + " Jam");
                    } else if (hours != 0) {
                        //console.log(hours + " Jam " + minutes + " Menit");
                        $("#countdown1").html(
                            hours + " Jam " + minutes + " Menit"
                        );
                        $("#countdown2").html(
                            hours + " Jam " + minutes + " Menit"
                        );
                    } else if (minutes != 0) {
                        //console.log(minutes + " Menit " + seconds + " Detik");
                        $("#countdown1").html(
                            minutes + " Menit " + seconds + " Detik"
                        );
                        $("#countdown2").html(
                            minutes + " Menit " + seconds + " Detik"
                        );
                    } else {
                        //console.log(minutes + " Menit " + seconds + " Detik");
                        $("#countdown1").html(
                            minutes + " Menit " + seconds + " Detik"
                        );
                        $("#countdown2").html(
                            minutes + " Menit " + seconds + " Detik"
                        );
                    }
                } else {
                    if (paramCout == 0) {
                        $("#countdown1").html("");
                        $("#countdown2").html("");
                        $("#status-event1 button").html("Acara Telah Selesai");
                        $("#status-event2").html("Acara Telah Selesai");
                        streamType == 'zoom' ? $('#stream-blank').removeClass('d-none') : '';
                    }
                }
            }
        }, 500);
    },
    false
);

try {
    document.getElementById('reload-zoom-stream').addEventListener('click', () => {
        websdkready(); websdkready();
    })
} catch (error) {
    
}

// document.querySelectorAll('#stage-btn').forEach(element => {
//     element.addEventListener('click', () => {
//         // -- Untuk desain baru -> lakukan pengubahan BG dasar ke BG khusus --
//         document.getElementById('bg-img-top').style.display="none";
//         document.getElementById('bg-img-top-stream').style.display="unset";
//         document.styleSheets[13].cssRules[13].style.setProperty('overflow','hidden');
//         console.log('testing gsufgdufhishfhu')
//     })
// })
// document.querySelectorAll('.tablinks-stream-menu').forEach(element => {
//     element.addEventListener('click', function(){
//         if(this.getAttribute('id') != 'stage-btn'){
//             document.getElementById('bg-img-top-stream').style.display="none";
//             document.getElementById('bg-img-top').style.display="unset";
//             document.getElementById('bg-img-sidebar').style.display="unset";
//             document.styleSheets[13].cssRules[13].style.removeProperty('overflow');
//         }
//     })
// })

// function showSidebar() {
//     document.getElementById('bg-img-sidebar').style.display="unset";
//     if (streamType == "zoom") {
//         document.getElementById("sidebar-show").style.display = "none";
//         document.getElementById("sidebar-hidden").style.display = "unset";
//         document.getElementById("zmmtg-root").classList.add("zmmtg-root-att");
//         document
//             .getElementsByClassName("meeting-client")[0]
//             .classList.add("meeting-client-add");
//         document
//             .getElementsByClassName("meeting-client-inner")[0]
//             .classList.add("meeting-client-inner-add");
//     } else if (streamType == "youtube") {
//         $("#sidebar-show").hide();
//         $("#sidebar-hidden").show();

//         var navLeft = $(".left");
//         var mainContent = $(".content-stream");
//         var chatBox = $("#right-stream");
//         var contentStream = $("#content-stream");
//         var streamRow = $("#stream-row");

//         //console.log(navLeft);
//         navLeft.show();
//         mainContent.removeClass("content-stream-yt");
//         chatBox.removeClass('d-none');
//         contentStream.addClass("col-md-8");
//         contentStream.removeClass("col-md-12");
//         streamRow.addClass("stream-row");
//         streamRow.removeClass("stream-row-2");
//         $("iframe").removeClass("iframe-full");
//         $("#frame").removeClass("text-center");

//         // ---------- Desain baru -------------------
//             // Hapus full mode backgroud dan iframe
//             document.getElementById('streaming').classList.remove('streaming-full-wrap');
//             document.getElementById('bg-img-top-stream').classList.remove('w-100');
//     }
// }

// function hiddSidebar() {
//     document.getElementById('bg-img-sidebar').style.display="none";
//     try {
//         if (streamType == "zoom") {
//             document.getElementById("sidebar-show").style.display = "unset";
//             document.getElementById("sidebar-hidden").style.display = "none";
//             document
//                 .getElementById("zmmtg-root")
//                 .classList.remove("zmmtg-root-att");
//             document
//                 .getElementsByClassName("meeting-client")[0]
//                 .classList.remove("meeting-client-add");
//             document
//                 .getElementsByClassName("meeting-client-inner")[0]
//                 .classList.remove("meeting-client-inner-add");
//         } else if (streamType == "youtube") {
//             $("#sidebar-show").show();
//             $("#sidebar-hidden").hide();
//             // document.getElementById("").style.display = "unset";
//             // document.getElementById("").style.display = "none";

//             var navLeft = $(".left");
//             var mainContent = $(".content-stream");
//             var chatBox = $("#right-stream");
//             var contentStream = $("#content-stream");
//             var streamRow = $("#stream-row");

//             //console.log("Nav Left Atas :");
//             //console.log(navLeft);
//             navLeft.hide();
//             mainContent.addClass("content-stream-yt");
//             chatBox.addClass('d-none');
//             contentStream.removeClass("col-md-8");
//             contentStream.addClass("col-md-12");
//             streamRow.removeClass("stream-row");
//             streamRow.addClass("stream-row-2");
//             $("iframe").addClass("iframe-full");
//             $("#frame").addClass("text-center");

//             // ---------- Desain baru -------------------
//             // Full-kan backgroud dan iframe
//             document.getElementById('streaming').classList.add('streaming-full-wrap');
//             document.getElementById('bg-img-top-stream').classList.add('w-100');
//         }
//     } catch (error) {}
// }

// function showButtonSidebar() {
//     var eventStatus = document.getElementById("event-status").value;
//     // //console.log(eventStatus);
//     if (
//         eventStatus == "Acara Telah Selesai" ||
//         eventStatus == "Acara Dimulai Dalam"
//     ) {
//         if (streamType == "zoom") {
//             //======== Untuk zoom ===============
//             // //console.log(eventStatus);
//             document.getElementById("zmmtg-root").style.display = "none";
//             exitSessionPage();
//             document.getElementById("stream-blank").style.display = "unset";
//             // =================================
//         } else if (streamType == "youtube") {
//             if(eventStatus == 'Acara Telah Selesai'){
//                 hiddSidebar();
//             }
//         }
//     } else {
//         if (streamType == "zoom") {
//             //======== Untuk zoom ===============
//             document.getElementById("zmmtg-root").style.display = "unset";
//             document.getElementById("right-stream").classList.add('d-none-0');
//             hiddSidebar();
//             try {
//                 var errJoin = $("#err_code_join").attr("value");
//                 var meetStat = $("#meet_status").attr("value");

//                 if (meetStat == "3" || errJoin != "") {
//                     document.getElementById("zmmtg-root").style.display =
//                         "none";
//                     exitSessionPage();
//                     document.getElementById("stream-blank").style.display =
//                         "unset";
//                 }
//             } catch (error) {}
//             //=================================
//         } else if (streamType == "youtube") {
            
//             hiddSidebar();
//         }
//     }
// }

// function hiddButtonSidebar() {
//     if (streamType == "zoom") {
//         document.getElementById("sidebar-show").style.display = "none";
//         document.getElementById("sidebar-hidden").style.display = "none";
//         exitSessionPage();
//     } else if (streamType == "youtube") {
//         document.getElementById("sidebar-show").style.display = "none";
//         showSidebar();
//         document.getElementById("sidebar-hidden").style.display = "none";
//     }
// }

// function shoMessage() {
//     document.getElementById('chat-foat').style.zIndex = 3;
//     document.getElementById('btn-chat-stream').classList.add('d-none');
//     setTimeout(() => {
//         var floatChat = document.getElementById('float-chat-stream');
//         floatChat.classList.remove('d-none');
//         floatChat.style.boxShadow = "0px 5px 40px 20px rgb(193 193 193 / 63%)";
//         floatChat.style.marginTop = "100px";
//         document.getElementById('head-chat').style.display = 'unset';
//         document.getElementById('chat-foat').appendChild(floatChat);
//     }, 300);
    
// }

// function hideMessage(){
//     document.getElementById('btn-chat-stream').classList.remove('d-none');
//     var floatChat = document.getElementById('float-chat-stream');
//     floatChat.classList.add('d-none');
//     floatChat.style.boxShadow = "unset";
//     floatChat.style.marginTop = "unset";
//     document.getElementById('chat-foat').style.zIndex = -5;
//     document.getElementById('head-chat').style.display = 'none';
//     document.getElementById('Chat').appendChild(floatChat);
// }
