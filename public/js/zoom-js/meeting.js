window.addEventListener("DOMContentLoaded", function (event) {
    //console.log("DOM fully loaded and parsed");
    websdkready();
});

let joinStatus;

function websdkready() {
    var testTool = window.testTool;
    // get meeting id from form

    var signature1 = ZoomMtg.generateSignature({
        meetingNumber: parseInt(
            document.getElementById("meeting_number").value
        ),
        apiKey: "5Ktt2Z3fQTyn0wSVVTKpXg",
        apiSecret: "S4psQKnlyJWkXEzgP3H9fyEJ5ryft3tPCJlM",
    });
    var signature1 = ZoomMtg.generateSignature({
        meetingNumber: parseInt(
            document.getElementById("meeting_number").value
        ),
        apiKey: API_KEY,
        apiSecret: API_SECRET,
        role: parseInt(document.getElementById("meeting_role").value, 10),
    });
    //meeting config
    var meetingConfig = {
        //apikey
        apiKey: "5Ktt2Z3fQTyn0wSVVTKpXg",
        meetingNumber: parseInt(
            document.getElementById("meeting_number").value
        ),
        userName: document.getElementById("display_name").value,
        passWord: document.getElementById("meeting_pwd").value,
        //leveurl??
        leaveUrl: document.getElementById("url_leave").value,
        role: parseInt(document.getElementById("meeting_role").value, 10),
        userEmail: document.getElementById("meeting_email").value,
        lang: document.getElementById("meeting_lang").value,
        signature: signature1,
        china: document.getElementById("meeting_china").value === 1,
    };

    while (meetingConfig.meetingNumber == undefined || meetingConfig.meetingNumber == '' || meetingConfig.passWord == undefined || meetingConfig.passWord == '') {
        meetingConfig.meetingNumber = parseInt(
            document.getElementById("meeting_number").value
        )
        meetingConfig.userName = document.getElementById("display_name").value
        meetingConfig.passWord = document.getElementById("meeting_pwd").value
        //leveurl??
        meetingConfig.leaveUrl = document.getElementById("url_leave").value
        meetingConfig.role = parseInt(document.getElementById("meeting_role").value, 10)
        meetingConfig.userEmail = document.getElementById("meeting_email").value
        meetingConfig.lang = document.getElementById("meeting_lang").value,
        meetingConfig.signature = signature1
        meetingConfig.china = document.getElementById("meeting_china").value === 1
    }

    //console.log(meetingConfig);
    //a tool use debug mobile device
    if (testTool.isMobileDevice()) {
        vConsole = new VConsole();
    }
    //console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

    // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
    // ZoomMtg.setZoomJSLib("https://source.zoom.us/1.9.0/lib", "/av"); // CDN version defaul
    if (meetingConfig.china)
        ZoomMtg.setZoomJSLib("https://jssdk.zoomus.cn/1.9.0/lib", "/av"); // china cdn option
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();
    function beginJoin(signature) {
        ZoomMtg.init({
            leaveUrl: meetingConfig.leaveUrl,
            webEndpoint: meetingConfig.webEndpoint,
            success: function () {
                //console.log(meetingConfig);
                //console.log("signature", signature);
                ZoomMtg.i18n.load(meetingConfig.lang);
                ZoomMtg.i18n.reload(meetingConfig.lang);
                //ini
                ZoomMtg.join({
                    meetingNumber: meetingConfig.meetingNumber,
                    userName: meetingConfig.userName,
                    signature: signature,
                    apiKey: meetingConfig.apiKey,
                    userEmail: meetingConfig.userEmail,
                    passWord: meetingConfig.passWord,
                    success: function (res) {
                        //console.log("join meeting success");
                        //console.log("get attendeelist");
                        document.getElementById('err_code_join').value = '';
                        ZoomMtg.getAttendeeslist({});
                        ZoomMtg.getCurrentUser({
                            success: function (res) {
                                //console.log("success getCurrentUser",res.result.currentUser);
                            },
                        });
                        // document.getElementById('stage-btn').click();
                    },
                    error: function (res) {
                        // console.log(res, meetingConfig);
                        document.getElementById('err_code_join').value = res.errorCode;
                        document.getElementById('join_status').value = res.status;
                        joinStatus = res;
                        
                    },
                });
            },
            error: function (res) {
                // console.log(res);
            },
        });

        ZoomMtg.inMeetingServiceListener("onUserJoin", function (data) {
            //console.log("inMeetingServiceListener onUserJoin", data);
        });

        ZoomMtg.inMeetingServiceListener("onUserLeave", function (data) {
            //console.log("inMeetingServiceListener onUserLeave", data);
        });

        ZoomMtg.inMeetingServiceListener(
            "onUserIsInWaitingRoom",
            function (data) {
                //console.log("inMeetingServiceListener onUserIsInWaitingRoom",data);
            }
        );

        ZoomMtg.inMeetingServiceListener("onMeetingStatus", function (data) {
            document.getElementById('meet_status').value = data.meetingStatus;
            //console.log("inMeetingServiceListener onMeetingStatus", data);
        });
    }

    beginJoin(meetingConfig.signature);
}
