<template>
    <div>
        <div v-if="currentPath.query.answerUsers == undefined">
            <div v-for="(questionData,i) in addData" :key="i" @click="editQuestion(questionData,i)" >
                <div class="category-title"></div>
                <div v-for="(sentence,j) in questionData" :key="j" class="question-item">
                    <div class="qi-content" v-if="j == 0">
                        <div class="qi-name">{{i+1}}. {{sentence.title}} <span v-if="type">（{{type}}）</span></div>
                        <div class="qi-medias" v-if="checkIfHasFile(sentence)">
                            <div class="media row m-0">
                                <div class="image-item col-12 p-0" v-if="sentence.imgUrl && sentence.imgUrl.length">
                                    <div class="image-block row m-0 p-0">
                                        <div class="image-upload-list col-6 col-sm-6 col-md-4 col-lg-3 mr-0 shadow-none h-100px" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                            <img :src="imgUrl" alt=""  class="pr-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="file-item row col-12" v-if="sentence.otherUrl && sentence.otherUrl.length">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,l) in sentence.otherUrl" :key="l">
                                        <div class="image-upload-list float-left file-gravatar-icon">
                                            <img src="/img/icon/icon_rar@2x.png" alt="">
                                        </div>
                                        <div class="title pt-2 gray-font bg-light-gray w-100">
                                            <div class="text-break word-ellipse">{{otherUrl.fileOriName}}</div>
                                            <div class="">{{otherUrl.fileSize}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="file-item row col-12" v-if="sentence.videoUrl && sentence.videoUrl.length">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,m) in sentence.videoUrl" :key="m">
                                        <div class="image-upload-list float-left file-gravatar-icon">
                                            <img src="/img/icon/icon_mp4@2x.png" alt="">
                                        </div>
                                        <div class="title pt-2 gray-font bg-light-gray w-100">
                                            <div class="text-break word-ellipse">{{videoUrl.fileOriName}}</div>
                                            <div class="">{{videoUrl.fileSize}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="qi-options" v-else>
                        <div class="w-100 op-item choice" :class="{'active-answer':sentence.active}" @click="selSentence(questionData,sentence)">
                            <div class="answer op-num">{{alphabet[j-1]}}</div> <div class="op-content">{{sentence.title}}</div>
                            <div><span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span>人，<span v-if="sentence.answerCnt">{{parseFloat(sentence.answerCnt/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %</div>
                            <div class="media row m-0">
                                <div class="image-item col-12 p-0" v-if="sentence.imgUrl && sentence.imgUrl.length">
                                    <div class="image-block row m-0 p-0">
                                        <div class="image-upload-list col-6 col-sm-6 col-md-4 col-lg-3 mr-0 shadow-none h-100px" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                            <img :src="imgUrl" alt=""  class="pr-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="file-item row col-12" v-if="sentence.otherUrl && sentence.otherUrl.length">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,l) in sentence.otherUrl" :key="l">
                                        <div class="image-upload-list float-left file-gravatar-icon">
                                            <img src="/img/icon/icon_rar@2x.png" alt="">
                                        </div>
                                        <div class="title pt-2 gray-font bg-light-gray w-100">
                                            <div class="text-break word-ellipse">{{otherUrl.fileOriName}}</div>
                                            <div class="">{{otherUrl.fileSize}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="file-item row col-12" v-if="sentence.videoUrl && sentence.videoUrl.length">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,m) in sentence.videoUrl" :key="m">
                                        <div class="image-upload-list float-left file-gravatar-icon">
                                            <img src="/img/icon/icon_mp4@2x.png" alt="">
                                        </div>
                                        <div class="title pt-2 gray-font bg-light-gray w-100">
                                            <div class="text-break word-ellipse">{{videoUrl.fileOriName}}</div>
                                            <div class="">{{videoUrl.fileSize}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="qi-options" v-if="checkIfRating(sentence)">
                        <div class="op-item marking">
                            <div v-for="(n, index) in parseInt(sentence.maxMinute)" :key="index" class="op-score">
                                {{index+1}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="es-model-operate" v-if="contentType == 18">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else>
            <div class="vx-item" v-for="(user,i) in currentPath.query.answerUsers" :key="i">
                <div class="vx-item-left">
                    {{user}}
                </div>
                <div class="vx-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    props:['addData','type','contentType','bulletinId','viewType'],
    data(){
        return{
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
            isLoading:false,
        }
    },
    computed:{
        ...mapGetters([
            'getQuestionItemCnt'
        ]),
        currentPath(){
            return this.$route
        }
    },
    created(){
        for(let i = 1;i <= this.addData.length;i++){
            this.$store.commit('setQuestionItemCnt',1)
        }
    },
    methods:{
        editQuestion(data,index){
            if(this.currentPath.query.questionType == '问卷'){
                let arrayData = []
                arrayData.push(data)
                this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,questionType:this.currentPath.query.questionType,addQuestion:'edit',editData:JSON.stringify(arrayData),index:index}})
            }
        },
        selSentence(questionData,sentence){
            console.log('+++++++++++++++',this.viewType)
            if(this.viewType == 'view'){
                if(sentence.answerUsers == undefined){
                    return this.error('暂无人选择该选项')
                }else if(sentence.answerUsers.length>0){

                    this.$router.push({path:this.currentPath.path,query:{postDetail:this.currentPath.query.postDetail,answerUsers:sentence.answerUsers}})
                }
                return
            }else if(this.viewType == 'answer'){
                let userId = this.$store.state.user.id
                if(questionData[0].type == "单选题"){
                    for(let i=0;i<questionData.length;i++){
                        if(questionData[i].title != sentence.title){
                            delete questionData[i].active
                        } 
                    }
                    if(sentence.active == undefined){
                        this.$set(sentence,'active',true)
                    }else{
                        sentence.active = ! sentence.active
                    }
                }else if(questionData[0].type == "多选题"){
                    if(sentence.active == undefined){
                        this.$set(sentence,'active',true)
                    }else{
                        sentence.active = ! sentence.active
                    }
                }
            }
        },
        async submit(){
            this.isLoading = true;
            
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.addData,userId:this.$store.state.user.id,bulletinId:this.bulletinId})
             if(res.status == 200){
                this.success('操作成功')
                this.$emit('answer',res.data[0])
                this.$store.commit('setShowAnswerDetail',false);
            }else{
                this.swr()
            }
            this.isLoading = false;
        },

        checkIfHasFile(sentence){
            if (!('imgUrl' in sentence) || !('otherUrl' in sentence) || !('otherUrl' in sentence)){
                return false;
            }
            if(sentence.imgUrl.length == 0 && sentence.otherUrl.length == 0 && sentence.otherUrl.length == 0){
                return false;
            }
            return true;
        },

        checkIfRating(sentence){
            if ('maxMinute' in sentence){
                return true;
            }
            return false;
        }
    }
}
</script>