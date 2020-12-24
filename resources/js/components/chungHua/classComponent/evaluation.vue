<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <contactComponent @selectedUser="selUser"></contactComponent>
        </div>
        <div v-else-if="currentPath.query.selType == 'student'" class="px-3">
            <div class="surplus">
                积分剩余：暂无限制
            </div>
            <div class="user-item">
                <span>评价对象：</span>
                <span v-for="user in userList" :key="user.id" class="and text-color">@{{user}}</span>
            </div>
            <div class="ml-item" v-for="(medal,i) in medalData.medal" :key="i">
                <div class="md-dimension">
                    <span class="md-dimension-before"></span>
                    {{medal.type}}
                </div>
                <div class="md-content">
                    <div class="md-block" v-for="(menu,j) in medal.menuList" :key="j" @click="clickMedal(menu)">
                        <div style="display: flex;flex-direction: column;">
                            <div class="medal-04">
                                <div class="md-score-line">
                                    <img src="data:image/png;base64,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" alt="">
                                    <span class="txt">+{{menu.medalPoint}}分</span>
                                </div>
                                <img :src="`${baseUrl}/img/icon/medal_empty.png`" :style="menu.medalImg" alt="" class="md-logo">
                                <div class="md-word-bg"></div>
                                <div class="md-content">
                                    <span class="md-name">{{menu.medalText}}</span>
                                </div>
                            </div>
                            <div class="select-icon-line">
                                <Checkbox v-model="menu.medalFlag"></Checkbox>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                <Button type="primary" @click="addComment">添加评语</Button>
            </div>
        </div>
       <div v-else-if="currentPath.query.selType == 'medal'">
           <div class="es-item">
               <div class="es-item-left">
                   评价对象
               </div>
               <div class="es-item-right">
                   <span>{{userList.length}}人</span>
               </div>
           </div>
           <div class="es-item">
               <div class="es-item-left">
                   评价勋章

               </div>
               <div class="es-item-right">
                   <span>{{selMedalList.length}}个</span>
               </div>
           </div>
           <contentComponent @contentData="commentData"></contentComponent>
           <div class="es-model-operate">
                <Button type="primary" @click="commentSubmit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
       </div>

    </div>
</template>

<script>
import contactComponent from '../contactComponent'
import medalData from '../../../json/chungHua/medalView.json'
import contentComponent from '../contentComponent'
export default {
    components:{
        contactComponent,
        contentComponent
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
    },
    data(){
        return{
            userList:[],
            baseUrl:window.Laravel.base_url,
            // single:false,
            medalData,
            isLoading:false,
            selMedalList:[],
            comment:''
        }
    },
    methods:{
        selUser(val){
            this.userList = val;
        },
        async submit(){
            if(this.selMedalList.length == 0){
                return this.error('请选择评价勋章');
            }
            let answerData = {
                userList:[],
                selMedalList:[],
                viewList:[],
                postShow:[],
            }
            answerData.userList = this.userList
            answerData.selMedalList = this.selMedalList
            // answerData.commentData =this.comment
            this.isLoading = true
            let userId = this.$store.state.user.id;
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            answerData.viewList.push(this.currentPath.params.className)
            answerData.postShow.push(this.currentPath.params.className)
            answerData.postShow.push(2)
            const res = await this.callApi('post','/api/questionnaire',{data:answerData,userId:userId,contentType:21,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false);
                this.userList = [];
                this.selMedalList = [];
                //init medal data
                let medal = this.medalData.medal;
                for(let i=0;i<medal.length;i++){
                    for(let j=0;j<medal[i].menuList.length;j++){
                        if(medal[i].menuList[j].medalFlag == true){
                            medal[i].menuList[j].medalFlag = false;
                        }
                    }
                }
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false);
                this.userList = [];
                this.selMedalList = [];
                this.swr();
            }
            this.isLoading = false;
        },
        async commentSubmit(){
            let answerData = {
                userList:[],
                selMedalList:[],
                viewList:[],
                postShow:[],
                commentData:'',

            }
            answerData.userList = this.userList
            answerData.selMedalList = this.selMedalList
            // for(let i=0;i<answerData.selMedalList.length;i++){
            //     delete answerData.selMedalList[i].medalImg
            // }
            // answerData.selMedalList
            answerData.commentData =this.comment
            this.isLoading = true
            let userId = this.$store.state.user.id;
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            answerData.viewList.push(this.currentPath.params.className)
            answerData.postShow.push(this.currentPath.params.className)
            answerData.postShow.push(2)
            const res = await this.callApi('post','/api/questionnaire',{data:answerData,userId:userId,contentType:21,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        },
        addComment(){
            let medal = this.medalData.medal;
            this.selMedalList = []
            for(let i=0;i<medal.length;i++){
                for(let j=0;j<medal[i].menuList.length;j++){
                    // console.log(medal[i].menuList[j])
                    if(medal[i].menuList[j].medalFlag == true){
                        // console.log(medal[i].menuList[j])
                        this.selMedalList.push(medal[i].menuList[j])
                    }
                }
            }
            this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,questionType:this.currentPath.query.questionType,selType:'medal'}})
        },
        commentData(val){
            this.comment = val
        },
        clickMedal(menu){
            menu.medalFlag = ! menu.medalFlag
            let medal = this.medalData.medal;
            this.selMedalList = []
            for(let i=0;i<medal.length;i++){
                for(let j=0;j<medal[i].menuList.length;j++){
                    // console.log(medal[i].menuList[j])
                    if(medal[i].menuList[j].medalFlag == true){
                        // console.log(medal[i].menuList[j])
                        this.selMedalList.push(medal[i].menuList[j])
                    }
                }
            }
        }
    } 
}
</script>

<style>

</style>