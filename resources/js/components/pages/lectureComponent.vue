<template>
    <div class="h-100">
        <Tabs value="recordedVideo" class="h-100" @on-click="chooseVideoTab">
            <TabPane :label="label" name="recordedVideo" class="bg-light-gray" >
                <div class="vh-73 bg-light-gray">
                    <div class="search-recorded-video h-10 bg-white d-flex justify-content-center align-items-center">
                        <Input v-model="searchVideo" style="width: 80%">
                            <Button slot="append" icon="ios-search"></Button>
                        </Input> 
                        <div class="upload-recorded-video-btn">
                            <Button type="primary" icon="md-add" ghost @click="addUploadVideoModal">上传视频讲座</Button>
                            <Modal
                                v-model="UploadVideoModal"
                                title="视频上传模式"
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
                                    action="/api/video/upload">
                                    <div style="padding: 20px 0">
                                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                        <p>Click or drag files here to upload</p>
                                    </div>
                                </Upload>

                                <div slot="footer">
                                    <Button type="default" @click="UploadVideoModal=false">Close</Button>
                                    <Button type="primary" @click="uploadVideo" :disabled="isUploading" :loading="isUploading">{{isUploading ? '上载中': '上传视频讲座'}}</Button>
                                </div>
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
                    <div class="lecture-list-view h-90 bg-light-gray overflow-auto">
                        <div class="lecture-list-view-item row m-0 bg-white py-2 my-3" v-for="(videoLecture,i) in filteredVideos" :key="i" v-if="filteredVideos.length">
                            <div class="col-3 position-relative text-center d-flex align-items-center justify-content-center">
                                <img v-bind:src="'/img/coverImage/' + videoLecture.subject + '_image.jpg'" />
                                <div class="play-video-lecture-icon position-absolute" @click="playLectureVideo(videoLecture)">
                                    <Icon type="ios-play" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="recorded-lecture-title-view">
                                    <p><strong>{{videoLecture.title}}</strong></p>
                                </div>
                                <div class="recorded-lecture-description-view">
                                    <p>{{videoLecture.description}}</p>
                                </div>
                                
                            </div>
                            <div class="col-3 text-right position-relative">
                                <div class="recorded-lecture-teacher-name">
                                    <p>{{videoLecture.user.name}}</p>
                                </div>
                                <div class="recorded-lecture-date-view">
                                    <p>{{TimeView(videoLecture.created_at)}}</p>
                                </div>
                                <div class="desc-recorded-lecture">
                                    <div class="view-like-lecture position-absolute d-flex">
                                        <div class="view-lecture mr-3">
                                            <Icon size="25" type="md-eye" />
                                            <span v-if="videoLecture.view_cnt !== null">{{videoLecture.view_cnt.length}}</span>
                                            <span v-else>0</span>
                                        </div>
                                        <div class="like-lecture">
                                            <Icon v-if="checkIfLike(videoLecture.like_cnt)" @click="likeVideo(videoLecture)" size="25" type="md-heart-outline" />
                                            <Icon v-else  @click="unLikeVideo(videoLecture)" size="25" type="md-heart" color="#2D8CF0" />
                                            <span v-if="videoLecture.like_cnt !== null">{{videoLecture.like_cnt.length}}</span>
                                            <span v-else>0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InfiniteLoading 
                            class="pb-3"
                            @infinite="infiniteHandler"
                            spinner="circles"
                        >
                            <div slot="no-more">没有更多数据</div>
                        </InfiniteLoading>
                    </div>
                </div>
            </TabPane>
            <TabPane :label="labellive" name="liveLecture">
                <div class="vh-73 bg-light-gray">
                    <div class="search-live-lecture h-10 bg-white d-flex justify-content-center align-items-center">
                        <Input v-model="searchLiveLecture" style="width: 80%">
                            <Button slot="append" icon="ios-search"></Button>
                        </Input> 
                        <div class="register-live-lecture-btn">
                            <Button v-if="$store.state.user.role.roleName == 'admin'" type="primary" icon="md-add" ghost @click="addRegisterLiveLectureModal">注册现场讲座</Button>
                            <Modal
                                footer-hide	    
                                v-model="registerLiveLectureModal"
                                :styles="{top:'75px',left:'-90px'}"
                                title="注册您的现场讲座"
                                class-name="register-live-lecture-modal"
                                >
                                <div class="container">
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon class="mr-1" type="ios-person" /><h6>Teacher Name</h6>
                                        </div>
                                        <div class="col-8">
                                            <Input v-model="registerLectureData.teacherName" placeholder="Enter Your Name..."  />
                                        </div>
                                    </div>
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon class="mr-1" type="ios-closed-captioning" /><h6>Lecture Title</h6>
                                        </div>
                                        <div class="col-8">
                                            <Input v-model="registerLectureData.lectureTitle" placeholder="Enter Title of Lecture..."  />
                                        </div>
                                    </div>
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon class="mr-1" type="ios-brush" /><h6>Lecture Description</h6>
                                        </div>
                                        <div class="col-8">
                                            <Input v-model="registerLectureData.lectureDescription" type="textarea"  placeholder="Enter Description of Lecture..." />
                                        </div>
                                    </div>
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon type="ios-man" /><h6>Target Grade</h6>
                                        </div>
                                        <div class="col-8">
                                            <Select v-model="registerLectureData.grade">
                                                <Option v-for="item in gradeList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                            </Select>
                                        </div>
                                    </div>
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon type="ios-keypad" /><h6>Subject of Lecture</h6>
                                        </div>
                                        <div class="col-8">
                                            <Select v-model="registerLectureData.subject">
                                                <Option v-for="item in subjectList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                            </Select>
                                        </div>
                                    </div>
                                    <div class="row m-3 p-3">
                                        <div class="col-4 d-flex">
                                            <Icon type="ios-clock-outline" /> <h6>Lecture Start Time</h6>
                                        </div>
                                        <div class="col-8">
                                            <DatePicker 
                                            :value="registerLectureData.lectureTime" 
                                            @on-change="handleChange"
                                            type="datetime" 
                                            placeholder="Select date and time"
                                            ></DatePicker>
                                        </div>
                                    </div>
                                    <div slot="footer" class="w-100 row position-absolute mx-auto text-center justify-content-center mt-5" style="bottom:27px;">
                                        <Button type="default" class="ml-auto" @click="registerLectureModal=false">Close</Button>
                                        <Button type="primary" class="mr-auto" @click="registerLecture" :disabled="isRegistering" :loading="isRegistering">{{isRegistering ? 'Registering': 'Register Your Lecture'}}</Button>
                                    </div>
                                </div>
                            </Modal>
                        </div>
                    </div>
                    <div class="live-lecture-list-view h-90 bg-light-gray overflow-auto">
                        <div class="live-lecture-list-view-item row m-0 bg-white py-2 my-3" v-for="(liveLecture,i) in filteredLiveLectures" :key="i" v-if="filteredLiveLectures.length">
                            <div class="col-3 text-center d-flex align-items-center justify-content-center">
                                <Badge :text="checkLiveLectureStatus(liveLecture.status)" :type="checkLiveLectureStatusColor(liveLecture.status)">
                                    <img v-bind:src="'/img/coverImage/' + liveLecture.subject + '_image.jpg'" />
                                </Badge>
                            </div>
                            <div class="col-6">
                                <div class="live-lecture-title-view">
                                    <p><strong>{{liveLecture.lecture_title}}</strong></p>
                                </div>
                                <div class="live-lecture-description-view">
                                    <p>{{liveLecture.lecture_description}}</p>
                                </div>
                            </div>
                            <div class="col-3 text-right position-relative">
                                <div class="live-lecture-teacher-">
                                    <p>{{liveLecture.teacher_name}}</p>
                                </div>
                                <div class="live-lecture-start-ti">
                                    <p>{{liveLecture.lecture_time}}</p>
                                </div>
                                <div class="live-lecture-remain-time">
                                    <vue-countdown-timer
                                        @end_callback="endCallBack(liveLecture)"
                                        :start-time="liveLecture.created_at"
                                        :end-time="liveLecture.lecture_time"
                                        :interval="1000"
                                        :end-text="''"
                                        :day-txt="'日'"
                                        :hour-txt="'小时'"
                                        :minutes-txt="'分钟'"
                                        :seconds-txt="'秒'">
                                        <template slot="countdown" slot-scope="scope">
                                            <span>{{scope.props.days}}</span><i>{{scope.props.dayTxt}}</i>
                                            <span>{{scope.props.hours}}</span><i>{{scope.props.hourTxt}}</i>
                                            <span>{{scope.props.minutes}}</span><i>{{scope.props.minutesTxt}}</i>
                                            <span>{{scope.props.seconds}}</span><i>{{scope.props.secondsTxt}}</i>
                                        </template>
                                    </vue-countdown-timer>
                                </div>
                                <div class="desc-live-lecture">
                                    <div class="register-edit-view-lecture position-absolute d-flex">
                                        <div v-if="$store.state.user.id === liveLecture.userId" class="update-remove-live-lecture-icon" >
                                             <ButtonGroup shape="circle">
                                                <Button icon="md-create" class="mr-0" @click="addUpdateLectureModal(liveLecture, i)"></Button>
                                                <Button icon="ios-trash-outline" @click="addDeleteLectureModal(liveLecture, i)"></Button>
                                            </ButtonGroup>
                                            <Modal
                                                footer-hide	    
                                                v-model="updateLectureModal"
                                                :styles="{top:'75px',left:'-90px'}"
                                                title="更新您的现场讲座"
                                                class-name="register-live-lecture-modal"
                                                >
                                                <div class="container">
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                            <Icon class="mr-1" type="ios-person" /><h6>Teacher Name</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <Input v-model="updateLecture.teacher_name" placeholder="输入你的名字..."  />
                                                        </div>
                                                    </div>
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                            <Icon class="mr-1" type="ios-closed-captioning" /><h6>Lecture Title</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <Input v-model="updateLecture.lecture_title" placeholder="输入演讲标题..."  />
                                                        </div>
                                                    </div>
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                            <Icon class="mr-1" type="ios-brush" /><h6>Lecture Description</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <Input v-model="updateLecture.lecture_description" type="textarea"  placeholder="输入演讲说明..." />
                                                        </div>
                                                    </div>
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                            <Icon type="ios-man" /><h6>Target Grade</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <Select v-model="updateLecture.grade">
                                                                <Option v-for="item in gradeList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                            <Icon type="ios-keypad" /><h6>Subject of Lecture</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <Select v-model="updateLecture.subject">
                                                                <Option v-for="item in subjectList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="row m-3 p-3">
                                                        <div class="col-4 d-flex">
                                                        <Icon type="ios-clock-outline" /> <h6>Lecture Start Time</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <DatePicker 
                                                            :value="updateLecture.lecture_time" 
                                                            @on-change="handleUpdateChange"
                                                            type="datetime" 
                                                            placeholder="Select date and time"
                                                            ></DatePicker>
                                                        </div>
                                                    </div>
                                                    <div slot="footer" class="text-center justify-content-center mt-5">
                                                        <Button type="default" @click="updateLectureModal=false">Close</Button>
                                                        <Button type="primary" @click="updateLectureMethod" :disabled="isUpdating" :loading="isUpdating">{{isUpdating ? '更新中': '编辑您的演讲'}}</Button>
                                                    </div>
                                                </div>
                                            </Modal>
                                            <Modal
                                                footer-hide	
                                                v-model="comfirmDeleteModal"
                                                :title="'Are you sure delete ' + deleteLecture.lecture_title +' lecture?'"
                                                :styles="{top:'355px',left:'50px'}"
                                                class-name="delete-live-lecture-modal"
                                                >
                                                <div class="btn-area m-5">
                                                    <Button type="primary" @click="abortDeleteLecture()">中止</Button>
                                                    <Button type="error" :loading="isDeleting" @click="confirmedDeleteLecture()">确认</Button>
                                                </div>
                                            </Modal>
                                        </div>
                                        <div v-else class="register-live-lecture">
                                            <div v-if="liveLecture.status === 'finish'">
                                                <Button size="small" class="mb-1"  type="primary" disabled>登记</Button>
                                            </div>
                                            <div v-else>
                                                <Button size="small" v-if="checkIfRegisteredVal(liveLecture.registerlivelecture)" class="mb-1"  type="primary"  @click="unregisterFromLecture(liveLecture, i)">取消登记</Button>
                                                <Button size="small" v-else class="mb-1"  type="primary" @click="registerToLecture(liveLecture, i)">登记</Button>
                                            </div>
                                        </div>
                                        <div class="register-lecture-icon">
                                            <Icon size="25" type="md-people" />
                                            <span>{{liveLecture.registered_members}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InfiniteLoading 
                            class="pb-3"
                            @infinite="infiniteHandlerLiveLecture"
                            spinner="circles"
                        >
                            <div slot="no-more">没有更多数据</div>
                        </InfiniteLoading>
                    </div>
                    <Modal
                        footer-hide	
                        v-model="showEnteringLiveLectureModal"
                        :styles="{top:'140px',left:'-244px'}"
                        class-name="live-streaming-modal"
                        :mask-closable="false"
                        @on-cancel="endLecture"
                        >   
                        <div class="recording-btn-area">
                            <!-- <ButtonGroup><Icon type="ios-cloud-upload" />
                                <Button type="primary" icon="ios-play"></Button>
                                <Button type="primary" icon="ios-pause"></Button>
                                <Button type="primary" icon="md-square"></Button>
                                <Button type="primary" icon="ios-cloud-upload"></Button>
                            </ButtonGroup> -->

                            <Button class="btnclass" @click="recordStart" :disabled="isRecord">{{isRecord ? 'Recording...': 'Record'}}</Button>
                            <Button class="btnclass" @click="recordPause" :disabled="isPause">Pause</Button>
                            <Button class="btnclass" @click="recordStop" :disabled="isStop">Stop</Button>
                            <Button class="btnclass" @click="recordSave" :disabled="isSave">Save</Button>
                        </div>
                        <div id="liveLecture"></div>
                    </Modal>
                    <Modal
                        footer-hide	
                        v-model="confirmStopCurrentLectureModal"
                        title="'您确定结束当前讲座吗？'"
                        :styles="{top:'355px',left:'50px'}"
                        class-name="confrim-next-live-lecture-modal"
                        >
                        <div class="btn-area m-5">
                            <Button type="primary" @click="abortNextLecture()">没有</Button>
                            <Button type="error"  @click="confirmedNextLecture()">是</Button>
                        </div>
                    </Modal>
                </div>
            </TabPane>
        </Tabs>
    </div>
</template>

<script>
//infinitLoding
import InfiniteLoading from 'vue-infinite-loading';
import {mapGetters} from 'vuex'
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import gradeList from '../../json/vSchool/grade.json';
import subjectList from '../../json/vSchool/subject.json';
export default {
    components:{
        videoPlayer,
        InfiniteLoading,
    },
    data () {
        return {
            gradeList,
            subjectList,
            label: (h) => {
                return h('div', [
                    h('span', '讲演录'),
                    h('Badge', {
                        props: {
                            count: this.$store.state.user.new_video_cnt
                        }
                    })
                ])
            },
            labellive: (h) => {
                return h('div', [
                    h('span', '现场讲座'),
                    h('Badge', {
                        props: {
                            count: this.$store.state.user.new_live_cnt
                        }
                    })
                ])
            },
            token:'',
            UploadVideoModal:false,
            playLectureVideoModal:false,
            registerLiveLectureModal:false,
            isUploading:false,
            isLikeVideo:false,
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
            videoLists:[],
            searchVideo:'',
            selectOption:'title',

            //infinit loading
            pageOfRecordedVideo: 2,
            lastPageOfRecordedVideo: 0,
            pageOfLiveLecture: 2,
            lastPageOfLiveLecture: 0,

            registerLectureData:{
                teacherName:'',
                lectureTitle:'',
                grade:'',
                subject:'',
                lectureTime:'',
                lectureDescription:'',
            },
            searchLiveLecture:'',
            isRegistering:false,
            liveLectureLists:[],
            updateLectureModal:false,
            updateLecture:{},
            updatingIndex:-2,
            isUpdating:false,
            deletingIndex:-1,
            deleteLecture:{},
            comfirmDeleteModal:false,
            isDeleting:false,

            
            domain : "118.31.71.41",
            videoOptions:{
                roomName: "testLecture",
                // width: 1010,
                height: 610,
                parentNode: undefined,
                // configOverwrite: {
                // },
                // interfaceConfigOverwrite: {
                //     filmStripOnly: true
                // },
            },
            showEnteringLiveLectureModal:false,
            confirmStopCurrentLectureModal:false,
            EnteringLiveLecture:null,
            EnteringNextLiveLecture:null,
            LiveMeeting:{},
            recordingData: [],
            recorder:'',
            recorderStream:'',
            isRecord: false,
            isPause: true,
            isStop: true,
            isSave: true,
        }
    },

    mounted(){
        this.listenVideoUpload();
        this.listenLiveLecture();
    },
    
    watch: {
        getUserInfo:{
            handler(val){
                let totalVideoCnt = this.getUserInfo.new_video_cnt + this.getUserInfo.new_live_cnt;
                this.$store.commit('setTotalNewVideoCnt',totalVideoCnt);
            },
            deep:true
        }
    },

    computed:{
        ...mapGetters([
            'getUserInfo','getTotalNewMsgCnt'
        ]),
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
        
        filteredLiveLectures(){
            if(this.searchLiveLecture){
                return this.liveLectureLists.filter((item) => {
                    return (item.grade.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.lecture_description.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.lecture_time.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.lecture_title.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.status.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.subject.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.teacher_name.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                        ||(item.teacher_name.toUpperCase().startsWith(this.searchLiveLecture.toUpperCase()))
                });
            } else{
                return this.liveLectureLists;
            }
        },
    },

    async created(){
        this.token = window.Laravel.csrfToken;
        const vid = await this.callApi('get','/api/video');
        if(vid.status == 200){
            this.videoLists = vid.data.data;
            console.log("VideoList", this.videoLists);
        }
        const lec = await this.callApi('get','/api/liveLecture');
        if(lec.status == 200){
            this.liveLectureLists = lec.data.data;
            console.log("liveLectureList", this.liveLectureLists);
        }

        let newVideoCnt = this.getUserInfo.new_live_cnt + this.getUserInfo.new_video_cnt;
        this.$store.commit('setTotalNewVideoCnt',newVideoCnt);
    },

    methods:{
        handleSuccess (res, file) {
            res = `/uploads/video/${res}`
            this.addUploadVideo.videoFile = res;
        },
        handleError (res, file) {
            console.log('res',res);
            console.log('file',file);
            this.$Notice.warning({
                title:'文件格式不正确',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 不正确，请选择mp4。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件 ' + file.name + ' 太大，不超过512M。'
            });
        },

        addUploadVideoModal(){
            this.UploadVideoModal = true;
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
            player.currentTime(10)
            // console.log('example 01: the player is readied', player)
        },
        //playVideo
        async playLectureVideo(video){
            this.playLectureVideoModal = true;
            // this.playerOptions.sources[0].src = "http://127.0.0.1:8000" + video.videoFile;
            this.playerOptions.sources[0].src = "http://47.111.233.60" + video.videoFile;
            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/coverImage/"+ video.subject + "_image.jpg";
            const res = await this.callApi('post', '/api/video/view', video);
            video.view_cnt = res.data;
        },

        async uploadVideo(){
            this.isUploading = true;
            const res = await this.callApi('post', '/api/video',this.addUploadVideo)
            if(res.status === 201){
                console.log("videoData", res);
                this.videoLists.unshift(res.data.video);
                this.success('视频已成功添加');
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
            this.isUploading = false;
        },
        
        infiniteHandler($state){
            let timeOut = 0;
            
            if (this.pageOfRecordedVideo > 1) {
                timeOut = 1000;
            }
            setTimeout(() => {
                let vm = this;
                window.axios.get('/api/video?page='+this.pageOfRecordedVideo).then(({ data }) => {
                    vm.lastPageOfRecordedVideo = data.last_page;
                        
                    $.each(data.data, function(key, value){
                        vm.videoLists.push(value); 
                    });
                    if (vm.pageOfRecordedVideo - 1 === vm.lastPageOfRecordedVideo) {
                        $state.complete();
                    }
                    else {
                        $state.loaded();
                    }
                this.pageOfRecordedVideo = this.pageOfRecordedVideo + 1;
                });
            }, timeOut);
        },

        infiniteHandlerLiveLecture($state){
            let timeOut = 0;
            
            if (this.pageOfLiveLecture > 1) {
                timeOut = 1000;
            }
            setTimeout(() => {
                let vm = this;
                window.axios.get('/api/liveLecture?page='+this.pageOfLiveLecture).then(({ data }) => {
                    vm.lastpageOfLiveLecture = data.last_page;
                        
                    $.each(data.data, function(key, value){
                        vm.liveLectureLists.push(value); 
                    });
                    if (vm.pageOfLiveLecture - 1 === vm.lastpageOfLiveLecture) {
                        $state.complete();
                    }
                    else {
                        $state.loaded();
                    }
                this.pageOfLiveLecture = this.pageOfLiveLecture + 1;
                });
            }, timeOut);
        },

        checkIfLike(likeCntArray){
            if (likeCntArray == null){
                return true;
            }
            else{
                let userId = this.$store.state.user.id;
                for( let i = 0; i < likeCntArray.length ; i++){
                    if(likeCntArray[i] == userId){
                        return false;
                    }
                }
                return true;
            }
        },

        likeVideo(video){
            console.log("likeCntArray", video.like_cnt);
            let userId = this.$store.state.user.id;
            if(video.like_cnt == null){
                video.like_cnt = [userId];
            }
            else{
                for(let i = 0; i < video.like_cnt.length ; i++){
                    if(video.like_cnt[i] == userId){
                        return;
                    }
                }
                video.like_cnt.push(userId);
            }
            const liv = this.callApi('post', '/api/video/like', video);
        },

        unLikeVideo(video){
            let userId = this.$store.state.user.id;
            if(video.like_cnt.length == 1 && video.like_cnt[0] == userId){
                video.like_cnt = null;
            }
            else{
                video.like_cnt = video.like_cnt.filter((n) => {return n != userId});
            }
            const liv = this.callApi('post', '/api/video/unlike', video);
        },

        listenVideoUpload(){
            Echo.private('uploadVideoCh')
                .listen('NewVideoLectureUpload', (video) => {
                    this.$store.state.user.new_video_cnt += 1; 
                    this.videoLists.unshift(video.uploadVideo);
                    Notification.requestPermission( permission => {
                        let notification = new Notification('New post alert!', {
                            body: video.uploadVideo.title, // content for the alert
                            icon: "http://127.0.0.1:8000/img/logo.png" // optional image url
                        });

                        // link to page on clicking the notification
                        notification.onclick = () => {
                            window.open(window.location.href);
                        };
                    });
                    const res = this.callApi('post','/api/users/newVideoCount',{new_video_cnt:this.$store.state.user.new_video_cnt});
                });
        },

        listenLiveLecture(){
            Echo.private('livelecture')
                .listen('NewLiveLecture', (livelecture) => {
                    this.$store.state.user.new_live_cnt += 1; 
                    this.liveLectureLists.unshift(livelecture.livelecture);
                    const res = this.callApi('post','/api/users/newLiveCount',{new_live_cnt:this.$store.state.user.new_live_cnt});
                });
        },

        chooseVideoTab(name){
            if(name == "recordedVideo"){
                if(this.$store.state.user.new_video_cnt != 0){
                    this.$store.state.user.new_video_cnt = 0;
                    const res = this.callApi('post','/api/users/newVideoCount',{new_video_cnt:this.$store.state.user.new_video_cnt});
                }
            }
            else{
                if(this.$store.state.user.new_live_cnt != 0){
                    this.$store.state.user.new_live_cnt = 0;
                    const res = this.callApi('post','/api/users/newLiveCount',{new_live_cnt:this.$store.state.user.new_live_cnt});
                }
            }
        },
        
        addRegisterLiveLectureModal(){
            this.registerLiveLectureModal = true;
        },

        handleChange(date){
            this.registerLectureData.lectureTime = date;
        },

        handleUpdateChange(date){
            this.updateLecture.lecture_time = date;
        },

        async registerLecture(){
            this.isRegistering = true;
            const res = await this.callApi('post', '/api/liveLecture',this.registerLectureData);
            if(res.status === 201){
                console.log("resres", res.data.lecture);
                this.liveLectureLists.unshift(res.data.lecture);
                this.success('现场讲座已成功注册');
                this.registerLiveLectureModal = false;
                this.registerLectureData.teacherName = '';
                this.registerLectureData.lectureTitle = '';
                this.registerLectureData.lectureDescription = '';
                this.registerLectureData.grade = '';
                this.registerLectureData.subject = '';
                this.registerLectureData.lectureTime = '';
                
            }else{
                if(res.status === 422){
                    if(res.data.errors.teacherName){
                        this.info(res.data.errors.teacherName[0]);
                    }
                    if(res.data.errors.lectureTitle){
                        this.info(res.data.errors.lectureTitle[0]);
                    }
                    if(res.data.errors.lectureDescription){
                        this.info(res.data.errors.lectureDescription[0]);
                    }
                    if(res.data.errors.lectureTime){
                        this.info(res.data.errors.lectureTime[0]);
                    }
                    if(res.data.errors.grade){
                        this.info(res.data.errors.grade[0]);
                    }
                    if(res.data.errors.subject){
                        this.info(res.data.errors.subject[0]);
                    }
                }else if(res.status === 400){
                    this.info(res.data.error);
                }
                else{
                    this.swr()
                }
                
            }
            this.isRegistering = false;
        },

        

        addUpdateLectureModal(lecture, index){
            this.updateLecture = lecture;
            this.updatingIndex = index;
            this.updateLectureModal = true;
        },

        async updateLectureMethod(){
            this.isUpdating = true;
            const res = await this.callApi('put', '/api/liveLecture',this.updateLecture)
            if(res.status === 200){
               this.liveLectureLists[this.updatingIndex].teacher_name = this.updateLecture.teacher_name;
               this.liveLectureLists[this.updatingIndex].lecture_title = this.updateLecture.lecture_title;
               this.liveLectureLists[this.updatingIndex].lecture_description = this.updateLecture.lecture_description;
               this.liveLectureLists[this.updatingIndex].grade = this.updateLecture.grade;
               this.liveLectureLists[this.updatingIndex].subject = this.updateLecture.subject;
               this.liveLectureLists[this.updatingIndex].lecture_time = this.updateLecture.lecture_time;
               this.success('讲座已成功更新');
               this.updateLectureModal = false;
            }else{
                if(res.status == 422){
                    if(res.data.errors.teacher_name){
                        this.info(res.data.errors.teacher_name[0]);
                    }
                    if(res.data.errors.lecture_title){
                        this.info(res.data.errors.lecture_title[0]);
                    }
                    if(res.data.errors.lecture_description){
                        this.info(res.data.errors.lecture_description[0]);
                    }
                    if(res.data.errors.grade){
                        this.info(res.data.errors.grade[0]);
                    }
                    if(res.data.errors.subject){
                        this.info(res.data.errors.subject[0]);
                    }
                    if(res.data.errors.lecture_time){
                        this.info(res.data.errors.lecture_time[0]);
                    }
                }else if (res.status == 400){
                    this.info(res.data.error);
                }else{
                    this.swr();
                }
                
            }
            this.isUpdating = false;
        },

        addDeleteLectureModal(lecture, i){
            this.deleteLecture = lecture;
            this.deletingIndex = i
            this.comfirmDeleteModal = true;
        },

        abortDeleteLecture(){
            this.comfirmDeleteModal = false;
        },

        async confirmedDeleteLecture(){
            this.isDeleting = true;
            const res = await this.callApi('delete','/api/liveLecture',this.deleteLecture);
            if(res.status == 200){
                this.liveLectureLists.splice(this.deletingIndex,1);
                this.success( this.deleteLecture.lecture_title + '讲座已成功删除！');
                // if(this.liveLectureLists.length < 5){
                //     // this.page = this.page + 1;
                //     this.infiniteHandler();
                // }
                this.deleteLecture = {};
                this.deletingIndex = -1;
            }else{
                this.swr();
            }
            // this.infiniteHandler();
            this.isDeleting = false;
            this.comfirmDeleteModal = false;
        },

        checkIfRegisteredVal(registerLiveLecture){
            for(let j = 0; j < registerLiveLecture.length; j++){
                let registeredUserId = registerLiveLecture[j].userId;
                if(registeredUserId == this.$store.state.user.id){
                    return true;
                }
            }
            return false;
        },

        unregisterFromLecture(liveLecture, i){
            liveLecture.registered_members = liveLecture.registered_members - 1;
            for(let i = 0; i < liveLecture.registerlivelecture.length; i++){
                let registeredUserId = liveLecture.registerlivelecture[i].userId;
                if(registeredUserId == this.$store.state.user.id){
                    liveLecture.registerlivelecture.splice(i,1);
                }
            }
            const res = this.callApi('delete', '/api/user/livelecture', liveLecture);
            this.success('你已经取消登记' + liveLecture.lecture_title +'讲课');
        },

        registerToLecture(liveLecture, i){
            let registerData = {};
            registerData.live_lecture_id = liveLecture.id;
            registerData.userId = this.$store.state.user.id;
            liveLecture.registerlivelecture.push(registerData);
            liveLecture.registered_members = liveLecture.registered_members + 1;
            const res = this.callApi('post', '/api/user/livelecture', liveLecture);
            this.success('您已经登记' + liveLecture.lecture_title + '讲课.');
        },

        endCallBack(liveLectureData) {
            if(liveLectureData.status == "finish"){
                return;
            }
            //send signal for start lecture to backend
            const res = this.callApi('post', '/api/liveLecture/start', liveLectureData);
            liveLectureData.status = "running";
            //check if user registered to started lecture
            let isRegisteredThisLecture = false;
            console.log("liveLectureData",liveLectureData);
            console.log("isRegisteredThisLecture",isRegisteredThisLecture);
            for(let i = 0; i < liveLectureData.registerlivelecture.length; i++){
                if(liveLectureData.registerlivelecture[i].userId == this.$store.state.user.id){
                    isRegisteredThisLecture = true;
                    console.log(liveLectureData.registerlivelecture[i]);
                }
            }
            console.log("isRegisteredThisLecture",isRegisteredThisLecture);
            //if started lecture is created by teacher
            //or if student registered to this started lecture
            if( liveLectureData.userId == this.$store.state.user.id || isRegisteredThisLecture == true){
                if(this.EnteringLiveLecture == null){
                    this.EnteringLiveLecture = liveLectureData;
                    this.showEnteringLiveLectureModal = true;
                    this.videoOptions.roomName = liveLectureData.lecture_title;
                    this.videoOptions.parentNode = document.querySelector('#liveLecture');
                    console.log(this.videoOptions.parentNode);
                    this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
                }
                else{
                    this.EnteringNextLiveLecture = liveLectureData;
                    this.confirmStopCurrentLectureModal = true;
                }
            }
            else{
                return;
            }
            
        },

        abortNextLecture(){
            this.confirmStopCurrentLectureModal = false;
        },

        confirmedNextLecture(){
            this.LiveMeeting.dispose();
            const stop = this.callApi('post', '/api/liveLecture/end', this.EnteringLiveLecture);
            this.EnteringLiveLecture = this.EnteringNextLiveLecture;
            this.EnteringNextLiveLecture = null;
            const start = this.callApi('post', '/api/liveLecture/start', this.EnteringLiveLecture);
            this.EnteringLiveLecture.status = "running";
            this.showEnteringLiveLectureModal = true;
            this.videoOptions.roomName = liveLectureData.lecture_title;
            this.videoOptions.parentNode = document.querySelector('#liveLecture');
            console.log(this.videoOptions.parentNode);
            this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        },

        endLecture(){
            this.LiveMeeting.dispose();
            if(this.EnteringLiveLecture.userId == this.$store.state.user.id){
                const stop = this.callApi('post', '/api/liveLecture/end', this.EnteringLiveLecture);
                this.EnteringLiveLecture.status = "finish";
            }
            this.EnteringLiveLecture = null;
        },

        checkLiveLectureStatus(status){
            if(status == "before"){
                return "准备讲课";
            }
            if(status == "finish"){
                return "完成讲课";
            }
            if(status == "running"){
                return "讲课进行中";
            }
        },

        checkLiveLectureStatusColor(status){
            if(status == "before"){
                return "warning";
            }
            if(status == "finish"){
                return "normal";
            }
            if(status == "running"){
                return "primary";
            }
        },

        async recordStart(){
            let gumStream, gdmStream;
            // try {
            gumStream = await navigator.mediaDevices.getUserMedia({video: false, audio: true});
            gdmStream = await navigator.mediaDevices.getDisplayMedia({video: {displaySurface: "browser"}, audio: true});

            // } catch (e) {
            //     console.error("capture failure", e);
            //     return;
            // }

            this.recorderStream = gumStream ? this.mixer(gumStream, gdmStream) : gdmStream;
            this.recorder = new MediaRecorder(this.recorderStream, {mimeType: 'video/webm'});

            this.recorder.ondataavailable = e => {

                if (e.data && e.data.size > 0) {
                    console.log(e.data);
                    this.recordingData.push(e.data);
                }
            };

            this.recorder.onStop = () => {
                console.log("oh, stopped.");
                this.recorderStream.getTracks().forEach(track => track.stop());
                gumStream.getTracks().forEach(track => track.stop());
                gdmStream.getTracks().forEach(track => track.stop());
            };

            this.recorderStream.addEventListener('inactive', () => {
                console.log('Capture stream inactive');
                stopCapture();
            });

            this.recorder.start();
            console.log("started recording");

            this.isRecord = true;
            this.isPause = false;
            this.isStop = false;
            this.isSave = true;

        },

        recordPause(){
            if(this.recorder.state ==='paused'){
                this.recorder.resume();
            }
            else if (this.recorder.state === 'recording'){
                this.recorder.pause();
            }
            else
                console.error(`recorder in unhandled state: ${this.recorder.state}`);

            console.log(`recorder ${this.recorder.state === 'paused' ? "paused" : "recording"}`);
        },

        recordStop(){
            console.log("Stopping recording");
            this.recorder.stop();
            
            this.isRecord = false;
            this.isPause = true;
            this.isStop = true;
            this.isSave = false;
        },

        recordSave(){
            const blob = new Blob(this.recordingData, {type: 'video/webm'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `${this.getFilename()}.webm`;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url); 
                console.log(`${a.download} save option shown`);
            }, 100);
        },


        /**
         * Returns a filename based ono the Jitsi room name in the URL and timestamp
         * */
        getFilename(){
            const now = new Date();
            const timestamp = now.toISOString();
            const room = new RegExp(/(^.+)\s\|/).exec(document.title);
            if(room && room[1]!=="")
                return `${room[1]}_${timestamp}`;
            else
                return `recording_${timestamp}`;
        },
        mixer(stream1, stream2){
            const ctx = new AudioContext();
            const dest = ctx.createMediaStreamDestination();

            if(stream1.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream1).connect(dest);

            if(stream2.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream2).connect(dest);

            let tracks = dest.stream.getTracks();
            tracks = tracks.concat(stream1.getVideoTracks()).concat(stream2.getVideoTracks());

            return new MediaStream(tracks)
        },

    }


}
</script>