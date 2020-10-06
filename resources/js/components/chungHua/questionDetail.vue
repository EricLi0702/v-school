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
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
    }

}
</script>