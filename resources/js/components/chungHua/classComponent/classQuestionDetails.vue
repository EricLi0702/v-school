<template>
    <div>
        <div v-if="currentPath.query.questionType =='问卷'">
            <newQuestionaire></newQuestionaire>
        </div>
        <div v-else-if="currentPath.query.questionType == '投票'">
            <votingComponent></votingComponent>
        </div>
        <div v-else-if="currentPath.query.questionType=='作业'">
            <newHomework></newHomework>
        </div>
        <div v-else-if="currentPath.query.questionType=='习题'">
            <homeworkQuestion></homeworkQuestion>
        </div>
        <div v-else-if="currentPath.query.questionType=='家访'">
            <homeVisit></homeVisit>
        </div>
        <div v-else-if="currentPath.query.questionType=='养成打卡'">
            <checkIn></checkIn>
        </div>
        <div v-else-if="currentPath.query.questionType=='通知'">
            <notification></notification>
        </div>
        <div v-else-if="currentPath.query.questionType=='评价'">
            <evaluation></evaluation>
        </div>
        <div v-else-if="currentPath.query.questionType=='表彰'">
            <recognition></recognition>
        </div>
        <div v-else-if="currentPath.query.questionType=='少年秀'">
            <assessment></assessment>
        </div>
        <div v-else-if="currentPath.query.questionType=='成绩'">

        </div>
        <div v-else-if="currentPath.query.questionType=='考勤'">

        </div>
        <div v-else-if="currentPath.query.questionType=='课表'">
            <timeTable></timeTable>
        </div>
        <div v-else-if="currentPath.query.questionType=='相册'">

        </div>
        <div v-else-if="currentPath.query.questionType=='文件'">

        </div>
    </div>
</template>
<script>
import newQuestionaire from '../newQuestionaire'
import votingComponent from '../votingComponent'
import newHomework from '../newHomework'
import homeworkQuestion from '../homeworkQuestion'
import homeVisit from '../homeVisit'
import checkIn from '../checkIn'
import timeTable from './timeTable'
import evaluation from './evaluation'
import recognition from './recognition'
import assessment from './studentAssessment'
import notification from './notification'
export default {
    components:{
        newQuestionaire,
        votingComponent,
        newHomework,
        homeworkQuestion,
        homeVisit,
        checkIn,
        timeTable,
        evaluation,
        recognition,
        assessment,
        notification,
        
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        currentPath:{
            handler(val){
                console.log(val)
            },
            deep:true
        }
    }
}
</script>

<style>

</style>