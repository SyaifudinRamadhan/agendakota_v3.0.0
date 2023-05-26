var paramLoop = "yes";

var paramSetMoving = true;

// while (paramSetMoving == true) {
// try {
var elementMoved = document.getElementById("zmmtg-root");
var elementTarget = document.getElementById("streaming");

console.log("Moving ZMMTG Root", elementMoved, elementTarget);
elementTarget.appendChild(elementMoved);
paramSetMoving = false;
// } catch (error) {
// paramSetMoving = true;
// }
// }
hiddSidebar();

function detectAuto() {
    var errJoin = $("#err_code_join").attr("value");
    var meetStat = $("#meet_status").attr("value");

    try {
        var parent = $(".ReactModalPortal .ReactModal__Content");
        console.log(parent.length);
        var elementMoved = document.getElementsByClassName(
            "ReactModal__Content"
        );
        // console.log(elementMoved.length);
        var target = document.getElementsByTagName("body");
        if (parent.length > 0) {
            for (var i = 0; i < elementMoved.length; i++) {
                target[0].appendChild(elementMoved[i]);
            }
        }
        // var target2 = document.getElementsByClassName('participants-item__buttons');
        // target[0].appendChild(target2[0]);
        // if(elementMoved.length != 0){
        //     paramLoop = "no";
        // }
    } catch (err) {
        console.log(err);
    }
    $(".zm-btn-legacy").click(function () {
        $(".ReactModal__Content").hide();
    });
    $(".zm-icon-close").click(function () {
        $(".ReactModal__Content").hide();
    });

    if (errJoin != "" || meetStat == "3") {
        // console.log("ReactMOdal deleted");
        $(".ReactModal__Content").remove();
        $(".ReactModal__Overlay").remove();
        if (meetStat == "3" && errJoin == "") {
            window.location.replace(document.getElementById("url_leave").value);
        }
    }

    if (errJoin != "" && paramLoop == "yes") {
        paramLoop = "no";
        document.getElementById("zmmtg-root").style.display = "none";
        // exitSessionPage();
        // document.getElementById("stream-blank").style.display = "unset";
        console.log("Gagal mencoba join");
        document.getElementById('stream-blank').style.display = "block";
    }
}

function showSidebar() {
    document.getElementById("sidebar-show").style.display = "none";
    document.getElementById("sidebar-hidden").style.display = "unset";
    document.getElementById("zmmtg-root").classList.add("zmmtg-root-att");
    document
        .getElementsByClassName("meeting-client")[0]
        .classList.add("meeting-client-add");
    document
        .getElementsByClassName("meeting-client-inner")[0]
        .classList.add("meeting-client-inner-add");
}

function hiddSidebar() {
    try {
        document.getElementById("sidebar-show").style.display = "unset";
        document.getElementById("sidebar-hidden").style.display = "none";
        document
            .getElementById("zmmtg-root")
            .classList.remove("zmmtg-root-att");
        document
            .getElementsByClassName("meeting-client")[0]
            .classList.remove("meeting-client-add");
        document
            .getElementsByClassName("meeting-client-inner")[0]
            .classList.remove("meeting-client-inner-add");
    } catch (error) {}
}

// function showButtonSidebar() {
//     var eventStatus = document.getElementById("event-status").value;
//     // console.log(eventStatus);
//     if (
//         eventStatus == "Acara Telah Selesai" ||
//         eventStatus == "Acara Dimulai Dalam"
//     ) {
//         if (streamType == "zoom") {
//             //======== Untuk zoom ===============
//             // console.log(eventStatus);
//             document.getElementById("zmmtg-root").style.display = "none";
//             exitSessionPage();
//             document.getElementById("stream-blank").style.display = "unset";
//             // =================================
//         } else if (streamType == "youtube") {
//             if (eventStatus == "Acara Telah Selesai") {
//                 hiddSidebar();
//             }
//         }
//     } else {
//         if (streamType == "zoom") {
//             //======== Untuk zoom ===============
//             document.getElementById("zmmtg-root").style.display = "unset";
//             document.getElementById("right-stream").style.display = "none";
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
