<template>
    <div>
        <div v-for="(questionData,i) in addData" :key="i" @click="editQuestion(questionData)">
            <div class="category-title"></div>
            <div v-for="(sentence,j) in questionData" :key="j" >
                <div class="es-item" v-if="j == 0">
                    <div class="w-100">
                        <div>{{i+1}}.{{sentence.title}} <span v-if="type">（{{type}}）</span></div>
                        <div class="media row m-0">
                            <div class="image-item col-12" v-if="sentence.imgUrl && sentence.imgUrl.length">
                                <div class="image-block row">
                                    <div class="image-upload-list col-1" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                        <img :src="imgUrl" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="file-item row col-12" v-if="sentence.otherUrl && sentence.otherUrl.length">
                                <div class="col-4" v-for="(otherUrl,l) in sentence.otherUrl" :key="l">
                                    <div class="image-upload-list float-left">
                                        <img src="/img/icon/icon_rar@2x.png" alt="">
                                    </div>
                                    <div class="title pt-2">
                                        <div class="text-break">{{otherUrl.fileOriName}}</div>
                                        <div class="text-secondary">{{otherUrl.fileSize}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="file-item row col-12" v-if="sentence.videoUrl && sentence.videoUrl.length">
                                <div class="col-4" v-for="(videoUrl,m) in sentence.videoUrl" :key="m">
                                    <div class="image-upload-list float-left">
                                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                                    </div>
                                    <div class="title pt-2">
                                        <div class="text-break">{{videoUrl.fileOriName}}</div>
                                        <div class="text-secondary">{{videoUrl.fileSize}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="es-item" v-else>
                    <div class="w-100" :class="{'active-answer':sentence.active}" @click="selSentence(questionData,sentence)">
                        <div class="answer">{{alphabet[j-1]}} : {{sentence.title}}</div>
                        <div><span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span>人，<span v-if="sentence.answerCnt">{{parseFloat(sentence.answerCnt/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %</div>
                        <div class="media row m-0">
                            <div class="image-item col-12" v-if="sentence.imgUrl && sentence.imgUrl.length">
                                <div class="image-block row">
                                    <div class="image-upload-list col-1" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                        <img :src="imgUrl" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="file-item row col-12" v-if="sentence.otherUrl && sentence.otherUrl.length">
                                <div class="col-4" v-for="(otherUrl,l) in sentence.otherUrl" :key="l">
                                    <div class="image-upload-list float-left">
                                        <img src="/img/icon/icon_rar@2x.png" alt="">
                                    </div>
                                    <div class="title pt-2">
                                        <div class="text-break">{{otherUrl.fileOriName}}</div>
                                        <div class="text-secondary">{{otherUrl.fileSize}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="file-item row col-12" v-if="sentence.videoUrl && sentence.videoUrl.length">
                                <div class="col-4" v-for="(videoUrl,m) in sentence.videoUrl" :key="m">
                                    <div class="image-upload-list float-left">
                                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                                    </div>
                                    <div class="title pt-2">
                                        <div class="text-break">{{videoUrl.fileOriName}}</div>
                                        <div class="text-secondary">{{videoUrl.fileSize}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="es-model-operate" v-if="contentType == 18">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    props:['addData','type','contentType','bulletinId'],
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
    },
    created(){
        for(let i = 1;i <= this.addData.length;i++){
            this.$store.commit('setQuestionItemCnt',1)
        }
        console.log(this.addData)
    },
    methods:{
        editQuestion(data){
        },
        selSentence(questionData,sentence){
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
        }
    }
}
</script>