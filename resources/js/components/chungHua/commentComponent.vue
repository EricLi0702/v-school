<template>
    <div>
        <div v-if="item" class="main-comment-modal-content-con" :class="{'second-tab-comment': !firstTabComment}">
            <List item-layout="vertical">
                <ListItem class="bulletin-board-item-group">
                    <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName} ▪ ${item.user.name}`">
                        <template slot="description">
                            <div class="ct-1-post-container" v-if="item.contentType == 1">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>问卷标题: {{item.addData.title}}</li>
                                <li>问卷说明：{{item.addData.description}}</li>
                                <li>问卷形式： <span v-if="item.addData.questionnaireFlag">匿名问卷</span><span v-else>公开问卷</span></li>
                                <li>截止时间：{{TimeView(item.addData.deadline)}}</li>
                                <li class="moreDetails">
                                    <span @click="showViewDetails(item)">查看详情</span>
                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                                </li>
                            </div>
                            <div class="ct-2-post-container" v-else-if="item.contentType == 2">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>投票内容：{{item.addData.content.votingDataArr[0][0].title}}</li>
                                <li>投票形式：<span v-if="item.addData.anonyVote">匿名投票</span>
                                            <span v-else>公开投票</span>
                                </li>
                                <li>投票上限：{{item.addData.maxVote}}项</li>
                                <li>截止时间：{{TimeView(item.addData.deadline)}}</li>
                                <li class="moreDetails">
                                    <span @click="showViewDetails(item)">查看详情</span>
                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                                </li>
                            </div>
                            <div class="row ct-3-post-container w-100 m-0" v-else-if="item.contentType == 3" >
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <p class="col-12 pl-0 text-dark pb-2">{{item.addData.text}}</p>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0">
                                    <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                            <div class="ct-4-post-container" v-else-if="item.contentType == 4" >
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <div class="w-sm-100 image-title image-viewer"  v-viewer>
                                    <div class="text-color">{{item.addData.camposeCategory}}</div>
                                    <img :src="item.addData.imgUrl" alt="" @click="showSendImage">
                                    <li class="w-100 bg-light">
                                        <p class="p-3 w-100" @click="comment(item)">{{item.addData.title}}</p>
                                    </li>
                                </div>
                            </div>
                            <div class="ct-5-post-container text-dark" v-else-if="item.contentType == 5">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>公告标题：{{item.addData.title}}</li>
                                <li v-html="item.addData.content"></li>
                                <!-- <li>{{item.addData.content}}</li> -->
                                <div class="ct-5-post-user-time-detail text-right">
                                    <li>{{item.user.name}}</li>
                                    <li>{{TimeView(item.created_at)}}</li>
                                </div>
                                <div class="ct-5-post-see-more">
                                    <p href="#" class="pb-2 text-primary"><small>查看详情</small> </p>
                                </div>
                            </div>
                            <div class="ct-6-post-container" v-else-if="item.contentType == 6">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
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
                            <div class="ct-7-post-container row w-100 m-0 d-block" v-else-if="item.contentType == 7" key="contentType7">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>{{item.addData.title}}</li>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0 ">
                                    <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                            <div class="ct-8-post-container" v-else-if="item.contentType == 8">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>展示时间：{{TimeView(item.addData.startShow)}}至{{TimeView(item.addData.endShow)}}</li>
                                <li>发布到：{{item.addData.target}}</li>
                                <li>{{item.addData.description}}</li>
                                <li>{{item.addData.name}}</li>
                                <div v-for="img in item.addData.imgUrl" :key="img.fileName">
                                    <div class="image-viewer" v-viewer>
                                        <img :src="img" alt="" class="" @click="showSendImage">
                                    </div>
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
                            <div class="ct-9-post-container" v-else-if="item.contentType == 9">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>活动主题：{{item.addData.title}}</li>
                                <li>截止时间：{{TimeView(item.created_at)}}</li>
                                <div v-for="img in item.addData.imgUrl" :key="img.fileName">
                                    <div class="image-viewer" v-viewer>
                                        <img :src="img" alt="" class="" @click="showSendImage">
                                    </div>
                                </div>
                            </div>
                            <div class="ct-10-post-container" v-else-if="item.contentType == 10">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>栏目：{{item.addData.type}}</li>
                                <li>标题：{{item.title}}</li>
                                <li v-html="item.addData.content"></li>
                            </div>
                            <div class="ct-15-post-container" v-else-if="item.contentType == 15">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>作业科目:{{item.addData.subject}}</li>
                                <li>作业类型:{{item.addData.type}}</li>
                                <div v-if="item.addData.type == '常规作业'">
                                    <li>作业内容:{{item.addData.text}}</li>
                                </div>
                                <div v-if="item.addData.type == '在线作业'">
                                    <li>截止时间:{{TimeView(item.addData.publishingRules.deadline)}}</li>
                                    <li>作业内容:{{item.addData.text}}</li>
                                    <li>预发布时间:{{TimeView(item.addData.publishingRules.releaseTime)}}</li>
                                </div>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0">
                                    <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                                <li class="moreDetails" @click="postView(item)" v-if="item.answerUserList == null && $store.state.user.roleId == 5">开始作答</li>
                                <li class="moreDetails" @click="studentView(item)" v-if="$store.state.user.roleId == 5">查看详情</li>
                                <li class="moreDetails" @click="teacherView(item)" v-else>查看详情</li>
                            </div>
                            <div class="ct-18-post-container" v-else-if="item.contentType == 18">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>截止日期：{{TimeView(item.addData.deadline)}}</li>
                                <li>家访内容：15项</li>
                                <li>{{item.addData.content.text}}</li>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.content.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.content.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.content.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0">
                                    <div v-for="file in item.addData.content.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                                <div v-for="video in item.addData.content.videoUrl" :key="video.fileName">
                                    <div class="video-box video-cover">
                                        <div class="vb-bg"></div>
                                        <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                                    </div>
                                </div>
                                <li class="moreDetails" @click="homeVisitView(item)">已反馈<span> {{item.answers.length}}</span>人</li>
                                <li class="moreDetails" @click="showAnswerDetails(item)" v-for="answerUser in item.addData.userInfo" :key="answerUser.id" v-if="answerUser.id == $store.state.user.id && item.answerUserList == null">开始作答</li>
                            </div>
                            <div class="ct-19-post-container" v-else-if="item.contentType == 19">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>{{item.addData.title}}</li>
                                <li>{{item.addData.checkInTime.clockCycle}}，每天</li>
                                <li>{{item.addData.content.text}}</li>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.content.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.content.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.content.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div v-for="video in item.addData.content.videoUrl" :key="video.fileName">
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
                                <li class="moreDetails" @click="postView(item)">查看详情</li>
                            </div>
                            <div class="ct-20-post-container" v-else-if="item.contentType == 20">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>{{item.addData.title}}</li>
                                <li>共{{item.addData.addDataList.length}}题：{{item.addData.addDataList[0].selQuestion}}{{item.addData.addDataList.length}}题</li>
                                <li>难度：{{item.addData.addDataList[0].selLevel}}{{item.addData.addDataList.length}}题</li>
                                <template v-if="$store.state.user.roleId == 5">
                                    <li class="moreDetails" @click="homeworkView(item)" v-if="item.answered">查看详情</li>
                                    <li class="moreDetails" @click="homeworkSolve(item)" v-else>开始作答</li>
                                </template>
                                <template v-else>
                                    <li class="moreDetails" @click="homeworkCheck(item)">查看详情</li>
                                </template>
                            </div>
                            <div class="ct-21-post-container" v-else-if="item.contentType == 21">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>
                                    <span class="and text-color" v-for="(user,i) in item.addData.userList" :key="i">@{{user}}</span>
                                </li>
                                <li v-if="item.addData.commentData">
                                    <span v-if="item.addData.commentData.title">{{item.addData.commentData.title}}</span>
                                </li>
                                <div class="card-medals card-component">
                                    <div class="each-row">
                                        <div class="medal-background">
                                            <div class="medal-list" v-if="item.addData.selMedalList.length < 4" >
                                                <div class="ml-item" v-for="(medal,j) in item.addData.selMedalList" :key="j" >
                                                    <div class="medal-04" @click="showMedalDetail(medal)">
                                                        <div class="md-score-line">
                                                            <img src="data:image/png;base64,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" alt="">
                                                            <span class="txt">+{{medal.medalPoint}}分</span>
                                                        </div>
                                                        <img src="/img/icon/medal_empty.png" :style="medal.medalImg" alt="" class="md-logo">
                                                        <div class="md-word-bg"></div>
                                                        <div class="md-content">
                                                            <span class="md-name">{{medal.medalText}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="select-icon-line"></div>
                                                </div>
                                            </div>
                                            <div v-else class="medal-list">
                                                <div class="ml-item">
                                                    <div class="medal-04" @click="showMedalDetail(item.addData.selMedalList[0])">
                                                        <div class="md-score-line">
                                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEoAAAAoCAYAAABZ7GwgAAAMK0lEQVRoQ+2ae4xUZxnGf+c6M7vL7rLbBRZZWigtymIpVKi1tqVN0VgqUUjpBato01ox0RqNsU0aqJgYa5qoif5TozEao63pRaMRo63QkMaWYKncSpfbwrLAsgO7zM7MuZvvXGbOnJnZnV3a+oc9yWYu55yZ+Z593ud93vf9JC7h8DxPuoTb3/NbJUnypvqlk1poFTBPPBHcv2+fxF3A/qFJfd5Uf3TD9y3q8ngW6O0NANq8uQKoyQDX0MIqABLgxIHpvigxWJQYnitxFZAtNPSZDS92qhd2ZDzeBjr7PbrTHoPTPOLAxUBrBLAJF1UCSQC0aJ8UsGZIpmOuxNELMsqYzEygyZHItUvkbYnOcHXi+Xt5NKkBY4bF71E9Wi545BWPM4DT7DKv3SXb70GX64O2v9eLWDYRWHUXUgGQYFBvCBBdMlyUSU/XWLvhXtLt14O7GknqRFYkXEfCMcG1wLHBLIDjgOeC54WP4XM8cMXz+LnwGnFOXC/OU+N86bOiz0w+uiZ4F3Dcw7jOa5jWNvb27USybZjmwpDrA7avy/NDM2RYPcBqAlXFovNHZIwZMtmzCh2qyuqvbyLT9iiS0uov0l9I7C/52i5CcQwsO7jOB2Eqi4/dJ77DB1mQKHw/+br6O/pw7K3sOfyCD1jHDIfUWZfp892J2FUFVAVI/FP2w6y1VWF0VOEzm5YzY96vkNUef6FWDkZOwMhJKGTBKoLrgaxBuhWmzYS2btBSATDifD4XXOMvUCw28RgxxWdSLUATrGwE8Ii5JUC91zk/8iAnjh6mtdVhdNTx2cVKtx6zagPlZ7MYSFZWZd3ja2np+jlIKtYYnHkDsofBdYLF+osPFx6FjA+CBx2Xw+yFAWC2CfkxcOqEXDKkkoucMOQaZCveMPniRgb2b0frsJNgJUOwAiifTSXR3q/4TBpxNTY8vJa2Ob9AnB/thxM7gwXXAiYOUvy8JMP866B9JngO5HIBWHWZcwmhWdK0iTTOM7Hz6zhwdgdtsuWDtWiRE4VhHKwSUKWQW79e5vYjMmO6imDSqk0r6F64DUlWGD4Ap3aVBTgKIbHYOKPqPRcgXr4YZl0BjgW5fDkMk0IfD6lI2Es6GGPNpICuCX6W0bHbGT7S5zOr2bT5+3yXZ54RsU8EViVQUch1ZBSy/Rp6c4ovbH4DRZvjM6l/ezlkkuEmNOnqVX6kcWAb5IZrh6MAZOFymD4DLAPyViwb1tOf+PvRYmPZsVbmrNK38L4qYH3W7eLgW58ETDrmWmQLTqRXFUCV2PTsepn9+xVIq+Do3Petr9I643u+Jh36Y2W4RRnGDzUPFt0J03sC15Q9DrufC9N7LM1HTJMUWLYSNB3GCmAns1kMmAo2VVmAEORJCHyVJQmTiWU+QN/Ac6CYULT9ELyrzCqfUSVt8gUclYylIXsZNjzWh6ROY+BVGO5LaFLMA7XMhCXrKq3la7+D8wPle5JiP6MHrloMlhD3pM8Kwa0ValFIxi1BTcEfD7wEK4PPPMzR/htQmooUNAuw46wqAyW0qTdkU9FMcf/X7qej58e+BTj4Qjm7JcVaa4beO6E5suMhXrlzsOsPkB+trV/iH7liJegpuFgAVy57sZqalFz4RK9jelRiUdzM1tCrseJn6RvcTlo3fFbtW+QIrRLhVwZKhN3pnMrJIzpNLSk2fudHpJo3cO4tGHg9EHA/3UswazG0dEG6DZo6QGS0Wof4T108B2NZyJ6Cvn8FLj0K2wWLYHaP/5swaglt0sFHC435r3fCLkR+zrF/xuEzm8nnDObMN5nVYovwKwO1ZYvs+6YRQ2OmqeMYaT7/3b0o+mUcfRlGBsqsmH0tzL1+ahXcvpfhwCvl0mR6J1zzETAMMJTK8qYKgDp2IWlak6VShahPqGWvcez4GpRUkTO6SVvKEuEnbdniSiV96v6TwiAaKTNFUc3w4OMnkFSZ/c+DKdJ4+F/sWQ49100NKAHS3pfKn6Xr8LGVgU4VIqCSjj1y6DFDWyqRGjSX9Uxs9H4JTHeIQ4PXkrYLGLpBNxaDn3aEWw+AivSpWdFQmlOMjDXx0NZ+32C++fuguC1lLA0Wr4FW0TKYxJEdgB2/BkPoUbRA4NZPgGdDLtKoKS4+DkbEsigZ1MycSYb6VYTJoRNX0tacxxkzGHOskk75QAl9euXfKp0pUaSlUY0MG7ceB0/iDQGUyEphlotquWvWBHVcI8fwSdjxm6CTEM9+4t7bVgWJwgcqAVItUW/IYCazWh1bEXfwQciaHOtfgJ0qQLHIsGFx09JAp0pACSEvHNFRWlJcpIkvffsosiKz90UwYqEXlSV6C9z4xfpCHgEorv/LTyB3PpH93CDj3XQzmCYYYeFc5dDrtlEqPVTVohsR/CqbcI6+oeuYRh4nZ5CJCXpdoDZ+YzeK3knfdrhwqobL9uDWTaCmxueU6Bg89/2wcI79MMGsjg5YuhQMC8wQqApPNJH4TnA+GYJRS6aClbEQdL1dHB5aNz5QydC765Efkm65m7OH4PjuauOoNcHKhxsJPHj+SSiEfioeeh9cCHPmgAFYQswnoU/1+lHj1YWljFgHYNt6moHTW+uHXi0xv+O++5g19wcYY7Dnz9WGs6MHlq9vDKh//BIG+8oFsO+jgJs/DkIWc0JHtQk6mTXd9PgNwDg7G9G2vLGBU8M76ou5KIaT9qBZb2btl3ejqC0c3QVnjlSWMO0fgI/eWwZKZLWjgnkezFsGnXPK5/72NJw+Wnn/7G7o/VDQ9TQy9dn0bvWj4tbAtz7uMU4NraLo5WvaA7/Wq2U4LTXD6nsepHPWY76P2vNXsMJKP/JU81dAezf0/wdOHYwxxoPZV8MVS2DoBOzdUQmSLMONNwRsykvg6ZfeGo5azBU99gn7UWWTWyg+wqkLL6LZhZqGs1QUJ0sYYRG8dBPrH3gJVZuNYMzBV8tmMcp+FcVumGnq9qbC8Fm6BLo6wXLAbKruANSszSYS9kYaffVsg7uHQ4N3k3bzvj7VLWHiplO0WERRLOtpMlaaZTct56rFv0WSFF9njuwpg1Wv/VsLxOjahVfD5XNADEOMNFBLxGuYwfjwwg+b0MGXeuyRo08MHMZrCAafc57zI/cwknubglbENYvjF8WlPnnYZikUU3hWGlXOcMsdd9I95yk8V2L4FBzcFZQdUS+qVvs3KnyjLqgItw/3BkwSBrMo7IBaHXINuepJdA7qli8+oBaFwkOcubAT2y0gaUUyaWP8NouIwWTjTrNTeFoaz0lz46dW0939JBKKb0CPHYTB45XDhWQzL8o03bPhynmBJgkmmRm/7VUecdWyBRN5pKj2m/K05gIXC9/k3PBOJKWIZBWxVGPcxl1V8y5qBYOOlk4hOykMJc2SpUtZsOApVLXbX6SY1Q2dhnOn4eKI7/p9lqkaNDfDZZ3Q1RUA5I+rxJ8INzUcVUVhEmvTJkOqrveZgFVxZsaHqb4suG+SHXmU88UjpJwirmJgFYWbG78VXAJKPEkOF5w2HcNKkfZ0bDeFrKa48bbP0dH+FRS5pWLwmRyGRlW+EG1bB5JlykQGM9GPKlf55XlgpFfjdUMjsF3nOPniTzmZ3YZqG6iyQVEySWkGyojZ0HChglX+HoPYuCrlaXiOjufqeJ6OI2mklBTLVqyhvXUJinoLstuOLIUj9XDWJ2kgmqqyACjWJhl3Wlw3M9X2WvX7URaeO4rjnsAx3yRffIVz2V1YkoniWUiSiSSbSIqJIVkNj6tKNWw020sOQAuqhiZpmLKGiuaDhawi2wquovi9XFl0TMXb/+N9U67YB2WB/yi7yI6Dq4r/nu2DZGOhuxaWZ5GxrUkPQCtCMDktFiN1NBUUFRONJkPFVhUURcGxA6AUTcIJQRKRFh22++7ualHl8r4nM/xSRfJwrAAoRXVwHAfVdsinbHSBomiyWfaUR+pVYIkwjG/SyKQVzo+qpHUFzVAwNQUcGWwZLRUAYodgTdBYaKxQnMRVQo7FoYY76yxDZBYXFBfdcrBSDkXTYXqrTaHoXNImjYoQFC+ijWPJbT/5nEJHi4xnyngZGdOR0F3JfxRHs8gh7zKTkhjqssdY+KaueJiyh3iUCi6S7pLNuTS1OO/Ytp/497+/kSxAoyHteH9rYoNAVYVj9Mb7m10bE9D/p+3T/wWwG+TcKs60HgAAAABJRU5ErkJggg==" alt="">
                                                            <span class="txt">+{{item.addData.selMedalList[0].medalPoint}}分</span>
                                                        </div>
                                                        <img src="/img/icon/medal_empty.png" :style="item.addData.selMedalList[0].medalImg" alt="" class="md-logo">
                                                        <div class="md-word-bg"></div>
                                                        <div class="md-content">
                                                            <span class="md-name">{{item.addData.selMedalList[0].medalText}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="select-icon-line"></div>
                                                </div>
                                                <div class="ml-item">
                                                    <div class="medal-04" @click="showMedalDetail(item.addData.selMedalList[1])">
                                                        <div class="md-score-line">
                                                            <img src="data:image/png;base64,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" alt="">
                                                            <span class="txt">+{{item.addData.selMedalList[1].medalPoint}}分</span>
                                                        </div>
                                                        <img src="/img/icon/medal_empty.png" :style="item.addData.selMedalList[1].medalImg" alt="" class="md-logo">
                                                        <div class="md-word-bg"></div>
                                                        <div class="md-content">
                                                            <span class="md-name">{{item.addData.selMedalList[1].medalText}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="select-icon-line"></div>
                                                </div>
                                                <div class="ml-item position-relative">
                                                    <div class="medal-04 filter-brightness" @click="viewExtraMedals(item.addData.selMedalList)">
                                                        <div class="md-score-line">
                                                            <img src="data:image/png;base64,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" alt="">
                                                            <span class="txt">+{{item.addData.selMedalList[2].medalPoint}}分</span>
                                                        </div>
                                                        <img src="/img/icon/medal_empty.png" :style="item.addData.selMedalList[2].medalImg" alt="" class="md-logo">
                                                        <div class="md-word-bg"></div>
                                                        <div class="md-content">
                                                            <span class="md-name">{{item.addData.selMedalList[2].medalText}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="select-icon-line"></div>
                                                    <div class="medal-plus-text">
                                                        +{{item.addData.selMedalList.length - 2}}
                                                    </div>
                                                </div>
                                                <Modal
                                                    footer-hide
                                                    :value="showMedalArrModal"
                                                    :styles="{top:'75px',left:'-90px'}"
                                                    class-name="extra-medal-view-modal"
                                                    @on-visible-change="initeExtraMelalArr"
                                                >
                                                    <a @click="$router.go(-1)"><Icon class="question-view-modal-back-icon" type="ios-arrow-back" /></a>
                                                    <div class="p-modal-scroll">
                                                        <div class="md-content">
                                                            <div class="md-block" v-for="(medal,j) in showMedalArr" :key="j">
                                                                <div style="display: flex;flex-direction: column;">
                                                                    <div class="medal-04" @click="showMedalDetail(medal)">
                                                                        <div class="md-score-line">
                                                                            <img src="data:image/png;base64,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" alt="">
                                                                            <span class="txt">+{{medal.medalPoint}}分</span>
                                                                        </div>
                                                                        <img :src="`/img/icon/medal_empty.png`" :style="medal.medalImg" alt="" class="md-logo">
                                                                        <div class="md-word-bg"></div>
                                                                        <div class="md-content">
                                                                            <span class="md-name">{{medal.medalText}}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="select-icon-line">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Modal>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-medals card-component" v-if="item.addData.commentData">
                                    <div v-for="img in item.addData.commentData.imgUrl" :key="img.fileName" class="col-12 col-md-4 p-0 mb-1 m-0 row">
                                        <div v-if="item.addData.commentData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                            <img :src="img" alt="" @click="showSendImage">
                                        </div>
                                        <div v-else class="ct-3-img-container image-viewer col-12 m-0 pl-0" v-viewer>
                                            <img :src="img" alt="" class="w-100" @click="showSendImage">
                                        </div>
                                    </div>
                                    <div v-for="file in item.addData.commentData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0 col-md-6">
                                        <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                            <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                            <div class="file-info-tag">
                                                <p class="text-dark">{{file.fileOriName}}</p>
                                                <p class="text-secondary">{{file.fileSize}}</p>
                                                <p class="file-download-counter text-secondary">下载 <span>0</span></p>
                                            </div>
                                        </a>
                                    </div>                                               
                                    <div v-for="video in item.addData.commentData.videoUrl" :key="video.fileName">
                                        <div class="video-box video-cover">
                                            <div class="vb-bg"></div>
                                            <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                                        </div>
                                    </div>
                                </div>
                                <li v-else>
                                    <span class="text-color" @click="medalComment">添加评价内容</span>
                                </li>
                                <Modal 
                                    v-model="showMedalDetailModal" 
                                    width="360"
                                    footer-hide
                                    class-name= "show-medal-detail-modal"
                                    @on-visible-change="initeMedalData"
                                >
                                    <div style="text-align:center">
                                        <div class="medal-type-area-of-modal text-center p-3">
                                            <span>{{showMedalData.medalType}}</span>
                                        </div>
                                        <div class="ml-item d-flex justify-content-center">
                                            <div class="medal-04">
                                                <div class="md-score-line">
                                                    <img src="data:image/png;base64,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" alt="">
                                                    <span class="txt">+{{showMedalData.medalPoint}}分</span>
                                                </div>
                                                <img src="/img/icon/medal_empty.png" :style="showMedalData.medalImg" alt="" class="md-logo">
                                                <div class="md-word-bg"></div>
                                                <div class="md-content">
                                                    <span class="md-name">{{showMedalData.medalText}}</span>
                                                </div>
                                            </div>
                                            <div class="select-icon-line"></div>
                                        </div>

                                        <div class="medal-content-detail text-center py-3 px-1">
                                            {{showMedalData.medalContent}}
                                        </div>
                                    </div>
                                    <div slot="footer">
                                    </div>
                                </Modal>
                            </div>
                            <div class="ct-22-post-container" v-else-if="item.contentType == 22">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <div class="card-content">
                                    <div class="commendation">
                                        <img src="/img/icon/medal_empty.png" alt="" :style="item.addData.imgStyle.recImg">
                                        <div class="cmdn-content">
                                            <div class="cmdn-name scrollBar">
                                                <span v-for="(name,j) in item.addData.students" :key="j">{{name}},</span>
                                            </div>
                                            <div class="cmdn-word">{{item.addData.description}}</div>
                                            <div class="cmdn-title">{{item.addData.awardTitle}}</div>
                                        </div>
                                        <div class="cmdn-tag">
                                            <div>{{item.addData.className}}</div>
                                            <div>{{TimeView(item.addData.publishDate)}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ct-24-post-container" v-else-if="item.contentType == 24">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <div>通知标题：{{item.addData.title}}</div>
                                <div class="card-content">
                                    <label for="">
                                        <span>{{item.addData.description.title}}</span>
                                    </label>
                                </div>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.description.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.description.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.description.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0">
                                    <div v-for="file in item.addData.description.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                                <div v-for="video in item.addData.description.videoUrl" :key="video.fileName">
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
                                <div class="inscription">
                                    {{item.addData.signName}}
                                    <br>
                                    {{TimeView(item.created_at)}}
                                </div>
                            </div>
                            <div class="ct-25-post-container" v-else-if="item.contentType == 25">
                                <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                <li>{{item.addData.title}}</li>
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="item.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="item.addData.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in item.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0">
                                    <div v-for="file in item.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                            <li class="float-left gray-font">
                                已阅 <span v-if="item.readCnt">{{item.readCnt}}</span><span v-else>0</span>
                            </li>
                            <li class="float-right" style="margin-right:16px">
                                <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20" @click="comment(item)"/>
                                <span style="font-size:17px" class="iconHover" v-if="item.comments.length > 0">{{item.comments.length}}</span>
                            </li>
                            <li class="float-right mr-3">
                                <Icon type="md-heart" :disabled="isLiked" v-if="item.isLiked == true"  @click="clickLike(item,0)" style="color:#19be6b;cursor:pointer" size="20"/>
                                <Icon type="md-heart-outline" :disabled="isLiked" v-else @click="clickLike(item,1)" size="20" style="cursor:pointer" class="iconHover"/>
                                <span style="font-size:17px" class="iconHover" v-if="item.likes.length > 0">{{item.likes.length}}</span>
                            </li>
                        </template>
                    </ListItemMeta>
                </ListItem>
            </List>
            <div v-if="!$isMobile()" class="category-title"></div>
            <div class="vx-item py-3" v-for="(comment,i) in item.comments" :key="i">
                <div class="vx-item-left">
                    <div>
                        <avatar :size="40" :src="comment.user.userAvatar" :username="comment.user.name" class="pr-0"></avatar>
                    </div>
                    <div class="es-item-info">
                        <div class="title">{{comment.user.name}}</div>
                        <div class="main comment">{{comment.comment}}</div>
                    </div>
                </div>
                <div class="vx-item-right">
                    <div class="es-item-detail">
                        <div class="remark" v-if="comment.created_at">{{TimeView(comment.created_at)}}</div>
                        <div class="describe"></div>
                    </div>
                    <Icon v-if="comment.userId == $store.state.user.id" size="25" color="#E40D0D" class="iconHover ml-2" type="ios-trash" @click="delComment(comment,i)"/>
                </div>
            </div> 
        </div>
        <div class="emoji-area-popup">
            <Picker v-if="emoStatus" set="emojione" @select="onInput" title="选择你的表情符号..." />
        </div>
        <div class="send-block position-absolute">
            <div class="emoji-div"><img src="/img/icon/emoji.png" alt="" class="uploadicon"  @click="toggleEmo"></div>
            <div><textarea v-model="commentText" @keydown.enter.exact.prevent @keyup.enter.exact="submitComment(item)" @keydown.enter.shift.exact="newline" name="" id="" rows="4" class="custom-textarea" placeholder="输入内容"></textarea></div>
            <div class="send-item pr-0">
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
import Avatar from 'vue-avatar'
export default {
    components:{
        Picker,
        videoPlayer,
        Viewer,
        Avatar,
    },
    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },
        currentPath(){
            return this.$route
        },
        // ...mapGetters([
        //     'getModalView','getClassView','getMemberView','getActionView','getShowQuestionModal','getShowAnswerDetail','getPostDetailsView','getAboutDetailsView','getInputModalView'
        // ]),
    },
    props:['item'],
    // created(){
    //     console.log(this.currentPath);
    //     if(this.currentPath.query.postView == true){
    //         this.firstTabComment = true;
    //     }
    //     else{
    //         this.firstTabComment = false;
    //     }
    // },
    mounted(){
        if(this.currentPath.query.postView == "true"){
            this.firstTabComment = true;
        }
        else{
            this.firstTabComment = false;
        }
    },
    watch:{
        item:{
            handler(val){
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
            firstTabComment: null
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