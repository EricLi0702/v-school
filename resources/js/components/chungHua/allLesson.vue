<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
            <div v-if="currentPath.query.selLesson == undefined">
                <div style="all:unset" v-for="lesson in permissionLesson" :key="lesson.lessonName">
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
            permissionLesson:[],
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
        console.log('this.getUserPermission',this.getUserPermission)
    },
    async created(){
        if(this.currentPath.query.applicationType == '问卷'){
            this.contentType = 1
        }else if(this.currentPath.query.applicationType == '投票'){
            this.contentType = 2
        }
        axios.get('/api/allLesson',{params:{
            schoolId:this.currentPath.params.schoolName,
            classId:this.currentPath.params.className
        }}).then(res=>{
            if(this.currentPath.params.className == undefined){
                let index = this.getUserPermission.findIndex(school=>school.schoolName.resourceName == res.data[0].schoolName)
                let permission = this.getUserPermission[index]
                let classList = permission.data
                console.log('classList',classList)
                this.lessonList = res.data[0].grades[0].lessons
                for(let i=0;i<this.lessonList.length;i++){
                    let index = classList.findIndex(lesson=>lesson.resourceName == this.lessonList[i].lessonName)
                    if(index>-1){
                        if(classList[index].read == true){
                            // this.lessonList.splice(i,1)
                            // console.log(this.lessonList[i].lessonName)
                            this.permissionLesson.push(this.lessonList[i])
                        }
                    }
                }
                console.log('this.permissionLesson',this.permissionLesson)
            }else{
                this.permissionLesson = res.data
                console.log('this.permissionLesson',this.permissionLesson)
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