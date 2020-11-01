<template>
    <div>
        <div v-if="item" style="height:63vh;overflow:auto; padding: 0 20px;">
            <List item-layout="vertical">
                <ListItem>
                    <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName}▪${item.user.name}`">
                        <template slot="description">
                            <div v-if="item.contentType == 1">
                                <li>问卷标题: {{item.addData.title}}</li>
                                <li>问卷说明：{{item.addData.description}}</li>
                                <li>问卷形式： <span v-if="item.addData.questionnaireFlag">匿名问卷</span><span v-else>公开问卷</span></li>
                                <li class="moreDetails">
                                    <span @click="showViewDetails(item)">查看详情</span>
                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                                </li>
                                <li>截止时间：{{TimeView(item.addData.deadline)}}</li>
                            </div>
                            <div v-else-if="item.contentType == 2">
                                <li>投票内容：{{item.addData.content.votingDataArr[0][0].title}}</li>
                                <li>投票形式：
                                    <span v-if="item.addData.anonyVote">匿名投票</span>
                                    <span v-else>公开投票</span>
                                </li>
                                <li>投票上限：{{item.addData.maxVote}}项</li>
                                <li class="moreDetails">
                                    <span @click="showViewDetails(item)">查看详情</span>
                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                                </li>
                                <li>截止时间：{{TimeView(item.addData.deadline)}}</li>
                            </div>
                            <div class="row ct-3-post-container w-100 m-0" v-else-if="item.contentType == 3">
                                <p class="col-12 pl-0 text-dark pb-2">{{item.addData.text}}</p>
                                <div v-for="img in item.addData.imgUrl" :key="img.fileName" class="col-12 col-md-4 p-0 mb-1 m-0 row">
                                    <div v-if="item.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                        <img :src="img" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="ct-3-img-container image-viewer col-12 m-0 pl-0" v-viewer>
                                        <img :src="img" alt="" class="w-100" @click="showSendImage">
                                    </div>
                                </div>
                                <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0 col-md-6">
                                    <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                        <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                        <div class="file-info-tag">
                                            <p class="text-dark">{{file.fileOriName}}</p>
                                            <p class="text-secondary">{{file.fileSize}}</p>
                                            <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                                        </div>
                                    </a>
                                </div>                                               
                                <div v-for="video in item.addData.videoUrl" :key="video.fileName">
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
                            <div class="cd-main" v-else-if="item.contentType == 4">
                                <div class="cd-title">{{item.addData.title}}</div>
                                <div class="cd-sub-title">
                                    <div class="text-color">校园新闻:</div>
                                    <div class="text-label f12">{{TimeView(item.created_at)}}</div>
                                </div>
                                <div class="cd-content" v-html="item.addData.content">

                                </div>
                            </div>
                            <div v-else-if="item.contentType == 5">
                                <li>公告标题：{{item.addData.title}}</li>
                                <li v-html="item.addData.content"></li>
                                <!-- <li>{{item.addData.content}}</li> -->
                                <div class="ct-5-post-user-time-detail text-right pr-4">
                                    <li>{{item.user.name}}</li>
                                    <li>{{TimeView(item.created_at)}}</li>
                                </div>
                                <div class="ct-5-post-see-more">
                                    <p href="#" class="pb-2 text-success"><small>查看详情</small> </p>
                                </div>
                            </div>
                            <div v-else-if="item.contentType ==6">
                                <li>使用人：{{item.addData.userName}}</li>
                                <li>类型：{{item.addData.type}}</li>
                                <li>日期：{{TimeView(item.addData.deadline)}}</li>
                                <li>时段：{{item.addData.timePeriod}}</li>
                                <li>场所：{{item.addData.place}}</li>
                                <div v-for="img in item.addData.imgUrl" :key="img.fileName" class="col-12 col-md-4 p-0 mb-1 m-0 row">
                                    <div v-if="item.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                        <img :src="img" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="ct-3-img-container image-viewer col-12 m-0 pl-0" v-viewer>
                                        <img :src="img" alt="" class="w-100" @click="showSendImage">
                                    </div>
                                </div>
                                <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0 col-md-6">
                                    <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                        <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                        <div class="file-info-tag">
                                            <p class="text-dark">{{file.fileOriName}}</p>
                                            <p class="text-secondary">{{file.fileSize}}</p>
                                            <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                                        </div>
                                    </a>
                                </div>                                               
                                <div v-for="video in item.addData.videoUrl" :key="video.fileName">
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
                            <div v-else-if="item.contentType == 7">
                                <li>{{item.addData.title}}</li>
                                <div v-for="img in item.addData.imgUrl" :key="img.fileName" class="col-12 col-md-4 p-0 mb-1 m-0 row">
                                    <div v-if="item.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                        <img :src="img" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="ct-3-img-container image-viewer col-12 m-0 pl-0" v-viewer>
                                        <img :src="img" alt="" class="w-100" @click="showSendImage">
                                    </div>
                                </div>
                                <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0 col-md-6">
                                    <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                        <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                        <div class="file-info-tag">
                                            <p class="text-dark">{{file.fileOriName}}</p>
                                            <p class="text-secondary">{{file.fileSize}}</p>
                                            <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                                        </div>
                                    </a>
                                </div>                                               
                                <div v-for="video in item.addData.videoUrl" :key="video.fileName">
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
                            <li class="float-left">
                                已阅:<span v-if="item.readCnt">{{item.readCnt}}</span><span v-else>0</span>
                            </li>
                            <li class="float-right">
                                <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20" />
                                <span style="font-size:17px" class="iconHover" v-if="item.comments.length > 0">{{item.comments.length}}</span>
                            </li>
                            <li class="float-right mr-3">
                                <Icon type="md-heart" v-if="item.isLiked == true"  @click="clickLike(item,0)" style="color:#19be6b;cursor:pointer" size="20"/>
                                <Icon type="md-heart-outline" v-else @click="clickLike(item,1)" size="20" style="cursor:pointer" class="iconHover"/>
                                <span style="font-size:17px" class="iconHover" v-if="item.likes.length>0">{{item.likes.length}}</span>
                            </li>
                        </template>
                    </ListItemMeta>
                </ListItem>
            </List>
            <div class="category-title"></div>
            <div class="es-item" v-for="(comment,i) in item.comments" :key="i">
                <div class="es-item-left">
                    <div>
                        <img src="/img/icon/def_avatar.png" alt="" class="avatar">
                    </div>
                    <div class="es-item-info">
                        <div class="title">{{comment.user.name}}</div>
                        <div class="main comment">{{comment.comment}}</div>
                    </div>
                </div>
                <div class="es-item-right">
                    <div class="es-item-detail">
                        <div class="remark" v-if="comment.created_at">{{TimeView(comment.created_at)}}</div>
                        <div class="describe"></div>
                    </div>
                    <Icon v-if="comment.userId == $store.state.user.id" type="ios-remove-circle-outline" @click="delComment(comment,i)"/>
                </div>
            </div> 
        </div>
        <div class="emoji-area-popup">
            <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
        </div>
        <div class="send-block position-absolute">
            <div class="emoji-div"><img src="/img/icon/emoji.png" alt="" class="uploadicon"  @click="toggleEmo"></div>
            <div><textarea v-model="commentText" @keydown.enter.exact.prevent @keyup.enter.exact="submitComment(item)" @keydown.enter.shift.exact="newline" name="" id="" rows="4" class="custom-textarea" placeholder="输入内容"></textarea></div>
            <div class="send-item">
                <span class="toolbar-remark"> 按回车发送，shift+回车换行 </span>
                <Button :disabled="isLoading" :loading="isLoading"  class="btnclass" @click="submitComment(item)">发送</Button>
            </div>
        </div>
        
    </div>
</template>

<script>
import { Picker } from 'emoji-mart-vue'
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
export default {
    components:{
        Picker,
        videoPlayer,
        Viewer,
    },
    props:['item'],
    data(){
        return{
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
    created(){
        
    },
    watch:{
        item:{
            handler(val){
                console.log('----',val)
            },
            deep:true
        }
    },
    data(){
        return{
            commentText:'',
            commentList:[],
            commentData:{
                bulletinId:null,
                userId:null,
                comment:null
            },
            emoStatus:false,
            likeCnt:0,
            isLiked:false,
            isLoading:false,
        }
    },
    methods:{
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

        //playVideo
        playSmsVideo(video){
            this.playSmsVideoModal = true;
            // this.playerOptions.sources[0].src = "http://127.0.0.1:8000/" + video.imgUrl;
            this.playerOptions.sources[0].src = "http://47.111.233.60" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/icon/default_video.png";
        },
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
            console.log(this.emoStatus)
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.commentText){
                this.commentText = e.native
            }else{
                this.commentText = this.commentText + e.native
            }
        },
        async submitComment(item){
            if(this.commentText.trim().length == 0){
                return
            }
            if(this.isLoading == true){
                return
            }
            this.isLoading = true
            this.commentData.bulletinId = item.id
            this.commentData.userId = this.$store.state.user.id
            this.commentData.comment = this.commentText
            const res = await this.callApi('post','/api/comment',this.commentData)
            if(res.status == 201){
                console.log('comment response',res.data)
                this.item.comments.unshift(res.data[0])
                this.commentText = '';
            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }    
            this.emoStatus = false
            this.isLoading = false
        },
        async delComment(comment,index){
            const res = await this.callApi('delete','/api/comment',{id:comment.id})
            if(res.status == 200){
                this.item.comments.splice(index,1);
            }
        },
        newline(){
            this.commentText = `${this.commentText}\n`
            console.log('newline')
        },
        showViewDetails(item){

        },
        showAnswerDetails(item){

        },
        comment(item){

        },
       async clickLike(item,type){
            if(this.isLiked == true){
               return
           }
           this.isLiked = true
           if(type == 1){
               const res = await this.callApi('post','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                if(res.status == 201){
                    this.item.likes.unshift(res.data[0])
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',true)
                    else
                        item.isLiked = true;
                }
           }else if(type == 0){
               const res = await this.callApi('delete','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                if(res.status == 200){
                    this.item.likes.splice(0,1)
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',false)
                    else
                        item.isLiked = false;
                }
           }
           this.isLiked = false    
        },
        showSendImage(){
            const viewer = this.$el.getElementsByClassName('.image-viewer').$viewer;
            viewer.show();
        },
    }
}
</script>

<style scoped>
.emoji-mart{
  height:330px!important;
  position: absolute;
  bottom:167px;
}
.emoji-area-popup{
    top:0!important;
    left:0!important;
    bottom:0!important;
}
</style>