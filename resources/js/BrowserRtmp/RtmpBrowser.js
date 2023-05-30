import { io } from "socket.io-client";
import MediaSwitcher from "./SwitchStream";

class BrowserToRtmpClientOptions {
    host = "";
    port = 0;
    socketio;
    framerate = 0;
    audioSampleRate = 0;
    rtmp = "";
    audioBitsPerSecond = 0;
    videoBitsPerSecond = 0;
}

const DEFAULT_OPTIONS = {
    port: 8086,
    audioBitsPerSecond: 128000,
    videoBitsPerSecond: 2500000,
    framerate: 25
};

// export type BrowserToRtmpClientEvents = {
//     error: (error: ServerError) => void;
//     destroyed: () => void;
//     ffmpegOutput: (message: string) => void;
// }

export default class BrowserToRtmpClient {
    #socket;
    #stream;
    #options;
    #mediaRecorder;
    #mediaSwitcher = new MediaSwitcher();

    constructor(stream, options) {
        this.#stream = stream;

        this.#options = {
            ...DEFAULT_OPTIONS,
            ...options
        };
        if (!this.#options.audioSampleRate) {
            this.#options.audioSampleRate = Math.round(
                this.#options.audioBitsPerSecond / 4
            );
        }
        if (!options.host) {
            throw new Error("Missing required 'host' value");
        }

        if (options.host[options.host.length - 1] === "/") {
            options.host = options.host.substring(0, options.host.length - 2);
        }

        if (options.host.indexOf("http://") === 0) {
            options.host = "https://" + options.host.substring("http://".length);
        }

        const socketOptions = {
            reconnectionDelayMax: 10000,
            ...(options.socketio || {})
        };

        this.#socket = io(`${options.host}:${options.port}`, socketOptions);

        this.#socket.on("error", err => this.#onRemoteError(err));
        this.#socket.on("ffmpegOutput", msg => this.emit("ffmpegOutput", msg));

        // this.#socket.on("connect_error", (err) => {
        //     console.log(err.message, "error koneksi"); // prints the message associated with the error
        //   });
    }

    async start() {
        if (this.#mediaRecorder) {
            if (this.#mediaRecorder.state === "inactive") {
                this.#mediaRecorder.start();
            } else if (this.#mediaRecorder.state === "paused") {
                this.#mediaRecorder.resume();
            }
            return;
        }

        this.#mediaSwitcher
            .initialize(this.#stream)
            .then(switcherStream => {
                // this.#stream = switcherStream
                this.#mediaRecorder = new MediaRecorder(switcherStream, {
                    audioBitsPerSecond: this.#options.audioBitsPerSecond,
                    videoBitsPerSecond: this.#options.videoBitsPerSecond
                });
        
                this.#socket.emit("start", this.#options, () => {
                    this.#mediaRecorder.ondataavailable = data =>
                        this.#onMediaRecorderDataAvailable(data);
        
                    try {
                        this.#mediaRecorder.start(250);
                    } catch (e) {
                        this.#socket.emit("stop");
                        throw e;
                    }
                });
            })
            .catch(err => console.error(err.message));
    }

    pause() {
        if (this.#mediaRecorder) {
            this.#mediaRecorder.pause();
        }
    }

    async stop() {
        if (this.#mediaRecorder) {
            if (this.#mediaRecorder.state === "inactive") {
                return;
            }
            this.#mediaRecorder.onstop = () => {
                this.#socket.emit("stop", () => {
                    this.#mediaRecorder = undefined;
                });
            };
            this.#mediaRecorder.stop();
        }
    }

    restart(newStream) {
        // const newRecorder = new MediaRecorder(newStream, {
        //     audioBitsPerSecond: this.#options.audioBitsPerSecond,
        //     videoBitsPerSecond: this.#options.videoBitsPerSecond
        // });

        // newRecorder.ondataavailable = data =>
        //     this.#onMediaRecorderDataAvailable(data);

        // try {
        //     newRecorder.start(250);
        //     this.#mediaRecorder.stop();
        //     this.#mediaRecorder = newRecorder;
        // } catch (e) {
        //     this.#socket.emit("stop");
        //     throw e;
        // }

        // this.#mediaRecorder = newRecorder;
        console.log("restart stream", this.#mediaSwitcher);
        // this.#mediaRecorder.pause();
        this.#mediaSwitcher.changeStream(newStream)
        // this.#mediaRecorder.resume();
    }

    on(msg, callback){
        this.#socket.on(msg, callback)
    }

    #onRemoteError(err) {
        this.#socket.emit("error", err);
        if (
            err.fatal &&
            this.#mediaRecorder &&
            this.#mediaRecorder.state !== "inactive"
        ) {
            this.#mediaRecorder.onstop = () =>
                (this.#mediaRecorder = undefined);
            this.#mediaRecorder.stop();
        }
    }

    #onMediaRecorderDataAvailable(data) {
        this.#socket.emit("binarystream", data.data, err => {
            if (err) {
                this.#onRemoteError(err);
            }
        });
    }
}
