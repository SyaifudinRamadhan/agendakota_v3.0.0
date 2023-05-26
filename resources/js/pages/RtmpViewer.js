import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import VideoFrame2 from "../components/VideoFrame";
import '../../css/rtmp-viewer.css'

let loop = 0;
let loopSet = 0;
let loopSet2 = 0;

function RtmpViewer() {
    const [urlServer, setUrl] = useState(null);
    const [xAccessToken, setToken] = useState(null);
    const [player, setVideoJs] = useState(null);
    const [VideoFrame, setFrame] = useState(null);
    const [time, setTime] = useState(null);
    const [isStart, setSTart] = useState(false);
    const [startTime, setStart] = useState(null);
    const [endTime, setEnd] = useState(null);

    const reloadVideoSrc = () => {
        console.log(player);
        if (player !== null) {
            player.dispose();
            setFrame(null);
            setVideoJs(null);
        }
    };

    useEffect(() => {
        if (loop === 0) {
            let url = document.getElementById("url-stream");
            let token = document.getElementById("x-access-token");
            let startTime = document.getElementById('start-time');
            let endTime = document.getElementById('end-time');

            setUrl(url.value);
            setToken(token.value);
            setStart(new Date(startTime.value));
            setEnd(new Date(endTime.value));
            url.remove();
            token.remove();
            startTime.remove();
            endTime.remove();
            setTime(new Date())

            setInterval(() => {
                setTime(new Date())
                console.log('set time');
            }, 30000)

            loop++;
        }

        if (document.getElementById("reload-stream")) {
            document
                .getElementById("reload-stream").onclick = () => {
                    console.log("dirender ulang");
                    reloadVideoSrc();
                }
        }
    });

    useEffect(() => {
        if(time && startTime && endTime){
            if(startTime <= time && endTime >= time && isStart === false ){
                setSTart(true)
                console.log('start');
            }
        }
    }, [time, startTime, endTime])

    useEffect(() => {
        if (urlServer !== null && xAccessToken !== null && isStart === true) {
            console.log('frmae di set');
            setFrame(
                <VideoFrame2
                    urlServer={urlServer}
                    xAccessToken={xAccessToken}
                    fnSetStatePlayer={setVideoJs}
                ></VideoFrame2>
            );
        }
    }, [urlServer, xAccessToken, isStart]);

    useEffect(() => {
        console.log("frame changed");
        if (player !== null) {
            player.on("error", function(err) {
                console.log(err);
                document
                    .getElementById("stream-blank")
                    .classList.remove("d-none");
                document.getElementById("rtmp-video").classList.add("d-none");
                if(loopSet2 === 0){
                    reloadVideoSrc()
                    loopSet2++
                }
            });
        } else if(isStart) {
            console.log("set ulang frame");
            setFrame(
                <VideoFrame2
                    urlServer={urlServer}
                    xAccessToken={xAccessToken}
                    fnSetStatePlayer={setVideoJs}
                ></VideoFrame2>
            );
            document.getElementById("stream-blank").classList.add("d-none");
            document.getElementById("rtmp-video").classList.remove("d-none");
        }else{
            document.getElementById("stream-blank").classList.remove("d-none");
            document.getElementById("rtmp-video").classList.add("d-none");
        }
    }, [player]);

    return <div id="frame">{VideoFrame}</div>;
}

export default RtmpViewer;

if (document.getElementById("rtmp-video")) {
    ReactDOM.render(<RtmpViewer />, document.getElementById("rtmp-video"));
}
