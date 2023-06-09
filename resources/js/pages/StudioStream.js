import React, { useEffect, useRef, useState } from "react";
import NavTopBar from "../components/Navbar";
import Sidebar from "../components/Sidebar";
import ReactDOM from "react-dom";
import "../../css/studio-main.css";
import CamSelector from "../components/CamSelector";
import { VideoStreamMerger } from "video-stream-merger";
import presets from "../components/Presets";
import SetProgram from "../components/SetProgram";
import BrowserToRtmpClient from "../BrowserRtmp/RtmpBrowser";
import * as Icon from "react-bootstrap-icons";
import Alert from "react-bootstrap/Alert";
import ModalInfoStream from "../components/ModalInfoStream";

let currentStream = [];
let currentAudioStream = [];
let currentCombineStream;
let selectedCams = [];
let streamPrograms = [];
let rtmpClient;

let modeVideo = 1;

let audioInputs = [];

const canvasResolution = {
    height: 1080,
    width: 1920,
    coefX: 1,
    coefY: 1
};

let preset = undefined;

let loopStart = 0;

function stopMediaTracks(stream) {
    if (stream != null || stream != undefined) {
        stream.getTracks().forEach(track => {
            // console.log(track);
            track.stop();
        });
    }
}

function removeMediaTracks(stream) {
    if (stream != null || stream != undefined) {
        stream.getTracks().forEach(track => {
            stream.removeTrack(track);
        });
    }
}

function changeLayout(preset, videoStreams, audioStreams, numOfCam, newVideo) {
    let result;
    let vs = videoStreams;
    console.log(vs[0]);
    // Pengecekan 3 kondisi
    if (vs.length === 0 || vs.length < preset.length) {
        if (newVideo) {
            if (vs.length < preset.length) {
                stopMediaTracks(currentStream[numOfCam - 1]);
                stopMediaTracks(vs[numOfCam - 1]);
            }
            vs[numOfCam - 1] = newVideo;
        }
    } else if (vs.length > preset.length) {
        for (
            let i = vs.length - (vs.length - preset.length);
            i <
            vs.length -
                (vs.length - preset.length) +
                (vs.length - preset.length);
            i++
        ) {
            stopMediaTracks(currentStream[i]);
        }
        vs.splice(
            vs.length - (vs.length - preset.length),
            vs.length - preset.length
        );
        if (newVideo) {
            stopMediaTracks(currentStream[numOfCam - 1]);
            stopMediaTracks(vs[numOfCam - 1]);
            vs[numOfCam - 1] = newVideo;
        }
    } else if (vs.length === preset.length) {
        if (newVideo) {
            console.log("masuk track = preset");
            stopMediaTracks(currentStream[numOfCam - 1]);
            stopMediaTracks(vs[numOfCam - 1]);
            vs[numOfCam - 1] = newVideo;
        }
    }
    // merger preset
    let merger = new VideoStreamMerger();
    if (vs.length > 0) {
        for (let i = 0; i < preset.length; i++) {
            console.log(vs[i], i, preset.length, vs.length, newVideo, numOfCam);
            if (vs[i]) {
                console.log(preset[i]);
                merger.removeStream(vs[i], preset[i]);
                merger.addStream(vs[i], preset[i]);
            }
        }
        merger.width = canvasResolution.width;
        merger.height = canvasResolution.height;
        merger.start();
        // combine tracks
        result = combineTracks(merger.result, audioStreams);
    }

    return { result, vs, resMer: merger.result };
}

function combineTracks(videoInput, arrAudioInput) {
    const constraints = {
        width: { min: 640, ideal: 1280 },
        height: { min: 480, ideal: 720 },
        advanced: [{ width: 1920, height: 1280 }, { aspectRatio: 1.333 }]
    };
    try {
        let tracks = [...videoInput.getVideoTracks()];
        console.log(tracks, "combine tracks");
        // tracks.push(...arrAudioInput.getAudioTracks())
        for (let i = 0; i < arrAudioInput.length; i++) {
            tracks.push(...arrAudioInput[i].getAudioTracks());
        }
        console.log(tracks, "combine tracks 2");
        let newStream = new MediaStream(tracks);
        return newStream;
    } catch (error) {
        console.log(error);
    }
}

function changeVideo(
    mediaDevice,
    currentCombineStream,
    camera = true,
    preset,
    numOfCam,
    setStreamFn
) {
    if (typeof currentCombineStream !== "undefined") {
        stopMediaTracks(currentCombineStream);
    }
    const videoConstraints = {};
    if (mediaDevice === "") {
        videoConstraints.facingMode = "environment";
    } else {
        videoConstraints.deviceId = { exact: mediaDevice };
        videoConstraints.width = { min: 1280, ideal: 1920 };
        videoConstraints.height = { min: 720, ideal: 1080 };
        videoConstraints.advanced = [
            { width: 1920, height: 1080 },
            { aspectRatio: 1.333 }
        ];
    }
    const constraints = {
        video: videoConstraints,
        audio: false
    };

    const constraintScreen = {
        video: { cursor: "always" },
        audio: false
    };

    selectedCams[numOfCam - 1] = mediaDevice;

    const handleSuccess = (stream, audio) => {
        let resSetLayout = changeLayout(
            preset,
            currentStream,
            audio,
            numOfCam,
            stream
        );
        currentStream = null;
        currentStream = resSetLayout.vs;
        setStreamFn(resSetLayout.result);
        console.log(currentStream);
        console.log(resSetLayout.result.getTracks(), "udio stream");
        return navigator.mediaDevices.enumerateDevices();
    };

    if (camera) {
        navigator.mediaDevices
            .getUserMedia(constraints)
            .then(stream => handleSuccess(stream, currentAudioStream))
            .catch(error => {
                console.error(error);
            });
    } else {
        navigator.mediaDevices
            .getDisplayMedia(constraintScreen)
            .then(stream => handleSuccess(stream, currentAudioStream))
            .catch(error => {
                console.error(error);
            });
    }
}

function mergerAudioTrack(currentAudioStream) {
    const audioContext = new AudioContext();
    let audiosIn = [];

    for (let i = 0; i < currentAudioStream.length; i++) {
        audiosIn.push(
            audioContext.createMediaStreamSource(currentAudioStream[i])
        );
    }

    let dest = audioContext.createMediaStreamDestination();

    for (let i = 0; i < audiosIn.length; i++) {
        audiosIn[i].connect(dest);
    }

    return dest.stream;
}

async function getAudio(deviceId, currentAudioStream) {
    // Ubah cara kerja get audio stream
    const audioConstraint = {};
    audioConstraint.deviceId = deviceId;
    try {
        if (deviceId != "--- No selected devices ----") {
            let res = await navigator.mediaDevices.getUserMedia({
                audio: audioConstraint,
                video: false,
                echoCancellation: true,
                // noiseSuppression: true
                suppressLocalAudioPlayback: true
            });
            currentAudioStream.push(res);
            console.log(currentAudioStream, "get audio fn");
        }
    } catch (error) {
        console.log(error);
    }
}

function listDevices(mediaDevices, target, kind, name) {
    try {
        target.innerHTML =
            "<option value=undefined>--- No selected devices ----</option>";
        let count = 1;
        console.log("list devices berjalan ", kind);
        // console.log(target.current.innerHTML);
        mediaDevices.forEach(element => {
            // console.log(element);
            if (element.kind === kind) {
                const option = document.createElement("option");
                option.value = element.deviceId;
                const label = element.label || `${name} ${count}`;
                const textNode = document.createTextNode(label);
                option.appendChild(textNode);
                target.appendChild(option);
                count++;
            }
        });
        if ("videoinput" === kind) {
            const option = document.createElement("option");
            option.value = "share-screen";
            option.id = "share-screen";
            const label = "Share screen";
            const textNode = document.createTextNode(label);
            option.appendChild(textNode);
            target.appendChild(option);
        }
    } catch (err) {
        console.log(err);
    }
}

function listMicrophone(mediaDevices) {
    if (currentAudioStream.length != 0) {
        for (let i = 0; i < currentAudioStream.length; i++) {
            stopMediaTracks(currentAudioStream[i]);
        }
        currentAudioStream = [];
    }
    mediaDevices.forEach(element => {
        audioInputs.push(element.deviceId);
        getAudio(element.deviceId, currentAudioStream);
    });

    // return mergerAudioTrack(currentAudioStream)
    return currentAudioStream;
}

function StudioStream() {
    console.log("studio stream emlaku brooooo");
    const [myData, setMyData] = useState("");
    const [orgId, setOrgId] = useState("");
    const [eventId, setEventId] = useState("");
    const [category, setcategory] = useState("");
    const [breakdown, setBreakdown] = useState("");
    const [progState, setProgState] = useState(0);
    const [combinedStream, setStream] = useState(null);
    const [progStream, setProgStream] = useState(null);
    const [isStreaming, setStateStream] = useState(false);
    const [isReadyStream, setStateReadyStream] = useState(false);
    const [errMsg, setMsgErr] = useState(null);
    const [alertShow, setAlertState] = useState(false);
    const [alertType, setTypeAlert] = useState(null);
    const [sessionId, setSessionId] = useState(null);
    const [token, setTokenJwt] = useState(null);
    const [xAccessToken, setAccessToken] = useState(null);
    const [streamKey, setStreamKey] = useState(null);
    const [showPopUp, setPopUp] = useState(true);

    // const [modeVideo, setMode] = useState("");
    const [listSelectCam, setListSelect] = useState([]);
    const [listSelectMic, setListSelectMic] = useState([]);
    let srcProgram = null;

    const videoPrev = useRef(null);
    const videoProg = useRef(null);
    const canvasRes = useRef(null);
    const divListCam = useRef(null);

    function reloadDevices() {
        navigator.mediaDevices.enumerateDevices().then(listMic);
        navigator.mediaDevices.enumerateDevices().then(listCam);
    }

    function handleClose() {
        setPopUp(false);
    }

    function listCam(mediaDevices) {
        let index = 0;
        document.querySelectorAll(".list-select").forEach(el => {
            listDevices(mediaDevices, el, "videoinput", "Camera");
            el.value = selectedCams[index];
            index++;
        });
    }

    function listMic(mediaDevices) {
        let mics = [];
        let index = 1;
        mediaDevices.forEach(el => {
            if (el.kind == "audioinput") {
                mics.push(
                    <option key={index} value={el.deviceId}>
                        {el.label ? el.label : `Input audio ${index}`}
                    </option>
                );
                index++;
            }
        });
        console.log(mics);
        setListSelectMic(mics);
    }

    function changeCamera(event) {
        console.log(preset[modeVideo - 1][0], modeVideo - 1);
        if (event.target.value == "undefined") {
            // stopMediaTracks(combinedStream);
            if (typeof combinedStream !== "undefined") {
                stopMediaTracks(combinedStream);
            }
            stopMediaTracks(currentStream[parseInt(event.target.id) - 1]);
            removeMediaTracks(currentStream[parseInt(event.target.id) - 1]);
            let resChanged = changeLayout(
                preset[modeVideo - 1][0],
                currentStream,
                currentAudioStream,
                undefined,
                undefined
            );
            currentStream = resChanged.vs;
            selectedCams[parseInt(event.target.id) - 1] = event.target.value;
            setTimeout(() => {
                setStream(resChanged.result);
            }, 500);
            // setStream(resChanged.result);
        } else if (event.target.value === "share-screen") {
            changeVideo(
                event.target.value,
                combinedStream,
                false,
                preset[modeVideo - 1][0],
                parseInt(event.target.id),
                setStream
            );
        } else {
            changeVideo(
                event.target.value,
                combinedStream,
                undefined,
                preset[modeVideo - 1][0],
                parseInt(event.target.id),
                setStream
            );
        }
    }

    function changeAudioIn(event) {
        if (currentAudioStream.length != 0) {
            for (let i = 0; i < currentAudioStream.length; i++) {
                stopMediaTracks(currentAudioStream[i]);
                removeMediaTracks(currentAudioStream[i]);
            }
            currentAudioStream = [];
            console.log(currentAudioStream);
        }
        if (typeof combinedStream !== null) {
            stopMediaTracks(combinedStream);
        }
        getAudio(event.target.value, currentAudioStream).then(() => {
            console.log(event.target.value, "chang eaudio");
            let resChanged = changeLayout(
                preset[modeVideo - 1][0],
                currentStream,
                currentAudioStream,
                undefined,
                undefined
            );
            currentStream = resChanged.vs;
            console.log(resChanged, "Change audio");
            setStream(resChanged.result);
        });
    }

    function changeResolution(event) {
        event.preventDefault();
        let numOfCam = parseInt(event.target[0].id) - 1;
        let originPreset = presets()[modeVideo - 1][0][numOfCam];
        let centerX = event.target[1].checked;
        let centerY = event.target[2].checked;

        originPreset.x = originPreset.x / canvasResolution.coefX;
        originPreset.y = originPreset.y / canvasResolution.coefY;
        originPreset.width = originPreset.width / canvasResolution.coefX;
        originPreset.height = originPreset.height / canvasResolution.coefY;
        console.log(originPreset, canvasResolution, "origin preset");

        const centerPos = (originX, originLenX, newLenX) => {
            let x = originX + (originLenX / 2 - newLenX / 2);
            return x;
        };

        if (event.target[0].value.match(/x/)) {
            let resX = parseInt(event.target[0].value.split("x")[0]);
            let resY = parseInt(event.target[0].value.split("x")[1]);
            if (
                centerX &&
                centerY &&
                (resX <= originPreset.width || resY <= originPreset.height)
            ) {
                preset[modeVideo - 1][0][numOfCam].width = resX;
                preset[modeVideo - 1][0][numOfCam].height = resY;
                let newX = centerPos(originPreset.x, originPreset.width, resX);
                let newY = centerPos(originPreset.y, originPreset.height, resY);
                preset[modeVideo - 1][0][numOfCam].x = newX;
                preset[modeVideo - 1][0][numOfCam].y = newY;
            } else if (centerX && resX <= originPreset.width) {
                preset[modeVideo - 1][0][numOfCam].width = resX;
                preset[modeVideo - 1][0][numOfCam].height = resY;
                let newX = centerPos(originPreset.x, originPreset.width, resX);
                preset[modeVideo - 1][0][numOfCam].x = newX;
            } else if (centerY && resY <= originPreset.height) {
                preset[modeVideo - 1][0][numOfCam].width = resX;
                preset[modeVideo - 1][0][numOfCam].height = resY;
                let newY = centerPos(originPreset.y, originPreset.height, resY);
                preset[modeVideo - 1][0][numOfCam].y = newY;
                console.log(newY);
            }

            if (resX <= originPreset.width || resY <= originPreset.height) {
                preset[modeVideo - 1][0][numOfCam].width = resX;
                preset[modeVideo - 1][0][numOfCam].height = resY;
                if (
                    originPreset.x === 0 &&
                    originPreset.y === 0 &&
                    originPreset.height === 1080 &&
                    originPreset.width === 960 &&
                    centerX == false
                ) {
                    console.log("masuk bagi 3");
                    preset[modeVideo - 1][0][numOfCam].x = originPreset.x;
                    if (centerY == false) {
                        preset[modeVideo - 1][0][numOfCam].y = originPreset.y;
                        console.log("masuk tidak centerY");
                    }
                    for (let i = 0; i < preset[modeVideo - 1][0].length; i++) {
                        if (
                            presets()[modeVideo - 1][0][i].x !== 0 ||
                            presets()[modeVideo - 1][0][i].y !== 0
                        ) {
                            preset[modeVideo - 1][0][i].x = resX + 1;
                        }
                    }
                } else if (originPreset.x === 0 && originPreset.y === 0) {
                    if (centerX == false)
                        preset[modeVideo - 1][0][numOfCam].x = originPreset.x;
                    if (centerY == false)
                        preset[modeVideo - 1][0][numOfCam].y = originPreset.y;
                    console.log("masuk sini");
                    for (let i = 0; i < preset[modeVideo - 1][0].length; i++) {
                        if (
                            presets()[modeVideo - 1][0][i].x !== 0 &&
                            presets()[modeVideo - 1][0][i].y === 0 &&
                            centerX == false
                        ) {
                            preset[modeVideo - 1][0][i].x = resX + 1;
                        } else if (
                            presets()[modeVideo - 1][0][i].x === 0 &&
                            presets()[modeVideo - 1][0][i].y !== 0 &&
                            centerY == false
                        ) {
                            preset[modeVideo - 1][0][i].y = resY + 1;
                        }
                    }
                } else if (originPreset.x === 0 && centerX == false) {
                    preset[modeVideo - 1][0][numOfCam].x = originPreset.x;
                    if (centerY == false)
                        preset[modeVideo - 1][0][numOfCam].y = originPreset.y;
                    for (let i = 0; i < preset[modeVideo - 1][0].length; i++) {
                        if (
                            presets()[modeVideo - 1][0][i].x !== 0 &&
                            presets()[modeVideo - 1][0][i].y !== 0
                        ) {
                            preset[modeVideo - 1][0][i].x = resX + 1;
                        }
                    }
                } else if (originPreset.y === 0 && centerY == false) {
                    if (centerX == false)
                        preset[modeVideo - 1][0][numOfCam].x = originPreset.x;
                    preset[modeVideo - 1][0][numOfCam].y = originPreset.y;
                    for (let i = 0; i < preset[modeVideo - 1][0].length; i++) {
                        if (
                            presets()[modeVideo - 1][0][i].x !== 0 &&
                            presets()[modeVideo - 1][0][i].y !== 0
                        ) {
                            preset[modeVideo - 1][0][i].y = resY + 1;
                        }
                    }
                }
            }
            stopMediaTracks(combinedStream);
            let resChanged = changeLayout(
                preset[modeVideo - 1][0],
                currentStream,
                currentAudioStream,
                undefined,
                undefined
            );
            currentStream = resChanged.vs;
            console.log(resChanged);
            setStream(resChanged.result);
        } else {
            event.target[0].value = `${originPreset.width}x${originPreset.height}`;
        }
    }

    function changeCanvasResolution(event) {
        event.preventDefault();
        if (event.target[0].value.match(/x/)) {
            console.log(event.target[0].value);
            let width = parseInt(event.target[0].value.split("x")[0]);
            let height = parseInt(event.target[0].value.split("x")[1]);
            canvasResolution.width = width;
            canvasResolution.height = height;
            canvasResolution.coefX = 1920 / width;
            canvasResolution.coefY = 1080 / height;
            stopMediaTracks(combinedStream);
            let resChanged = changeLayout(
                preset[modeVideo - 1][0],
                currentStream,
                currentAudioStream,
                undefined,
                undefined
            );
            currentStream = resChanged.vs;
            console.log(resChanged);
            setStream(resChanged.result);
        } else {
            event.target[0].value = `${canvasResolution.width}x${canvasResolution.height}`;
        }
    }

    function changeMode(event) {
        let mode = parseInt(event.target.value);
        let arrSelect = [];
        // let preset = presets()[mode - 1];
        for (let i = 0; i < preset[mode - 1][0].length; i++) {
            arrSelect.push(
                <CamSelector
                    key={i + 1}
                    id={i + 1}
                    changeCamFn={changeCamera}
                    changeResFn={() => {}}
                    submitHandle={changeResolution}
                    value={`${preset[mode - 1][0][i].width}x${
                        preset[mode - 1][0][i].height
                    }`}
                ></CamSelector>
            );
        }
        setListSelect(arrSelect);
        modeVideo = mode;
        console.log(mode, modeVideo);
        stopMediaTracks(combinedStream);
        let resChanged = changeLayout(
            preset[mode - 1][0],
            currentStream,
            currentAudioStream,
            undefined,
            undefined
        );
        currentStream = resChanged.vs;
        console.log(resChanged);
        setStream(resChanged.result);
    }

    function applyProgram() {
        let stream = new SetProgram(
            currentStream,
            currentAudioStream,
            changeLayout,
            modeVideo,
            preset
        );
        streamPrograms[progState] = stream;
        setProgStream(stream.getResult());
        if (rtmpClient) rtmpClient.restart(stream.getResult());
        else console.log("RTMP client tidak ada");
    }

    function startStream() {
        let loop = 0;
        if (progStream && !rtmpClient) {
            console.log(
                `rtmp://${process.env.MIX_RTMP_HOST}:${process.env.MIX_RTMP_PORT}${process.env.MIX_RTMP_PATH}/${streamKey}?email=${myData.email}&password=${token}&session=${sessionId}`
            );
            rtmpClient = new BrowserToRtmpClient(progStream, {
                host: `https://${process.env.MIX_PEER_HOST}`,
                rtmp: `rtmp://${process.env.MIX_RTMP_HOST}:${process.env.MIX_RTMP_PORT}${process.env.MIX_RTMP_PATH}/${streamKey}?email=${myData.email}&password=${token}&session=${sessionId}`, // RTMP endpoint
                port: process.env.MIX_MANAGE_STREAM_SERVER_PORT,
                socketio: {
                    cors: {
                        withCredentials: true
                    },
                    query: {
                        token: xAccessToken
                    }
                }
            });
            try {
                rtmpClient.start();
                setStateStream(true);
                setMsgErr("Stream success to start");
                setTypeAlert("success");
                setAlertState(true);
            } catch (err) {
                console.log(err, "error stream");
                setMsgErr(err.message);
                setTypeAlert("danger");
                setAlertState(true);
                rtmpClient.stop();
            }
            rtmpClient.on("error", error => {
                // console.log(`An error occured: ${error}`);
                console.log(error);
                stopStream();
                setMsgErr(error.name);
                setTypeAlert("danger");
                setAlertState(true);
                // window.location.reload();
            });
            rtmpClient.on("connect_error", error => {
                if (loop == 0) {
                    stopStream();
                    setStateStream(false);
                    console.log(`An error connection: ${error}`);
                    console.log(error.message);
                    setMsgErr(error.message);
                    setTypeAlert("danger");
                    setAlertState(true);
                    rtmpClient = undefined;
                    loop++;
                }
            });
        } else {
            console.log("streaming belum di set");
        }
    }

    function stopStream() {
        if (rtmpClient) {
            rtmpClient.stop();
            rtmpClient = undefined;
            setStateStream(false);
            setMsgErr("Stream success to stop");
            setTypeAlert("success");
            setAlertState(true);
        }
    }

    useEffect(() => {
        setTimeout(() => [setAlertState(false)], 5000);
    }, [alertShow]);

    useEffect(() => {
        if (progStream && !isStreaming) setStateReadyStream(true);
        else setStateReadyStream(false);
        console.log(isReadyStream, !isStreaming, "ready start");
    }, [isStreaming, progStream]);

    useEffect(() => {
        videoPrev.current.srcObject = combinedStream;
    }, [combinedStream]);

    useEffect(() => {
        console.log("jalan di sebelum prog state", progState, progStream);
        if (progStream) {
            videoProg.current.srcObject = progStream;
            setTimeout(() => {
                // videoProg.current.srcObject = streamPrograms[progState].getResult();
                if (progState === 0) {
                    if (streamPrograms[1]) {
                        streamPrograms[1].stopTrack(stopMediaTracks);
                        streamPrograms[1] = undefined;
                    }
                    setProgState(1);
                } else if (progState == 1) {
                    if (streamPrograms[0]) {
                        streamPrograms[0].stopTrack(stopMediaTracks);
                        streamPrograms[0] = undefined;
                    }
                    setProgState(0);
                }
            }, 1000);
            console.log("jalan di prog state");
        }
    }, [progStream]);

    useEffect(() => {
        let myDataRaw = document.getElementById("my-data");
        let orgIdRaw = document.getElementById("org-id");
        let eventIdRaw = document.getElementById("event-id");
        let categoryRaw = document.getElementById("category");
        let breakdownRaw = document.getElementById("breakdown");
        let sesssionId = document.getElementById("session-id");
        let token = document.getElementById("password-token");
        let xAccessToken = document.getElementById("x-access-token");
        let streamKey = document.getElementById("stream-key");

        if (loopStart === 0) {
            setMyData(JSON.parse(myDataRaw.value));
            setOrgId(orgIdRaw.value);
            setEventId(eventIdRaw.value);
            setcategory(categoryRaw.value);
            setBreakdown(breakdownRaw.value);
            setSessionId(sesssionId.value);
            setTokenJwt(token.value);
            setAccessToken(xAccessToken.value);
            setStreamKey(streamKey.value);

            myDataRaw.remove();
            orgIdRaw.remove();
            eventIdRaw.remove();
            categoryRaw.remove();
            breakdownRaw.remove();
            sesssionId.remove();
            token.remove();
            streamKey.remove();
            xAccessToken.remove();

            preset = presets();

            setListSelect([
                <CamSelector
                    key={1}
                    id={1}
                    changeCamFn={changeCamera}
                    changeResFn={() => {}}
                    submitHandle={changeResolution}
                    value={`${preset[0][0][0].width}x${preset[0][0][0].height}`}
                    // refValue={camSelectors}
                ></CamSelector>
            ]);
            // camSelectors.current[0].value = `${preset[0][0][0].width}x${preset[0][0][0].height}`
            navigator.mediaDevices.enumerateDevices().then(listMic);
        }
        navigator.mediaDevices.enumerateDevices().then(listCam);

        loopStart++;
    });

    return (
        <div>
            {/* Pop Up Petunjuk penggunaan */}
            <ModalInfoStream handleClose={handleClose} showPopUp={showPopUp} email={myData.email} passToken={token} host={process.env.MIX_RTMP_HOST} port={process.env.MIX_RTMP_PORT} path={process.env.MIX_RTMP_PATH} streamKey={streamKey} sessionId={sessionId}></ModalInfoStream>
            <NavTopBar
                myData={myData}
                orgId={orgId}
                eventId={eventId}
                category={category}
                breakdown={breakdown}
            ></NavTopBar>
            <div className="row m-0">
                <div className="col-lg-3" id="sidebar">
                    <Sidebar
                        orgId={orgId}
                        eventId={eventId}
                        breakdown={breakdown}
                        category={category}
                    ></Sidebar>
                </div>
                <div className="col-lg-9">
                    <div className="row main-container">
                        <div className="col-lg-6">
                            <h5>Preview Video </h5>
                            <video
                                id="preview"
                                src="..."
                                className="object-fit-scale bg-secondary w-100"
                                autoPlay
                                ref={videoPrev}
                                style={{ aspectRatio: "16/9" }}
                                muted={true}
                            ></video>
                            <hr></hr>
                        </div>
                        <div className="col-lg-6">
                            <h5>Program Video</h5>
                            <video
                                id="program"
                                src="..."
                                // srcObject = {srcProgram}
                                className="object-fit-scale bg-secondary w-100"
                                autoPlay
                                ref={videoProg}
                                style={{ aspectRatio: "16/9" }}
                                muted={true}
                            ></video>
                            <hr></hr>
                        </div>
                        <div className="col-12">
                            <h5>Select your mode</h5>
                            <select
                                className="form-select mb-3"
                                onChange={changeMode}
                            >
                                <option value={1}>Single Screen</option>
                                <option value={2}>Split Dual Screen</option>
                                <option value={3}>Split Kuadran Screen</option>
                                <option value={4}>Tutorial Mode</option>
                                <option value={5}>Split Triple Screen</option>
                            </select>
                            <h5>Canvas Resoution</h5>
                            <p className="text-danger">
                                *Press Enter to apply canvas resolution
                            </p>
                            <form onSubmit={changeCanvasResolution}>
                                <input
                                    className="form-control"
                                    placeholder="Default resolution is FHD 16:9 (1920 x 1080)"
                                    ref={canvasRes}
                                ></input>
                            </form>
                            <h5 className="mt-4">Select your audio input</h5>
                            <div>
                                <div className="col-md-12">
                                    <select
                                        className="form-select mb-3"
                                        onChange={changeAudioIn}
                                    >
                                        <option value={null}>
                                            --- No selected devices ----
                                        </option>
                                        {listSelectMic}
                                    </select>
                                </div>
                            </div>
                            <h5 className="mt-4">Select your camera</h5>
                            <div ref={divListCam}>{listSelectCam}</div>
                        </div>
                        <div className="col-12">
                            <div className="row mt-4">
                                <div className="col-12">
                                    <Alert
                                        className={`mt-3 mb-3 ${
                                            alertShow ? "" : "d-none"
                                        }`}
                                        key={alertType}
                                        variant={alertType}
                                    >
                                        {errMsg}
                                    </Alert>
                                </div>

                                <div className="col-12 mb-3">
                                    <label>Action control button :</label>
                                </div>

                                <div className="col-md-3 text-center">
                                    <button
                                        className="btn btn-warning mb-5"
                                        onClick={reloadDevices}
                                    >
                                        <i className="fa fa-refresh"></i> Reload
                                        Devices
                                    </button>
                                </div>
                                <div className="col-md-3 text-center">
                                    <button
                                        className="btn btn-success mb-5"
                                        onClick={applyProgram}
                                    >
                                        Apply to Program
                                    </button>
                                </div>
                                <div className="col-md-3 text-center">
                                    <button
                                        className="btn btn-primary mb-5"
                                        onClick={startStream}
                                        disabled={!isReadyStream}
                                    >
                                        <Icon.Play className="fs-4"></Icon.Play>{" "}
                                        Start Stream
                                    </button>
                                </div>
                                <div className="col-md-3 text-center">
                                    <button
                                        className="btn btn-danger mb-5"
                                        onClick={stopStream}
                                        disabled={!isStreaming}
                                    >
                                        <Icon.Stop className="fs-4"></Icon.Stop>{" "}
                                        Stop Stream
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default StudioStream;

if (document.getElementById("studio")) {
    ReactDOM.render(<StudioStream />, document.getElementById("studio"));
}
