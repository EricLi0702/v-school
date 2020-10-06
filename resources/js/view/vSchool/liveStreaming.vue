<template>
    <div class="">
        <Row type="flex" justify="space-between" class="code-row-bg">
            <Col span="6">
                <div  @click="addStreamModal">
                    <img src="/img/icon/会议 拷贝.png" alt="">
                    <span>开始直播讲课</span>
                </div>
            </Col>
        </Row>
        <!-- <Button class="btnclass" @click="addStreamModal">Oh!</Button> -->
        <Modal
        footer-hide	
        v-model="showVideoModal"
        :styles="{top:'140px',left:'-244px'}"
        class-name="live-streaming-modal"
        :mask-closable="false"
        @on-cancel="cancel"
        >   
            <div class="recording-btn-area">
                <!-- <ButtonGroup><Icon type="ios-cloud-upload" />
                    <Button type="primary" icon="ios-play"></Button>
                    <Button type="primary" icon="ios-pause"></Button>
                    <Button type="primary" icon="md-square"></Button>
                    <Button type="primary" icon="ios-cloud-upload"></Button>
                </ButtonGroup> -->

                <Button class="btnclass" @click="recordStart" :disabled="isRecord">{{isRecord ? 'Recording...': 'Record'}}</Button>
                <Button class="btnclass" @click="recordPause" :disabled="isPause">Pause</Button>
                <Button class="btnclass" @click="recordStop" :disabled="isStop">Stop</Button>
                <Button class="btnclass" @click="recordSave" :disabled="isSave">Save</Button>
            </div>
            <div id="meeting"></div>
        </Modal>
            
    </div>
</template>
<script>


export default {
    data: () => ({
        showVideoModal:false,
        domain : "118.31.71.41",
        videoOptions:{
            roomName: "testLecture",
            // width: 1010,
            height: 610,
            parentNode: undefined,
            // configOverwrite: {
            // },
            // interfaceConfigOverwrite: {
            //     filmStripOnly: true
            // },
        },
        LiveMeeting:{},
        recordingData: [],
        recorder:'',
        recorderStream:'',
        isRecord: false,
        isPause: true,
        isStop: true,
        isSave: true,
    }),
    
    methods: {
        async addStreamModal(){
            this.showVideoModal = true;
            this.videoOptions.parentNode = document.querySelector('#meeting');
            console.log(this.videoOptions.parentNode);
            this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        },
        cancel(){
            this.LiveMeeting.dispose();
        },
        async recordStart(){
            let gumStream, gdmStream;
            // try {
            gumStream = await navigator.mediaDevices.getUserMedia({video: false, audio: true});
            gdmStream = await navigator.mediaDevices.getDisplayMedia({video: {displaySurface: "browser"}, audio: true});

            // } catch (e) {
            //     console.error("capture failure", e);
            //     return;
            // }

            this.recorderStream = gumStream ? this.mixer(gumStream, gdmStream) : gdmStream;
            this.recorder = new MediaRecorder(this.recorderStream, {mimeType: 'video/webm'});

            this.recorder.ondataavailable = e => {

                if (e.data && e.data.size > 0) {
                    console.log(e.data);
                    this.recordingData.push(e.data);
                }
            };

            this.recorder.onStop = () => {
                console.log("oh, stopped.");
                this.recorderStream.getTracks().forEach(track => track.stop());
                gumStream.getTracks().forEach(track => track.stop());
                gdmStream.getTracks().forEach(track => track.stop());
            };

            this.recorderStream.addEventListener('inactive', () => {
                console.log('Capture stream inactive');
                stopCapture();
            });

            this.recorder.start();
            console.log("started recording");

            this.isRecord = true;
            this.isPause = false;
            this.isStop = false;
            this.isSave = true;

        },

        recordPause(){
            if(this.recorder.state ==='paused'){
                this.recorder.resume();
            }
            else if (this.recorder.state === 'recording'){
                this.recorder.pause();
            }
            else
                console.error(`recorder in unhandled state: ${this.recorder.state}`);

            console.log(`recorder ${this.recorder.state === 'paused' ? "paused" : "recording"}`);
        },

        recordStop(){
            console.log("Stopping recording");
            this.recorder.stop();
            
            this.isRecord = false;
            this.isPause = true;
            this.isStop = true;
            this.isSave = false;
        },

        recordSave(){
            const blob = new Blob(this.recordingData, {type: 'video/webm'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `${this.getFilename()}.webm`;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                console.log(`${a.download} save option shown`);
            }, 100);
        },


        /**
         * Returns a filename based ono the Jitsi room name in the URL and timestamp
         * */
        getFilename(){
            const now = new Date();
            const timestamp = now.toISOString();
            const room = new RegExp(/(^.+)\s\|/).exec(document.title);
            if(room && room[1]!=="")
                return `${room[1]}_${timestamp}`;
            else
                return `recording_${timestamp}`;
        },
        mixer(stream1, stream2){
            const ctx = new AudioContext();
            const dest = ctx.createMediaStreamDestination();

            if(stream1.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream1).connect(dest);

            if(stream2.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream2).connect(dest);

            let tracks = dest.stream.getTracks();
            tracks = tracks.concat(stream1.getVideoTracks()).concat(stream2.getVideoTracks());

            return new MediaStream(tracks)
        },
    }
}
</script>