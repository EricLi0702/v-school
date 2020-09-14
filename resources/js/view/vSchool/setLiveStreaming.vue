<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <Button type="primary" @click="addRegisterLectureModal">Register Live Lecture</Button>
            </div>
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
                            <Icon type="ios-image-outline" /><h6>Select Cover Image</h6>
                        </div>
                        <div class="col-8">
                            <Upload 
                                ref="uploads"
                                type="drag"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :format="['jpg','jpeg','png']"
                                :max-size="2048"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="api/liveLecture/coverImage">
                                <div style="padding: 20px 0">
                                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                    <p>Click or drag files here to upload</p>
                                </div>
                            </Upload>
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
    </div>
</template>
<script>
export default {
    data(){
        return{
            token:'',
            registerLectureModal:false,
            registerLectureData:{
                teacherName:'',
                lectureTitle:'',
                coverImage:'',
                lectureTime:'',
                lectureDescription:'',
            },
            isRegistering:false,
        }
    },

    async created(){
        this.token = window.Laravel.csrfToken
    },

    methods:{
        handleChange(date){
            this.registerLectureData.lectureTime = date;
        },
        addRegisterLectureModal(){
            this.registerLectureModal = true;
        },

        async registerLecture(){
            if(this.registerLectureData.teacherName.trim()==''){
                return this.error('Your name is required');
            }
            if(this.registerLectureData.lectureTitle.trim()==''){
                return this.error('Title of lecture is required');
            }
            if(this.registerLectureData.lectureDescription.trim()==''){
                return this.error('Description of lecture is required');
            }
            if(this.registerLectureData.lectureTime.trim()==''){
                return this.error('Start time of lecture is required');
            }
            if(this.registerLectureData.coverImage.trim()==''){
                return this.error('Cover Image of lecture is required');
            }
            
            this.isRegistering = true;
            const res = await this.callApi('post', 'api/liveLecture',this.registerLectureData);
            console.log(res);
            if(res.status === 201){
                this.success('Tag has been added successfully!');
                this.registerLectureModal = false;
                this.registerLectureData.teacherName = '';
                this.registerLectureData.lectureTitle = '';
                this.registerLectureData.lectureDescription = '';
                this.registerLectureData.coverImage = '';
                this.registerLectureData.lectureTime = '';
                this.$refs.uploads.clearFiles();
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
                    if(res.data.errors.lectureTime){
                        this.info(res.data.errors.lectureTime[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
            this.isRegistering = false;
        },

        //handling upload cover image
        handleSuccess (res, file) {
            res = `/uploads/images/coverImageOfLiveLecture/${res}`
            this.registerLectureData.coverImage = res;
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
    }

}
</script>