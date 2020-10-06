<template>
    <div>
        <div v-for="data in allBoardList" :key="data.id">
            <div class="es-card">
                <div class="es-card-logo">
                    <img :src="data.content.imgUrl" alt="" class="avatar">
                </div>
                <div class="es-card-main">
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
                        <label v-if="contentType == 1 || contentType == 2">
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
                        <label v-else-if="contentType == 3">
                            <span>{{data.addData.text}}</span>
                        </label>
                    </div>
                    <div class="card-images card-component">
                        <div class="card-image-content" v-for="(imgUrl,i) in data.addData.imgUrl" :key="i">
                            <div class="card-image-item">
                                <img :src="imgUrl" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="file-list card-component">
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
                    <div class="file-list card-component">
                        <a href="" target="_blank" v-for="(video,k) in data.addData.videoUrl" :key="k">
                            <div class="video-box card-component video-cover">
                                <div class="vb-bg"></div>
                                <div class="vb-play"></div>
                            </div>
                        </a>
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
            </div>
        </div>
    </div>
</template>

<script>
export default {
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

}
</script>