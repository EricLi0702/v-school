<template>
    <div>
        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
        <div class="es-app-detail-header">
            <Input prefix="ios-search" placeholder="搜索"/>
            <div class="operate-item">
                <Tooltip content="Bottom Center text" placement="bottom">
                    <img src="/img/icon/ico_report.png" alt="">
                </Tooltip>

                <Tooltip content="Bottom Center text" placement="bottom">
                    <img src="/img/icon/ico_app_set.png" alt="">
                </Tooltip>
                <Button class="btnclass ml-2" @click="addModal"><Icon type="md-add" /> 发布 </Button>
            </div>
        </div>
        <div class="p-modal-scroll">
            <div v-if="currentPath.query.selLesson == undefined">
                <router-link :to="`${currentPath.path}?applicationName=${currentPath.query.applicationName}&selLesson=${lessonList.schoolName}`">
                    <div class="es-item" v-if="lessonList.schoolName">
                        <div class="es-item-left">
                            {{lessonList.schoolName}}
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
                <div style="all:unset" v-for="grade in lessonList.grades" :key="grade.id">
                    <router-link :to="`${currentPath.path}?applicationName=${currentPath.query.applicationName}&selLesson=${grade.gradeName}`">
                    <div class="es-item">
                        <div class="es-item-left">
                            {{grade.gradeName}}
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                    </router-link>
                    <div style="all:unset" v-for="lesson in grade.lessons" :key="lesson.id">
                        <router-link :to="`${currentPath.path}?applicationName=${currentPath.query.applicationName}&selLesson=${lesson.lessonName}`">
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
    </div>
</template>

<script>
import applicationBoard from './applicationBoard'
export default {
    components:{
        applicationBoard,
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
        if(this.currentPath.query.applicationName == '问卷'){
            this.contentType = 1
        }else if(this.currentPath.query.applicationName == '投票'){
            this.contentType = 2
        }
        const res = await this.callApi('get','/api/allLesson');
        console.log('!!!!!',res);
        if(res.status == 200){
            this.lessonList= res.data[0];
        }
    },
    methods:{
        addModal(){
            this.$router.push(`${this.$route.path}?questionType=问卷`)
        }
    }
}
</script>