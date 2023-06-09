import "../../css/videconference.css";
import Peer from "peerjs";
import { useEffect, useRef, useState } from "react";
import io from "socket.io-client";
import React from "react";
import ReactDOM from "react-dom";
import {
    ArrowLeftCircle,
    ArrowRightCircle,
    BoxArrowInUp,
    CameraVideo,
    CameraVideoOff,
    Mic,
    MicMute
} from "react-bootstrap-icons";
import Dropdown from "react-bootstrap/Dropdown";
import PopUpRaiseHand from "../components/PopUpRaiseHand";

let loop = 0;
let loopSet = 0;
let loopSetShare = 0;
let loopSetShare2 = 0;
let idShare = 0;
const otherUsername = [];
let myVideoStream;
const peers = [];
let lastMediaId = undefined;
let mode;
let nowPos = 0;
let isStream = false;

const getInitial = name => {
    let initial = "";
    name.split(" ").forEach(part => {
        initial += part[0];
    });
    return initial;
};

const autoArangeDelete = ({ slides, startPosition }) => {
    for (let i = startPosition; i < slides.length; i++) {
        if (i < slides.length - 1) {
            let nextSlide = slides[i + 1].getElementsByClassName("video-grid");
            slides[i].appendChild(nextSlide[0]);
            nextSlide[0].remove();
        }
    }
};

const setLabelMic = ({ muted = false, peerId }) => {
    let micLabel = document.getElementById(peerId).getElementsByTagName("i")[0];
    if (muted) {
        micLabel.classList.remove("bi-mic");
        micLabel.classList.add("bi-mic-mute");
    } else {
        micLabel.classList.remove("bi-mic-mute");
        micLabel.classList.add("bi-mic");
    }
};

const setCoverCam = ({ disabled = false, peerId, name }) => {
    let parentGrid = document.getElementById(peerId);
    let cover = parentGrid.getElementsByClassName("cam-cover")[0];
    let video = parentGrid.getElementsByTagName("video")[0];
    if (cover == undefined) {
        cover = document.createElement("div");
        cover.classList.add("cam-cover");
        cover.classList.add("d-none");
        cover.innerHTML = `<div class="avatar" style="background-color: #${Math.floor(
            Math.random() * 16777215
        ).toString(16)}">
            <p class="text-avatar">
            ${getInitial(name)}
            </p>
        </div>`;
        parentGrid.appendChild(cover);
    }
    if (disabled) {
        video.classList.add('d-none');
        cover.classList.remove("d-none");
    } else {
        video.classList.remove('d-none');
        cover.classList.add("d-none");
    }
};

const pinnedLayout = async (stream, name, videoEl, videoGrids, idGrid) => {
    // let videoEl = document.createElement("video");
    videoEl.srcObject = stream;
    videoEl.addEventListener("loadedmetadata", () => {
        videoEl.play();
    });
    const videoGrid = document.createElement("div");
    videoGrid.id = idGrid;
    videoGrid.classList.add("video-grid");
    // const label = document.createElement("p");
    // label.classList.add("label");
    // label.innerHTML = name;
    // videoGrid.appendChild(label);

    if (!navigator.userAgent.match(/firefox|fxios/i)) {
        let cover = document.createElement("div");
        cover.classList.add("cam-cover-overlay");
        cover.classList.add("position-absolute");
        cover.innerHTML = `<div class="avatar avatar-pin" style="background-color: #68656578">
                <p class="text-avatar font-pin">
                    <i class="bi bi-pip"></i>
                </p>
            </div>`;
        cover.onclick = function() {
            if ("pictureInPictureEnabled" in document) {
                if (document.pictureInPictureElement) {
                    document.exitPictureInPicture().catch(err => {
                        console.log(err);
                    });
                } else {
                    videoEl
                        .requestPictureInPicture()
                        .then(() => {
                            videoEl.play();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            }
        };

        videoGrid.appendChild(cover);
    }

    videoGrid.append(videoEl);
    videoGrid.style.width = `calc(63.6% - 15px)`;
    videoGrid.style.height = "unset";
    videoGrid.style.aspectRatio = "16/9";

    let indexTarget;
    let slides = videoGrids.current.getElementsByClassName("pin-share-screen");

    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.forEach(classText => {
            if (idGrid.match(classText)) {
                indexTarget = i;
                i = slides.length;
            }
        });
    }

    if (indexTarget === undefined) {
        let videoSlide = document.createElement("div");
        videoSlide.classList.add("video-slide");
        videoSlide.classList.add("pin-share-screen");
        videoSlide.classList.add(idGrid.split("-x-")[0]);
        videoSlide.appendChild(videoGrid);
        videoGrids.current
            .querySelectorAll(".video-slide")[0]
            .insertAdjacentElement("beforebegin", videoSlide);

        videoGrids.current.style.transform = "translate(0, 0)";
        nowPos = 0;
    } else {
        slides[indexTarget]
            .getElementsByClassName("video-grid")[0]
            .insertAdjacentElement("beforebegin", videoGrid);

        videoGrids.current.style.transform = `translate(-${indexTarget *
            100}%, 0)`;
        nowPos = -(indexTarget * 100);
        setLayoutSharePinning(slides[indexTarget]);
    }
};

const shareMedia = async (
    peerjs,
    mainPeerjs,
    socket,
    name,
    videoGrids,
    stopBtn,
    deviceId,
    fnSetShareState,
    fnSetAudioTracks
) => {
    try {
        console.log("Mulai mengakses screen");

        let video;
        // let audio;

        let audioTracks = [];
        let devices = await navigator.mediaDevices.enumerateDevices();
        devices.forEach(e => {
            if(e.kind == "audioinput"){
                navigator.mediaDevices.getUserMedia({
                    video: false,
                    audio: {
                        deviceId: e.deviceId
                    }
                }).then(stream => {
                    let audTrack = stream.getTracks()[0];
                    audTrack.labelDevice = e.label;
                    audioTracks.push(audTrack);
                })
            }
        })

        if (deviceId === "share-screen") {
            video = await navigator.mediaDevices.getDisplayMedia({
                video: true,
                audio: false
            });

            // audio = await navigator.mediaDevices.getUserMedia({
            //     video: false,
            //     audio: false
            // });
        } else {
            video = await navigator.mediaDevices.getUserMedia({
                video: {
                    deviceId: { exact: deviceId }
                },
                audio: false
            });

            // audio = new MediaStream([...video.getTracks()]);
        }

        if (video && audioTracks.length > 0) {
            let finalAV;
            finalAV = new MediaStream([
                ...video.getVideoTracks(),
                ...audioTracks
            ]);

            let videoEl = document.createElement("video");
            videoEl.muted = true;
            // videoEl.id = peerjs.id;
            // let video = new MediaStream([...finalAV.getVideoTracks()])
            pinnedLayout(video, name, videoEl, videoGrids, peerjs.id);
            isStream = true;

            const endVideo = () => {
                if (isStream) {
                    isStream = false;
                    socket.emit("share-disconnect", peerjs.id);
                    console.log("share-disconnect", peerjs.id);
                    console.log(finalAV);
                    try {
                        finalAV.getTracks().forEach(track => {
                            track.stop();
                        });
                    } catch (error) {
                        console.log(error);
                    }
                    peerjs.destroy();
                    finalAV = null;
                    // audio = null;
                    video = null;
                    videoEl.remove();
                    fnSetShareState(false);
                    fnSetAudioTracks([]);
                    RemoveUnusedDivs(videoGrids);
                }
            };
            video.getVideoTracks()[0].onended = endVideo;
            stopBtn.current.addEventListener("click", () => {
                isStream = false;
                socket.emit("share-disconnect", peerjs.id);
                console.log("share-disconnect", peerjs.id);
                console.log(finalAV);
                try {
                    finalAV.getTracks().forEach(track => {
                        track.stop();
                    });
                } catch (error) {
                    console.log(error);
                }
                peerjs.destroy();
                finalAV = null;
                // audio = null;
                video = null;
                videoEl.remove();
                fnSetShareState(false);
                fnSetAudioTracks([]);
                RemoveUnusedDivs(videoGrids);
            });

            const calling = id => {
                if (id != mainPeerjs.id && isStream) {
                    peerjs.call(id, finalAV);
                }
            };

            let lastIdGuest;
            socket.on("user-connected", (id, username) => {
                if (lastIdGuest == undefined || lastIdGuest != id) {
                    console.log(
                        "Menerima panggilan user baru untuk meminta share media"
                    );
                    calling(id);
                    lastIdGuest = id;
                }
            });

            fnSetAudioTracks(audioTracks);
            console.log(audioTracks);
            console.log(finalAV);
            return finalAV;
        } else {
            console.log("Failed, AV not success get source");
            return null;
        }
    } catch (error) {
        console.log(error);
    }
};

const muteAudioShare = (track, evt) => {
    let icon = evt.currentTarget.getElementsByTagName("i")[0];
    if(track.enabled === true){
        track.enabled = false;
        icon.classList.remove('bi-mic');
        icon.classList.add('bi-mic-mute');
    }else{
        track.enabled = true;
        icon.classList.remove('bi-mic-mute');
        icon.classList.add('bi-mic');
    }
}


const slideLeft = () => {
    let slides = document.querySelectorAll(".video-slide");
    if (Math.abs(nowPos) < (slides.length - 1) * 100 && slides.length > 1) {
        document.getElementById(
            "video-grids"
        ).style.transform = `translate(${nowPos - 100}%, 0)`;
        nowPos = nowPos - 100;
        console.log(nowPos);
    }
};

const slideRight = () => {
    let slides = document.querySelectorAll(".video-slide");
    if (Math.abs(nowPos) > 0 && slides.length > 1) {
        document.getElementById(
            "video-grids"
        ).style.transform = `translate(${nowPos + 100}%, 0)`;
        nowPos = nowPos + 100;
        console.log(nowPos);
    }
};

const changeLayout = (width, height, videoGrid, counVideo) => {
    if (width <= 992 && width > 850) {
        // execute change layout by width stop 1
        console.log("execute change layout by width stop 1");
        videoGrid.style.width = "unset";
        videoGrid.style.height = `calc(${100 /
            Math.round(counVideo / 2)}% - 30px)`;
    } else if (width <= 850) {
        // execute change layout by  with stop 2
        videoGrid.style.width = "unset";
        videoGrid.style.height = `calc(${100 / counVideo}% - 30px)`;
    } else {
        // width gt 992 px
        if (height <= 630 && height > 520) {
            // execute change layout by height stop 1
            console.log("execute change layout by height stop 1");
            videoGrid.style.width = "unset";
            videoGrid.style.height = `calc(50% - 30px)`;
        } else if (height <= 520) {
            // execute change layout by height stop 2
            console.log("execute change layout by height stop 2", counVideo);
            videoGrid.style.width = `calc(${100 / counVideo}% - 30px)`;
            videoGrid.style.height = "unset";
        } else {
            // execute chang elayout to standar mode
            //   let users = document.querySelectorAll(".video-grid");
            if (counVideo > 1) {
                if (counVideo > 1 && counVideo <= 3) {
                    videoGrid.style.width = `calc(33.33% - 25px)`;
                    videoGrid.style.height = "unset";
                    videoGrid.style.aspectRatio = "1 / 1";
                } else if (counVideo > 3) {
                    videoGrid.style.width = `calc(33.33% - 30px)`;
                    videoGrid.style.height = "unset";
                    videoGrid.style.aspectRatio = "16 / 9";
                }
            } else if (counVideo === 1) {
                videoGrid.style.width = `calc(60% - 15px)`;
                videoGrid.style.height = "unset";
                videoGrid.style.aspectRatio = "16/9";
            }
        }
    }
};

const setLayoutSharePinning = slide => {
    let grids = slide.getElementsByClassName("video-grid");
    let isMatch = false;
    for (let i = 0; i < grids.length; i++) {
        if (grids[i].id.match("universal-media-share-name")) {
            isMatch = true;
            i = grids.length;
        }
    }
    if (isMatch) {
        if (grids.length <= 3) {
            // sesuikan witdhny saja
            // Height cukup megikuti
            for (let i = 1; i < grids.length; i++) {
                grids[i].style.width = "unset";
                grids[i].style.height = "calc(33.3% - 15px)";
                grids[i].style.aspectRatio = "16/9";
            }
        } else {
            // sesuaikan width dan heigth dengan jumlah video
            for (let i = 1; i < grids.length; i++) {
                grids[i].style.width = "unset";
                grids[i].style.height = "calc(16.6% - 15px)";
                grids[i].style.aspectRatio = "16/9";
            }
        }
        // for (let i = 1; i < grids.length; i++) {
        //     grids[i].style.width = "unset";
        //     grids[i].style.height = "calc(33.33% - 15px)";
        //     grids[i].style.aspectRatio = "16/9";
        // }
    } else {
        let width = window.innerWidth;
        let height = window.innerHeight;

        for (let i = 0; i < grids.length; i++) {
            changeLayout(width, height, grids[i], grids.length);
        }
    }
};

const removePin = (slide, videoGrid) => {
    videoGrid.remove();
    if (slide.getElementsByTagName("video").length == 0) {
        slide.remove();
    }
    setLayoutSharePinning(slide);
};

const pinVideo = (idTarget, videoGrids, peerIdHost, socket, isHost) => {
    // Belum sempurna. Sesuaikan id grid share screen dalam slide dengan id peer yang melakukan pinning
    let videogrid = [];
    let allVideoGrid = document.querySelectorAll(".video-grid");
    let maxPinned = false;
    allVideoGrid.forEach(videoGrid => {
        // console.log(videoGrid.id, idTarget);
        if (videoGrid.id == idTarget) {
            videogrid.push(videoGrid);
        }
    });
    if (videogrid.length < 2 && videogrid.length > 0) {
        console.log(videogrid);
        let indexTarget;
        videogrid = videogrid[0];
        let clone = videogrid.cloneNode(true);
        let videoTag = clone.getElementsByTagName(
            "video"
        )[0];
        videoTag.srcObject = videogrid.getElementsByTagName("video")[0].srcObject;
        videoTag.muted = true;
        videoTag.play();
        if (isHost) {
            clone.getElementsByClassName(`font-pin`)[0].innerHTML =
                '<i class="bi bi-pin-angle"></i>';
        }

        let slide = document.getElementsByClassName("pin-share-screen");
        for (let i = 0; i < slide.length; i++) {
            // let grids = slide[i].getElementsByClassName('video-grid')
            if (slide[i].classList.toString().indexOf(peerIdHost) != -1) {
                indexTarget = i;
                if (slide[i].getElementsByClassName("video-grid").length >= 6) {
                    maxPinned = true;
                }
                i = slide.length;
            }
        }

        if (maxPinned === false) {
            if (indexTarget == undefined) {
                slide = [];
            }

            if (slide.length == 0) {
                // clone.id = peerIdHost
                let videoSlide = document.createElement("div");
                videoSlide.classList.add("video-slide");
                videoSlide.classList.add("pin-share-screen");
                videoSlide.classList.add(peerIdHost);
                videoSlide.appendChild(clone);
                videoGrids.current
                    .querySelectorAll(".video-slide")[0]
                    .insertAdjacentElement("beforebegin", videoSlide);
                videoGrids.current.style.transform = "translate(0, 0)";
                nowPos = 0;
                slide = videoSlide;
            } else {
                videoGrids.current.style.transform = `translate(-${indexTarget *
                    100}%, 0)`;
                nowPos = -(indexTarget * 100);
                slide = slide[indexTarget];
            }
            if (isHost) {
                clone.getElementsByClassName(`font-pin`)[0].onclick = () => {
                    remoteRemovePin(clone.id, slide.classList[2], socket);
                };
            }
            slide.appendChild(clone);
            setLayoutSharePinning(slide);
        }
    }
};

const remotePinning = (idTarget, peerIdHost, socket, isHost) => {
    if (isHost) {
        let storage = JSON.parse(localStorage.getItem("pinning-tmp"));
        let findId = false;
        if (storage !== null) {
            for (let i = 0; i < storage.length; i++) {
                if (storage[i].targetId === idTarget) {
                    findId = true;
                }
            }
        } else {
            storage = [];
        }

        if ((storage.length < 6 && findId === false) || storage.length === 0) {
            storage.push({
                targetId: idTarget,
                hostId: peerIdHost
            });
            localStorage.setItem("pinning-tmp", JSON.stringify(storage));
            socket.emit("command-pinning", idTarget, peerIdHost);
        } else {
            console.log("Notify fialed remoting");
        }
    }
};

const remoteRemovePin = (idTarget, peerIdHost, socket) => {
    let storage = JSON.parse(localStorage.getItem("pinning-tmp"));
    if (storage !== null) {
        for (let i = 0; i < storage.length; i++) {
            if (storage[i].targetId === idTarget) {
                storage.splice(i, 1);
                socket.emit("command-rm-pinning", idTarget, peerIdHost);
            }
        }
        if (storage.length === 0) {
            localStorage.removeItem("pinning-tmp");
        } else {
            localStorage.setItem("pinning-tmp", JSON.stringify(storage));
        }
    }
};

const changeSize = () => {
    // console.log(event.target);
    let width = window.innerWidth;
    let height = window.innerHeight;

    let slides = document.querySelectorAll(".video-slide");
    slides.forEach(slide => {
        let listClass = slide.classList.toString();
        if (listClass.indexOf("pin-share-screen") == -1) {
            let videoGrids = slide.querySelectorAll(".video-grid");
            videoGrids.forEach(videoGrid => {
                changeLayout(width, height, videoGrid, videoGrids.length);
            });
        }
    });
};

const RemoveUnusedDivs = videoGrids => {
    //
    let removedPosition;
    let slides = videoGrids.current.getElementsByClassName("video-slide");
    for (let i = 0; i < slides.length; i++) {
        let grids = slides[i].getElementsByClassName("video-grid");
        for (let j = 0; j < grids.length; j++) {
            let target = grids[j].getElementsByTagName("video").length;
            if (target == 0) {
                let id = grids[j].id;
                grids[j].remove();

                let slideSameClass = videoGrids.current.getElementsByClassName(
                    id
                );
                if (slideSameClass.length === 1) {
                    slideSameClass[0].remove();
                }

                let duplicate = document.getElementById(id);
                if (duplicate) {
                    duplicate.remove();
                }
                removedPosition = i + 1;
            }
        }
    }
    if (removedPosition) {
        let classLists = slides[removedPosition - 1].classList;
        let isExecute = true;
        for (let i = 0; i < classLists.length; i++) {
            if (classLists[i] == "pin-share-screen") {
                isExecute = false;
                setLayoutSharePinning(slides[removedPosition - 1]);
            }
        }
        if (isExecute) {
            autoArangeDelete({
                slides: slides,
                startPosition: removedPosition - 1
            });
        }
    }

    // remove blank slide
    for (let i = 0; i < slides.length; i++) {
        let grids = slides[i].getElementsByClassName("video-grid");
        if (grids.length === 0) {
            slides[i].remove();
        }
    }

    changeSize();
};

const connectToNewUser = (
    peer,
    userId,
    streams,
    myname,
    username,
    videoGrids,
    fnSetCalling,
    socket,
    isHost,
    micState,
    camState
) => {
    const call = peer.call(userId, streams);

    const video = document.createElement("video");
    call.on("stream", userVideoStream => {
        fnSetCalling(call);

        console.log(userVideoStream, call);
        if (
            (lastMediaId === undefined || userVideoStream.id !== lastMediaId) &&
            call.peer !== peer.id &&
            userVideoStream.id !== myVideoStream.id
        ) {
            console.log(userVideoStream);
            console.log("Menrima video dari jawaban guest");
            addVideoStream(
                videoGrids,
                video,
                userVideoStream,
                username,
                call.peer,
                peer.id,
                socket,
                isHost
            );
            lastMediaId = userVideoStream.id;
        }
    });
    call.on("close", () => {
        video.remove();
        RemoveUnusedDivs(videoGrids);
        delete peers[call.peer];
    });
    peers[userId] = call;

    socket.emit("user-count", peers.length);

    let storage = JSON.parse(localStorage.getItem("pinning-tmp"));
    if (storage !== null) {
        socket.emit("command-auto-pinning", storage);
    }

    console.log(peers);
};

const addVideoStream = (
    videoGrids,
    videoEl,
    stream,
    name,
    id,
    peerHostId,
    socket,
    isHost
) => {
    if (document.getElementById(id) == undefined) {
        console.log(videoEl, stream);
        videoEl.srcObject = stream;
        videoEl.addEventListener("loadedmetadata", () => {
            videoEl.play();
        });
        const videoGrid = document.createElement("div");
        videoGrid.id = id;
        videoGrid.classList.add("video-grid");
        const label = document.createElement("p");
        label.classList.add("label");
        label.innerHTML = `<i class="bi bi-mic" style="font-style: normal"> ${name}</i>`;
        videoGrid.appendChild(label);

        if (isHost) {
            let cover = document.createElement("div");
            cover.classList.add("cam-cover-overlay");
            cover.classList.add("position-absolute");
            cover.classList.add("pin-remote");
            cover.innerHTML = `<div class="avatar avatar-pin" style="background-color: #68656578">
                    <p id="${id}-pin-btn" class="text-avatar font-pin">
                        <i class="bi bi-pin"></i>
                    </p>
                </div>`;
            cover.onclick = function() {
                // pinVideo(id, videoGrids, peerHostId);
                remotePinning(id, peerHostId, socket, isHost);
            };

            videoGrid.appendChild(cover);
        }

        videoGrid.append(videoEl);

        // Membuat audio tag untuk memisahkan player audio & video
        if(videoEl.muted == false){
           let audTag = document.createElement('audio');
           audTag.srcObject = stream;
           audTag.play();
           videoEl.muted = true;
           videoGrid.appendChild(audTag); 
        }

        console.log(videoGrid);
        let slides = [];
        document.querySelectorAll(".video-slide").forEach(slide => {
            let classlist = slide.classList;
            if (classlist.length == 1) {
                slides.push(slide);
            }
        });
        let slidePos = Math.ceil(
            (document.querySelectorAll(".video-grid").length + 1) / 6
        );
        let videoSlide;

        if (slides.length < slidePos) {
            videoSlide = document.createElement("div");
            videoSlide.classList.add("video-slide");
            videoSlide.appendChild(videoGrid);

            videoGrids.current.appendChild(videoSlide);
        } else {
            videoSlide = slides[slidePos - 1];
            videoSlide.appendChild(videoGrid);
        }

        RemoveUnusedDivs(videoGrids);
    }
};

function VideoConference() {
    const [peerjs, setPeer] = useState(null);
    const [peerjsOpen, setPeerOpen] = useState(null);
    const [room, setRoom] = useState(null);
    const [username, setName] = useState(null);
    const [socket, setSocket] = useState(null);
    const [urlServer, setUrl] = useState(null);
    const [userId, setId] = useState(null);
    const [mediaDevices, setMediaDevices] = useState([]);
    const [mediaSelected, setSelectMedia] = useState(null);
    const [userMediaStream, setUserMediaStrean] = useState(null);
    const [userMediaStreamAns, setUserMediaStreanAns] = useState(null);
    const [micState, setMicState] = useState(true);
    const [videoState, setVidState] = useState(true);
    const [incomingCall, setIncomingCall] = useState(null);
    const [peerCall, setCalling] = useState(null);
    const [hasAnsweared, setHasAnsweared] = useState(null);
    const [isShare, setShareState] = useState(false);
    const [dataPin, setDataPin] = useState(null);
    const [userConnect, setCounUserConnect] = useState(null);
    const [userAdded, setUserAdd] = useState(false);
    const [userData, setUser] = useState(null);
    const [orgData, setOrg] = useState(null);
    const [team, setTeam] = useState(null);
    const [isHost, setHost] = useState(null);
    const [token, setToken] = useState(null);
    const [isStart, setStart] = useState(false);
    const [time, setTime] = useState(null);
    const [startTime, setStartTime] = useState(null);
    const [endTime, setEndTime] = useState(null);
    const [audioShareTracks, setAudioShareTrack] = useState([]);

    const videoGrids = useRef();
    const shareBtn = useRef();
    const stopShareBtn = useRef();
    const stopCam = useRef();
    const stopMic = useRef();
    const raisehand = useRef();
    const lowerhand = useRef();

    console.log("Memualai awal", loop);
    console.log(peers);

    const shareMediaClick = mediaId => {
        if (isHost) {
            setSelectMedia(mediaId);
        }
    };

    useEffect(() => {
        if (userMediaStream !== null && socket !== null) {
            // let micState = true;
            // let vidSTate = true;
            const changeStateCamera = () => {
                if (videoState) {
                    console.log("stop cam dijalankan");
                    userMediaStream.getVideoTracks().forEach(track => {
                        track.stop();
                    });
              
                    setVidState(false);
                } else {
                    console.log("stop cam dijalankan restart");
                 
                    navigator.mediaDevices
                        .getUserMedia({ video: {
                            facingMode: 'user',
                            // width: {min: 640, ideal: 1280},
                            // height: {min: 360, ideal: 720},
                            // aspectRatio: 1.333,
                        }, audio: false })
                        .then(stream => {
                            stream.getVideoTracks().forEach(vidTrack => {
                                userMediaStream.addTrack(vidTrack);
                                userMediaStream.getVideoTracks().forEach(track => {
                                    if(track.readyState == "ended"){
                                        userMediaStream.removeTrack(track);
                                    }
                                });
                               
                                for(let x in peers){
                                    console.log("===== Run restart ======", peers[x]);
                                    if (
                                        !peers[x].peer.match(
                                            /universal-media-share/gi
                                        )
                                    ) {
                                        console.log("restart");
                                        console.log(peers[x].peerConnection
                                            .getSenders()[1]);
                                        peers[x].peerConnection
                                            .getSenders()[1]
                                            .replaceTrack(vidTrack);
                                    }
                                }
                            });
                        });

                    setVidState(true);
                }
            };

            const changeStateMic = () => {
                if (micState) {
                    console.log("stop mic dijalankan");
                    userMediaStream.getAudioTracks().forEach(track => {
                        track.stop();
                    });

                    setMicState(false);
                } else {
                   
                    navigator.mediaDevices
                        .getUserMedia({ video: false, audio: true })
                        .then(stream => {
                            stream.getAudioTracks().forEach(audTrack => {
                                userMediaStream.addTrack(audTrack);
                                userMediaStream.getAudioTracks().forEach(track => {
                                    if(track.readyState == "ended"){
                                        userMediaStream.removeTrack(track);
                                    }
                                });
                                
                                for(let x in peers){
                                    console.log("===== Run restart ======", peers[x]);
                                    if (
                                        !peers[x].peer.match(
                                            /universal-media-share/gi
                                        )
                                    ) {
                                        console.log("restart");
                                        console.log(peers[x].peerConnection
                                            .getSenders()[0]);
                                        peers[x].peerConnection
                                            .getSenders()[0]
                                            .replaceTrack(audTrack);
                                    }
                                }
                            });
                        });
                  
                    setMicState(true);
                }
            };
        
            stopCam.current.onclick = changeStateCamera;
            stopMic.current.onclick = changeStateMic;
        }

        if (socket !== null) {
            // Memberikan status camera dan mic
            videoState == false
                ? socket.emit("video-close", peerjs.id, username)
                : socket.emit("video-on", peerjs.id, username);
            micState == false
                ? socket.emit("audio-close", peerjs.id, username)
                : socket.emit("audio-on", peerjs.id, username);
        }
    }, [userMediaStream, peerCall, hasAnsweared, videoState, micState, socket]);

    useEffect(() => {
        if (mediaSelected !== null && isHost) {
            shareBtn.current.value = mediaSelected;
            shareBtn.current.click();
            setSelectMedia(null);
        }
    }, [mediaSelected, isHost]);

    useEffect(() => {
        if (socket && username && peerjs) {
            const startShare = (peer, devideId) => {
                let lastIdGuest;
                shareMedia(
                    peer,
                    peerjs,
                    socket,
                    username,
                    videoGrids,
                    stopShareBtn,
                    devideId,
                    setShareState,
                    setAudioShareTrack
                ).then(finalAV => {
                    if (finalAV) {
                        console.log(finalAV);
                        console.log(peer.id);
                        const calling = id => {
                            console.log(peer.id);
                            if (id != peerjs.id) {
                                peer.call(id, finalAV);
                            }
                        };
                        socket.on("request-share-universal-media", id => {
                            if (
                                (lastIdGuest == undefined ||
                                    id != lastIdGuest) &&
                                finalAV
                            ) {
                                console.log(
                                    id,
                                    "request-share-universal-media"
                                );
                                lastIdGuest = id;
                                calling(id);
                            }
                        });
                        socket.emit("share-universal-media", username);
                    }
                });
            };

            shareBtn.current.addEventListener("click", () => {
                if (!isStream) {
                    let deviceId = shareBtn.current.value;
                    console.log(`Selected media device is ${deviceId}`);
                    console.log("share button di click");
                    let peer = new Peer(
                        `${peerjs.id}-x-${idShare}-universal-media-share-name ${username}`,
                        {
                            host: process.env.MIX_PEER_HOST,
                            path: process.env.MIX_PEER_PATH,
                            port: process.env.MIX_MANAGE_STREAM_SERVER_PORT,
                            token: JSON.stringify({ token: token, room: room })
                        }
                    );
                    // setPeerShare(peer);
                    peer.on("open", id => {
                        // setIdShare(id);
                        // shareMedia(peer, peers, username, videoGrids)
                        startShare(peer, deviceId);
                        idShare++;
                        setShareState(true);
                    });
                }
            });
        }
    }, [socket, username, peerjs]);

    useEffect(() => {
        if (
            peerjs !== null &&
            room !== null &&
            username !== null &&
            socket !== null &&
            peerjsOpen !== null
        ) {
            if (loopSet === 0) {
                console.log("setup peerjs on calls");

                navigator.mediaDevices
                    .getUserMedia({
                        video: {
                            facingMode: 'user',
                            // width: {min: 640, ideal: 1280},
                            // height: {min: 360, ideal: 720},
                            // aspectRatio: 1.333,
                        },
                        audio: true
                    })
                    .then(stream => {
                        console.log(stream, username);
                        console.log("stream di jalankan");
                        setUserMediaStrean(stream);
                        myVideoStream = stream;
                    });

                socket.on("AddName", data => {
                    otherUsername[data.id] = data.username;
                });

                loopSet++;
            }
        }
    }, [peerjs, peerjsOpen, room, username, socket]);

    useEffect(() => {
        if (
            userMediaStream !== null &&
            socket !== null &&
            peerjs !== null &&
            username !== null &&
            room !== null &&
            userData !== null
        ) {
            peerjs.on("call", call => {
                console.log("peerjs receive calling from host");
                console.log(call.peer, peerjs.id);
                console.log(
                    lastMediaId,
                    peerjs.id.match(/universal-media-share/gi)
                );
                setIncomingCall(call);
            });

            socket.emit("join-room", room, peerjsOpen, username, userData);

            const myVideo = document.createElement("video");
            myVideo.autoplay = true;
            // myVideo.id = peerjs.id;
            myVideo.muted = true;
            console.log(myVideo);
            addVideoStream(
                videoGrids,
                myVideo,
                userMediaStream,
                username,
                peerjs.id,
                peerjs.id,
                socket,
                isHost
            );
            lastMediaId = userMediaStream.id;

            socket.on("user-connected", (id, usernameSc) => {
                if (peerjs.id !== id) {
                    socket.emit("tellName", {
                        id: peerjs.id,
                        username: username
                    });
                    console.log("userid:" + id);
                    setId(id);
                    connectToNewUser(
                        peerjs,
                        id,
                        userMediaStream,
                        username,
                        usernameSc,
                        videoGrids,
                        setCalling,
                        socket,
                        isHost,
                        micState,
                        videoState
                    );
                }
            });

            socket.on("user-disconnected", id => {
                console.log("user disconected " + id);
                console.log(peers);
                if (peers[id]) {
                    console.log(peers[id]);
                    peers[id].close();
                } else console.log("peer id gagal ditemukan untuk close");
            });

            socket.on("call-share-universal-media", () => {
                console.log(peerjs.id);
                socket.emit("receive-share-universal-media", peerjs.id);
            });

            socket.on("msg-video-close", (id, username) => {
                setCoverCam({ disabled: true, peerId: id, name: username });
            });

            socket.on("msg-video-on", (id, username) => {
                setCoverCam({ peerId: id, name: username });
            });

            socket.on("msg-audio-close", (id, username) => {
                setLabelMic({ muted: true, peerId: id });
            });

            socket.on("msg-audio-on", (id, username) => {
                setLabelMic({ peerId: id });
            });

            // socket.emit("join-room", room, peerjsOpen, username, userData);
        }
    }, [userMediaStream, socket, peerjs, username, room, userData]);

    useEffect(() => {
        if (
            userMediaStream !== null &&
            peerjs !== null &&
            incomingCall !== null
        ) {
            console.log(incomingCall.peer);
            try {
                let callAns = incomingCall.answer(userMediaStream);
                console.log(callAns, incomingCall);
                setHasAnsweared(callAns);

                // video.id = call.peer;
                incomingCall.on("stream", remoteStream => {
                    if (incomingCall.peer.match(/universal-media-share/gi)) {
                        if (
                            peers[incomingCall.peer] == undefined ||
                            peers[incomingCall.peer] == null
                        ) {
                            const video = document.createElement("video");
                            pinnedLayout(
                                remoteStream,
                                incomingCall.peer.split("name")[1],
                                video,
                                videoGrids,
                                incomingCall.peer
                            );
                            peers[incomingCall.peer] = incomingCall;
                            console.log("Masuk univerasl medoa  share screen");

                            incomingCall.on("close", callIn => {
                                video.remove();
                                console.log(callIn);
                                console.log("peer close remove divs");
                                RemoveUnusedDivs(videoGrids);
                                delete peers[incomingCall.peer];
                            });
                        }
                    } else {
                        if (
                            (lastMediaId === undefined ||
                                remoteStream.id !== lastMediaId) &&
                            incomingCall.peer !== peerjs.id &&
                            remoteStream.id !== myVideoStream.id
                        ) {
                            const video = document.createElement("video");
                            console.log(remoteStream);
                            console.log(userMediaStream);
                            console.log("Menerima panggilan dari host");
                            addVideoStream(
                                videoGrids,
                                video,
                                remoteStream,
                                otherUsername[incomingCall.peer],
                                incomingCall.peer,
                                peerjs.id,
                                socket,
                                isHost
                            );

                            setUserAdd(true);
                            lastMediaId = remoteStream.id;
                            peers[incomingCall.peer] = incomingCall;
                            mode = "host";

                            incomingCall.on("close", callIn => {
                                video.remove();
                                console.log(callIn);
                                console.log("peer close remove divs");
                                RemoveUnusedDivs(videoGrids);
                                delete otherUsername[incomingCall.peer];
                                delete peers[incomingCall.peer];
                            });
                        }
                    }
                });
            } catch (error) {
                console.log(error);
            }
        }
    }, [userMediaStream, peerjs, incomingCall]);

    useEffect(() => {
        if (
            dataPin !== null &&
            userConnect === peers.length &&
            peerjs &&
            userAdded
        ) {
            console.log("set localstr", dataPin);
            // let newTmpPinning  = []
            localStorage.removeItem("pinning-tmp");
            for (let i = 0; i < dataPin.length; i++) {
                console.log(document.getElementById(dataPin[i].targetId));
                // pinVideo(dataPin[i].targetId, videoGrids, peerjs.id, socket, isHost);
                if (document.getElementById(dataPin[i].targetId)) {
                    // newTmpPinning.push(dataPin[i])
                    remotePinning(
                        dataPin[i].targetId,
                        peerjs.id,
                        socket,
                        isHost
                    );
                    if (!isHost) {
                        pinVideo(
                            dataPin[i].targetId,
                            videoGrids,
                            dataPin[i].hostId,
                            socket,
                            isHost
                        );
                    }
                }
            }
            setUserAdd(false);
        }
        console.log(
            "set localstr",
            dataPin,
            userConnect,
            peers.length,
            userAdded
        );
    }, [dataPin, userConnect, peerjs, socket, userAdded]);

    useEffect(() => {
        if (userData && orgData && team) {
            if (userData.id === orgData.user_id) {
                setHost(true);
            } else if (team.length > 0) {
                let match = false;
                for (let i = 0; i < team.length; i++) {
                    if (
                        team[i].user_id === userData.id &&
                        team[i].organization_id === orgData.id
                    ) {
                        setHost(true);
                        match = true;
                        i = team.length;
                    }
                }
                if (!match) {
                    setHost(false);
                }
            } else {
                setHost(false);
            }
        }
    }, [userData, orgData, team]);

    useEffect(() => {
        if (isHost !== null && socket) {
            socket.on("pinning-request", (idTarget, idHost) => {
                pinVideo(idTarget, videoGrids, idHost, socket, isHost);
            });

            socket.on("set-user-count", value => {
                setCounUserConnect(value);
            });

            // io on rename slide classname by new peerjsID from new Host connection
            // socket.on('rename-class-slide', (old, newName) => {
            //     let el = document.getElementsByClassName(old)
            //     if(el.length > 0){
            //         el[0].classList.add(newName)
            //         el[0].classList.remove(old)
            //     }else{
            //         let slides = document.getElementsByClassName('pin-share-screen')
            //         for (let i = 0; i < slides.length; i++) {
            //             slides[i].classList.forEach(slideClass => {
            //                 if(slideClass.match(old)){
            //                     slides[i].classList.add(newName)
            //                     slides[i].classList.remove(old)
            //                 }
            //             })
            //         }
            //     }
            // })

            socket.on("auto-pinning", data => {
                setDataPin(data);
            });

            socket.on("rm-pinning", (idTarget, idHost) => {
                let target = document.getElementById(idTarget);
                let slide = document.getElementsByClassName(idHost);
                if (target && slide.length > 0) {
                    removePin(slide[0], target);
                }
            });
        }
    }, [isHost, socket]);

    useEffect(() => {
        if (isStart) {
            let url = document.getElementById("url-server");
            let room = document.getElementById("room");
            let name = document.getElementById("name");
            let myData = document.getElementById("myData");
            let org = document.getElementById("organization");
            let userTeam = document.getElementById("user-team");
            let token = document.getElementById("token");

            let userData = JSON.parse(myData.value);
            let orgData = JSON.parse(org.value);
            if (userData.id === orgData.user_id) {
                userData.organizations = [orgData];
            } else {
                userData.organizations = [];
            }

            setUrl(url.value);
            setRoom(room.value);
            setName(name.value);
            setUser(userData);
            setOrg(JSON.parse(org.value));
            setTeam(JSON.parse(userTeam.value));
            setToken(token.value);

            let ioSc = io.connect(
                `https://${process.env.MIX_PEER_HOST}:${process.env.MIX_MANAGE_STREAM_SERVER_PORT}`,
                {
                    reconnectionDelayMax: 10000,
                    cors: {
                        withCredentials: true
                    },
                    query: { token: token.value }
                }
            );
            setSocket(ioSc);

            let peer = new Peer(undefined, {
                host: process.env.MIX_PEER_HOST,
                path: process.env.MIX_PEER_PATH,
                port: process.env.MIX_MANAGE_STREAM_SERVER_PORT,
                token: JSON.stringify({ token: token.value, room: room.value })
            });
            setPeer(peer);

            console.log(ioSc);
            console.log(peer, room);
            peer.on("open", id => {
                setPeerOpen(id);
                console.log("peerjs open ", id);
                // ioSc.emit("join-room", room.value, id, name.value, userData);
                console.log(peer._open);
            });

            url.remove();
            room.remove();
            name.remove();
            myData.remove();
            org.remove();
            userTeam.remove();
            token.remove();

            changeSize();
            window.addEventListener("resize", changeSize);

            navigator.mediaDevices.enumerateDevices().then(mediaDevices => {
                let devices = [];
                let count = 1;
                mediaDevices.forEach(device => {
                    if (device.kind === "videoinput") {
                        let tmp = [];
                        tmp.push(device.deviceId);
                        tmp.push(device.label || `Camera ${count}`);
                        count++;
                        devices.push(tmp);
                    }
                });
                devices.push(["share-screen", "Share Screen"]);
                setMediaDevices(devices);
            });

            let storage = JSON.parse(localStorage.getItem("pinning-tmp"));
            if (storage !== null) {
                // localStorage.removeItem('pinning-tmp')
                setDataPin(storage);
            }

            document.getElementById("stream-blank").classList.add("d-none");
            document
                .getElementById("multiple-conference")
                .classList.remove("d-none");
        } else {
            document.getElementById("stream-blank").classList.remove("d-none");
            document
                .getElementById("multiple-conference")
                .classList.add("d-none");
        }
    }, [isStart]);

    useEffect(() => {
        if (time && startTime && endTime) {
            if (startTime <= time && endTime >= time && isStart === false) {
                setStart(true);
            }
        }
    }, [time, startTime, endTime]);

    useEffect(() => {
        if (loop == 0) {
            let startTime = document.getElementById("start-time");
            let endTime = document.getElementById("end-time");

            setTime(new Date());
            setInterval(() => {
                setTime(new Date());
            }, 30000);
            setStartTime(new Date(startTime.value));
            setEndTime(new Date(endTime.value));

            loop++;
        }
    });

    return (
        <div className="mainclone">
            <PopUpRaiseHand
                title={"List of Raise Hand"}
                socket={socket}
                refBtnTrigger={raisehand}
                refBtnLower={lowerhand}
                username={username}
            ></PopUpRaiseHand>
            <div className="main_left">
                <div className="main_videos">
                    <button
                        className="btn btn-secondary btn-left-slide text-white fs-1"
                        onClick={slideLeft}
                    >
                        <ArrowLeftCircle></ArrowLeftCircle>
                    </button>
                    <button
                        className="btn btn-secondary btn-right-slide text-white fs-1"
                        onClick={slideRight}
                    >
                        <ArrowRightCircle></ArrowRightCircle>
                    </button>
                    <div id="video-grids" ref={videoGrids}></div>
                </div>
                <div className="main_controls">
                    <div className="main_controls_block w-100">
                        <div
                            className="main_controls_button"
                            id="mic"
                            ref={stopMic}
                        >
                            {micState ? <MicMute></MicMute> : <Mic></Mic>}
                            {micState ? "Mute" : "Un Mute"}
                        </div>

                        <div
                            className="main_controls_button"
                            id="video"
                            ref={stopCam}
                        >
                            {videoState ? (
                                <CameraVideoOff></CameraVideoOff>
                            ) : (
                                <CameraVideo></CameraVideo>
                            )}
                            {videoState ? "Off Cam" : "On Cam"}
                        </div>

                        <div className="d-flex m-auto">
                            <div
                                className="main_controls_button d-none"
                                ref={shareBtn}
                            >
                                <BoxArrowInUp></BoxArrowInUp>
                                <span>Share Media</span>
                            </div>
                            <Dropdown
                                className={
                                    isShare || isHost === false ? "d-none" : ""
                                }
                            >
                                <Dropdown.Toggle
                                    variant="dark"
                                    id="dropdown-basic"
                                    className="main_controls_button d-flex"
                                >
                                    <BoxArrowInUp></BoxArrowInUp>
                                    <span>Share Media</span>
                                </Dropdown.Toggle>
                                <Dropdown.Menu>
                                    {mediaDevices.map(media => {
                                        return (
                                            <Dropdown.Item
                                                key={media[0]}
                                                as={"button"}
                                                onClick={() => {
                                                    shareMediaClick(media[0]);
                                                }}
                                            >
                                                {media[1]}
                                            </Dropdown.Item>
                                        );
                                    })}
                                </Dropdown.Menu>
                            </Dropdown>
                            <div
                                className={`main_controls_button ${
                                    isShare && isHost ? "" : "d-none"
                                }`}
                                ref={stopShareBtn}
                            >
                                <BoxArrowInUp></BoxArrowInUp>
                                <span>Stop Share</span>
                            </div>
                            <div
                                className="main_controls_button"
                                ref={raisehand}
                            >
                                <i className="fa-regular fa-hand"></i>
                                <span>Raise Hand</span>
                            </div>
                            <div
                                className="main_controls_button d-none"
                                ref={lowerhand}
                            >
                                <i className="fa-regular fa-hand"></i>
                                <span>Lower Hand</span>
                            </div>
                            {audioShareTracks.map(el => {
                                return (
                                    <div
                                        className="main_controls_button"
                                        onClick={event => {
                                            muteAudioShare(el, event);
                                        }}
                                    >
                                        <i className="bi bi-mic"></i>
                                        <span>{el.labelDevice}</span>
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                    {/* <div className="main_controls_block">
                        <div className="main_controls_button">
                            <i className="fas fa-user-friends"></i>
                            <span>Participants</span>
                        </div>
                    </div> */}
                </div>
            </div>
        </div>
    );
}

export default VideoConference;

if (document.getElementById("multiple-conference")) {
    ReactDOM.render(
        <VideoConference />,
        document.getElementById("multiple-conference")
    );
}
