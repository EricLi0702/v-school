<template>
    <div id="applicationBoard">
        <div v-if="allBoardList && allBoardList.length >0">
            <div v-for="data in allBoardList" :key="data.id">
                <div class="es-card">
                    <div class="es-card-logo">
                        <img :src="data.content.imgUrl" alt="" class="avatar m-0">
                    </div>
                    <div class="es-card-main" v-if="contentType == 1 || contentType == 2">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="card-content">
                            <label>
                                <span>问卷标题：{{data.addData.title}}</span>
                                <br>
                                <span>问卷标题：{{data.addData.description}}</span>
                                <br>
                                <span>问卷形式：
                                    <span v-if="data.addData.answerFlag == true">公开问卷</span>
                                    <span v-else>匿名问卷</span>
                                </span>
                                <br>
                                <span>截止时间：{{TimeView(data.addData.deadline)}}</span>
                            </label>                            
                        </div>
                        <div>
                            <div class="card-images card-component" v-if="data.addData.imgUrl && data.addData.imgUrl.length>0">
                                <div class="card-image-content" v-for="(imgUrl,i) in data.addData.imgUrl" :key="i">
                                    <div class="card-image-item">
                                        <img :src="imgUrl" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="file-list card-component" v-if="data.addData.otherUrl && data.addData.otherUrl.length>0">
                                <a href="" target="_blank" v-for="(other,j) in data.addData.otherUrl" :key="j">
                                    <div class="logo">
                                        <img src="/img/icon/icon_pdf@2x.png" alt="">
                                    </div>
                                    <div class="title">
                                        <div class="file-name">{{other.fileOriName}}</div>
                                        <div>{{other.fileSize}}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="file-list card-component" v-if="data.addData.videoUrl && data.addData.videoUrl.length>0">
                                <a href="" target="_blank" v-for="(video,k) in data.addData.videoUrl" :key="k">
                                    <div class="video-box card-component video-cover">
                                        <div class="vb-bg"></div>
                                        <div class="vb-play"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="card-click">
                            <span class="card-click_href-left text-color">查看详情</span>|
                            <span class="card-click_href text-color">查看详情</span>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 3">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="card-content">
                            <label>
                                <span>{{data.addData.text}}</span>
                            </label>
                        </div>
                        <div>
                            <!-- <div class="card-images card-component" v-if="data.addData.imgUrl && data.addData.imgUrl.length>0">
                                <div class="card-image-content" v-for="(imgUrl,i) in data.addData.imgUrl" :key="i">
                                    <div class="card-image-item">
                                        <img :src="imgUrl" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="file-list card-component" v-if="data.addData.otherUrl && data.addData.otherUrl.length>0">
                                <a href="" target="_blank" v-for="(other,j) in data.addData.otherUrl" :key="j">
                                    <div class="logo">
                                        <img src="/img/icon/icon_pdf@2x.png" alt="">
                                    </div>
                                    <div class="title">
                                        <div class="file-name">{{other.fileOriName}}</div>
                                        <div>{{other.fileSize}}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="file-list card-component" v-if="data.addData.videoUrl && data.addData.videoUrl.length>0">
                                <a href="" target="_blank" v-for="(video,k) in data.addData.videoUrl" :key="k">
                                    <div class="video-box card-component video-cover">
                                        <div class="vb-bg"></div>
                                        <div class="vb-play"></div>
                                    </div>
                                </a>
                            </div> -->
                            <div v-for="img in data.addData.imgUrl" :key="img.fileName">
                                <div v-if="data.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                    <img :src="img" alt="" @click="showSendImage">
                                </div>
                                <div v-else class="ct-3-img-container image-viewer" v-viewer>
                                    <img :src="img" alt="" class="" @click="showSendImage">
                                </div>
                            </div>
                            <div v-for="file in data.addData.otherUrl" :key="file.fileName">
                                <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                    <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                    <div class="file-info-tag">
                                        <p class="text-dark">{{file.fileOriName}}</p>
                                        <p class="text-secondary">{{file.fileSize}}</p>
                                        <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                                    </div>
                                </a>
                            </div>                                               
                            <div v-for="video in data.addData.videoUrl" :key="video.fileName">
                                <div class="video-box video-cover">
                                    <div class="vb-bg"></div>
                                    <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 4">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="image-title image-viewer"  v-viewer>
                            <img :src="data.addData.imgUrl" alt="" @click="showSendImage">
                            <li class="bg-light">
                                <p class="p-3">{{data.addData.title}}</p>
                            </li>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 5">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="card-content">

                                <li>公告标题：{{data.addData.title}}</li>
                                <li v-html="data.addData.content"></li>
                                <!-- <li>{{item.addData.content}}</li> -->
                                <div class="ct-5-post-user-time-detail text-right pr-4">
                                    <li>{{data.user.name}}</li>
                                    <li>{{TimeView(data.created_at)}}</li>
                                </div>
                                <div class="ct-5-post-see-more">
                                    <p href="#" class="pb-2 text-success"><small>查看详情</small> </p>
                                </div>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 6">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="card-content">
                            <li>使用人：{{data.addData.userName}}</li>
                            <li>类型：{{data.addData.type}}</li>
                            <li>日期：{{TimeView(data.addData.deadline)}}</li>
                            <li>时段：{{data.addData.timePeriod}}</li>
                            <li>场所：{{data.addData.place}}</li>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 7">
                        <div class="app-card-header">
                            <div class="card-header_name">
                                <span class="pointer">{{data.content.contentName}}</span>
                                <span class="spot pointer">▪</span>
                                <span class="pointner">{{data.user.name}}</span>
                                <div class="card-header_time text-label f12">
                                    {{TimeView(data.created_at)}}
                                </div>
                            </div>
                            <div class="drop-down">
                                <Icon type="ios-arrow-down" size="20"/>
                            </div>
                        </div>
                        <div class="card-content">
                            <li>{{data.addData.title}}</li>
                            <div v-for="img in data.addData.imgUrl" :key="data.fileName">
                                <div class="image-viewer" v-viewer>
                                    <img :src="img" alt="" class="" @click="showSendImage">
                                </div>
                            </div>
                        </div>
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item"><Icon type="md-heart-outline" size="20" style="cursor:pointer" /></div>
                                <div class="counter-item"><Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="es-card-main" v-else-if="contentType == 8">

                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <notConnect></notConnect>
        </div>
    </div>
</template>

<script>
import notConnect from '../pages/notConnect'
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
export default {
    components:{
        notConnect,
        videoPlayer,
        Viewer,
    },
    props:['selLesson','contentType'],
    data(){
        return{
            allBoardList:[],
            boardList:[],
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){
        console.log('@@@@@@',this.contentType)
        axios.get('/api/applicationLists',{params:{
            contentType:this.contentType
        }}).then(res=>{
            // this.allBoardList = res.data
            for(let i=0;i<res.data.length;i++){
                res.data[i].addData = JSON.parse(res.data[i].addData)
            }
            this.allBoardList = res.data
            console.log(this.allBoardList)
        })    
    },
    methods:{
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
    }

}
</script>