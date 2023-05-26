var loop = 0;
function autoChangeZoom(jsonData, IDs, pwds) {
    var paramShowZoom = 0;
    //console.log(jsonData);
    //console.log(IDs);
    var now = new Date($.now());
    for (let i = 0; i < jsonData.length; i++) {
        var start = new Date(
            jsonData[i].start_date + " " + jsonData[i].start_time
        );
        var end = new Date(jsonData[i].end_date + " " + jsonData[i].end_time);
        var zoomID = $("#meeting_number");
        var passZoom = $("#meeting_pwd");
        //console.log("Check print ID Zoom "+i);
        //console.log(IDs[i]);
        //console.log(passZoom[i]);
        if (zoomID.attr("value") == "") {
            if (now >= start && now <= end) {
                zoomID.attr("value", IDs[i]);
                passZoom.attr("value", pwds[i]);
                //console.log(zoomID.attr("value"));
            }else{
                if(loop == 0){
                    zoomID.attr("value", '#');
                    loop = 1;
                }
            }
        }

        if (now >= start && now <= end && zoomID.attr("value") != IDs[i]) {
            var leave = $("#url_leave").attr("value");
            window.location.replace(leave);
        }
        // else if((now < start || now > end) && zoomID.css('display') != 'none'){
        //     zoomID.hide();
        // }

        // if (
        //     now >= start &&
        //     now <= end &&
        //     zoomID.css('display') != 'none'){
        //         paramShowZoom += 1;
        //     }
    }
    // if(paramShowZoom == 0){
    //     //console.log(paramShowZoom);
    //     var divTarget = $('#stream-blank');
    //     if(divTarget.css('display') == 'none'){
    //         divTarget.css('display', 'unset');
    //     }
    // }
}
