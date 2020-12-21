<template>
    <div>
        
        <div v-if="type == 'rating'">
            <div class="grade-table-box">
                <div class="grade-table-title">
                    <div class="studentName">学生昵称</div>
                    <div>家长评价</div>
                    <div>老师评价</div>
                </div>
            </div>
            <table class="table table-bordered">
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="text-center">
                            {{user.name}}
                        </td>
                        <td class="text-center">
                            <Rate v-model="parRate" />
                        </td>
                        <td class="text-center">
                            <Rate v-model="techRate" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else-if="viewType== 'answer'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">{{propsData.addData.text}}</div>
            </div>
            <div class="post-image-container-cu col-12 p-0">
                <div v-if="propsData.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                    <img :src="propsData.addData.imgUrl[0]" alt="" @click="showSendImage">
                </div>
                <div v-else class="w-100 row m-0 p-0">
                    <div v-for="img in propsData.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                        <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                    </div>
                </div>
            </div>
            <div class="post-file-container-cu col-12 p-0 row m-0">
                <div v-for="file in propsData.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
                    <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                        <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                        <div class="file-info-tag">
                            <p class="text-dark">{{file.fileOriName}}</p>
                            <p class="text-secondary">{{file.fileSize}}</p>
                            <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                        </div>
                    </a>
                </div>                                               
            </div>
            <div v-for="video in propsData.addData.videoUrl" :key="video.fileName">
                <div class="video-box video-cover">
                    <div class="vb-bg"></div>
                    <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                </div>

            </div>
            <Modal
                footer-hide	
                v-model="playSmsVideoModal"
                class-name="vertical-center-modal"
                :styles="{top:'140px',left:'-244px'}"
                :mask-closable="false"
                >
                <video-player  
                    class="video-player-box vjs-custom-skin w-100"
                    ref="videoPlayer"
                    :options="playerOptions"
                    :playsinline="true"
                    @play="onPlayerPlay($event)"
                    @pause="onPlayerPause($event)"
                    @ended="onPlayerEnded($event)"
                    @loadeddata="onPlayerLoadeddata($event)"
                    @waiting="onPlayerWaiting($event)"
                    @playing="onPlayerPlaying($event)"
                    @timeupdate="onPlayerTimeupdate($event)"
                    @canplay="onPlayerCanplay($event)"
                    @canplaythrough="onPlayerCanplaythrough($event)"
                    @ready="playerReadied"
                    @statechanged="playerStateChanged($event)"
                    >
                </video-player>
            </Modal>
            <div class="category-title"></div> 
            <contentComponent @contentData="homeworkResult"></contentComponent>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :loading="isAdding" :disabled="isAdding">提交</Button>
            </div>
        </div>
        <div v-else-if="viewType='studentType' || viewType == 'teacherType'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">
                    {{propsData.addData.text}}
                </div>
                <div class="post-image-container-cu col-12 p-0">
                    <div v-if="propsData.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                        <img :src="propsData.addData.imgUrl[0]" alt="" @click="showSendImage">
                    </div>
                    <div v-else class="w-100 row m-0 p-0">
                        <div v-for="img in propsData.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                        </div>
                    </div>
                </div>
                <div class="post-file-container-cu col-12 p-0 row m-0">
                    <div v-for="file in propsData.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
                        <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                            <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                            <div class="file-info-tag">
                                <p class="text-dark">{{file.fileOriName}}</p>
                                <p class="text-secondary">{{file.fileSize}}</p>
                                <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                            </div>
                        </a>
                    </div>                                               
                </div>
                <div v-for="video in propsData.addData.videoUrl" :key="video.fileName">
                    <div class="video-box video-cover">
                        <div class="vb-bg"></div>
                        <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                    </div>

                </div>
                <Modal
                    footer-hide	
                    v-model="playSmsVideoModal"
                    class-name="vertical-center-modal"
                    :styles="{top:'140px',left:'-244px'}"
                    :mask-closable="false"
                    >
                    <video-player  
                        class="video-player-box vjs-custom-skin w-100"
                        ref="videoPlayer"
                        :options="playerOptions"
                        :playsinline="true"
                        @play="onPlayerPlay($event)"
                        @pause="onPlayerPause($event)"
                        @ended="onPlayerEnded($event)"
                        @loadeddata="onPlayerLoadeddata($event)"
                        @waiting="onPlayerWaiting($event)"
                        @playing="onPlayerPlaying($event)"
                        @timeupdate="onPlayerTimeupdate($event)"
                        @canplay="onPlayerCanplay($event)"
                        @canplaythrough="onPlayerCanplaythrough($event)"
                        @ready="playerReadied"
                        @statechanged="playerStateChanged($event)"
                        >
                    </video-player>
                </Modal>
                <div class="category-title"></div>
            </div>
            <div v-for="homework in answerData" :key="homework.id">
                <div class="category-title">{{homework.user.name}}</div>
                <div class="vx-item">
                    {{homework.answerData.title}}
                </div>
                <div class="post-image-container-cu col-12">
                    <div v-if="homework.answerData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                        <img :src="homework.answerData.imgUrl[0]" alt="" @click="showSendImage">
                    </div>
                    <div v-else class="w-100 row m-0 p-0">
                        <div v-for="img in homework.answerData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                        </div>
                    </div>
                </div>
                <div class="post-file-container-cu col-12 p-0 row m-0">
                    <div v-for="file in homework.answerData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
                        <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                            <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                            <div class="file-info-tag">
                                <p class="text-dark">{{file.fileOriName}}</p>
                                <p class="text-secondary">{{file.fileSize}}</p>
                                <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                            </div>
                        </a>
                    </div>                                               
                </div>
                <div v-for="video in homework.answerData.videoUrl" :key="video.fileName">
                    <div class="video-box video-cover">
                        <div class="vb-bg"></div>
                        <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                    </div>

                </div>
                <Modal
                    footer-hide	
                    v-model="playSmsVideoModal"
                    class-name="vertical-center-modal"
                    :styles="{top:'140px',left:'-244px'}"
                    :mask-closable="false"
                    >
                    <video-player  
                        class="video-player-box vjs-custom-skin w-100"
                        ref="videoPlayer"
                        :options="playerOptions"
                        :playsinline="true"
                        @play="onPlayerPlay($event)"
                        @pause="onPlayerPause($event)"
                        @ended="onPlayerEnded($event)"
                        @loadeddata="onPlayerLoadeddata($event)"
                        @waiting="onPlayerWaiting($event)"
                        @playing="onPlayerPlaying($event)"
                        @timeupdate="onPlayerTimeupdate($event)"
                        @canplay="onPlayerCanplay($event)"
                        @canplaythrough="onPlayerCanplaythrough($event)"
                        @ready="playerReadied"
                        @statechanged="playerStateChanged($event)"
                        >
                    </video-player>
                </Modal>
            </div>
        </div>
        <!-- <div v-else-if="viewType='teacherType'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">{{propsData.addData.text}}</div>
                <div class="category-title"></div>
            </div>
        </div> -->
    </div>
</template>

<script>
import contentComponent from '../contentComponent'
export default {
    components:{
        contentComponent,
    },
    props:['propsData','viewType'],
    data(){
        return{
            users:[],
            stuRate:0,
            parRate:0,
            techRate:0,
            type:'',
            homework:null,
            isAdding:false,
            answerData:[],
            playSmsVideoModal:false,
            playerOptions: {
                width:'1010',
                height: '610',
                autoplay: false,
                muted: false,
                language: 'en',
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                sources: [{
                    type: "video/mp4",
                    src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
                }],
                poster: "https://surmon-china.github.io/vue-quill-editor/static/images/surmon-1.jpg",
            },
        }
    },
    async created(){
        // const res = await this.callApi('get','/api/users')
        // if(res.status == 200){
        //     this.users = res.data
        // }
        if(this.viewType == 'studentView'){
            await axios.get('/api/getMyAnswerBulletin',{params:{bulletinId:this.propsData.id,userId:this.$store.state.user.id}})
                        .then(res=>{
                            this.answerData = res.data[0]
                            for(let i=0;i<this.answerData.length;i++){
                                this.answerData[i].answerData = JSON.parse(this.answerData[i].answerData)
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
        }
        if(this.viewType == 'teacherView'){
            await axios.get('/api/homeVisitAnswer',{params:{bulletinId:this.propsData.id}})
                        .then(res=>{
                            this.answerData = res.data
                            for(let i=0;i<this.answerData.length;i++){
                                this.answerData[i].answerData = JSON.parse(this.answerData[i].answerData)
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
        }
    },
    mounted(){
        console.log('----------------',this.viewType)
    },
    methods:{
        homeworkResult(val){
            this.homework = val
        },
        async submit(){
            if(this.homework == null){
                return this.error('')
            }
            this.isAdding = true
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.homework,userId:userId,bulletinId:this.propsData.id})
            if(res.status == 200){
                this.success('操作成功')
                console.log(res)
                this.$store.commit('setPostDetailsView',false)
            }
            this.isAdding = false
        },

        test(){
            // let items = {}
            // for(let i=0;i<85;i++){
            //     let array = []
            //     items.i = array
            // }
            // console.log(items)
        },
        showSendImage(){
            const viewer = this.$el.getElementsByClassName('.image-viewer').$viewer;
            viewer.show();
        },
        fileExtentionDetector(extention){
            let src = "/img/icon/icon_" + extention + "@2x.png";
            return src;
        },
        unknownFileImage(){
            this.fileExtentionDetector("query");
        },
        // listen event
        onPlayerPlay(player) {
            // console.log('player play!', player)
        },
        onPlayerPause(player) {
            // console.log('player pause!', player)
        },
        onPlayerEnded(player) {
            // console.log('player ended!', player)
        },
        onPlayerLoadeddata(player) {
            // console.log('player Loadeddata!', player)
        },
        onPlayerWaiting(player) {
            // console.log('player Waiting!', player)
        },
        onPlayerPlaying(player) {
            // console.log('player Playing!', player)
        },
        onPlayerTimeupdate(player) {
            // console.log('player Timeupdate!', player.currentTime())
        },
        onPlayerCanplay(player) {
            // console.log('player Canplay!', player)
        },
        onPlayerCanplaythrough(player) {
            // console.log('player Canplaythrough!', player)
        },
        // or listen state event
        playerStateChanged(playerCurrentState) {
            // console.log('player current update state', playerCurrentState)
        },
        // player is ready
        playerReadied(player) {
            // seek to 10s
            // console.log('example player 1 readied', player)
            player.currentTime(10)
            // console.log('example 01: the player is readied', player)
        },
        playSmsVideo(video){
            this.playSmsVideoModal = true;
            // this.playerOptions.sources[0].src = "http://127.0.0.1:8000/" + video.imgUrl;
            this.playerOptions.sources[0].src = "http://47.111.233.60/" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/icon/default_video.png";
        },
    }
}
</script>

<style>

</style>