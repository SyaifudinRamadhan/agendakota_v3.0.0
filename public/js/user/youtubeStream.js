function autoChangeVideo(jsonData) {
    var paramShowVideo = 0;
    var now = new Date($.now());
    for (let i = 0; i < jsonData.length; i++) {
        var start = new Date(
            jsonData[i].start_date + " " + jsonData[i].start_time
        );
        var end = new Date(jsonData[i].end_date + " " + jsonData[i].end_time);
        var idFrame = "#stream" + i;
        var iFrame = $(idFrame);

        // if (now >= start && now <= end && iFrame.css("display") == "none") {
        //     var divTarget = $("#stream-blank");
        //     //console.log("Enter set Yt show");
        //     divTarget.css("display", "none");
        //     iFrame.show();
        //     //console.log(start, end, now, iFrame.css("display"));
        //     try {
        //         $("#stage-btn").trigger("click");
        //     } catch (error) {}
        // } else if (
        //     (now < start || now > end) &&
        //     iFrame.css("display") != "none"
        // ) {
        //     iFrame.hide();
        // }

        // if (now >= start && now <= end && iFrame.css("display") != "none") {
        //     paramShowVideo += 1;
        // }
        if (now >= start && iFrame.css("display") == "none") {
            var divTarget = $("#stream-blank");
            //console.log("Enter set Yt show");
            divTarget.css("display", "none");
            iFrame.show();
            //console.log(start, end, now, iFrame.css("display"));
            if(now <= end){
                try {
                    $("#stage-btn").trigger("click");
                } catch (error) {}
            }
        } else if (
            now < start &&
            iFrame.css("display") != "none"
        ) {
            iFrame.hide();
        }

        if (now >= start && iFrame.css("display") != "none") {
            paramShowVideo += 1;
        }
    }
    if (paramShowVideo == 0) {
        //console.log(paramShowVideo);
        var divTarget = $("#stream-blank");
        if (divTarget.css("display") == "none") {
            divTarget.css("display", "unset");
            divTarget.removeClass('d-none');
        }
    }

    //Delete iFrame YouTube Components
    // try{
    //     $('.ytp-popup').detach;
    //     $('.ytp-title').detach;
    //     $('.ytp-title-channel').detach;
    //     $('.ytp-pause-overlay').detach;
    //     $('.ytp-youtube-button').detach;
    // }
        //console.log('Area delete items iframe');
        $('.ytp-popup').remove;
        $('.ytp-title').remove;
        $('.ytp-title-channel').remove;
        $('.ytp-pause-overlay').remove;
        $('.ytp-youtube-button').remove;
        var Ifrm = $('iframe');
        //console.log($('.ytp-title', Ifrm.contents()));
        $('.ytp-title', Ifrm.contents()).html('<script>//console.log($(".ytp-title");</script>');
        
        //console.log(Ifrm.contents().find('body'));
}
