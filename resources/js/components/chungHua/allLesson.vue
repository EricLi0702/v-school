<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <applicationAdd></applicationAdd>
            <div v-if="currentPath.query.selLesson == undefined">
                <!-- <router-link :to="`${currentPath.path}?applicationType=${currentPath.query.applicationType}&selLesson=${lessonList.schoolName}`">
                    <div class="es-item" v-if="lessonList.schoolName">
                        <div class="es-item-left">
                            {{lessonList.schoolName}}
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link> -->
                <div style="all:unset" v-for="grade in lessonList.grades" :key="grade.id">
                    <!-- <router-link :to="`${currentPath.path}?applicationType=${currentPath.query.applicationType}&selLesson=${grade.gradeName}`">
                    <div class="es-item">
                        <div class="es-item-left">
                            {{grade.gradeName}}
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    </router-link> -->
                    <div style="all:unset" v-for="lesson in grade.lessons" :key="lesson.id">
                        <router-link :to="`${currentPath.path}?applicationType=${currentPath.query.applicationType}&selLesson=${lesson.id}`">
                        <div class="es-item">
                            <div class="es-item-left">
                                {{lesson.lessonName}}
                            </div>
                            <div class="es-item-right">
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
export default {
    components:{
        applicationAdd,
        applicationBoard,
        questionDetail,
    },
    data(){
        return{
            lessonList:[],
            contentType:null,
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
        }
    },
    methods:{
        
    }
}
</script>