class MediaSwitcher {

    inputPeerConnection;
    outputPeerConnection;

    //  Change the entire input stream
    changeStream = function (stream) {
        console.log('masuk change stream awal', stream,
        stream.constructor.name,
        this.inputPeerConnection,
        this.outputPeerConnection,
        this.inputPeerConnection.iceConnectionState,
        this.outputPeerConnection.iceConnectionState,);
        if (
            !stream ||
            stream.constructor.name !== 'MediaStream' ||
            !this.inputPeerConnection ||
            !this.outputPeerConnection ||
            this.inputPeerConnection.iceConnectionState !== 'connected' ||
            this.outputPeerConnection.iceConnectionState !== 'connected'
        ) return;
        
        console.log('masuk change stream');
        stream.getTracks().forEach(track => {
            this.changeTrack(track);
        })
    }

    //  Change one input track
    changeTrack = function (track) {
        if (
            !track ||
            (track.constructor.name !== 'MediaStreamTrack' && track.constructor.name !== 'CanvasCaptureMediaStreamTrack') ||
            !this.inputPeerConnection ||
            !this.outputPeerConnection ||
            this.inputPeerConnection.iceConnectionState !== 'connected' ||
            this.outputPeerConnection.iceConnectionState !== 'connected'
        ) return;

        const senders = this.inputPeerConnection.getSenders().filter(sender => !!sender.track && sender.track.kind === track.kind);
        console.log(senders, "change track");
        if (!!senders.length)
            {   console.log(senders.length, "change track 2");
                senders[0].replaceTrack(track);}
    }

    //  Call this to, you guessed, initialize the class
    initialize = function (inputStream) {

        return new Promise(async (resolve, reject) => {

            //  ---------------------------------------------------------------------------------------
            //  Create input RTC peer connection
            //  ---------------------------------------------------------------------------------------
            this.inputPeerConnection = new RTCPeerConnection(null);
            this.inputPeerConnection.onicecandidate = e =>
                this.outputPeerConnection.addIceCandidate(e.candidate)
                    .catch(err => reject(err));
            this.inputPeerConnection.ontrack = e => console.log(e.streams);

            //  ---------------------------------------------------------------------------------------
            //  Create output RTC peer connection
            //  ---------------------------------------------------------------------------------------
            this.outputPeerConnection = new RTCPeerConnection(null);
            this.outputPeerConnection.onicecandidate = e =>
                this.inputPeerConnection.addIceCandidate(e.candidate)
                    .catch(err => reject(err));
            this.outputPeerConnection.ontrack = e => {
                console.log(e.streams[0].getTracks());
                resolve(e.streams[0])};

            //  ---------------------------------------------------------------------------------------
            //  Get video source
            //  ---------------------------------------------------------------------------------------

            //  Create input stream
            if (!inputStream || inputStream.constructor.name !== 'MediaStream') {
                reject(new Error('Input stream is nonexistent or invalid.'));
                return;
            }

            //  Add stream to input peer
            inputStream.getTracks().forEach(track => {
                if (track.kind === 'video')
                    this.videoSender = this.inputPeerConnection.addTrack(track, inputStream);
                if (track.kind === 'audio')
                    this.audioSender = this.inputPeerConnection.addTrack(track, inputStream);
            });

            //  ---------------------------------------------------------------------------------------
            //  Make RTC call
            //  ---------------------------------------------------------------------------------------

            const offer = await this.inputPeerConnection.createOffer();
            await this.inputPeerConnection.setLocalDescription(offer);
            await this.outputPeerConnection.setRemoteDescription(offer);

            const answer = await this.outputPeerConnection.createAnswer();
            await this.outputPeerConnection.setLocalDescription(answer);
            await this.inputPeerConnection.setRemoteDescription(answer);
        });

    }

}

module.exports = MediaSwitcher;