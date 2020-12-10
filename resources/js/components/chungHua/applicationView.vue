<template>
    <div id="applicationViwe">
        <!-- <div class="es-item" v-for="(questionnaireMenu,modalMenuIndex) in questionnaireLists" :key="modalMenuIndex" @click="modalMenuClick(questionnaireMenu)">
            <div class="es-item-left">{{questionnaireMenu.name}}</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div> -->
        <div v-if="currentPath.query.applicationType == '出入管理'">
            <applicationAdd></applicationAdd>
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationType == '问卷'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationType == '投票'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationType == '短信'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'3'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '校园动态'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'4'"></applicationBoard>
        </div>
         <div v-else-if="currentPath.query.applicationType == '公告'">
             <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'5'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '审批'">
            <approveComponent></approveComponent>
        </div>
        <div v-else-if="currentPath.query.applicationType == '场所预约'">
            <applicationBoard :contentType="'6'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '布告栏'">
            <applicationBoard :contentType="'7'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '班牌发布'">
            <allLesson></allLesson>
        </div>
        <div v-else-if="currentPath.query.applicationType == '活动'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationType == '倒计时'">
            
        </div>
        <div v-else-if="currentPath.query.applicationType == '评比'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationType == '安全信息'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationType == '宿舍考勤'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationType == '联考'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.applicationType == '考勤'">
            <attendance></attendance>
        </div>
        <div v-else-if="currentPath.query.applicationType == '食谱'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.applicationType == '应用中心'">
            <applicationCenter></applicationCenter>
        </div>
        
        <div v-else-if="currentPath.query.applicationType == '提示'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.applicationType == '话题'">
            <!-- <div>homeVisit</div>
            <applicationBoard :contentType="'24'"></applicationBoard> -->
        </div>
        <div v-else-if="currentPath.query.applicationType == '出勤'">
            <attendance></attendance>
        </div>
        <div v-else-if="currentPath.query.applicationType == '作业'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'15'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '习题'">
            <newHomework></newHomework>
        </div>
        <div v-else-if="currentPath.query.applicationType == '家访'">
            <homeVisit></homeVisit>
        </div>
        <div v-else-if="currentPath.query.applicationType == '养成打卡'">
            <checkIn></checkIn>
        </div>
        <div v-else-if="currentPath.query.applicationType == '评价'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'21'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '表彰'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'22'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '通知'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'24'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '课表'">
            <applicationAdd></applicationAdd>
            <applicationBoard :contentType="'25'"></applicationBoard>
        </div>
        <div v-else-if="currentPath.query.applicationType == '相册'">
            <album></album>
        </div>
        <div v-else-if="currentPath.query.applicationType == '文件'">
            <file></file>
        </div>
    </div>
</template>

<script>
import applicationAdd from './applicationAdd'
import Questionnaire from './问卷Component'
import notConnect from '../pages/notConnect'
import smsComponent from './短信Component'
import approveComponent from './审批Component'
import applicationCenter from './应用中心Component'
import imageComponent from './相册Component'
import allLesson from './allLesson'
import applicationBoard from './applicationBoard'
import attendance from '../attendance/index'
import operation from './newHomework'
import newHomework from './homeworkQuestion'
import homeVisit from './homeVisit'
import checkIn from './checkIn'
import album from './classComponent/album'
import file from './classComponent/file'
import homeworkResult from './homework/homeWorkResult'
export default {
    props:['currentPath'],
    components:{
        applicationAdd,
        Questionnaire,
        notConnect,
        smsComponent,
        approveComponent,
        applicationCenter,
        imageComponent,
        allLesson,
        applicationBoard,
        attendance,
        operation,
        newHomework,
        homeVisit,
        checkIn,
        album,
        file,
        homeworkResult
    },
    data(){
        return{
            questionnaireLists:[],
        }
    },
    methods:{
        modalMenuClick(item){
        }
    }
}
</script>