<template>
<div>
    <div v-if="currentPath.query.questionType == undefined" id="applicationBoard">
        <div v-if="isGettingData" class="row justify-content-center pt-3 m-0" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
        </div>
        <div v-else-if="noResult">
            <notConnect></notConnect>
        </div>
        <div v-else>
            <div v-if="commentView == false">
                <div v-for="(data,i) in allBoardList" :key="i">
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
                                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType($event,data,i)">
                                        <a href="javascript:void(0)">
                                            <Icon type="ios-arrow-down" />
                                        </a>
                                        <DropdownMenu slot="list">
                                            <DropdownItem name="置顶">置顶</DropdownItem>
                                            <DropdownItem name="删除">删除</DropdownItem>
                                            <DropdownItem name="编辑">编辑</DropdownItem>
                                        </DropdownMenu>
                                    </Dropdown>
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
                            <!-- <div class="card-click">
                                <span class="card-click_href-left text-color" @click="showViewDetails(data)">查看详情</span>|
                                <span class="card-click_href text-color" @click="showAnswerDetails(data)">查看详情</span>
                            </div> -->
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
                                <li>标题：{{data.addData.title}}</li>
                                <li>落款：{{data.addData.signName}}</li>
                                <li>日期：{{TimeView(data.created_at)}}</li>
                                <div class="file-list card-component">
                                    <div class="file-block">
                                        <div class="logo">
                                            <img src="/img/icon/icon_notice@2x.png" alt="">
                                        </div>
                                        <div class="title">
                                            <div class="file-name">
                                                {{data.addData.title}}
                                            </div>
                                        </div>
                                    </div>
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
                                <div class="post-image-container-cu col-12 p-0">
                                    <div v-if="data.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                        <img :src="data.addData.imgUrl[0]" alt="" @click="showSendImage">
                                    </div>
                                    <div v-else class="w-100 row m-0 p-0">
                                        <div v-for="img in data.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                            <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="post-file-container-cu col-12 p-0 row m-0 ">
                                    <div v-for="file in data.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                                <div v-for="video in data.addData.videoUrl" :key="video.fileName">
                                    <div class="video-box video-cover">
                                        <div class="vb-bg"></div>
                                        <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon"/></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="es-card-main" v-else-if="contentType == 8">

                        </div>
                        <div class="es-card-main" v-else-if="contentType == 18">
                            
                        </div>
                        <div class="es-card-main" v-else-if="contentType == 21">
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
                            <li>
                                <span class="and text-color" v-for="(user,i) in data.addData.userList" :key="i">@{{user}}</span>
                            </li>
                            <li v-if="data.addData.commentData">
                                <span v-if="data.addData.commentData.title">{{data.addData.commentData.title}}</span>
                            </li>
                            <div class="card-medals card-component">
                                <div class="each-row">
                                    <div class="medal-background">
                                        <div class="medal-list" v-if="data.addData.selMedalList.length < 4" >
                                            <div class="ml-item" v-for="(medal,j) in data.addData.selMedalList" :key="j" >
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
                                                <div class="medal-04" @click="showMedalDetail(data.addData.selMedalList[0])">
                                                    <div class="md-score-line">
                                                        <img src="data:image/png;base64,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" alt="">
                                                        <span class="txt">+{{data.addData.selMedalList[0].medalPoint}}分</span>
                                                    </div>
                                                    <img src="/img/icon/medal_empty.png" :style="data.addData.selMedalList[0].medalImg" alt="" class="md-logo">
                                                    <div class="md-word-bg"></div>
                                                    <div class="md-content">
                                                        <span class="md-name">{{data.addData.selMedalList[0].medalText}}</span>
                                                    </div>
                                                </div>
                                                <div class="select-icon-line"></div>
                                            </div>
                                            <div class="ml-item">
                                                <div class="medal-04" @click="showMedalDetail(data.addData.selMedalList[1])">
                                                    <div class="md-score-line">
                                                        <img src="data:image/png;base64,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" alt="">
                                                        <span class="txt">+{{data.addData.selMedalList[1].medalPoint}}分</span>
                                                    </div>
                                                    <img src="/img/icon/medal_empty.png" :style="data.addData.selMedalList[1].medalImg" alt="" class="md-logo">
                                                    <div class="md-word-bg"></div>
                                                    <div class="md-content">
                                                        <span class="md-name">{{data.addData.selMedalList[1].medalText}}</span>
                                                    </div>
                                                </div>
                                                <div class="select-icon-line"></div>
                                            </div>
                                            <div class="ml-item position-relative">
                                                <div class="medal-04 filter-brightness" @click="viewExtraMedals(data.addData.selMedalList)">
                                                    <div class="md-score-line">
                                                        <img src="data:image/png;base64,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" alt="">
                                                        <span class="txt">+{{data.addData.selMedalList[2].medalPoint}}分</span>
                                                    </div>
                                                    <img src="/img/icon/medal_empty.png" :style="data.addData.selMedalList[2].medalImg" alt="" class="md-logo">
                                                    <div class="md-word-bg"></div>
                                                    <div class="md-content">
                                                        <span class="md-name">{{data.addData.selMedalList[2].medalText}}</span>
                                                    </div>
                                                </div>
                                                <div class="select-icon-line"></div>
                                                <div class="medal-plus-text">
                                                    +{{showMedalDetailModal.addData.selMedalList.length - 2}}
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
                            <div class="card-medals card-component" v-if="data.addData.commentData">
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
                        <div class="es-card-main" v-else-if="contentType == 22">
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
                                <div class="commendation">
                                    <img src="/img/icon/medal_empty.png" alt="" :style="data.addData.imgStyle.recImg">
                                    <div class="cmdn-content">
                                        <div class="cmdn-name scrollBar">
                                            <span v-for="(name,j) in data.addData.students" :key="j">{{name}},</span>
                                        </div>
                                        <div class="cmdn-word">{{data.addData.description}}</div>
                                        <div class="cmdn-title">{{data.addData.awardTitle}}</div>
                                    </div>
                                    <div class="cmdn-tag">
                                        <div>{{data.addData.className}}</div>
                                        <div>{{TimeView(data.addData.publishDate)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="es-card-main" v-else-if="contentType == 24">
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
                            <div>通知标题：{{data.addData.title}}</div>
                            <div class="card-content">
                                <label for="">
                                    <span>{{data.addData.description.title}}</span>
                                </label>
                            </div>
                            <div class="post-image-container-cu col-12 p-0">
                                <div v-if="data.addData.description.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                    <img :src="data.addData.description.imgUrl[0]" alt="" @click="showSendImage">
                                </div>
                                <div v-else class="w-100 row m-0 p-0">
                                    <div v-for="img in data.addData.description.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                        <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                    </div>
                                </div>
                            </div>
                            <div class="post-file-container-cu col-12 p-0 row m-0">
                                <div v-for="file in data.addData.description.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                            <div v-for="video in data.addData.description.videoUrl" :key="video.fileName">
                                <div class="video-box video-cover">
                                    <div class="vb-bg"></div>
                                    <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                                </div>
                            </div>
                        </div>
                        <div class="es-card-main" v-else-if="contentType == 25">
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
                            <li>{{data.addData.title}}</li>
                            <div class="post-image-container-cu col-12 p-0">
                                <div v-if="data.addData.imgUrl.length == 1" class="row m-0 p-0 w-100 image-viewer one-image" v-viewer>
                                    <img :src="data.addData.imgUrl[0]" alt="" @click="showSendImage">
                                </div>
                                <div v-else class="w-100 row m-0 p-0">
                                    <div v-for="img in data.addData.imgUrl" :key="img.fileName"  class="ct-3-img-container image-viewer col-12 m-0 pl-0 col-md-4 p-0 mb-1 m-0" v-viewer>
                                        <img :src="img" alt="" class="w-100 pr-3" @click="showSendImage">
                                    </div>
                                </div>
                            </div>
                            <div class="post-file-container-cu col-12 p-0 row m-0">
                                <div v-for="file in data.addData.otherUrl" :key="file.fileName" class="col-12 m-0 p-0">
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
                            <div v-for="video in data.addData.videoUrl" :key="video.fileName">
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
                        <div class="card-counter">
                            <div class="counter-left">
                                <span class="text-label">已答0</span>
                            </div>
                            <div class="counter-right">
                                <div class="counter-item">
                                    <Icon type="md-heart" :disabled="isLiked" v-if="data.isLiked == true"  @click="clickLike(data,0)" style="color:#19be6b;cursor:pointer" size="20"/>
                                    <Icon type="md-heart-outline" :disabled="isLiked" v-else @click="clickLike(data,1)" size="20" style="cursor:pointer" class="iconHover"/>
                                    <span style="font-size:17px" class="iconHover" v-if="data.likes.length > 0">{{data.likes.length}}</span>
                                </div>
                                <div class="counter-item">
                                    <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20" @click="comment(data)"/>
                                    <span style="font-size:17px" class="iconHover" v-if="data.comments.length > 0">{{data.comments.length}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else>
                <commentComponent v-if="commentItem" :item="commentItem" @commentCnt="commentCnt"></commentComponent>
            </div>
        </div>
        
    </div>
    <div v-else>
        <questionDetail></questionDetail>
    </div>
</div>
</template>

<script>
import notConnect from '../pages/notConnect'
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
import questionDetail from './questionDetail'
import commentComponent from './commentComponent'
export default {
    components:{
        notConnect,
        videoPlayer,
        Viewer,
        questionDetail,
        commentComponent,
    },
    props:['selLesson','contentType'],
    data(){
        return{
            allBoardList:[],
            boardList:[],
            isLiked:false,
            commentView:false,
            commentItem:null,
            commentCount:0,
            showMedalData:{},
            showMedalDetailModal:false,
            showMedalArr : [],
            showMedalArrModal : false,
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
            playSmsVideoModal:false,
            isGettingData: true,
            noResult: false,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){
        axios.get('/api/applicationLists',{params:{
            selLesson:this.selLesson,
            contentType:this.contentType
        }}).then(res=>{
            // this.allBoardList = res.data
            this.isGettingData = false;
            for(let i=0;i<res.data.length;i++){
                res.data[i].addData = JSON.parse(res.data[i].addData)
                if(this.contentType == 1 || this.contentType == 2){
                    for(let j=0;j<res.data[i].addData.viewList.length-1;j++){
                        if(res.data[i].addData.viewList[j] == this.selLesson){
                            this.allBoardList.push(res.data[i]);
                        }
                    }
                }
                else{
                    this.allBoardList = res.data
                }
            }
            if(this.contentType == 1 || this.contentType == 2){
                if(this.allBoardList.length == 0){
                    this.noResult = true;
                }
            }
            else{
                if(this.allBoardList.length == 0){
                    this.noResult = true;
                }
            }
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
        async chooseType($event,item,index){
            if($event == '删除'){//delete
                const res = await this.callApi('delete','/api/questionnaire',{id:item.id})
                if(res.status == 200){
                    this.success('操作成功')
                    this.allBoardList.splice(index,1)
                }
            }else if($event == '编辑'){//edit
            }else if($event == '置顶'){//to top
            }
        },
        comment(data){
            this.commentView = true
            this.commentItem = data
        },
        commentCnt(value){
            // this.commentCount = value;
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
        showViewDetails(data){
        },
        showAnswerDetails(data){
            this.viewDetailModal = true;
            this.$store.commit('setShowAnswerDetail',true);
            this.$router.push({path:this.currentPath.path,query:{postView:true}})
            this.postProps = item;
            this.postModalTitle = this.postProps.content.contentName
            this.viewType = 'answer'
        },
        showMedalDetail(medalData){
            this.showMedalData = medalData;
            this.showMedalDetailModal = true;
        },
        medalComment(){

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
    }

}
</script>