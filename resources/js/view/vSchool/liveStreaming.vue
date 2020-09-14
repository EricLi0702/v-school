<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <Button class="btnclass" @click="addStreamModal">Oh!</Button>
                <Modal
                footer-hide	
                v-model="showVideoModal"
                :styles="{top:'140px',left:'-244px'}"
                class-name="live-streaming-modal"
                >
                    <div id="meeting"></div>
                </Modal>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data: () => ({
        showVideoModal:false,
        domain : "botrun.norwayeast.cloudapp.azure.com",
        videoOptions:{
            roomName: "JitsiMeetAPIExample",
            // width: 1010,
            height: 610,
            parentNode: undefined,
            // configOverwrite: {
            // },
            // interfaceConfigOverwrite: {
            //     filmStripOnly: true
            // },
        },
        

    }),
    mounted() {
        // let videoScript = document.createElement('script')
        // videoScript.setAttribute('src', 'https://botrun.norwayeast.cloudapp.azure.com/external_api.js')
        // document.head.appendChild(videoScript);
        // let api = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
    },
    methods: {
        addStreamModal(){
            this.showVideoModal = true;
            this.videoOptions.parentNode = document.querySelector('#meeting');
            console.log(this.videoOptions.parentNode);
            let api = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        }
    }
}
</script>