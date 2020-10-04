<template>
    <div>
        <!-- <div class="es-item" v-for="(questionnaireMenu,modalMenuIndex) in questionnaireLists" :key="modalMenuIndex" @click="modalMenuClick(questionnaireMenu)">
            <div class="es-item-left">{{questionnaireMenu.name}}</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div> -->
        <div v-if="currentPath.query.applicationName == '出入管理'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationName == '问卷'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '投票'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '短信'">
            <applicationBoard :contentType="'3'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationName == '校园动态'">
            <applicationBoard :contentType="'3'"></applicationBoard>
        </div>
         <div v-else-if="currentPath.query.applicationName == '公告'">
            <applicationBoard :contentType="'4'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationName == '审批'">
            <approveComponent></approveComponent>
        </div>
        <div v-else-if="currentPath.query.applicationName == '场所预约'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationName == '布告栏'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '班牌发布'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '活动'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationName == '倒计时'">
            
        </div>
        <div v-else-if="currentPath.query.applicationName == '评比'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationName == '评价'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '表彰'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '安全信息'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationName == '宿舍考勤'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationName == '联考'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationName == '考勤'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationName == '食谱'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationName == '应用中心'">
            <applicationCenter></applicationCenter>
        </div>
        <div v-else-if="currentPath.query.applicationName == '课表'">
            
        </div>
        <div v-else-if="currentPath.query.applicationName == '提示'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.applicationName == '话题'">
            
        </div>
        <div v-else-if="currentPath.query.applicationName == '相册'">
            
        </div>
        <div v-else-if="currentPath.query.applicationName == '文件'">
            
        </div>

    </div>
</template>

<script>
import Questionnaire from './问卷Component'
import notConnect from '../pages/notConnect'
import smsComponent from './短信Component'
import approveComponent from './审批Component'
import applicationCenter from './应用中心Component'
import imageComponent from './相册Component'
import allLesson from './allLesson'
import applicationBoard from './applicationBoard'
export default {
    props:['currentPath'],
    components:{
        Questionnaire,
        notConnect,
        smsComponent,
        approveComponent,
        applicationCenter,
        imageComponent,
        allLesson,
        applicationBoard,
    },
    data(){
        return{
            questionnaireLists:[],
        }
    },
    computed:{
        // currentPath(){
        //     console.log('component current path',$this.route)
        //     return $this.route
        // }
    },
    created(){
        // console.log('props:',this.currentPath)
        // axios.get('api/questionnaireLists').then(res=>{
        //     console.log(res.data)
        //     this.questionnaireLists = res.data;
        // })
    },
    methods:{
        modalMenuClick(item){
            console.log(item);
        }
    }
}
</script>