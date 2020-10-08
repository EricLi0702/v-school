<template>
    <div class="">
        <Row type="flex" justify="space-between" class="code-row-bg">
            <Col span="6">
                <div  @click="addRegisterLectureModal">
                    <img src="/img/icon/会议 拷贝.png" alt="">
                    <span>注册现场讲课</span>
                </div>
            </Col>
        </Row>
        <!-- <Button type="primary" @click="addRegisterLectureModal">Register Live Lecture</Button> -->
            
        <Modal
            footer-hide	    
            v-model="registerLectureModal"
            :styles="{top:'75px',left:'-90px'}"
            title="Register your Live Lecture"
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
                <div slot="footer" class="text-center justify-content-center mt-5">
                    <Button type="default" @click="registerLectureModal=false">Close</Button>
                    <Button type="primary" @click="registerLecture" :disabled="isRegistering" :loading="isRegistering">{{isRegistering ? 'Registering': 'Register Your Lecture'}}</Button>
                </div>
            </div>
        </Modal>
        
    </div>
</template>
<script>
import gradeList from '../../json/vSchool/grade.json';
import subjectList from '../../json/vSchool/subject.json';
export default {
    data(){
        return{
            gradeList,
            subjectList,
            registerLectureModal:false,
            registerLectureData:{
                teacherName:'',
                lectureTitle:'',
                grade:'',
                subject:'',
                lectureTime:'',
                lectureDescription:'',
            },
            isRegistering:false,
        }
    },

    methods:{
        handleChange(date){
            this.registerLectureData.lectureTime = date;
        },
        addRegisterLectureModal(){
            this.registerLectureModal = true;
        },

        async registerLecture(){
            // if(this.registerLectureData.teacherName.trim()==''){
            //     return this.error('Your name is required');
            // }
            // if(this.registerLectureData.lectureTitle.trim()==''){
            //     return this.error('Title of lecture is required');
            // }
            // if(this.registerLectureData.lectureDescription.trim()==''){
            //     return this.error('Description of lecture is required');
            // }
            // if(this.registerLectureData.lectureTime.trim()==''){
            //     return this.error('Start time of lecture is required');
            // }
            // if(this.registerLectureData.grade.trim()==''){
            //     return this.error('Grade of lecture is required');
            // }
            // if(this.registerLectureData.subject.trim()==''){
            //     return this.error('Subject of lecture is required');
            // }
            
            
            this.isRegistering = true;
            const res = await this.callApi('post', 'api/liveLecture',this.registerLectureData);
            if(res.status === 201){
                this.success('Tag has been added successfully!');
                this.registerLectureModal = false;
                this.registerLectureData.teacherName = '';
                this.registerLectureData.lectureTitle = '';
                this.registerLectureData.lectureDescription = '';
                this.registerLectureData.grade = '';
                this.registerLectureData.subject = '';
                this.registerLectureData.lectureTime = '';
            }else{
                if(res.status === 422){
                    // if(res.data.error){
                    //     this.info(res.data.error);
                    // }
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

        
    }

}
</script>