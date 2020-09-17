<template>
    <div>
        <div v-if="currentPath.query.questionType == '问卷'">
            <newQuestionaire></newQuestionaire>
        </div>
        <div v-else-if="currentPath.query.questionType == '投票'">

        </div>
        <div v-else-if="currentPath.query.questionType == '短信'">

        </div>
        <div v-else-if="currentPath.query.questionType == '校园动态'">

        </div>
        <div v-else-if="currentPath.query.questionType == '公告'">

        </div>
        <div v-else-if="currentPath.query.questionType == '场所预约'">

        </div>
        <div v-else-if="currentPath.query.questionType == '布告栏'">

        </div>
        <div v-else-if="currentPath.query.questionType == '班牌发布'">

        </div>
        <div v-else-if="currentPath.query.questionType == '活动'">

        </div>
        <div v-else-if="currentPath.query.questionType == '倒计时'">

        </div>
        <div v-else-if="currentPath.query.questionType == '评比'">

        </div>
        <div v-else-if="currentPath.query.questionType == '表彰'">

        </div>
        <div v-else-if="currentPath.query.questionType == '安全信息'">

        </div>
        <div v-else-if="currentPath.query.questionType == '宿舍考勤'">

        </div>
        <div v-else-if="currentPath.query.questionType == '联考'">

        </div>
        <div v-else-if="currentPath.query.questionType == '食谱'">

        </div>
        <div v-else>

        </div>
    </div>
</template>

<script>
import newQuestionaire from './newQuestionaire'
export default {
    components:{
        newQuestionaire,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        console.log('111111111',this.currentPath);
    }

}
</script>