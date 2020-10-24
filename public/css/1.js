<template>
    <div class="w-100">
        <Tabs name="schoolSpace" :animated="false">
            <TabPane label="æœ€æ–°">
                <div class="p-3">
                    <div class="p-scroll">
                        <go-top></go-top>
                        <List item-layout="vertical">
                            <div class="p-scroll">
                                <ListItem v-for="(item,index) in questionnaireLists" :key="index" >
                                    <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName}â–ª${item.user.name}`">
                                        <template slot="description">
                                            <li class="arrow-down">
                                                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType($event,item,index)">
                                                    <a href="javascript:void(0)">
                                                        <Icon type="ios-arrow-down" />
                                                    </a>
                                                    <DropdownMenu slot="list">
                                                        <DropdownItem name="ç½®é¡¶">ç½®é¡¶</DropdownItem>
                                                        <DropdownItem name="åˆ é™¤">åˆ é™¤</DropdownItem>
                                                        <DropdownItem name="ç¼–è¾‘">ç¼–è¾‘</DropdownItem>
                                                    </DropdownMenu>
                                                </Dropdown>
                                            </li>                                                
                                            <div class="ct-1-post-container" v-if="item.contentType == 1">
                                                <li>é—®å·æ ‡é¢˜: {{item.addData.title}}</li>
                                                <li>é—®å·è¯´æ˜Žï¼š{{item.addData.description}}</li>
                                                <li>é—®å·å½¢å¼ï¼š <span v-if="item.addData.questionnaireFlag">åŒ¿åé—®å·</span><span v-else>å…¬å¼€é—®å·</span></li>
                                                <li>æˆªæ­¢æ—¶é—´ï¼š{{TimeView(item.addData.deadline)}}</li>
                                                <li class="moreDetails">
                                                    <span @click="showViewDetails(item)">æŸ¥çœ‹è¯¦æƒ…</span>
                                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | å¼€å§‹ä½œç­”</span>
                                                </li>
                                            </div>
                                            <div class="ct-2-post-container" v-else-if="item.contentType == 2">
                                                <li>æŠ•ç¥¨å†…å®¹ï¼š{{item.addData.content.votingDataArr[0][0].title}}</li>
                                                <li>æŠ•ç¥¨å½¢å¼ï¼š<span v-if="item.addData.anonyVote">åŒ¿åæŠ•ç¥¨</span>
                                                            <span v-else>å…¬å¼€æŠ•ç¥¨</span>
                                                </li>
                                                <li>æŠ•ç¥¨ä¸Šé™ï¼š{{item.addData.maxVote}}é¡¹</li>
                                                <li>æˆªæ­¢æ—¶é—´ï¼š{{TimeView(item.addData.deadline)}}</li>
                                                <li class="moreDetails">
                                                    <span @click="showViewDetails(item)">æŸ¥çœ‹è¯¦æƒ…</span>
                                                    <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | å¼€å§‹ä½œç­”</span>
                                                </li>
                                            </div>
                                            <div class="row ct-3-post-container w-100 m-0" v-else-if="item.contentType == 3" >
                                                <p class="col-12 pl-0 text-dark pb-2">{{item.addData.text}}</p>
                                                <div v-for="img in item.addData.imgUrl" :key="img.fileName">
                                                    <div v-if="item.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                                                        <img :src="img" alt="" @click="showSendImage">
                                                    </div>
                                                    <div v-else class="ct-3-img-container image-viewer" v-viewer>
                                                        <img :src="img" alt="" class="" @click="showSendImage">
                                                    </div>
                                                </div>
                                                <div v-for="file in item.addData.otherUrl" :key="file.fileName">
                                                    <a class="file-box" :href="file.imgUrl" :download="file.fileOriName">
                                                        <img :src="fileExtentionDetector(file.fileExtension)" alt="" @error="unknownFileImage()">
                                                        <div class="file-info-tag">
                                                            <p class="text-dark">{{file.fileOriName}}</p>
                                                            <p class="text-secondary">{{file.fileSize}}</p>
                                                            <p class="file-download-counter text-secondary">ä¸‹è½½ <span>0</span></p>
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
                                            <div class="ct-4-post-container" v-else-if="item.contentType == 4" >
                                                <div class="image-title image-viewer"  v-viewer>
                                                    <img :src="item.addData.imgUrl" alt="" @click="showSendImage">
                                                    <li class="bg-light">
                                                        <p class="p-3">{{item.addData.title}}</p></li>
                                                </div>
                                            </div>
                                            <div class="ct-5-post-container text-dark" v-else-if="item.contentType == 5">
                                                <li>å…¬å‘Šæ ‡é¢˜ï¼š{{item.addData.title}}</li>
                                                <li v-html="item.addData.content"></li>
                                                <!-- <li>{{item.addData.content}}</li> -->
                                                <div class="ct-5-post-user-time-detail text-right pr-4">
                                                    <li>{{item.user.name}}</li>
                                                    <li>{{TimeView(item.created_at)}}</li>
                                                </div>
                                                <div class="ct-5-post-see-more">
                                                    <p href="#" class="pb-2 text-success"><small>æŸ¥çœ‹è¯¦æƒ…</small> </p>
                                                </div>
                                            </div>
                                            <div class="ct-6-post-container" v-else-if="item.contentType == 6">
                                                <li>ä½¿ç”¨äººï¼š{{item.addData.userName}}</li>
                                                <li>ç±»åž‹ï¼š{{item.addData.type}}</li>
                                                <li>æ—¥æœŸï¼š{{TimeView(item.addData.deadline)}}</li>
                                                <li>æ—¶æ®µï¼š{{item.addData.timePeriod}}</li>
                                                <li>åœºæ‰€ï¼š{{item.addData.place}}</li>
                                            </div>
                                            <div class="ct-7-post-container" v-else-if="item.contentType == 7">
                                                <li>{{item.addData.title}}</li>
                                                <div v-for="img in item.addData.imgUrl" :key="img.fileName">
                                                    <div class="image-viewer" v-viewer>
                                                        <img :src="img" alt="" class="" @click="showSendImage">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ct-8-post-container" v-else-if="item.contentType == 8">
                                                <li>å±•ç¤ºæ—¶é—´ï¼š{{TimeView(item.addData.startShow)}}è‡³{{TimeView(item.addData.endShow)}}</li>
                                                <li>å‘å¸ƒåˆ°ï¼š{{item.addData.target}}</li>
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
                                                <li>æ´»åŠ¨ä¸»é¢˜ï¼š{{item.addData.title}}</li>
                                                <li>æˆªæ­¢æ—¶é—´ï¼š{{TimeView(item.created_at)}}</li>
                                                <div v-for="img in item.addData.imgUrl" :key="img.fileName">
                                                    <div class="image-viewer" v-viewer>
                                                        <img :src="img" alt="" class="" @click="showSendImage">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ct-10-post-container" v-else-if="item.contentType == 10">
                                                <li>æ ç›®ï¼š{{item.addData.type}}</li>
                                                <li>æ ‡é¢˜ï¼š{{item.title}}</li>
                                                <li v-html="item.addData.content"></li>
                                            </div>
                                            <div class="ct-10-post-container" v-else-if="item.contentType == 18">
                                                <li>æˆªæ­¢æ—¥æœŸï¼š{{TimeView(item.addData.deadline)}}</li>
                                                <li>å®¶è®¿å†…å®¹ï¼š15é¡¹</li>
                                                <li>{{item.addData.content.text}}</li>
                                                <li class="moreDetails" @click="homeVisit(item)">å·²åé¦ˆ0äºº</li>
                                            </div>
                                            <div class="ct-10-post-container" v-else-if="item.contentType == 19">
                                                <li>{{item.addData.title}}</li>
                                                <li>{{item.addData.checkInTime.clockCycle}}ï¼Œæ¯å¤©</li>
                                                <li>{{item.addData.content.text}}</li>
                                                <div v-for="img in item.addData.content.imgUrl" :key="img.fileName">
                                                    <div class="image-viewer" v-viewer>
                                                        <img :src="img" alt="" class="" @click="showSendImage">
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
                                                <li class="moreDetails" @click="checkInView(item)">æŸ¥çœ‹è¯¦æƒ…</li>
                                            </div>
                                            <li class="float-left">
                                                å·²é˜…:<span v-if="item.readCnt">{{item.readCnt}}</span><span v-else>0</span>
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
                                    <div slot="no-more">æ²¡æœ‰æ›´å¤šæ•°æ®</div>
                                </InfiniteLoading>
                            </div>
                        </List>
                        <Modal
                            footer-hide
                            :title="`${postModalTitle}è¯¦æƒ…`"
                            :value="getShowAnswerDetail"
                            :styles="{top:'75px',left:'-90px'}"
                            @on-cancel="cancel"
                        >
                            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <div class="p-modal-scroll">
                                    <postDetails :postDetails="postProps" :viewType="viewType" @answer="answerQuestion"></postDetails>
                                </div>
                        </Modal>

                        <Modal
                            footer-hide
                            :title="`${postModalTitle}è¯¦æƒ…`"
                            :value="commentModal"
                            :styles="{top:'75px',left:'-90px'}"
                            @on-cancel="commentCancel"
                        >
                            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                            <commentComponent v-if="commentItem" :item="commentItem" @commentCnt="commentCnt"></commentComponent>
                        </Modal>
                        
                        <Modal
                            footer-hide
                            :title="`è¯¦æƒ…`"
                            :value="getPostDetailsView"
                            :styles="{top:'75px',left:'-90px'}"
                            @on-cancel="cancel"
                        >
                            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <div class="p-modal-scroll">
                                    <div v-if="postDetailView.contentType == 18">
                                        <homeVisitContent :propsData="postDetailView"></homeVisitContent>
                                    </div>
                                    <div v-else-if="postDetailView.contentType == 19">
                                        <checkInResultView :propsData="postDetailView"></checkInResultView>
                                    </div>
                                </div>
                        </Modal>

                    </div>
                </div>
            </TabPane>
            <TabPane label="åº”ç”¨">
                <div class="p-3">
                    <div class="p-scroll">
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
                </div>
            </TabPane>
            <TabPane label="æˆå‘˜">
                    <div class="p-3">
                        <div class="p-scroll">
                            <div  v-for="(menu,i) in menuLists.member" :key="i">
                                <Row type="flex" justify="space-between" class="code-row-bg">
                                    <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                        <router-link :to="`${currentPath.path}?gradeName=${subMenu.label}`">
                                            <div @click="displayMember(subMenu)">
                                                <img :src="subMenu.imgurl" alt="">
                                                <span>{{subMenu.label}}</span>
                                            </div>
                                        </router-link>
                                    </Col>
                                </Row>
                            </div>
                            <div id="gradeList">
                                <div v-for="(subGrade,j) in gradeList" :key="j">
                                    <router-link :to="`${currentPath.path}?gradeName=${subGrade.id}`">
                                    <!-- <router-link :to="{ name: 'schoolSpace', params: { name:'æˆå‘˜'}, query:{modalName:subGrade.grade}}"> -->
                                        <div  class="es-item"  @click="displayMember(subGrade)">
                                            <div class="es-item-left">
                                                <img :src="subGrade.imgUrl" alt="">
                                                <div class="es-item-info">
                                                    <div class="title">{{subGrade.gradeName}}</div>
                                                    <div class="main">{{`ç­çº§${subGrade.classCnt},è€å¸ˆ${subGrade.teacherCnt},å­¦ç”Ÿ${subGrade.studentCnt}`}}</div>
                                                </div>
                                            </div>
                                            <div class="es-item-right">
                                                <Icon type="ios-arrow-forward" />
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                            <!-- <div v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <router-link :to="`${currentPath.path}?modalName=${subMenu.label}`">
                                    <div class="es-item"  @click="displayMember(subMenu)">
                                        <div class="es-item-left">
                                            <img :src="subMenu.imgurl" alt="">
                                            <div class="es-item-info">
                                                <div class="title">é«˜ä¸€å¹´çº§</div>
                                                <div class="main">ç­çº§8,è€å¸ˆ24,å­¦ç”Ÿ0</div>
                                            </div>
                                        </div>
                                        <div class="es-item-right">
                                            <Icon type="ios-arrow-forward" />
                                        </div>
                                    </div>
                                </router-link>
                            </div> -->
                            
                            <Modal
                                footer-hide
                                draggable
                                :value="getMemberView"
                                :title="memberTitle"
                                :styles="{top:'75px',left:memberLeft}"
                                :class="{classModal:getClassView}"
                                @on-cancel="cancel"
                            >
                                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <div class="es-app-detail-header">
                                    <Input prefix="ios-search" placeholder="æœç´¢"/>
                                </div>
                                
                                <div class="p-modal-scroll">
                                    <memberViewComponent></memberViewComponent>
                                </div>
                                
                            </Modal>
                        </div>
                    </div>
            </TabPane>
            <TabPane label="å…³äºŽ">
                <div class="p-scroll">
                    <!-- <div v-for="(menu,i) in menuLists.about" :key="i">
                        <div v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                            <div class="es-item">
                                {{subMenu.label}}
                            </div>
                        </div>
                    </div> -->
                    <!-- <router-link :to="`${currentPath.path}?tab=å…³äºŽ&questionType=å°é¢`"> -->
                    <div class="es-item" @click="aboutView('å°é¢')">
                        <div class="es-item-left">
                            å°é¢
                        </div>
                        <div class="es-item-right cover-avatar">
                            <img src="/img/icon/item_def.png" alt="">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <!-- </router-link> -->
                    <div class="es-item">
                        <div class="es-item-left">
                            ç®€ç§°
                        </div>
                        <div class="es-item-right">
                            Væ ¡å®žéªŒå­¦æ ¡
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            å…¨ç§°
                        </div>
                        <div class="es-item-right">
                            Væ ¡å®žéªŒå­¦æ ¡
                        </div>
                    </div>
                    <div class="es-item" @click="showInputModal('ç©ºé—´åç§°')">
                        <div class="es-item-left">
                            ç©ºé—´åç§°
                        </div>
                        <div class="es-item-right">
                            å­¦æ ¡ç©ºé—´
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            ç¼–å·
                        </div>
                        <div class="es-item-right">
                            5d0d23nr
                        </div>
                    </div>
                    <div class="es-item" @click="showInputModal('ç®€ä»‹')">
                        <div class="es-item-left">
                            ç®€ä»‹
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item" @click="aboutView('è¯¦ç»†åœ°å€')">
                        <div class="es-item-left">
                            è¯¦ç»†åœ°å€
                        </div>
                        <div class="es-item-right">
                            1ä¸ªåœ°å€
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            æ ¡åŒº
                        </div>
                        <div class="es-item-right">
                            Væ ¡å®žéªŒå­¦æ ¡ã€æœ¬éƒ¨ã€ä¸œæ ¡åŒº
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            ç±»åž‹
                        </div>
                        <div class="es-item-right">
                            å¹¼å„¿å›­å°å­¦åˆä¸­é«˜ä¸­
                        </div>
                    </div>
                    <div class="es-item" @click="showInputModal('æ‰€å±ž')">
                        <div class="es-item-left">
                            æ‰€å±ž
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item" @click="aboutView('ç®¡ç†å‘˜')">
                        <div class="es-item-left">
                            ç®¡ç†å‘˜
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            æˆ‘åœ¨æœ¬ç¾¤çš„èº«ä»½
                        </div>
                        <div class="es-item-right">
                             
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            ç¾¤è®¾ç½®
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            åŸºç¡€ä¿¡æ¯
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            æ™ºèƒ½ç¡¬ä»¶
                        </div>
                        <div class="es-item-right">
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
                        class="custom-input-modal" 
                        :value="getInputModalView"
                        :title="aboutTitle" 
                        :styles="{top:'220px',left:'175px'}"
                        draggable 
                        scrollable 
                        @on-ok="addSubject" 
                        :mask-closable="false"
                        @on-cancel="cancel">
                            <Input v-model="subjectName" :placeholder="inputModalPlace"/>
                    </Modal>
                </div>
            </TabPane>
            <TabPane label="æç¤º">
                <div class="p-3">
                    <div class="p-scroll">
                        <notConnect></notConnect>
                        <!-- <baidumap></baidumap> -->
                    </div>
                </div>
            </TabPane>
            <template slot="extra">
                <Button class="btnclass" @click="questionModal"><Icon type="md-add" /> å‘å¸ƒ </Button>
            </template>
            <Modal
                footer-hide
                :value="getShowQuestionModal"
                title="å‘å¸ƒ"
                :styles="{top:'75px',left:'-90px'}"
                @on-cancel="cancel"
                :mask-closable="false"
            >
                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                <div class="p-modal-scroll">
                    <quesetionViewComponent></quesetionViewComponent>
                </div>
            </Modal>
        </Tabs>
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
import menuLists from '../../json/chungHua/ä»ŽåŒ–ç¬¬å››ä¸­å­¦-å­¦æ ¡ç©ºé—´.json';
import GoTop from '@inotom/vue-go-top';
import baidumap from '../../components/pages/baidumap'
import notConnect from '../../components/pages/notConnect';
import applicationViewComponent from '../../components/chungHua/applicationView';
import memberViewComponent from '../../components/chungHua/memberView';
import quesetionViewComponent from '../../components/chungHua/questionModal'
import postDetails from '../../components/chungHua/postDetails'
import commentComponent from '../../components/chungHua/commentComponent'
import homeVisitContent from '../../components/chungHua/homeVisitContent'
import checkInResultView from '../../components/chungHua/checkInResultView'
import aboutViewModal from '../../components/chungHua/aboutViewModal'
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
    },
    watch:{
        currentPath(value){
            console.log('current path:',value);
            if(value.query.gradeName == undefined){

            }else{
                this.memberLeft = '-90px';
            }
            if(value.query.className == undefined){
                this.$store.commit('setClassView',false)
                this.memberLeft = '-90px';
            }
            else{
                this.$store.commit('setClassView',true)
                this.memberLeft = '-224px'
            }
            if(value.query.actionName == undefined){
                this.$store.commit('setActionView',false)
            }else{
                this.memberLeft = '-90px'
                this.$store.commit('setClassView',false);
            }
            if(value.query.addData){
                value.query.addData[0].addData = JSON.parse(value.query.addData[0].addData)
                this.questionnaireLists.unshift(value.query.addData[0])
                console.log(this.questionnaireLists)
                console.log(value.query.addData)
            }
            if(JSON.stringify(value.query) === '{}'){
                this.$store.commit('setMemberView',false);
                this.$store.commit('setModalView',false);
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
                language: 'en',
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
            inputModalPlace:''
        }
    },
    mounted(){
        this.base_url = window.Laravel.base_url;
    },
    async created(){
        // console.log('!!!!!!!!!!!!',this.$route.query)
        // if(this.$route.qeury){
        //     this.$router.push(this.$route.path)
        // }
        this.$router.push(this.$route.path)
        this.currentTime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        console.log(this.currentTime)
	    this.start();
        if(JSON.stringify(this.currentPath.query) != '{}'){
            this.$router.push(this.$route.path)
        }
        this.start()
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
            this.playerOptions.sources[0].src = "http://47.111.233.60/" + video.imgUrl;
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
            this.$store.commit('setModalView',true);           
        },
        displayMember(item){
            if(item.label === undefined){
                this.memberTitle = item.gradeName;
            }else{
                this.memberTitle = item.label;
            }
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
            this.postProps = item;
            this.postModalTitle = this.postProps.content.contentName
            this.viewType = 'answer'
        },
        comment(item){
            this.commentModal = true;
            this.commentItem = item;

        },
        commentCancel(){
            this.commentModal = false;
        },
        closeAnswerModal(){
            this.answerDetailModal = false
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
            // const [questionnaireLists,grade] = await Promise.all([
                // this.callApi('get','/api/questionnaire'),
                // this.callApi('get','/api/getGrade'),
            // ])
            // if(questionnaireLists.status == 200){
                // this.questionnaireLists = questionnaireLists.data;
                // for(let i=0;i<this.questionnaireLists.length;i++){
                //     // console.log('!!!!!!!!!!!!!!!!!!',this.questionnaireLists[i])
                //     if(this.questionnaireLists[i].likes.length){
                //         for(let j=0;j<this.questionnaireLists[i].likes.length;j++){
                //             if(this.questionnaireLists[i].likes[j].userId == this.$store.state.user.id){
                //                 this.$set(this.questionnaireLists[i],'isLiked', true)
                //             }
                //         }
                //     }
                //     this.questionnaireLists[i].addData = JSON.parse(this.questionnaireLists[i].addData)
                //     if(this.questionnaireLists[i].answerUserList){
                //         let answerUserList = this.questionnaireLists[i].answerUserList.split(",")
                //         this.$set(this.questionnaireLists[i],'readCnt',answerUserList.length)
                //         for(let j=0;j< answerUserList.length;j++){
                //             if(parseInt(answerUserList[j]) == this.$store.state.user.id){
                //                 this.questionnaireLists[i].answerUserList = parseInt(answerUserList[j])
                //                 break
                //             }else{
                //                 this.questionnaireLists[i].answerUserList = null
                //             }
                //         }
                //     }
                // }
                // console.log(this.questionnaireLists)
            // }
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
                        questionnaireLists.answerUserList = parseInt(answerUserList[j])
                        break
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
                window.axios.get('api/questionnaire?page='+this.page).then(({ data }) => {
                    vm.lastPage = data.last_page;
                        
                    $.each(data.data, function(key, value){
                        vm.calcLike(value);
                        vm.questionnaireLists.push(value); 
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
        homeVisit(item){
            console.log('homevisit')
            this.$store.commit('setPostDetailsView',true)
            this.postDetailView = item
        },
        checkInView(item){
            this.$store.commit('setPostDetailsView',true)
            this.postDetailView = item
            console.log(this.postDetailView)
        },
        async chooseType($event,item,index){
             if($event == 'åˆ é™¤'){//delete
                console.log($event)
                const res = await this.callApi('delete','/api/questionnaire',{id:item.id})
                console.log(res)
                if(res.status == 200){
                    this.success('ok')
                    this.questionnaireLists.splice(index,1)
                }
            }else if($event == 'ç¼–è¾‘'){//edit
                console.log('ç¼–è¾‘')
            }else if($event == 'ç½®é¡¶'){//to top
                console.log('ç½®é¡¶')
            }
        },
        aboutView(type){
            console.log(type)
            this.aboutTitle = type
            this.viewType = type
            this.$store.commit('setAboutDetailsView',true)

        },
        showInputModal(type){
            this.aboutTitle = type
            if(type == 'ç©ºé—´åç§°'){
                this.inputModalPlace = 'è¾“å…¥'
            }
            else if(type == 'ç®€ä»‹'){
                this.inputModalPlace = 'Væ ¡å®žéªŒå­¦æ ¡'
            }else if(type == 'æ‰€å±ž'){
                this.inputModalPlace = 'è¾“å…¥æ‰€å±žæ•™è‚²å±€/æ•™è‚²é›†å›¢ç¼–å·'
            }
            this.$store.commit('setInputModalView',true)
        },
        addSubject(){
            this.$store.commit('setInputModalView',false)
            console.log(this.subjectName)
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