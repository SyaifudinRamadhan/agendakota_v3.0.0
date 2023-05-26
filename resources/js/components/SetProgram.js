class SetProgram {
    #videoStreams = []
    #audioStreams = []
    #resStream

    constructor(videoStreams, audioStreams, changeLayoutFn, modeVideo, preset) {
        for (let i = 0; i < videoStreams.length; i++) {
            this.#videoStreams.push(videoStreams[i].clone())
        }
        for (let i = 0; i < audioStreams.length; i++) {
            this.#audioStreams.push(audioStreams[i].clone())
        }
        // let audio = this.#mergerAudioTrack(this.#audioStreams)
        let resChanged = changeLayoutFn(
            preset[modeVideo - 1][0],
            this.#videoStreams,
            this.#audioStreams,
            undefined,
            undefined
        )
        this.#resStream = resChanged.result
    }

    #mergerAudioTrack(currentAudioStream) {
        const audioContext = new AudioContext();
        let audiosIn = [];
    
        for (let i = 0; i < currentAudioStream.length; i++) {
            audiosIn.push(
                audioContext.createMediaStreamSource(currentAudioStream[i])
            );
        }
    
        dest = audioContext.createMediaStreamDestination();
    
        for (let i = 0; i < audiosIn.length; i++) {
            audiosIn[i].connect(dest);
        }
    
        return dest.stream;
    }

    getResult(){
        return this.#resStream
    }

    stopTrack(stopFn){
        for (let i = 0; i < this.#videoStreams.length; i++) {
            stopFn(this.#videoStreams[i])
        }
        for (let i = 0; i < this.#audioStreams.length; i++) {
            stopFn(this.#audioStreams[i])
        }
        stopFn(this.#resStream)
    }
}

export default SetProgram