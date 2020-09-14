<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <Button class="btnclass" @click="addViewLiveLecturesModal"> View &amp; Modify Video Lecture  </Button>
            </div>
            <Modal
                footer-hide	
                v-model="showLiveLectureModal"
                title="Registered Lectures"
                :styles="{top:'140px',left:'-244px'}"
                class-name="index-registered-live-lecture-modal"
                >
                
                <div class="_overflow_table_div">
                    <table class="table _table __table text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Live Lecture</th>
                                <th>Lecture Title</th>
                                <th>Teacher</th>
                                <th>Start Time</th>
                                <th>Remain Time</th>
                                <th>status</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <tr class="mb-3" v-for="(liveLecture,i) in liveLectureLists" :key="i" v-if="liveLectureLists.length">
                                <td class="lecture-box">
                                    <img v-bind:src="liveLecture.cover_image" />
                                </td>
                                <td class="_table_name">{{liveLecture.lecture_title}}</td>
                                <td>{{liveLecture.teacher_name}}</td>
                                <td>{{liveLecture.lecture_time}}</td>
                                <td>2.33</td>
                                <td class="status-box">
                                    <Tooltip :content="liveLecture.status">
                                        <img v-bind:src="'/img/statusIcon/status_' + liveLecture.status + '_image.png'" />
                                    </Tooltip>
                                </td>
                                <td>
                                    <ButtonGroup size="small" shape="circle">
                                        <Button type="primary">Edit</Button>
                                        <Button type="error" @click="addDeleteLectureModal(liveLecture, i)" :loading="liveLecture.isDeleting">Delete</Button>
                                    </ButtonGroup>
                                </td>
                            </tr>
                            
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
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return{
            showLiveLectureModal:false,
            comfirmDeleteModal:false,
            liveLectureLists:[],
            deleteLecture:{},
            isDeleting:false,
            deletingIndex:-1
            
        }
    },
    
    async created(){
        this.token = window.Laravel.csrfToken;
        const lec = await this.callApi('get','api/liveLecture');
        if(lec.status == 200){
            this.liveLectureLists = lec.data;
        }
        console.log("liveLecture%%%%%%%%%%%%%%%", this.liveLectureLists);
    },

    methods:{
        addViewLiveLecturesModal(){
            this.showLiveLectureModal = true;
        },
        addDeleteLectureModal(lecture, i){
            this.deleteLecture = lecture;
            this.deletingIndex = i
            this.comfirmDeleteModal = true;
        },
        async confirmedDeleteLecture(){
            this.isDeleting = true;

            this.$set(liveLecture,'isDeleting',true);
            const res = await this.callApi('delete','api/liveLecture',this.deleteLecture);
            if(res.status == 200){
                this.liveLectureLists.splice(this.deletingIndex,1);
                this.success('Live lecture has been deleted successfully!');
            }else{
                this.swr();
            }
            this.isDeleting = false;
            this.comfirmDeleteModal = false;
        },
        abortDeleteLecture(){
            this.comfirmDeleteModal = false;
        }
    },

}
</script>