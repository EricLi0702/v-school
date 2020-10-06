<template>
    <div class="uploadBtn-area">
        <!-- <div class="row">
            <div class="col-4" @click="addUploadModal">
                <img src="/img/" alt="">
                <span>Upload Video</span>
            </div>
            <div class="col-4" @click="addViewModal">
                <img src="" alt="">
                <span>View Video</span>
            </div>
        </div> -->
        <Row type="flex" justify="space-between" class="code-row-bg">
            <Col span="5">
                <div  @click="addUploadModal">
                    <img src="/img/icon/会议 拷贝.png" alt="">
                    <span>上载视频</span>
                </div>
            </Col>
            <Col span="5">
                <div  @click="addViewModal">
                    <img src="/img/icon/会议 拷贝.png" alt="">
                    <span>观看视频</span>
                </div>
            </Col>
        </Row>

        <div class="modal-con-area">
            <Modal
                v-model="uploadModal"
                title="File Upload Modal"
                :styles="{top:'75px',left:'-90px'}"
                >
                <div class="text-center">
                    <p class="font-weight-bold p-2">Select your file type and upload</p>

                    <div class="upload-icon-area p-2">
                        <Button :size="buttonLargeSize" @click="addUploadLinkModal" icon="ios-attach" type="primary" shape="circle"></Button>
                        <Button :size="buttonLargeSize" @click="addUploadVideoModal" icon="md-film" type="primary" shape="circle"></Button>
                        <Button :size="buttonLargeSize" @click="addUploadImageModal" icon="ios-images" type="primary" shape="circle"></Button>
                        <Button :size="buttonLargeSize" @click="addUploadMusicModal" icon="ios-headset" type="primary" shape="circle"></Button>
                    </div>

                    <p class="font-weight-bold p-2">Please write some description.</p>

                    <Input v-model="uploadDescription" placeholder="Enter something..." style="width: 300px; margin-bottom:20px;" />

                    

                    <div class="upload-button-area">
                        <Button class="btnclass"> Upload Video </Button>
                    </div>
                </div>
                
            </Modal>
            <Modal
                footer-hide	
                v-model="showVideoModal"
                title="Video Upload Modal"
                :styles="{top:'74px',left:'-244px'}"
                class-name="index-video-modal"
                >
                
                <div class="select-category-area">
                    <Row>
                        <Col class="d-flex bg-white w-100 p-3 m-0">
                            <Input v-model="searchVideo" search placeholder="Enter something..." />
                        </Col>
                    </Row>
                </div>
                <div class="_overflow_table_div">
                    <table class="table _table">
                        <thead class="thead-light">
                            <tr>
                                <th>Video Lecture</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Crated at</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <tr class="mb-3" v-for="(video,i) in filteredVideos" :key="i" v-if="filteredVideos.length">
                                <td class="video-box position-relative">
                                    <img v-bind:src="'/img/coverImage/' + video.subject + '_image.jpg'" />
                                    <div class="play-video-icon position-absolute" @click="playLectureVideo(video)">
                                        <Icon type="ios-play" />
                                    </div>
                                    
                                </td>
                                <td>{{video.title}}</td>
                                <td class="_table_name">{{video.description}}</td>
                                <td>{{video.created_at}}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </Modal>
            


            <Modal
                v-model="UploadLinkModal"
                title="Link Upload Modal"
                >
                <p>Content of dialog</p>
                <p>Content of dialog</p>
                <p>Content of dialog</p>
            </Modal>
            <Modal
                v-model="UploadVideoModal"
                title="Video Upload Modal"
                :styles="{top:'75px',left:'-90px'}"
                >
                <h5>Title:</h5>
                <Input v-model="addUploadVideo.title" class="mb-2" placeholder="Enter title of video"/>
                <h5>Description:</h5>
                <Input v-model="addUploadVideo.description"  class="mb-2" type="textarea"  placeholder="Enter description of video"/>

                <div class="d-flex py-3">
                    <h5 class="p-2">Select Grade:</h5>
                    <Select v-model="addUploadVideo.grade" size="large" style="width:100px">
                        <Option v-for="item in gradeList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                    <h5 class="p-2">Select Subject:</h5>
                    <Select v-model="addUploadVideo.subject" size="large" style="width:100px">
                        <Option v-for="item in subjectList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                    </Select>
                </div>
                
                <Upload
                    ref="uploads"
                    type="drag"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :format="['mp4']"
                    :max-size="524288"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    action="api/video/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Click or drag files here to upload</p>
                    </div>
                </Upload>

                <div slot="footer">
                    <Button type="default" @click="UploadVideoModal=false">Close</Button>
                    <Button type="primary" @click="uploadVideo" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add Category'}}</Button>
                </div>
            </Modal>
            <Modal
                v-model="UploadImageModal"
                title="Gallery Upload Modal"
                >
                <p>Content of dialog</p>
                <p>Content of dialog</p>
                <p>Content of dialog</p>
            </Modal>
            <Modal
                v-model="UploadMusicModal"
                title="Music Upload Modal"
                >
                <p>Content of dialog</p>
                <p>Content of dialog</p>
                <p>Content of dialog</p>
            </Modal>

            <Modal
                footer-hide	
                v-model="playLectureVideoModal"
                class-name="vertical-center-modal"
                :styles="{top:'140px',left:'-244px'}"
                :mask-closable="false"
                >
                <video-player  
                    class="video-player-box"
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
    
</template>

<script>
import gradeList from '../../json/vSchool/grade.json';
import subjectList from '../../json/vSchool/subject.json';
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
export default {
    components:{
        videoPlayer,
    },
    data () {
        return{
            gradeList,
            subjectList,
            videoLists:[],
            uploadModal:false,
            showVideoModal:false,
            UploadLinkModal:false,
            UploadVideoModal:false,
            UploadImageModal:false,
            UploadMusicModal:false,
            playLectureVideoModal:false,
            uploadDescription:'',
            uploadDescription:'',
            searchVideo:'',
            buttonLargeSize:'large',
            token:'',
            isAdding:false,
            addUploadVideo:{
                videoFile:'',
                title:'',
                description:'',
                grade:'',
                subject:'',
            },

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
            

            //datas of search category
            
        }
        
    },
    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },

        filteredVideos(){
            if(this.searchVideo){
                return this.videoLists.filter((item) => {
                    return (item.grade.toUpperCase().startsWith(this.searchVideo.toUpperCase()))
                        ||(item.subject.toUpperCase().startsWith(this.searchVideo.toUpperCase()))
                        ||(item.description.toUpperCase().startsWith(this.searchVideo.toUpperCase()))
                        ||(item.created_at.toUpperCase().startsWith(this.searchVideo.toUpperCase()))
                        
                });
            } else{
                return this.videoLists;
            }
        },


    },
    async created(){
        this.token = window.Laravel.csrfToken;
        const vid = await this.callApi('get','api/video');
        if(vid.status == 200){
            this.videoLists = vid.data;
        }
        console.log("asdfasdfasdfa", this.videoLists);
    },
    methods:{
        addUploadModal(){
            this.uploadModal = true;
        },
        addViewModal(){
            this.showVideoModal = true;
        },
        addUploadLinkModal(){
            this.UploadLinkModal = true;
        },
        addUploadVideoModal(){
            this.UploadVideoModal = true;
        },
        addUploadImageModal(){
            this.UploadImageModal = true;
        },
        addUploadMusicModal(){
            this.UploadMusicModal = true;
        },
        
        handleSuccess (res, file) {
            res = `/uploads/video/${res}`
            this.addUploadVideo.videoFile = res;
        },
        handleError (res, file) {
            console.log('res',res);
            console.log('file',file);
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select mp4.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 512M.'
            });
        },
        async uploadVideo(){
            this.isAdding = true;
            const res = await this.callApi('post', 'api/video',this.addUploadVideo)
            console.log(res);
            if(res.status === 201){
                this.videoLists.unshift(res.data);
                this.success('Video has been added successfully!');
                this.UploadVideoModal = false;
                this.addUploadVideo.description = '';
                this.addUploadVideo.videoFile = '';
                this.addUploadVideo.grade = '';
                this.addUploadVideo.title = '';
                this.addUploadVideo.subject = '';
                this.$refs.uploads.clearFiles();
            }else{
                if(res.status === 422){
                    if(res.data.errors.description){
                        this.info(res.data.errors.description[0]);
                    }
                    if(res.data.errors.videoFile){
                        this.info(res.data.errors.videoFile[0]);
                    }
                    if(res.data.errors.grade){
                        this.info(res.data.errors.grade[0]);
                    }
                    if(res.data.errors.subject){
                        this.info(res.data.errors.subject[0]);
                    }
                    if(res.data.errors.title){
                        this.info(res.data.errors.title[0]);
                    }
                }else{
                    this.swr()
                }
                
               
            }
            this.isAdding = false;
        },


        //video play method
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
            console.log('example player 1 readied', player)
            player.currentTime(10)
            // console.log('example 01: the player is readied', player)
        },



        //search category
        remoteMethod1 (query) {
            if (query !== '') {
                this.levelLoading = true;
                setTimeout(() => {
                    this.levelLoading = false;
                    const list = this.list.map(item => {
                        return {
                            value: item,
                            label: item
                        };
                    });
                    this.levelOptions = list.filter(item => item.label.toLowerCase().indexOf(query.toLowerCase()) > -1);
                }, 200);
            } else {
                this.levelOptions = [];
            }
        },

        //playVideo
        playLectureVideo(video){
            this.playLectureVideoModal = true;


            this.playerOptions.sources[0].src = "http://47.111.233.60/" + video.videoFile;

            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/coverImage/"+ video.subject + "_image.jpg";
        },

    }
}
</script>
  