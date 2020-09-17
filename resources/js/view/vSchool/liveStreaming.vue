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
                :mask-closable="false"
                @on-cancel="cancel"
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
        LiveMeeting:{},
    }),
    
    methods: {
        addStreamModal(){
            this.showVideoModal = true;
            this.videoOptions.parentNode = document.querySelector('#meeting');
            console.log(this.videoOptions.parentNode);
            this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        },
        cancel(){
            this.LiveMeeting.dispose();
        }
    }
}
</script>