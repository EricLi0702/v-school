<template>
    <div class="">
        <Row type="flex" justify="space-between" class="code-row-bg">
            <Col span="8">
                <div  @click="addViewLiveLecturesModal">
                    <img src="/img/icon/会议 拷贝.png" alt="">
                    <span>观看和修改视频讲课</span>
                </div>
            </Col>
        </Row>
                <!-- <Button class="btnclass" :loading="isLoading" @click="addViewLiveLecturesModal"> View &amp; Modify Video Lecture  </Button> -->
            
            <Modal
                footer-hide	
                v-model="showLiveLectureModal"
                title="Registered Lectures"
                :styles="{top:'140px',left:'-244px'}"
                class-name="index-registered-live-lecture-modal"
                :mask-closable="false"
                @on-cancel="cancelLiveLectureModal"
                >
                
                <div class="_overflow_table_div">
                    <table class="table _table __table text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Live Lecture</th>
                                <th>Lecture Title</th>
                                <th>Teacher</th>
                                <th>Start Time</th>
                                <th style="width:217px;">Remain Time</th>
                                <th style="width:60px;">Num</th>
                                <th style="width:67px;">status</th>
                                <th style="width:132px; padding-right:38px;">Control</th>
                            </tr>
                        </thead>
                        <tbody class="p-scroll">
                            <tr class="mb-3" v-for="(liveLecture,i) in liveLectureLists" :key="i" v-if="liveLectureLists.length">
                                <td class="lecture-box">
                                    <img v-bind:src="'/img/coverImage/' + liveLecture.subject +'_image.jpg'" />
                                </td>
                                <td class="_table_name">{{liveLecture.lecture_title}}</td>
                                <td>{{liveLecture.teacher_name}}</td>
                                <td>{{liveLecture.lecture_time}}</td>
                                <td style="width:198px;">
                                    <vue-countdown-timer
                                        @end_callback="endCallBack('Start Lecture')"
                                        :start-time="liveLecture.created_at"
                                        :end-time="liveLecture.lecture_time"
                                        :interval="1000"
                                        :start-label="'Until start:'"
                                        :end-label="'This Lecture starts after'"
                                        label-position="begin"
                                        :end-text="'Lecture Begin!!'"
                                        :day-txt="'d'"
                                        :hour-txt="'h'"
                                        :minutes-txt="'m'"
                                        :seconds-txt="'s'">
                                        <template slot="countdown" slot-scope="scope">
                                            <span>{{scope.props.days}}</span><i>{{scope.props.dayTxt}}</i>
                                            <span>{{scope.props.hours}}</span><i>{{scope.props.hourTxt}}</i>
                                            <span>{{scope.props.minutes}}</span><i>{{scope.props.minutesTxt}}</i>
                                            <span>{{scope.props.seconds}}</span><i>{{scope.props.secondsTxt}}</i>
                                        </template>
                                    </vue-countdown-timer>
                                </td>
                                <td style="width:89px;">{{liveLecture.registered_members}}</td>
                                <td class="status-box">
                                    <Tooltip :content="liveLecture.status">
                                        <img v-bind:src="'/img/statusIcon/status_' + liveLecture.status + '_image.png'" />
                                    </Tooltip>
                                </td>
                                <td v-if="$store.state.user.role.roleName == 'admin'">
                                    <Button class="mb-1 px-4" style="width:78px;" type="primary" @click="addUpdateLectureModal(liveLecture, i)">Edit</Button>
                                    <Button class="mt-1" type="error" @click="addDeleteLectureModal(liveLecture, i)" :loading="liveLecture.isDeleting">Delete</Button>
                                </td>
                                <td v-else>
                                    <Button v-if="checkIfRegisteredVal(liveLecture.registerlivelecture)" class="mb-1"  type="primary" :loading="isUnregistering[i]" @click="unregisterFromLecture(liveLecture, i)">{{ isUnregistering[i] ? 'Unregistering': 'Unregister'}}</Button>
                                    <Button v-else class="mb-1"  type="primary" :loading="isRegistering[i]" @click="registerToLecture(liveLecture, i)">{{ isRegistering[i] ? 'Registering': 'Register'}}</Button>
                                </td>
                            </tr>
                            <InfiniteLoading 
                                @infinite="infiniteHandler"
                                spinner="bubbles"
                            >
                                <div slot="no-more">Whooops! No any more lecture</div>
                            </InfiniteLoading>
                        </tbody>
                    </table>
                </div>
            </Modal>
            <Modal
                footer-hide	
                v-model="comfirmDeleteModal"
                :title="'Are you sure delete ' + this.deleteLecture.lecture_title +' lecture?'"
                :styles="{top:'355px',left:'50px'}"
                class-name="delete-live-lecture-modal"
                >
                <div class="btn-area m-5">
                    <Button type="primary" @click="abortDeleteLecture()">Abort</Button>
                    <Button type="error" :loading="isDeleting" @click="confirmedDeleteLecture()">Confirm</Button>
                </div>
            </Modal>
            <Modal
                footer-hide	    
                v-model="updateLectureModal"
                :styles="{top:'75px',left:'-90px'}"
                title="Update your Live Lecture"
                class-name="register-live-lecture-modal"
                >
                <div class="container">
                    <div class="row m-3 p-3">
                        <div class="col-4 d-flex">
                            <Icon class="mr-1" type="ios-person" /><h6>Teacher Name</h6>
                        </div>
                        <div class="col-8">
                            <Input v-model="updateLecture.teacher_name" placeholder="Enter Your Name..."  />
                        </div>
                    </div>
                    <div class="row m-3 p-3">
                        <div class="col-4 d-flex">
                            <Icon class="mr-1" type="ios-closed-captioning" /><h6>Lecture Title</h6>
                        </div>
                        <div class="col-8">
                            <Input v-model="updateLecture.lecture_title" placeholder="Enter Title of Lecture..."  />
                        </div>
                    </div>
                    <div class="row m-3 p-3">
                        <div class="col-4 d-flex">
                            <Icon class="mr-1" type="ios-brush" /><h6>Lecture Description</h6>
                        </div>
                        <div class="col-8">
                            <Input v-model="updateLecture.lecture_description" type="textarea"  placeholder="Enter Description of Lecture..." />
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
                            @on-change="handleChange"
                            type="datetime" 
                            placeholder="Select date and time"
                            ></DatePicker>
                        </div>
                    </div>
                    <div slot="footer" class="text-center justify-content-center mt-5">
                        <Button type="default" @click="updateLectureModal=false">Close</Button>
                        <Button type="primary" @click="updateLectureMethod" :disabled="isUpdating" :loading="isUpdating">{{isUpdating ? 'Updating': 'Edit Your Lecture'}}</Button>
                    </div>
                </div>
            </Modal>
    </div>
</template>

<script>
//infinit
// import {mapGetters} from vuex;
import InfiniteLoading from 'vue-infinite-loading';
import gradeList from '../../json/vSchool/grade.json';
import subjectList from '../../json/vSchool/subject.json';
export default {
    name: 'Timer',
    components:{
        InfiniteLoading,
    },
    data(){
        return{
            gradeList,
            subjectList,
            showLiveLectureModal:false,
            comfirmDeleteModal:false,
            updateLectureModal:false,
            liveLectureLists:[],
            deleteLecture:{},
            updateLecture:{},
            updatingIndex:-2,
            isDeleting:false,
            isUpdating:false,
            isLoading:false,
            deletingIndex:-1,
            //register to lecture
            isUnregistering:[],
            isRegistering:[],
            unRegisteringIndex: -3,
            registeringIndex:-4,
            
            //infinitScrollDatas
            page: 2,
            lastPage: 0,
            
        }
    },
    
    computed:{
        
    },
    

    methods:{
        async addViewLiveLecturesModal(){
            this.isLoading = true;
            const lec = await this.callApi('get','api/liveLecture');
            if(lec.status == 200){
                this.liveLectureLists = lec.data.data;
                for(let i = 0; i < this.liveLectureLists.length; i++){
                    this.isUnregistering.push(false);
                    this.isRegistering.push(false);
                }
            }
            this.isLoading = false;
            this.showLiveLectureModal = true;
        },
        addDeleteLectureModal(lecture, i){
            this.deleteLecture = lecture;
            this.deletingIndex = i
            this.comfirmDeleteModal = true;
        },
        async confirmedDeleteLecture(){
            this.isDeleting = true;
            // const res = await this.callApi('delete','api/liveLecture',this.deleteLecture);
            // if(res.status == 200){
            //     this.liveLectureLists.splice(this.deletingIndex,1);
            //     this.success( 'The ' + this.deleteLecture.lecture_title + ' lecture has been deleted successfully!');
            //     // if(this.liveLectureLists.length < 5){
            //     //     // this.page = this.page + 1;
            //     //     this.infiniteHandler();
            //     // }
            // }else{
            //     this.swr();
            // }
            this.infiniteHandler();
            this.isDeleting = false;
            this.comfirmDeleteModal = false;
        },
        abortDeleteLecture(){
            this.comfirmDeleteModal = false;
        },

        infiniteHandler($state){
            let timeOut = 0;
            
            if (this.page > 1) {
                timeOut = 1000;
            }
            setTimeout(() => {
                let vm = this;
                window.axios.get('api/liveLecture?page='+this.page).then(({ data }) => {
                    console.log("asdfasdfasdfasdfasdfasdfasdfasdf ", data);
                    vm.lastPage = data.last_page;
                    $.each(data.data, function(key, value){
                        vm.liveLectureLists.push(value); 
                        vm.isUnregistering.push(false);
                        vm.isRegistering.push(false);
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
        addUpdateLectureModal(lecture, index){
            this.updateLecture = lecture;
            this.updatingIndex = index;
            this.updateLectureModal = true;
        },
        handleChange(date){
            this.updateLecture.lecture_time = date;
        },
        async updateLectureMethod(){
            this.isUpdating = true;
            const res = await this.callApi('put', 'api/liveLecture',this.updateLecture)
            if(res.status === 200){
               this.liveLectureLists[this.updatingIndex].teacher_name = this.updateLecture.teacher_name;
               this.liveLectureLists[this.updatingIndex].lecture_title = this.updateLecture.lecture_title;
               this.liveLectureLists[this.updatingIndex].lecture_description = this.updateLecture.lecture_description;
               this.liveLectureLists[this.updatingIndex].grade = this.updateLecture.grade;
               this.liveLectureLists[this.updatingIndex].subject = this.updateLecture.subject;
               this.liveLectureLists[this.updatingIndex].lecture_time = this.updateLecture.lecture_time;
               this.success('Lecture has been updated successfully!');
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
        endCallBack: function (x) {
            console.log(x)
        },

        async registerToLecture(liveLecture, i){
            this.isRegistering[i] = true;
            const res = await this.callApi('post', 'api/user/livelecture', liveLecture);
            if(res.status === 201){
                this.success('You have registered to ' + liveLecture.lecture_title + ' lecture.');
                let registerData = {};
                registerData.live_lecture_id = liveLecture.id;
                registerData.userId = this.$store.state.user.id;
                liveLecture.registerlivelecture.push(registerData);
                liveLecture.registered_members = liveLecture.registered_members + 1;
            }
            else{
                this.swr();
            }
            this.isRegistering[i] = false;
        },
        
        async unregisterFromLecture(liveLecture, i){
            this.isUnregistering[i] = true;
            const res = await this.callApi('delete', 'api/user/livelecture', liveLecture);
            if(res.status === 200){
                console.log("@@@unRegister@@@",res);
                this.success('You have unregistered from ' + liveLecture.lecture_title +' lecture');
                liveLecture.registered_members = liveLecture.registered_members - 1;
                for(let i = 0; i < liveLecture.registerlivelecture.length; i++){
                    let registeredUserId = liveLecture.registerlivelecture[i].userId;
                    if(registeredUserId == this.$store.state.user.id){
                        liveLecture.registerlivelecture.splice(i,1);
                    }
                }
            }
            this.isUnregistering[i] = false;
        },



        cancelLiveLectureModal($state){
            this.liveLectureLists = [];
            this.page = 1;
        },
        checkIfRegisteredVal(registerLiveLecture){
            for(let j = 0; j < registerLiveLecture.length; j++){
                let registeredUserId = registerLiveLecture[j].userId;
                if(registeredUserId == this.$store.state.user.id){
                    return true;
                }
            }
            return false;
        }

        

    },

}
</script>