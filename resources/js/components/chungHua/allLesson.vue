<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <div v-if="currentPath.query.selLesson == undefined">
                <div style="all:unset" v-for="lesson in lessonList" :key="lesson.lessonName">
                    <div class="vx-item is-click" v-if="isReadPermitted" @click="selLesson(lesson)">
                        <div class="vx-item-left">
                            {{lesson.lessonName}}
                        </div>
                        <div class="vx-item-right">
                            <Icon type="ios-arrow-forward"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.selLesson">
                <applicationBoard :selLesson="currentPath.query.selLesson" :contentType="contentType"></applicationBoard>
            </div>
        </div>
        <div v-else-if="currentPath.query.questionType" style="height:76vh;overflow:auto">
            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
            <questionDetail></questionDetail>
        </div>
    </div>
</template>

<script>
import applicationAdd from './applicationAdd'
import applicationBoard from './applicationBoard'
import questionDetail from './questionDetail'
import notConnect from '../pages/notConnect'
import {mapGetters,mapActions} from 'vuex'
export default {
    components:{
        applicationAdd,
        applicationBoard,
        questionDetail,
        notConnect
    },
    data(){
        return{
            lessonList:[],
            contentType:null,
            noResult : false,
            isGettingData : true,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        },
        ...mapGetters([
            'getUserPermission'
        ])
    },
    mounted(){
        console.log(this.getUserPermission)
    },
    async created(){
        if(this.currentPath.query.applicationType == '问卷'){
            this.contentType = 1
        }else if(this.currentPath.query.applicationType == '投票'){
            this.contentType = 2
        }
        // const res = await this.callApi('get','/api/allLesson');
        // if(res.status == 200){
        //     this.lessonList= res.data[0];
        //     this.isGettingData = false;
        //     if(this.lessonList.grades.length == 0){
        //         this.noResult = true;
        //     }
        // }

        axios.get('/api/allLesson',{params:{
            schoolId:this.currentPath.params.schoolName,
            classId:this.currentPath.params.className
        }}).then(res=>{
            console.log(res.data)
            if(this.currentPath.params.className == undefined){
                this.lessonList = res.data[0].grades[0].lessons
            }else{
                this.lessonList = res.data
            }
            if(this.lessonList == 0){
                this.noResult = true
            }
        }).catch(err=>{
            console.log(err)
        })
    },
    methods:{
        selLesson(lesson){
            // console.log(lesson)
            // let arr = lesson.name.split('/')
            // console.log(arr)
            this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,selLesson:lesson.id}})
        }
    }
}
</script>