<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <applicationAdd></applicationAdd>
            <div v-if="currentPath.query.selLesson == undefined">
                <div v-if="noResult" class="row">
                    <notConnect></notConnect>
                </div>
                <div v-else-if="isGettingData" class="row justify-content-center pt-3 m-0" >
                    <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
                </div>
                <div v-else style="all:unset" v-for="grade in lessonList.grades" :key="grade.id">
                    <div style="all:unset" v-for="lesson in grade.lessons" :key="lesson.id">
                        <router-link :to="`${currentPath.path}?applicationType=${currentPath.query.applicationType}&selLesson=${lesson.id}`">
                            <div class="vx-item is-click">
                                <div class="vx-item-left">
                                    {{lesson.lessonName}}
                                </div>
                                <div class="vx-item-right">
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
                
            </div>
            <div v-else-if="currentPath.query.selLesson">
                <applicationBoard :selLesson="currentPath.query.selLesson" :contentType="contentType"></applicationBoard>
            </div>
        </div>
        <div v-else-if="currentPath.query.questionType">
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
        }
    },
    async created(){
        if(this.currentPath.query.applicationType == '问卷'){
            this.contentType = 1
        }else if(this.currentPath.query.applicationType == '投票'){
            this.contentType = 2
        }
        const res = await this.callApi('get','/api/allLesson');
        if(res.status == 200){
            this.lessonList= res.data[0];
            this.isGettingData = false;
            if(this.lessonList.grades.length == 0){
                this.noResult = true;
            }
        }
    },
    methods:{
        
    }
}
</script>