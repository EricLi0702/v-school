<template>
    <div>
        <div v-if="viewType == 'answer'">
            <div v-if="postDetails.contentType == 1">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>已答</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{answerCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left w-100 d-block">
                        <div class="text-break">标题：{{postDetails.addData.title}}</div>
                        <div class="text-secondary">说明：{{postDetails.addData.description}}</div>
                    </div>
                </div>
                <div id="singleContentDataArr" v-if="postDetails.addData.content.singleContentDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.singleContentDataArr"
                        :type="'单选题'"
                    ></answerItemComponent>
                </div>
                <div id="multiContentDataArr" v-if="postDetails.addData.content.multiContentDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.multiContentDataArr"
                        :type="'多选题'"
                    ></answerItemComponent>
                </div>
                <div id="questionAnswerDataArr" v-if="postDetails.addData.content.questionAnswerDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.questionAnswerDataArr"
                        :type="'问答题'"
                    ></answerItemComponent>
                </div>
                <div id="statisticsDataArr" v-if="postDetails.addData.content.statisticsDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.statisticsDataArr"
                        :type="'统计题'"
                    ></answerItemComponent>
                </div>
                <div id="scoringQuestoinsDataArr" v-if="postDetails.addData.content.scoringQuestoinsDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.scoringQuestoinsDataArr"
                        :type="'评分题'"
                    ></answerItemComponent>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addQuestionnaire" :disabled="isLoading" :loading="isLoading">提交</Button>
                </div>
            </div>
            <div v-else-if='postDetails.contentType == 2'>
                <div class="es-item">
                    <div class="es-item-left">
                        <span>已答</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{answerCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div v-if="postDetails.addData.content.votingDataArr.length">
                    <answerItemComponent
                        :addData="postDetails.addData.content.votingDataArr"
                        :type="'单选题'"
                    ></answerItemComponent>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addVoting" :disabled="isLoading" :loading="isLoading">提交</Button>
                </div>
            </div>
        </div>
        <div v-else-if="viewType == 'view'">
            <div v-if="postDetails.contentType == 1">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>已答</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{postDetails.readCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left w-100 d-block">
                        <div class="text-break">标题：{{postDetails.addData.title}}</div>
                        <div class="text-secondary">说明：{{postDetails.addData.description}}</div>
                    </div>
                </div>
                <div id="singleContentDataArr" v-if="postDetails.addData.content.singleContentDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.singleContentDataArr"
                        :type="'单选题'"
                    ></viewItemComponent>
                </div>
                <div id="multiContentDataArr" v-if="postDetails.addData.content.multiContentDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.multiContentDataArr"
                        :type="'多选题'"
                    ></viewItemComponent>
                </div>
                <div v-if="postDetails.addData.content.questionAnswerDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.questionAnswerDataArr"
                        :type="'问答题'"
                    ></viewItemComponent>
                </div>
                <div v-if="postDetails.addData.content.statisticsDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.statisticsDataArr"
                        :type="'统计题'"
                    ></viewItemComponent>
                </div>
                <div v-if="postDetails.addData.content.scoringQuestoinsDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.scoringQuestoinsDataArr"
                        :type="'评分题'"
                    ></viewItemComponent>
                </div>
                <div class="es-model-operate">
                    <Button type="default" @click="dataExport" :disabled="isLoading" :loading="isLoading"> 导出 </Button>
                    <Button type="default" @click="showChart" :disabled="isLoading" :loading="isLoading"> 显示图表 </Button>    
                </div>
            </div>
            <div v-else-if="postDetails.contentType == 2">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>已答</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{postDetails.readCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div v-if="postDetails.addData.content.votingDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.votingDataArr"
                        :type="'单选题'"
                    ></viewItemComponent>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import answerItemComponent from './answerItemComponent'
import viewItemComponent from './viewItemComponent'
export default {
    props:['postDetails','viewType'],
    components:{
        answerItemComponent,
        viewItemComponent,
    },
    watch:{
    },
    computed:{
        
    },
    created(){
        
    },
    data(){
        return{
            answerCnt:0,
            isLoading:false,
        }
    },
    methods:{
        async addQuestionnaire(){
            let result = this.validateAnswer(this.postDetails.addData.content)
            switch(result){
                case 0:
                    return this.swr()
                case 1:
                    return this.error('至少选择一项（第1题）')
                case 2:
                    return this.error('至少选择一项（第2题）')
                case 3:
                    return this.error('请输入答案（第3题）')
                case 4:
                    return this.error('请输入统计值（第4题）')
                case 5:
                    return this.error('至少选择一个分数（第5题）')

            }
            this.isLoading = true;
            
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.postDetails.addData,userId:this.$store.state.user.id,bulletinId:this.postDetails.id})
            if(res.status == 201){
                this.success('ok')
                this.$emit('answer',false)
                
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
        validateAnswer(contentData){
            let singleAnswerCnt = 0;
            let multiAnswerCnt = 0;
            let thirdAnswerCnt = 0;
            let forthAnswerCnt = 0;
            let FifthAnswerCnt = 0;
            if(contentData.length == 0){
                return 0
            }
            if(contentData.singleContentDataArr.length){
                for(let i=0;i<contentData.singleContentDataArr.length;i++){
                    for(let j=0;j<contentData.singleContentDataArr[i].length;j++){
                        if(contentData.singleContentDataArr[i][j].isActive == true){
                            singleAnswerCnt ++
                        }
                    }
                }
                if(singleAnswerCnt == 0){
                    return 1
                }
            }
            if(contentData.multiContentDataArr.length){
                for(let i=0;i<contentData.multiContentDataArr.length;i++){
                    for(let j=0;j<contentData.multiContentDataArr[i].length;j++){
                        if(contentData.multiContentDataArr[i][j].isActive == true){
                            multiAnswerCnt ++
                        }
                    }
                }
                if(multiAnswerCnt == 0){
                    return 2
                }
            }
            if(contentData.questionAnswerDataArr.length){
                for(let i=0;i<contentData.questionAnswerDataArr.length;i++){
                    for(let j=0;j<contentData.questionAnswerDataArr[i].length;j++){
                        if(contentData.questionAnswerDataArr[i][j].isActive){
                            if(contentData.questionAnswerDataArr[i][j].isActive.description.length){
                                thirdAnswerCnt ++
                            }
                        }
                    }
                }
                if(thirdAnswerCnt == 0){
                    return 3
                }
            }
            if(contentData.statisticsDataArr.length){
                for(let i=0;i<contentData.statisticsDataArr.length;i++){
                    for(let j=0;j<contentData.statisticsDataArr[i].length;j++){
                        if(contentData.statisticsDataArr[i][j].isActive){
                            if(contentData.statisticsDataArr[i][j].isActive>0){
                                forthAnswerCnt ++
                            }
                        }
                    }
                }
                if(forthAnswerCnt == 0){
                    return 4
                }
            }
            if(contentData.scoringQuestoinsDataArr.length){
                for(let i=0;i<contentData.scoringQuestoinsDataArr.length;i++){
                    for(let j=0;j<contentData.scoringQuestoinsDataArr[i].length;j++){
                        if(contentData.scoringQuestoinsDataArr[i][j].isActive){
                            FifthAnswerCnt ++
                        }
                    }
                }
                if(FifthAnswerCnt == 0){
                    return 5
                }
            }

        },
        showChart(){

        },
        dataExport(){
            console.log(this.postDetails)
        },
        async addVoting(){
            let data = this.postDetails.addData.content.votingDataArr
            console.log(data)
            let singleAnswerCnt = 0
            if(data.length){
                for(let i=0;i<data.length;i++){
                    for(let j=1;j<data[i].length;j++)
                    if(data[i][j].isActive == true){
                        singleAnswerCnt ++
                    }
                }
            }
            if(singleAnswerCnt == 0){
                return this.error('至少选择一项（第1题）')
            }
            this.isLoading = true;
            
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.postDetails.addData,userId:this.$store.state.user.id,bulletinId:this.postDetails.id})
            if(res.status == 201){
                this.success('ok')
                this.$emit('answer',false)
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
    }
}
</script>