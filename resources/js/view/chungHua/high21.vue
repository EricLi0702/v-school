<template>
    <div class="w-100">
        <Tabs name="schoolSpace" :animated="false" v-if="!$isMobile()" class="school-space-tab">
            <TabPane label="最新">
                <div class="p-scroll">
                    <go-top></go-top>
                    <List item-layout="vertical">
                        <div class="p-scroll px-4">
                            <ListItem v-for="(item,index) in questionnaireLists" :key="index" class="bulletin-board-item-group">
                                <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName} ▪ ${item.user.name}`">
                                    <template slot="description">
                                        <li class="arrow-down">
                                            <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType($event,item,index)">
                                                <a href="javascript:void(0)">
                                                    <Icon type="ios-arrow-down" />
                                                </a>
                                                <DropdownMenu slot="list">
                                                    <DropdownItem name="置顶">置顶</DropdownItem>
                                                    <DropdownItem name="删除">删除</DropdownItem>
                                                    <DropdownItem name="编辑">编辑</DropdownItem>
                                                </DropdownMenu>
                                            </Dropdown>
                                        </li>                                                
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                            <div class="w-100 image-title image-viewer"  v-viewer>
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
                                            <div class="ct-5-post-user-time-detail text-right pr-4">
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                                    class="video-player-box  vjs-custom-skin w-100"
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
                                                                        <img src="data:image/png;base64,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" alt="">
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
                                                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEoAAAAoCAYAAABZ7GwgAAAMK0lEQVRoQ+2ae4xUZxnGf+c6M7vL7rLbBRZZWigtymIpVKi1tqVN0VgqUUjpBato01ox0RqNsU0aqJgYa5qoif5TozEao63pRaMRo63QkMaWYKncSpfbwrLAsgO7zM7MuZvvXGbOnJnZnV3a+oc9yWYu55yZ+Z593ud93vf9JC7h8DxPuoTb3/NbJUnypvqlk1poFTBPPBHcv2+fxF3A/qFJfd5Uf3TD9y3q8ngW6O0NANq8uQKoyQDX0MIqABLgxIHpvigxWJQYnitxFZAtNPSZDS92qhd2ZDzeBjr7PbrTHoPTPOLAxUBrBLAJF1UCSQC0aJ8UsGZIpmOuxNELMsqYzEygyZHItUvkbYnOcHXi+Xt5NKkBY4bF71E9Wi545BWPM4DT7DKv3SXb70GX64O2v9eLWDYRWHUXUgGQYFBvCBBdMlyUSU/XWLvhXtLt14O7GknqRFYkXEfCMcG1wLHBLIDjgOeC54WP4XM8cMXz+LnwGnFOXC/OU+N86bOiz0w+uiZ4F3Dcw7jOa5jWNvb27USybZjmwpDrA7avy/NDM2RYPcBqAlXFovNHZIwZMtmzCh2qyuqvbyLT9iiS0uov0l9I7C/52i5CcQwsO7jOB2Eqi4/dJ77DB1mQKHw/+br6O/pw7K3sOfyCD1jHDIfUWZfp892J2FUFVAVI/FP2w6y1VWF0VOEzm5YzY96vkNUef6FWDkZOwMhJKGTBKoLrgaxBuhWmzYS2btBSATDifD4XXOMvUCw28RgxxWdSLUATrGwE8Ii5JUC91zk/8iAnjh6mtdVhdNTx2cVKtx6zagPlZ7MYSFZWZd3ja2np+jlIKtYYnHkDsofBdYLF+osPFx6FjA+CBx2Xw+yFAWC2CfkxcOqEXDKkkoucMOQaZCveMPniRgb2b0frsJNgJUOwAiifTSXR3q/4TBpxNTY8vJa2Ob9AnB/thxM7gwXXAiYOUvy8JMP866B9JngO5HIBWHWZcwmhWdK0iTTOM7Hz6zhwdgdtsuWDtWiRE4VhHKwSUKWQW79e5vYjMmO6imDSqk0r6F64DUlWGD4Ap3aVBTgKIbHYOKPqPRcgXr4YZl0BjgW5fDkMk0IfD6lI2Es6GGPNpICuCX6W0bHbGT7S5zOr2bT5+3yXZ54RsU8EViVQUch1ZBSy/Rp6c4ovbH4DRZvjM6l/ezlkkuEmNOnqVX6kcWAb5IZrh6MAZOFymD4DLAPyViwb1tOf+PvRYmPZsVbmrNK38L4qYH3W7eLgW58ETDrmWmQLTqRXFUCV2PTsepn9+xVIq+Do3Petr9I643u+Jh36Y2W4RRnGDzUPFt0J03sC15Q9DrufC9N7LM1HTJMUWLYSNB3GCmAns1kMmAo2VVmAEORJCHyVJQmTiWU+QN/Ac6CYULT9ELyrzCqfUSVt8gUclYylIXsZNjzWh6ROY+BVGO5LaFLMA7XMhCXrKq3la7+D8wPle5JiP6MHrloMlhD3pM8Kwa0ValFIxi1BTcEfD7wEK4PPPMzR/htQmooUNAuw46wqAyW0qTdkU9FMcf/X7qej58e+BTj4Qjm7JcVaa4beO6E5suMhXrlzsOsPkB+trV/iH7liJegpuFgAVy57sZqalFz4RK9jelRiUdzM1tCrseJn6RvcTlo3fFbtW+QIrRLhVwZKhN3pnMrJIzpNLSk2fudHpJo3cO4tGHg9EHA/3UswazG0dEG6DZo6QGS0Wof4T108B2NZyJ6Cvn8FLj0K2wWLYHaP/5swaglt0sFHC435r3fCLkR+zrF/xuEzm8nnDObMN5nVYovwKwO1ZYvs+6YRQ2OmqeMYaT7/3b0o+mUcfRlGBsqsmH0tzL1+ahXcvpfhwCvl0mR6J1zzETAMMJTK8qYKgDp2IWlak6VShahPqGWvcez4GpRUkTO6SVvKEuEnbdniSiV96v6TwiAaKTNFUc3w4OMnkFSZ/c+DKdJ4+F/sWQ49100NKAHS3pfKn6Xr8LGVgU4VIqCSjj1y6DFDWyqRGjSX9Uxs9H4JTHeIQ4PXkrYLGLpBNxaDn3aEWw+AivSpWdFQmlOMjDXx0NZ+32C++fuguC1lLA0Wr4FW0TKYxJEdgB2/BkPoUbRA4NZPgGdDLtKoKS4+DkbEsigZ1MycSYb6VYTJoRNX0tacxxkzGHOskk75QAl9euXfKp0pUaSlUY0MG7ceB0/iDQGUyEphlotquWvWBHVcI8fwSdjxm6CTEM9+4t7bVgWJwgcqAVItUW/IYCazWh1bEXfwQciaHOtfgJ0qQLHIsGFx09JAp0pACSEvHNFRWlJcpIkvffsosiKz90UwYqEXlSV6C9z4xfpCHgEorv/LTyB3PpH93CDj3XQzmCYYYeFc5dDrtlEqPVTVohsR/CqbcI6+oeuYRh4nZ5CJCXpdoDZ+YzeK3knfdrhwqobL9uDWTaCmxueU6Bg89/2wcI79MMGsjg5YuhQMC8wQqApPNJH4TnA+GYJRS6aClbEQdL1dHB5aNz5QydC765Efkm65m7OH4PjuauOoNcHKhxsJPHj+SSiEfioeeh9cCHPmgAFYQswnoU/1+lHj1YWljFgHYNt6moHTW+uHXi0xv+O++5g19wcYY7Dnz9WGs6MHlq9vDKh//BIG+8oFsO+jgJs/DkIWc0JHtQk6mTXd9PgNwDg7G9G2vLGBU8M76ou5KIaT9qBZb2btl3ejqC0c3QVnjlSWMO0fgI/eWwZKZLWjgnkezFsGnXPK5/72NJw+Wnn/7G7o/VDQ9TQy9dn0bvWj4tbAtz7uMU4NraLo5WvaA7/Wq2U4LTXD6nsepHPWY76P2vNXsMJKP/JU81dAezf0/wdOHYwxxoPZV8MVS2DoBOzdUQmSLMONNwRsykvg6ZfeGo5azBU99gn7UWWTWyg+wqkLL6LZhZqGs1QUJ0sYYRG8dBPrH3gJVZuNYMzBV8tmMcp+FcVumGnq9qbC8Fm6BLo6wXLAbKruANSszSYS9kYaffVsg7uHQ4N3k3bzvj7VLWHiplO0WERRLOtpMlaaZTct56rFv0WSFF9njuwpg1Wv/VsLxOjahVfD5XNADEOMNFBLxGuYwfjwwg+b0MGXeuyRo08MHMZrCAafc57zI/cwknubglbENYvjF8WlPnnYZikUU3hWGlXOcMsdd9I95yk8V2L4FBzcFZQdUS+qVvs3KnyjLqgItw/3BkwSBrMo7IBaHXINuepJdA7qli8+oBaFwkOcubAT2y0gaUUyaWP8NouIwWTjTrNTeFoaz0lz46dW0939JBKKb0CPHYTB45XDhWQzL8o03bPhynmBJgkmmRm/7VUecdWyBRN5pKj2m/K05gIXC9/k3PBOJKWIZBWxVGPcxl1V8y5qBYOOlk4hOykMJc2SpUtZsOApVLXbX6SY1Q2dhnOn4eKI7/p9lqkaNDfDZZ3Q1RUA5I+rxJ8INzUcVUVhEmvTJkOqrveZgFVxZsaHqb4suG+SHXmU88UjpJwirmJgFYWbG78VXAJKPEkOF5w2HcNKkfZ0bDeFrKa48bbP0dH+FRS5pWLwmRyGRlW+EG1bB5JlykQGM9GPKlf55XlgpFfjdUMjsF3nOPniTzmZ3YZqG6iyQVEySWkGyojZ0HChglX+HoPYuCrlaXiOjufqeJ6OI2mklBTLVqyhvXUJinoLstuOLIUj9XDWJ2kgmqqyACjWJhl3Wlw3M9X2WvX7URaeO4rjnsAx3yRffIVz2V1YkoniWUiSiSSbSIqJIVkNj6tKNWw020sOQAuqhiZpmLKGiuaDhawi2wquovi9XFl0TMXb/+N9U67YB2WB/yi7yI6Dq4r/nu2DZGOhuxaWZ5GxrUkPQCtCMDktFiN1NBUUFRONJkPFVhUURcGxA6AUTcIJQRKRFh22++7ualHl8r4nM/xSRfJwrAAoRXVwHAfVdsinbHSBomiyWfaUR+pVYIkwjG/SyKQVzo+qpHUFzVAwNQUcGWwZLRUAYodgTdBYaKxQnMRVQo7FoYY76yxDZBYXFBfdcrBSDkXTYXqrTaHoXNImjYoQFC+ijWPJbT/5nEJHi4xnyngZGdOR0F3JfxRHs8gh7zKTkhjqssdY+KaueJiyh3iUCi6S7pLNuTS1OO/Ytp/497+/kSxAoyHteH9rYoNAVYVj9Mb7m10bE9D/p+3T/wWwG+TcKs60HgAAAABJRU5ErkJggg==" alt="">
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
                            <InfiniteLoading 
                                @infinite="infiniteHandlerFirstTab"
                                spinner="circles"
                            >
                                <div slot="no-more">没有更多数据</div>
                            </InfiniteLoading>
                        </div>
                    </List>
                    <Modal
                        footer-hide
                        :title="`${postModalTitle}详情`"
                        :value="getShowAnswerDetail"
                        class-name="show-answer-detail-modal"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="cancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon"/></a>
                            <div class="p-modal-scroll">
                                <postDetails :postDetails="postProps" :viewType="viewType" @answer="answerQuestion"></postDetails>
                            </div>
                    </Modal>

                    <Modal
                        footer-hide
                        :title="`${postModalTitle}详情`"
                        :value="commentModal"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="commentCancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon"/></a>
                        <commentComponent v-if="commentItem" :item="commentItem" @commentCnt="commentCnt"></commentComponent>
                    </Modal>
                    
                    <Modal
                        footer-hide
                        :title="`详情`"
                        :value="getPostDetailsView"
                        :styles="{top:'75px',left:'-90px'}"
                        class-name="show-answer-detail-modal"
                        @on-cancel="cancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon" /></a>
                            <div class="p-modal-scroll">
                                <postDetailView :propsData="postDetailView" :viewType="showType"></postDetailView>
                            </div>
                    </Modal>

                </div>
            </TabPane>
            <TabPane label="应用">
                <div class="p-scroll px-4">
                    <div  v-for="(menu,i) in menuLists.application" :key="i">
                        <div class="mt-2 text-align-left">
                            <label>{{menu.title}}</label>
                        </div>
                        <Row type="flex" justify="space-between" class="code-row-bg">
                            <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <router-link :to="`${currentPath.path}?applicationType=${subMenu.label}`"><div @click="displayModal(subMenu)">
                                    <img :src="subMenu.imgurl" alt="">
                                    <span>{{subMenu.label}}</span>
                                </div></router-link>
                            </Col>
                        </Row>
                    </div>
                    <Modal
                        footer-hide
                        :value="getModalView"
                        :title="queryTitle"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="cancel"
                    >
                    <div class="p-modal-scroll">
                        <applicationViewComponent :currentPath="currentPath"></applicationViewComponent>
                    </div>
                    </Modal>
                </div>    
            </TabPane>
            <TabPane label="成员">
                <div class="es-alphabet">
                    <div>
                        <label :title="key"  v-for="(value, key) in grouped" :key="key"><a :href="`#${key}`">{{key}}</a></label>
                        <!-- <a :href="`#${key}`"></a> -->
                    </div>
                </div>
                <div class="p-scroll px-4">
                    <div class="mb-2"  v-for="(menu,i) in menuLists.member" :key="i">
                        <Row type="flex" justify="space-between" class="code-row-bg" v-if="i == 0">
                            <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <div>
                                    <img :src="subMenu.imgurl" alt="">
                                    <span>{{subMenu.label}}</span>
                                </div>
                                <Modal
                                    footer-hide
                                    draggable
                                    v-model="subMenu.active"
                                    :title="subMenu.label"
                                    :styles="{top:'75px',left:'-90px'}"
                                >
                                    <div class="es-app-detail-header">
                                        <Input prefix="ios-search" placeholder="搜索"/>
                                        <div class="operate-item">
                                            <Tooltip content="报表" placement="bottom">
                                                <img src="/img/icon/ico_report.png" alt="">
                                            </Tooltip>

                                            <Tooltip content="报表" placement="bottom">
                                                <img src="/img/icon/ico_app_set.png" alt="">
                                            </Tooltip>

                                        </div>
                                    </div>
                                    
                                </Modal>
                            </Col>
                        </Row>
                        <div v-else-if="i < 6" class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                            <div class="es-item-left">
                                <img :src="subMenu.imgurl" alt="">
                                <div class="es-item-info">
                                    <div class="title">高一年级</div>
                                    <div class="main">班级8,老师24,学生0</div>
                                </div>
                            </div>
                            <div class="es-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                        <div v-else class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                            <div class="es-item-left">
                                <img :src="subMenu.imgurl" alt="">
                                <div class="es-item-info">
                                    <div class="title">高一年级</div>
                                    <div class="main">班级8,老师24,学生0</div>
                                </div>
                            </div>
                            <div class="es-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </div>
                    <div v-for="(value, key) in grouped" :key="key">
                        <div class="category-title">
                            <span :id="key">{{ key }}</span>
                        </div>
                        <div v-for="(contactName,i) in value" :key="i">
                            <div v-for="(contact,j) in contacts" :key="j">
                                <div v-if="contact.name == contactName.name">
                                    <div class="es-item">
                                        <div class="es-item-left">
                                            <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                                            <!-- <Avatar src="https://i.loli.net/2017/08/21/599a521472424.jpg" v-if="contact.userAvatar" />
                                            <Avatar icon="ios-person" v-else/> -->
                                            <div class="es-item-info">
                                                <div class="title">{{contact.name}}</div>
                                                <div class="main">{{contact.phoneNumber}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </TabPane>
            <TabPane label="关于">
                <div class="p-scroll">
                    <div class="vx-item is-click" @click="aboutView('封面')">
                        <div class="vx-item-left">
                            封面
                        </div>
                        <div class="vx-item-right cover-avatar">
                            <img src="/img/icon/item_def.png" alt="">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            简称
                        </div>
                        <div class="vx-item-right">
                            V校实验学校
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            全称
                        </div>
                        <div class="vx-item-right">
                            V校实验学校
                        </div>
                    </div>
                    <div class="vx-item is-click" @click="showInputModal('空间名称')">
                        <div class="vx-item-left">
                            空间名称
                        </div>
                        <div class="vx-item-right">
                            学校空间
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            编号
                        </div>
                        <div class="vx-item-right">
                            5d0d23nr
                        </div>
                    </div>
                    <div class="vx-item is-click" @click="showInputModal('简介')">
                        <div class="vx-item-left">
                            简介
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click" @click="aboutView('详细地址')">
                        <div class="vx-item-left">
                            详细地址
                        </div>
                        <div class="vx-item-right">
                            1个地址
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            校区
                        </div>
                        <div class="vx-item-right">
                            V校实验学校、本部、东校区
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            类型
                        </div>
                        <div class="vx-item-right">
                            幼儿园小学初中高中
                        </div>
                    </div>
                    <div class="vx-item is-click" @click="showInputModal('所属')">
                        <div class="vx-item-left">
                            所属
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click" @click="aboutView('管理员')">
                        <div class="vx-item-left">
                            管理员
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            我在本群的身份
                        </div>
                        <div class="vx-item-right">
                             
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            群设置
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            基础信息
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            智能硬件
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <Modal
                        :value="getAboutDetailsView"
                        :title="aboutTitle"
                        :styles="{top:'75px',left:'-90px'}"
                        :mask-closable="false"
                        @on-cancel="cancel">
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                        <div class="p-modal-scroll">
                            <aboutViewModal :propsData="viewType"></aboutViewModal>
                        </div>
                    </Modal>
                    <Modal 
                        :value="getInputModalView"
                        :title="aboutTitle" 
                        :styles="{top:'75px',left:'-90px'}"
                        @on-ok="addSubject" 
                        :mask-closable="false"
                        @on-cancel="cancel">
                            <Input v-model="subjectName" :placeholder="inputModalPlace"/>
                    </Modal>
                </div>
            </TabPane>
            <TabPane label="提示">
                <div class="p-3">
                    <div class="p-scroll">
                        <notConnect></notConnect>
                        <!-- <baidumap></baidumap> -->
                        <!-- <attendance></attendance> -->
                    </div>
                </div>
            </TabPane>
            <template slot="extra">
                <Button class="btnclass" @click="questionModal" v-if="isWritePermitted"><Icon type="md-add" /> 发布 </Button>
            </template>
            <!-- <template slot="extra">
                <Button class="btnclass" @click="apiTest"><Icon type="md-add" /> test </Button>
            </template> -->
            <Modal
                footer-hide
                :value="getShowQuestionModal"
                title="发布"
                :styles="{top:'75px',left:'-90px'}"
                @on-cancel="cancel"
                :mask-closable="false"
                class-name="question-view-modal"
            >
                <a @click="$router.go(-1)"><Icon class="question-view-modal-back-icon" type="ios-arrow-back" /></a>
                <div class="p-modal-scroll">
                    <quesetionViewComponent></quesetionViewComponent>
                    <!-- <mobileView></mobileView> -->
                </div>
            </Modal>
        </Tabs>
        <div class="w-100" v-else>
            <div class="app-bg-image w-100">
                <img src="/img/icon/default_video.png" class="w-100 h-100" alt="">
            </div>
            <div class="app-navigate-menu row pt-2 m-0 w-100 px-3">
                <div class="col-3 p-2 text-center app-navigate-menu-item" :class="{'selected':selectedMenuItem == '最新'}" @click="selectMenu(1)">最新</div>
                <div class="col-3 p-2 text-center app-navigate-menu-item" :class="{'selected':selectedMenuItem == '应用'}" @click="selectMenu(2)">应用</div>
                <div class="col-3 p-2 text-center app-navigate-menu-item" :class="{'selected':selectedMenuItem == '成员'}" @click="selectMenu(3)">成员</div>
                <div class="col-3 p-2 text-center app-navigate-menu-item" :class="{'selected':selectedMenuItem == '提示'}" @click="selectMenu(4)">提示</div>
            </div>
            <div v-if="selectedMenuItem == '最新'" class="p-0">
                <div class="p-scroll app-p-scroll p-2">
                    <go-top></go-top>
                    <List item-layout="vertical">
                        <ListItem v-for="(item,index) in questionnaireLists" :key="index" class="bulletin-board-item-group">
                            <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName} ▪ ${item.user.name}`">
                                <template slot="description">
                                    <li class="arrow-down">
                                        <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType($event,item,index)">
                                            <a href="javascript:void(0)">
                                                <Icon type="ios-arrow-down" />
                                            </a>
                                            <DropdownMenu slot="list">
                                                <DropdownItem name="置顶">置顶</DropdownItem>
                                                <DropdownItem name="删除">删除</DropdownItem>
                                                <DropdownItem name="编辑">编辑</DropdownItem>
                                            </DropdownMenu>
                                        </Dropdown>
                                    </li>                                                
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
                                    <div class="ct-4-post-container" v-else-if="item.contentType == 4" >
                                        <small class="gray-font"><Time :time="item.created_at" :interval="60" /></small> 
                                        <div class="w-100 image-title image-viewer"  v-viewer>
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
                                        <div class="ct-5-post-user-time-detail text-right pr-4">
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                        <div v-if="item.addData.selMedalList.length < 4" >
                                            <div class="card-medals card-component" v-for="(medal,j) in item.addData.selMedalList" :key="j" @click="showMedalDetail(medal)">
                                                <div class="each-row">
                                                    <div class="medal-background">
                                                        <div class="medal-list">
                                                            <div class="ml-item" >
                                                                <div class="medal-04">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="card-medals card-component" @click="showMedalDetail(item.addData.selMedalList[0])">
                                                <div class="each-row">
                                                    <div class="medal-background">
                                                        <div class="medal-list">
                                                            <div class="ml-item" >
                                                                <div class="medal-04">
                                                                    <div class="md-score-line">
                                                                        <img src="data:image/png;base64,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" alt="">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-medals card-component" @click="showMedalDetail(item.addData.selMedalList[1])">
                                                <div class="each-row">
                                                    <div class="medal-background">
                                                        <div class="medal-list">
                                                            <div class="ml-item" >
                                                                <div class="medal-04">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-medals card-component" @click="viewExtraMedals(item.addData.selMedalList)">
                                                <div class="each-row">
                                                    <div class="medal-background">
                                                        <div class="medal-list">
                                                            <div class="ml-item position-relative" >
                                                                <div class="medal-04 filter-brightness">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <Modal
                                                footer-hide
                                                :value="showMedalArrModal"
                                                :styles="{top:'75px',left:'-90px'}"
                                                class-name="extra-medal-view-modal-app"
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
                                            class-name= "show-medal-detail-modal-app"
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

                                                <div class="medal-content-detail text-center p-3">
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                                                class="video-player-box  vjs-custom-skin w-100"
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
                        <InfiniteLoading 
                            @infinite="infiniteHandlerFirstTab"
                            spinner="circles"
                        >
                            <div slot="no-more">没有更多数据</div>
                        </InfiniteLoading>
                    </List>
                    <Modal
                        footer-hide
                        :title="`${postModalTitle}详情`"
                        :value="getShowAnswerDetail"
                        class-name="show-answer-detail-modal"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="cancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon"/></a>
                            <div class="p-modal-scroll">
                                <postDetails :postDetails="postProps" :viewType="viewType" @answer="answerQuestion"></postDetails>
                            </div>
                    </Modal>

                    <Modal
                        footer-hide
                        :title="`${postModalTitle}详情`"
                        :value="commentModal"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="commentCancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon"/></a>
                        <commentComponent v-if="commentItem" :item="commentItem" @commentCnt="commentCnt"></commentComponent>
                    </Modal>
                    
                    <Modal
                        footer-hide
                        :title="`详情`"
                        :value="getPostDetailsView"
                        :styles="{top:'75px',left:'-90px'}"
                        class-name="show-answer-detail-modal"
                        @on-cancel="cancel"
                    >
                        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" class="question-view-modal-back-icon" /></a>
                            <div class="p-modal-scroll">
                                <postDetailView :propsData="postDetailView" :viewType="showType"></postDetailView>
                            </div>
                    </Modal>

                </div>
                <Icon @click="questionModal" v-if="isWritePermitted" size="65" class="position-absolute" color="#4297F2" style="bottom: 40px; right:20px;" type="ios-add-circle" />
            </div>
            <div v-if="selectedMenuItem == '应用'" class="p-0">
                <div class="p-scroll app-p-scroll p-2">
                    <div  v-for="(menu,i) in menuLists.application" :key="i">
                        <div class="mt-2 text-align-left">
                            <label>{{menu.title}}</label>
                        </div>
                        <Row type="flex" justify="space-between" class="code-row-bg">
                            <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <router-link :to="`${currentPath.path}?applicationType=${subMenu.label}`"><div @click="displayModal(subMenu)">
                                    <img :src="subMenu.imgurl" alt="">
                                    <span>{{subMenu.label}}</span>
                                </div></router-link>
                            </Col>
                        </Row>
                    </div>
                    <Modal
                        footer-hide
                        :value="getModalView"
                        :title="queryTitle"
                        :styles="{top:'75px',left:'-90px'}"
                        @on-cancel="cancel"
                    >
                    <div class="p-modal-scroll">
                        <applicationViewComponent :currentPath="currentPath"></applicationViewComponent>
                    </div>
                    </Modal>
                </div>    
                <Icon @click="questionModal" v-if="isWritePermitted" size="65" class="position-absolute" color="#4297F2" style="bottom: 40px; right:20px;" type="ios-add-circle" />
            </div>
            <div v-if="selectedMenuItem == '成员'" class="p-0">
                <div class="p-scroll app-p-scroll">
                    <div class="">
                        <div class="mb-2"  v-for="(menu,i) in menuLists.member" :key="i">
                            <Row type="flex" justify="space-between" class="code-row-bg" v-if="i == 0">
                                <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                    <div>
                                        <img :src="subMenu.imgurl" alt="">
                                        <span>{{subMenu.label}}</span>
                                    </div>
                                    <Modal
                                        footer-hide
                                        draggable
                                        v-model="subMenu.active"
                                        :title="subMenu.label"
                                        :styles="{top:'75px',left:'-90px'}"
                                    >
                                        <div class="es-app-detail-header">
                                            <Input prefix="ios-search" placeholder="搜索"/>
                                            <div class="operate-item">
                                                <Tooltip content="报表" placement="bottom">
                                                    <img src="/img/icon/ico_report.png" alt="">
                                                </Tooltip>

                                                <Tooltip content="报表" placement="bottom">
                                                    <img src="/img/icon/ico_app_set.png" alt="">
                                                </Tooltip>

                                            </div>
                                        </div>
                                        
                                    </Modal>
                                </Col>
                            </Row>
                            <div v-else-if="i < 6" class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <div class="es-item-left">
                                    <img :src="subMenu.imgurl" alt="">
                                    <div class="es-item-info">
                                        <div class="title">高一年级</div>
                                        <div class="main">班级8,老师24,学生0</div>
                                    </div>
                                </div>
                                <div class="es-item-right">
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
                            <div v-else class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <div class="es-item-left">
                                    <img :src="subMenu.imgurl" alt="">
                                    <div class="es-item-info">
                                        <div class="title">高一年级</div>
                                        <div class="main">班级8,老师24,学生0</div>
                                    </div>
                                </div>
                                <div class="es-item-right">
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
                        </div>
                        <div v-for="(value, key) in grouped" :key="key">
                            <div class="category-title">
                                <span :id="key">{{ key }}</span>
                            </div>
                            <div v-for="(contactName,i) in value" :key="i">
                                <div v-for="(contact,j) in contacts" :key="j">
                                    <div v-if="contact.name == contactName.name">
                                        <div class="es-item">
                                            <div class="es-item-left">
                                                <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                                                <!-- <Avatar src="https://i.loli.net/2017/08/21/599a521472424.jpg" v-if="contact.userAvatar" />
                                                <Avatar icon="ios-person" v-else/> -->
                                                <div class="es-item-info">
                                                    <div class="title">{{contact.name}}</div>
                                                    <div class="main">{{contact.phoneNumber}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Icon @click="questionModal" v-if="isWritePermitted" size="65" class="position-absolute" color="#4297F2" style="bottom: 40px; right:20px;" type="ios-add-circle" />
            </div>
            <div v-if="selectedMenuItem == '提示'" class="p-0">
                <div class="p-scroll app-p-scroll p-2">
                    <notConnect></notConnect>
                </div>
                <Icon @click="questionModal" v-if="isWritePermitted" size="65" class="position-absolute" color="#4297F2" style="bottom: 40px; right:20px;" type="ios-add-circle" />
            </div>
            <Modal
                footer-hide
                :value="getShowQuestionModal"
                title="发布"
                :styles="{top:'75px',left:'-90px'}"
                @on-cancel="cancel"
                :mask-closable="false"
                class-name="question-view-modal"
            >
                <a @click="$router.go(-1)"><Icon class="question-view-modal-back-icon" type="ios-arrow-back" /></a>
                <div class="p-modal-scroll">
                    <quesetionViewComponent></quesetionViewComponent>
                    <!-- <mobileView></mobileView> -->
                </div>
            </Modal>
        </div>
    </div>
</template>
<script>
//infinitLoding
import InfiniteLoading from 'vue-infinite-loading';
//video player
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
import {mapGetters,mapActions} from 'vuex'
import lodash from 'lodash';
// import menuLists from '../../json/chungHua/从化第四中学-学校空间.json';
import menuLists from '../../json/chungHua/从化第四中学-初二1班.json';
import GoTop from '@inotom/vue-go-top';
import baidumap from '../../components/pages/baidumap'
import notConnect from '../../components/pages/notConnect';
import applicationViewComponent from '../../components/chungHua/applicationView';
import memberViewComponent from '../../components/chungHua/memberView';
import quesetionViewComponent from '../../components/chungHua/classComponent/classQuestionModal'
import postDetails from '../../components/chungHua/postDetails'
import commentComponent from '../../components/chungHua/commentComponent'
import homeVisitContent from '../../components/chungHua/homeVisitContent'
import checkInResultView from '../../components/chungHua/checkInResultView'
import aboutViewModal from '../../components/chungHua/aboutViewModal'
import homeWorkResultView from '../../components/chungHua/homework/homeWorkResult'
import testQuestion from '../../components/chungHua/homework/testQuestion'
import postDetailView from '../../components/chungHua/postDetailView'
import attendance from '../../components/attendance/index'
import contactComponent from '../../components/contactComponent'
import Avatar from 'vue-avatar'
export default {
    components: {
        GoTop,
        notConnect,
        applicationViewComponent,
        memberViewComponent,
        baidumap,
        quesetionViewComponent,
        postDetails,
        commentComponent,
        videoPlayer,
        Viewer,
        InfiniteLoading,
        homeVisitContent,
        checkInResultView,
        aboutViewModal,
        homeWorkResultView,
        testQuestion,
        postDetailView,
        attendance,
        Avatar
    },
    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },
        currentPath(){
            return this.$route
        },
        ...mapGetters([
            'getModalView','getClassView','getMemberView','getActionView','getShowQuestionModal','getShowAnswerDetail','getPostDetailsView','getAboutDetailsView','getInputModalView'
        ]),
        grouped(){
            return lodash.groupBy(this.contactsName,(item)=>{
                return item.name.charAt(0)
            })
        },
    },
    watch:{
        currentPath(value){
            // console.log('current path:',value);
            if(value.query.gradeName == undefined){

            }else{
                // this.memberLeft = '-90px';
            }
            if(value.query.className == undefined){
                this.$store.commit('setClassView',false)
                // this.memberLeft = '-90px';
            }
            else{
                this.$store.commit('setClassView',true)
                // this.memberLeft = '-224px'
            }
            if(value.query.actionName == undefined){
                this.$store.commit('setActionView',false)
            }else{
                // this.memberLeft = '-90px'
                this.$store.commit('setClassView',false);
            }
            if(value.query.addData){
                value.query.addData[0].addData = JSON.parse(value.query.addData[0].addData)
                this.questionnaireLists.unshift(value.query.addData[0])
                // console.log(this.questionnaireLists)
                // console.log(value.query.addData)
            }
            if(JSON.stringify(value.query) === '{}'){
                this.$store.commit('setMemberView',false);
                this.$store.commit('setModalView',false);
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setShowAnswerDetail',false);
                this.$store.commit('setPostDetailsView',false);
                this.$store.commit('setClassView',false)
                this.$store.commit('setActionView',false)
            }
        },
    },
    data () {
        return {
            data: [],
            menuLists,
            showModal:false,
            playSmsVideoModal:false,
            modal_loading:false,
            currentTime:null,
            isLiked:false,
            isDisabled:false,
            questionnaireLists:[],
            modalMenu:[],
            base_url:'',
            queryModal:false,
            queryTitle:'',
            memberModal:false,
            memberTitle:'',
            gradeList:[],
            memberLeft:'-90px',
            content:null,
            viewType:'',
            answerDetailModal:false,
            viewDetailModal:false,
            postProps:null,
            postModalTitle:'',
            viewProps:null,
            haveAnswerFlag:null,
            commentModal:false,
            commentItem:null,
            commentCount:0,
            postDetailView:{},
            playerOptions: {
                width:'1010',
                height: '610',
                autoplay: false,
                muted: false,
                // language: 'en',
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                sources: [{
                    type: "video/mp4",
                    src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
                }],
                poster: "https://surmon-china.github.io/vue-quill-editor/static/images/surmon-1.jpg",
            },

            //infinit loading
            page: 1,
            lastPage: 0,
            aboutTitle:'',
            subjectName:'',
            inputModalPlace:'',
            gradeInfo:null,
	        showType:'',
            contacts:[],
            contactsName:[],
            selectedMenuItem: '最新',
            showMedalData:{},
            showMedalDetailModal:false,
            showMedalArr : [],
            showMedalArrModal : false,
        }
    },
    mounted(){
        this.base_url = window.Laravel.base_url;
        this.listenNewBullet()
    },
    async created(){
        if(JSON.stringify(this.currentPath.query) != '{}'){
            this.$router.push(this.$route.path)
        }
        const con = await this.callApi('get','/api/contact');
        if(con.status == 200){
            this.contacts = con.data.user;
            this.contactsName = con.data.userName;
        }
        this.start()
    },
    methods:{
        selectMenu(index){
            switch (index) {
                case 1:
                    this.selectedMenuItem = '最新'
                    break;
                case 2:
                    this.selectedMenuItem = '应用'
                    break;
                case 3:
                    this.selectedMenuItem = '成员'
                    break;
                case 4:
                    this.selectedMenuItem = '提示'
                    break;
            }
        },
        apiTest(){
            let paramStr = 'aW1laT04Njc1OTcwMTMwNDI1MjUmbmFtZT04Njc1OTcwMTMwNDI1MjUmYXBwaWQ9ZWQ3OTQxYTNlYWIzNDllNmEzZjhlZGIyMDk1NzkwNmI='
            var md5 = require('md5');
            let nowDate = this.formatDate(new Date())
            let md5Str = md5("rt688b91bc4f44e299199fd796b678bn"+nowDate)
            let time = Date.now();
            var instance = axios.create();

            delete instance.defaults.headers.common["X-Requested-With"];
            instance.get('http://hxyh5.jimicloud.com:7086/jumpIndex', {
            params:{
                params:paramStr,
                appkey:md5Str,
                time:time
            }}).then(res=>{
                console.log('111',res)
            }).catch(err=>{
                console.log('222',err)
            })
        },
        formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
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
            // console.log('example player 1 readied', player)
            player.currentTime(10)
            // console.log('example 01: the player is readied', player)
        },

        //playVideo
        playSmsVideo(video){
            this.playSmsVideoModal = true;
            // this.playerOptions.sources[0].src = "http://127.0.0.1:8000/" + video.imgUrl;
            this.playerOptions.sources[0].src = "http://47.111.233.60" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://asystem.test" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/icon/default_video.png";
        },
        
       addModal(){
           this.showModal = true;
       },
       commentCnt(value){
           this.commentCount = value;
       },
       questionModal(){
           this.$store.commit('setShowQuestionModal',true);
           this.$router.push({path:this.currentPath.path,query:{postView:true}})
       },
       async clickLike(item,type){
           if(this.isLiked == true){
               return
           }
           this.isLiked = true
           if(type == 1){
               const res = await this.callApi('post','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                if(res.status == 201){
                    item.likes.unshift(res.data[0])
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',true)
                    else
                        item.isLiked = true;
                }
           }else if(type == 0){
               const res = await this.callApi('delete','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                    item.likes.splice(0,1)
                if(res.status == 200){
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',false)
                    else
                        item.isLiked = false;
                }
           }
           this.isLiked = false
       },
        displayModal(item){
            this.queryTitle = item.label;
            this.gradeInfo = item;
            this.$store.commit('setModalView',true);           
        },
         displayMember(item){
            if(item.label === undefined){
                this.memberTitle = item.gradeName;
            }else{
                this.memberTitle = item.label;
            }
            this.gradeInfo = item;
            this.$store.commit('setMemberView',true);
        },
        cancel(){
            this.$store.commit('setMemberView',false);
            this.$store.commit('setModalView',false);
            this.$store.commit('setShowQuestionModal',false);
            this.$store.commit('setShowAnswerDetail',false);
            this.$store.commit('setPostDetailsView',false);
            this.$store.commit('setAboutDetailsView',false);
            this.$store.commit('setInputModalView',false)
            this.answerDetailModal = false;
            this.viewDetailModal = false;
            if(JSON.stringify(this.currentPath.query) != '{}'){
                this.$router.push(this.$route.path)
            }
        },
        async showViewDetails(data){
            let item = JSON.parse(JSON.stringify(data))
            this.postModalTitle = item.content.contentName
            let bulletinId = item.id
            this.$store.commit('setShowAnswerDetail',true);
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
            await axios.get('/api/answerBulletin',{
                params:{
                    bulletinId:bulletinId,
                }
            }).then(res=>{
                
                for(let i=0;i<res.data.length;i++){
                    let answerData = JSON.parse(res.data[i].answerData);
                    let singleContentDataArr = answerData.content.singleContentDataArr
                    
                    if(singleContentDataArr){
                        for(let j=0;j<singleContentDataArr.length;j++){
                            for(let k=1;k<singleContentDataArr[j].length;k++){
                                if(singleContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.singleContentDataArr[j][k].checkCnt == undefined){
                                        this.$set(item.addData.content.singleContentDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.singleContentDataArr[j][k].checkCnt +=1
                                    }
                                    if(item.addData.content.singleContentDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.singleContentDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.singleContentDataArr[j][0].allCnt +=1
                                    }
                                }
                            }
                        }
                    }
                    if(answerData.content.multiContentDataArr){
                        for(let j=0;j<answerData.content.multiContentDataArr.length;j++){
                            for(let k=1;k<answerData.content.multiContentDataArr[j].length;k++){
                                if(answerData.content.multiContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.multiContentDataArr[j][k].checkCnt == undefined){
                                        this.$set(item.addData.content.multiContentDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.multiContentDataArr[j][k].checkCnt +=1
                                    }
                                    if(item.addData.content.multiContentDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.multiContentDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.multiContentDataArr[j][0].allCnt +=1
                                    }
                                }
                            }
                        }
                    }
                    if(answerData.content.questionAnswerDataArr){
                        for(let j=0;j<answerData.content.questionAnswerDataArr.length;j++){
                            if(answerData.content.questionAnswerDataArr[j][0].isActive){
                                if(item.addData.content.questionAnswerDataArr[j][0].isActive == undefined){
                                    this.$set(item.addData.content.questionAnswerDataArr[j][0],'isActive',answerData.content.questionAnswerDataArr[j][0].isActive)
                                }else{
                                    item.addData.content.questionAnswerDataArr[j][0].isActive += answerData.content.questionAnswerDataArr[j][0].isActive
                                }
                            }
                        }
                    }
                    if(answerData.content.statisticsDataArr){
                        for(let j=0;j<answerData.content.statisticsDataArr.length;j++){
                           let value = answerData.content.statisticsDataArr[j][0].isActive
                           if(item.addData.content.statisticsDataArr[j][0].value == undefined){
                               this.$set(item.addData.content.statisticsDataArr[j][0],'value',value)
                               this.$set(item.addData.content.statisticsDataArr[j][0],'cnt',1)
                           }else{
                               item.addData.content.statisticsDataArr[j][0].value += value;
                               item.addData.content.statisticsDataArr[j][0].cnt += 1;
                           }
                        }
                    }
                    if(answerData.content.scoringQuestoinsDataArr){
                        for(let j=0;j<answerData.content.scoringQuestoinsDataArr.length;j++){
                           let value = answerData.content.scoringQuestoinsDataArr[j][0].isActive
                           if(item.addData.content.scoringQuestoinsDataArr[j][0].value == undefined){
                               this.$set(item.addData.content.scoringQuestoinsDataArr[j][0],'value',value)
                               this.$set(item.addData.content.scoringQuestoinsDataArr[j][0],'cnt',1)
                           }else{
                               item.addData.content.scoringQuestoinsDataArr[j][0].value += value;
                               item.addData.content.scoringQuestoinsDataArr[j][0].cnt += 1;
                           }
                        }
                    }
                    
                    let votingContentDataArr = answerData.content.votingDataArr
                    if(votingContentDataArr){
                        for(let j=0;j<votingContentDataArr.length;j++){
                            for(let k=1;k<votingContentDataArr[j].length;k++){
                                if(votingContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.votingDataArr[j][k].checkCnt == undefined){
                                        
                                        this.$set(item.addData.content.votingDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.votingDataArr[j][k].checkCnt +=1
                                        
                                    }
                                    if(item.addData.content.votingDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.votingDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.votingDataArr[j][0].allCnt +=1
                                    }
                                   
                                }
                            }
                        }
                    }
                }
            })
            this.postProps = item;
            this.viewType = 'view'
        },
        showAnswerDetails(item){
            this.viewDetailModal = true;
            this.$store.commit('setShowAnswerDetail',true);
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
            this.postProps = item;
            this.postModalTitle = this.postProps.content.contentName
            this.viewType = 'answer'
        },
        comment(item){
            this.commentModal = true;
            this.commentItem = item;
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        commentCancel(){
            this.commentModal = false;
            this.$router.push({path:this.currentPath.path})
        },
        closeAnswerModal(){
            this.answerDetailModal = false
            this.$router.push({path:this.currentPath.path})
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
        async start(){
            const grade = await this.callApi('get','/api/getGrade');
            if(grade.status == 200){
                this.gradeList = grade.data
            }
        },

        answerQuestion(value){
            this.calcLike(value)
            for(let i =0;i<this.questionnaireLists.length;i++){
                if(this.questionnaireLists[i].id == value.id){
                    this.questionnaireLists[i] = value
                }
            }
        },
        calcLike(questionnaireLists){
            if(questionnaireLists.likes.length){
                for(let j=0;j<questionnaireLists.likes.length;j++){
                    if(questionnaireLists.likes[j].userId == this.$store.state.user.id){
                        this.$set(questionnaireLists,'isLiked', true)
                    }
                }
            }
            questionnaireLists.addData = JSON.parse(questionnaireLists.addData)
            if(questionnaireLists.answerUserList){
                let answerUserList = questionnaireLists.answerUserList.split(",")
                this.$set(questionnaireLists,'readCnt',answerUserList.length)
                for(let j=0;j< answerUserList.length;j++){
                    if(parseInt(answerUserList[j]) == this.$store.state.user.id){
                        if(questionnaireLists.contentType == '1' || questionnaireLists.contentType == '2'){
                            questionnaireLists.answerUserList = parseInt(answerUserList[j])
                            break
                        }else if(questionnaireLists.contentType == '20'){
                            this.$set(questionnaireLists,'answered',true)
                        }
                    }else{
                        questionnaireLists.answerUserList = null
                    }
                }
            }
        },
        infiniteHandlerFirstTab($state){
            let timeOut = 0;
            if (this.page > 1) {
                timeOut = 1000;
            }
            setTimeout(() => {
                let vm = this;
                window.axios.get('/api/questionnaire?page='+this.page).then(({ data }) => {
                    vm.lastPage = data.last_page;
                        
                    $.each(data.data, function(key, value){
                        vm.calcLike(value);
                        console.log('----')
                        console.log(value)
                        if(value.addData.viewList){
                            for(let i=0;i<value.addData.viewList.length-1;i++){
                                if(value.addData.viewList[i] == vm.currentPath.params.className){
                                    vm.questionnaireLists.push(value);
                                }
                            }
                        }else{
                            vm.questionnaireLists.push(value);
                        }
                    });
                    if (vm.page - 1 === vm.lastPage) {
                        $state.complete();
                    }
                    else {
                        $state.loaded();
                    }
                this.page = this.page + 1;
                });
            }, timeOut);
        },
        async homeworkCheck(item){
            let data = JSON.parse(JSON.stringify(item))
            let bulletinId = data.id
            await axios.get('/api/homeworkCheck',{params:{homeworkId:bulletinId}})
                        .then(res=>{
                            for(let i=0;i<res.data.length;i++){
                                let homework = res.data[i]
                                let answerData = JSON.parse(homework.answerData)
                                answerData = answerData.addDataList
                                for(let j=0;j<answerData.length;j++){
                                    if(answerData[j].selQuestion == "单选题"){
                                        for(let k=0;k<answerData[j].questionDataArr.length;k++){
                                            if(answerData[j].questionDataArr[k].answer == true){
                                                if(data.addData.addDataList[j].allCnt == undefined){
                                                    this.$set(data.addData.addDataList[j],'allCnt',1)
                                                }else{
                                                    data.addData.addDataList[j].allCnt++
                                                }
                                                if(data.addData.addDataList[j].questionDataArr[k].answerCnt == undefined){
                                                    this.$set(data.addData.addDataList[j].questionDataArr[k],'answerCnt',1)
                                                    let answerUsers = []
                                                    data.addData.addDataList[j].questionDataArr[k]['answerUsers'] = answerUsers
                                                    data.addData.addDataList[j].questionDataArr[k]['answerUsers'].push(homework.userId)
                                                    
                                                }else{
                                                    data.addData.addDataList[j].questionDataArr[k].answerCnt++
                                                    data.addData.addDataList[j].questionDataArr[k].answerUsers.push(homework.userId)
                                                } 
                                            }
                                        }
                                    }else if(answerData[j].selQuestion == "多选题"){
                                        for(let k=0;k<answerData[j].questionDataArr.length;k++){
                                            if(answerData[j].questionDataArr[k].answer == true){
                                                if(data.addData.addDataList[j].allCnt == undefined){
                                                    this.$set(data.addData.addDataList[j],'allCnt',1)
                                                }else{
                                                    data.addData.addDataList[j].allCnt++
                                                }
                                                if(data.addData.addDataList[j].questionDataArr[k].answerCnt == undefined){
                                                    this.$set(data.addData.addDataList[j].questionDataArr[k],'answerCnt',1)
                                                    // this.$set(data.addData.addDataList[j].questionDataArr[k],'answerUsers',homework.userId)
                                                    let answerUsers = []
                                                    data.addData.addDataList[j].questionDataArr[k]['answerUsers'] = answerUsers
                                                    data.addData.addDataList[j].questionDataArr[k]['answerUsers'].push(homework.userId)
                                                }else{
                                                    data.addData.addDataList[j].questionDataArr[k].answerCnt++
                                                    data.addData.addDataList[j].questionDataArr[k].answerUsers.push(homework.userId)
                                                } 
                                            }
                                        }
                                    }else if(answerData[j].selQuestion == "解答题"){

                                    }
                                    else if(answerData[j].selQuestion == "判断题"){
                                        if(answerData[j].answerA == true){
                                            if(data.addData.addDataList[j].allCnt == undefined){
                                                this.$set(data.addData.addDataList[j],'allCnt',1)
                                            }else{
                                                data.addData.addDataList[j].allCnt++
                                            }
                                            if(data.addData.addDataList[j].answerACnt == undefined){
                                                this.$set(data.addData.addDataList[j],'answerACnt',1)
                                                let answerAUsers = []
                                                data.addData.addDataList[j]['answerAUsers'] = answerAUsers
                                                data.addData.addDataList[j]['answerAUsers'].push(homework.userId)
                                            }else{
                                                data.addData.addDataList[j].answerACnt++
                                                data.addData.addDataList[j]['answerAUsers'].push(homework.userId)
                                            }
                                        }
                                        if(answerData[j].answerB == true){
                                            if(data.addData.addDataList[j].allCnt == undefined){
                                                this.$set(data.addData.addDataList[j],'allCnt',1)
                                            }else{
                                                data.addData.addDataList[j].allCnt++
                                            }
                                            if(data.addData.addDataList[j].answerBCnt == undefined){
                                                this.$set(data.addData.addDataList[j],'answerBCnt',1)
                                                let answerBUsers = []
                                                data.addData.addDataList[j]['answerBUsers'] = answerBUsers
                                                data.addData.addDataList[j]['answerBUsers'].push(homework.userId)
                                            }else{
                                                data.addData.addDataList[j].answerBCnt++
                                                data.addData.addDataList[j]['answerBUsers'].push(homework.userId)
                                            }
                                        }
                                    }else if(answerData[j].selQuestion == "综合题"){
                                        let compreData = answerData[j].questionDataArr
                                        for(let l=0;l<compreData.length;l++){
                                            if(compreData[l].selQuestion == "单选题"){
                                                for(let k=0;k<compreData[l].questionDataArr.length;k++){
                                                    if(compreData[l].questionDataArr[k].answer == true){
                                                        if(data.addData.addDataList[j].questionDataArr[l].allCnt == undefined){
                                                            this.$set(data.addData.addDataList[j].questionDataArr[l],'allCnt',1)
                                                        }else{
                                                            data.addData.addDataList[j].questionDataArr[l].allCnt++
                                                        }
                                                        if(data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerCnt == undefined){
                                                            this.$set(data.addData.addDataList[j].questionDataArr[l].questionDataArr[k],'answerCnt',1)
                                                            let answerUsers = []
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k]['answerUsers'] = answerUsers
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k]['answerUsers'].push(homework.userId)
                                                            
                                                        }else{
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerCnt++
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerUsers.push(homework.userId)
                                                        } 
                                                    }
                                                }
                                            }else if(compreData[l].selQuestion == "多选题"){
                                                for(let k=0;k<compreData[l].questionDataArr.length;k++){
                                                    if(compreData[l].questionDataArr[k].answer == true){
                                                        if(data.addData.addDataList[j].questionDataArr[l].allCnt == undefined){
                                                            this.$set(data.addData.addDataList[j].questionDataArr[l],'allCnt',1)
                                                        }else{
                                                            data.addData.addDataList[j].questionDataArr[l].allCnt++
                                                        }
                                                        if(data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerCnt == undefined){
                                                            this.$set(data.addData.addDataList[j].questionDataArr[l].questionDataArr[k],'answerCnt',1)
                                                            // this.$set(data.addData.addDataList[j].questionDataArr[k],'answerUsers',homework.userId)
                                                            let answerUsers = []
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k]['answerUsers'] = answerUsers
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k]['answerUsers'].push(homework.userId)
                                                        }else{
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerCnt++
                                                            data.addData.addDataList[j].questionDataArr[l].questionDataArr[k].answerUsers.push(homework.userId)
                                                        } 
                                                    }
                                                }
                                            }else if(answerData[j].selQuestion == "解答题"){

                                            }
                                            else if(compreData[l].selQuestion == "判断题"){
                                                if(compreData[l].answerA == true){
                                                    if(data.addData.addDataList[j].questionDataArr[l].allCnt == undefined){
                                                        this.$set(data.addData.addDataList[j].questionDataArr[l],'allCnt',1)
                                                    }else{
                                                        data.addData.addDataList[j].questionDataArr[l].allCnt++
                                                    }
                                                    if(data.addData.addDataList[j].questionDataArr[l].answerACnt == undefined){
                                                        this.$set(data.addData.addDataList[j].questionDataArr[l],'answerACnt',1)
                                                        let answerAUsers = []
                                                        data.addData.addDataList[j].questionDataArr[l]['answerAUsers'] = answerAUsers
                                                        data.addData.addDataList[j].questionDataArr[l]['answerAUsers'].push(homework.userId)
                                                    }else{
                                                        data.addData.addDataList[j].questionDataArr[l].answerACnt++
                                                        data.addData.addDataList[j].questionDataArr[l]['answerAUsers'].push(homework.userId)
                                                    }
                                                }
                                                if(compreData[l].answerB == true){
                                                    if(data.addData.addDataList[j].questionDataArr[l].allCnt == undefined){
                                                        this.$set(data.addData.addDataList[j].questionDataArr[l],'allCnt',1)
                                                    }else{
                                                        data.addData.addDataList[j].questionDataArr[l].allCnt++
                                                    }
                                                    if(data.addData.addDataList[j].questionDataArr[l].answerBCnt == undefined){
                                                        this.$set(data.addData.addDataList[j].questionDataArr[l],'answerBCnt',1)
                                                        let answerBUsers = []
                                                        data.addData.addDataList[j].questionDataArr[l]['answerBUsers'] = answerBUsers
                                                        data.addData.addDataList[j].questionDataArr[l]['answerBUsers'].push(homework.userId)
                                                    }else{
                                                        data.addData.addDataList[j].questionDataArr[l].answerBCnt++
                                                        data.addData.addDataList[j].questionDataArr[l]['answerBUsers'].push(homework.userId)
                                                    }
                                                }
                                            }
                                        }                                    
                                    }
                                }
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
            this.postDetailView = data
            this.showType="answer"
            this.$store.commit('setPostDetailsView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        async homeworkView(item){
            let data = JSON.parse(JSON.stringify(item))
            let bulletinId = data.id
            let userId = this.$store.state.user.id
            await axios.get('/api/homeworkResult',{params:{homeworkId:bulletinId,userId:userId}})
                        .then(res=>{
                            console.log(res)
                            let addData = JSON.parse(res.data[0].answerData)
                            data.addData = addData
                        })
                        .catch(err=>{
                            console.log(err.response)
                        })
            this.postDetailView = data
            this.showType = "view"
            this.$store.commit('setPostDetailsView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        homeworkSolve(item){
            this.postDetailView = item
            this.showType="answer"
            this.$store.commit('setPostDetailsView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        postView(item){
            this.postDetailView = item
            this.$store.commit('setPostDetailsView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        async chooseType($event,item,index){
             if($event == '删除'){//delete
                // console.log($event)
                const res = await this.callApi('delete','/api/questionnaire',{id:item.id})
                // console.log(res)
                if(res.status == 200){
                    this.success('删除成功')
                    this.questionnaireLists.splice(index,1)
                }
            }else if($event == '编辑'){//edit
            }else if($event == '置顶'){//to top
            }
        },
        aboutView(type){
            this.aboutTitle = type
            this.viewType = type
            this.$store.commit('setAboutDetailsView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        showInputModal(type){
            this.aboutTitle = type
            if(type == '空间名称'){
                this.inputModalPlace = '输入'
            }
            else if(type == '简介'){
                this.inputModalPlace = 'V校实验学校'
            }else if(type == '所属'){
                this.inputModalPlace = '输入所属教育局/教育集团编号'
            }
            this.$store.commit('setInputModalView',true)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },
        addSubject(){
            this.$store.commit('setInputModalView',false)
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
        },

        listenNewBullet(){
            Echo.private('bulletin')
                .listen('NewBulletIn', (bulletin) => {
                    this.calcLike(bulletin.bulletIn[0]);
                    this.questionnaireLists.push(bulletin.bulletIn[0]); 
                });
        },
        homeVisitAnswer(item){
            // this.postDetailView = item
            // this.$store.commit('setPostDetailsView',true)
            // this.$router.push({path:this.currentPath.path,query:{postView:true}}) 
            console.log(item)
        },
        medalComment(){

        },
        showMedalDetail(medalData){
            this.showMedalData = medalData;
            this.showMedalDetailModal = true;
        },

        

        viewExtraMedals(medalArr){
            console.log("medalArr", medalArr);
            this.showMedalArr = medalArr;
            this.showMedalArrModal = true;
        },

        initeExtraMelalArr(val){
            if(val == false){
                this.showMedalArr = [];
                this.showMedalArrModal = false;
            }
        },

        initeMedalData(val){
            if(val == false){
                this.showMedalData = {};
                this.showMedalDetailModal = false;
            }
        }
    }
}
</script>

<style>
.ivu-menu-horizontal{
    height: 50px;
    line-height:50px;
}
.ivu-modal-header{
    background-color:#2d8cf0;
}
.btnclass{
    background: #2d8cf0!important;
    color: #fff!important;
    border-color: #2d8cf0!important;
}

.ivu-input-wrapper input {
    background:#f3f3f3;
}

.operate-item{
    margin-left: 20px;
    align-items: center;
    display: -webkit-flex;
}
.operate-item img{
    width: 20px;
    height: 20px;
    margin-left: 15px;
}
.ivu-list-item-meta-description{
    position:relative!important
}
.arrow-down{
    position: absolute;
    top: -35px;
    right: 10px;
    font-size:20px;
    margin-right:8px
}
</style>