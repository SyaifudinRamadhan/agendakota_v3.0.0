import React, { useEffect } from "react";
import { useRef } from "react";
import videojs from "video.js";
import "video.js/dist/video-js.css";

function VideoFrame({ urlServer, xAccessToken, fnSetStatePlayer }) {
    const videoTag = useRef();
    const videoSource = useRef();
    const videoDiv = useRef();
    let loop = 0;

    const setVideo = ({ refVideoTag, refVideoSrc, urlServer, authToken }) => {
        refVideoSrc.current.src = urlServer;
        // console.log(videojs.Vhs.xhr.beforeRequest);
        videojs.Vhs.xhr.beforeRequest = function(opt) {
            // opt.headers.access - control - allow - origin = nameDomain
            opt.headers = {
                "x-access-token": authToken
            };
            // console.log(opt.headers);
            // console.log(urlServer);
            return opt;
        };
        // videojs.registerPlugin('errors', errors());
        const a = new videojs(refVideoTag.current.id, {
            liveui: true,
            liveTracker: true,
            aspectRatio: "16:9",
            responsive: true,
            autoplay: "play"
        });
        a.liveTracker.options_.trackingThreshold = 0;
        // a.load();
        return a;
    };

    useEffect(()=>{
        if(urlServer && xAccessToken && fnSetStatePlayer){
            let player = setVideo({
                refVideoTag: videoTag,
                refVideoSrc: videoSource,
                urlServer: urlServer,
                authToken: xAccessToken
            })
            fnSetStatePlayer(player)
        }
    })

    return (
        <div
            style={{
                margin: "auto",
                marginTop: "50px",
                width: "80%",
                display: "inherit"
            }}
            ref={videoDiv}
        >
            <video
                id="hls-video"
                className="video-js vjs-default-skin"
                controls
                preload="auto"
                width="640"
                height="268"
                data-setup="{}"
                ref={videoTag}
            >
                <source
                    src=""
                    type="application/x-mpegURL"
                    ref={videoSource}
                ></source>
            </video>
        </div>
    );
}

export default VideoFrame;
