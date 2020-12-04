<template>
    <div>
        <div v-if="currentPath.query.questionType == '问卷'">
            <newQuestionaire></newQuestionaire>
        </div>
        <div v-else-if="currentPath.query.questionType == '投票'">
            <votingComponent></votingComponent>
        </div>
        <div v-else-if="currentPath.query.questionType == '短信'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.questionType == '校园动态'">
            <activityComponent></activityComponent>
        </div>
        <div v-else-if="currentPath.query.questionType == '公告'">
            <announcementComponent></announcementComponent>
        </div>
        <div v-else-if="currentPath.query.questionType == '场所预约'">
            <appointmentComponent></appointmentComponent>
        </div>
        <div v-else-if="currentPath.query.questionType == '布告栏'">
            <bulletinBoard></bulletinBoard>
        </div>
        <div v-else-if="currentPath.query.questionType == '班牌发布'">
            <newClassCard></newClassCard>
        </div>
        <div v-else-if="currentPath.query.questionType == '活动'">
            <newEvent></newEvent>
        </div>
        <div v-else-if="currentPath.query.questionType == '倒计时'">

        </div>
        <div v-else-if="currentPath.query.questionType == '评比'">

        </div>
        <div v-else-if="currentPath.query.questionType == '表彰'">
            <newAward></newAward>
        </div>
        <div v-else-if="currentPath.query.questionType == '安全信息'">
            <newSecurityInformation></newSecurityInformation>
        </div>
        <div v-else-if="currentPath.query.questionType == '宿舍考勤'">

        </div>
        <div v-else-if="currentPath.query.questionType == '联考'">
            <newEntranceExam></newEntranceExam>
        </div>
        <div v-else-if="currentPath.query.questionType == '食谱'">

        </div>
        <div v-else-if="currentPath.query.questionType == '错题本'">
            test
        </div>
        <div v-else-if="currentPath.query.questionType == '家访'">
            <homeVisit></homeVisit>
        </div>
        <div v-else-if="currentPath.query.questionType == '养成打卡'">
            <checkIn></checkIn>
        </div>
        <div v-else-if="currentPath.query.questionType == '习题'">
            <homeworkQuestion></homeworkQuestion>
        </div>
        <div v-else-if="currentPath.query.questionType == '作业'" >
            <newHomework></newHomework>
        </div>
        <div v-else-if="currentPath.query.questionType == '考勤'" >
            <attendance></attendance>
        </div>
        <div v-else-if="currentPath.query.questionType == 'test'" >
            <fluorescentplate></fluorescentplate>
        </div>
        <div v-else-if="currentPath.query.questionType=='消息'">
            <div v-if="$isMobile()">
                <chatAddress></chatAddress>
            </div>
        </div>
        <div v-else-if="currentPath.query.questionType == 'jimi'" >
            <fence></fence>
        </div>
        <div v-else>

        </div>
    </div>
</template>

<script>
import newQuestionaire from './newQuestionaire';
import votingComponent from './votingComponent';
import smsComponent from './smsComponent';
import activityComponent from './activityComponent';
import announcementComponent from './announcement';
import appointmentComponent from './appointment';
import bulletinBoard from './bulletinBoard';
import newClassCard from './newClassCard';
import newEvent from './newEvent';
import newAward from './newAward';
import newSecurityInformation from './newSecurityInformation'
import newEntranceExam from './newEntranceExam'
import homeworkQuestion from './homeworkQuestion'
import homeVisit from './homeVisit'
import checkIn from './checkIn'
import newHomework from './newHomework'
import chatAddress from '../pages/chat/mobile/chatAddress'
import attendance from '../attendance/index'
import fluorescentplate from '../fluorescentplate/index'
import fence from './fence'
export default {
    components:{
        newQuestionaire,
        votingComponent,
        smsComponent,
        activityComponent,
        announcementComponent,
        appointmentComponent,
        bulletinBoard,
        newClassCard,
        newEvent,
        newAward,
        newSecurityInformation,
        newEntranceExam,
        newHomework,
        homeVisit,
        checkIn,
        homeworkQuestion,
        chatAddress,
        attendance,
        fluorescentplate,
        fence,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
    },
    

}
</script>