<template>
    <div>
        <!-- <div class="es-item" v-for="(questionnaireMenu,modalMenuIndex) in questionnaireLists" :key="modalMenuIndex" @click="modalMenuClick(questionnaireMenu)">
            <div class="es-item-left">{{questionnaireMenu.name}}</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div> -->
        <div v-if="currentPath.query.modalName == '问卷'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '出入管理'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '投票'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '短信'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '校园动态'">
            <notConnect></notConnect>
        </div>
         <div v-else-if="currentPath.query.modalName == '公告'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '审批'">
            <approveComponent></approveComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '场所预约'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '布告栏'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '班牌发布'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '活动'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '倒计时'">
            
        </div>
        <div v-else-if="currentPath.query.modalName == '评比'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '评价'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '表彰'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '安全信息'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '宿舍考勤'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '联考'">
            <smsComponent></smsComponent>
        </div>
        <div v-else-if="currentPath.query.modalName == '考勤'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '食谱'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '活跃'">
            
        </div>
        <div v-else-if="currentPath.query.modalName == '应用中心'">
            <applicationCenter></applicationCenter>
        </div>
        <div v-else-if="currentPath.query.modalName == '课表'">
            
        </div>
        <div v-else-if="currentPath.query.modalName == '提示'">
            <Questionnaire></Questionnaire>
        </div>
        <div v-else-if="currentPath.query.modalName == '话题'">
            
        </div>
        <div v-else-if="currentPath.query.modalName == '相册'">
            
        </div>
        <div v-else-if="currentPath.query.modalName == '文件'">
            
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
export default {
    props:['currentPath'],
    components:{
        Questionnaire,
        notConnect,
        smsComponent,
        approveComponent,
        applicationCenter,
        imageComponent,
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