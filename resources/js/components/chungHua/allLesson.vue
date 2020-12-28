<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
            <div v-if="currentPath.query.selLesson == undefined">
                <div style="all:unset" v-for="school in getUserPermission" :key="school.schoolId">
                    <div style="all:unset" v-for="lesson in school.lessons" :key="lesson.lessonId">
                        <div class="vx-item is-click" @click="selLesson(lesson)">
                            <div class="vx-item-left">
                                {{lesson.lessonName}}
                            </div>
                            <div class="vx-item-right">
                                <Icon type="ios-arrow-forward"></Icon>
                            </div>
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
    },
    methods:{
        selLesson(lesson){
            console.log(lesson)
            this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,selLesson:lesson.lessonId}})
        }
    }
}
</script>