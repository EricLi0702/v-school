<template>
    <div>
        <div v-if="viewType == 'answer'">
            <div v-if="postDetails.contentType == 1">
                <div class="vx-item is-click">
                    <div class="vx-item-left">
                        <span>已答</span>
                    </div>
                    <div class="vx-item-right">
                        <span>{{answerCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="vx-item">
                    <div class="vx-item-left w-100 d-block py-2">
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
                <div class="vx-item is-click">
                    <div class="vx-item-left">
                        <span>已答</span>
                    </div>
                    <div class="vx-item-right">
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
            <div v-else-if="postDetails.contentType == 18">
                <questionItemComponent
                    :addData="postDetails.addData.description"
                    :contentType="postDetails.contentType"
                    :bulletinId="postDetails.id"
                ></questionItemComponent>
            </div>
        </div>
        <div v-else-if="viewType == 'view'">
            <div v-if="postDetails.contentType == 1">
                <div class="vx-item is-click">
                    <div class="vx-item-left">
                        <span>已答</span>
                    </div>
                    <div class="vx-item-right">
                        <span>{{postDetails.readCnt}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="vx-item">
                    <div class="vx-item-left w-100 d-block py-2">
                        <div class="text-break">标题：{{postDetails.addData.title}}</div>
                        <div class="text-secondary">说明：{{postDetails.addData.description}}</div>
                    </div>
                </div>
                <div id="singleContentDataArr" v-if="postDetails.addData.content.singleContentDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.singleContentDataArr"
                        :type="'单选题'"
                    ></viewItemComponent>
                    <!-- <lineChart :chart-data="datacollection"></lineChart> -->
                    <!-- <barChart></barChart> -->
                </div>
                <div id="multiContentDataArr" v-if="postDetails.addData.content.multiContentDataArr.length">
                    <viewItemComponent
                        :addData="postDetails.addData.content.multiContentDataArr"
                        :type="'多选题'"
                    ></viewItemComponent>
                    <!-- <pieChart></pieChart> -->
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
                    <!-- <download-excel 
                    :data="postDetails"
                    worksheet="My Worksheet"
                    name="filename.xls">
                        Download Data
                        </download-excel>   -->
                    <!-- <download-excel
                        class="btn btn-default"
                        :data="dataForExcel"
                        worksheet="My Worksheet"
                        name="问卷数据.xls"
                        >
                        Download Excel
                    </download-excel>   -->
                        
                        <!-- <vue-nested-json-to-csv
                            :object="postDetails"
                            :show-table="true"
                            :show-export-button="true"
                        ></vue-nested-json-to-csv> -->
                </div>
            </div>
            <div v-else-if="postDetails.contentType == 2">
                <div class="vx-item is-click">
                    <div class="vx-item-left">
                        <span>已答</span>
                    </div>
                    <div class="vx-item-right">
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
            <div v-else-if="postDetails.contentType == 18">
                <homeworkView
                    :viewData="postDetails"
                ></homeworkView>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import answerItemComponent from './answerItemComponent'
import viewItemComponent from './viewItemComponent'
import questionItemComponent from './questionItemComponent'
import lineChart from './lineChart'
import pieChart from './pieChart'
import barChart from './barChart'
import XLSX from 'xlsx'
import homeworkView from './homework/homeworkView'
export default {
    props:['postDetails','viewType'],
    components:{
        answerItemComponent,
        viewItemComponent,
        lineChart,
        pieChart,
        barChart,
        questionItemComponent,
        homeworkView,
    },
    watch:{
    },
    computed:{
      ...mapGetters(['getChartView'])  
    },
    created(){
        
    },
    mounted(){

    },
    data(){
        return{
            convertedArray:[],
            answerCnt:0,
            isLoading:false,
            json_fields:null,
            dataForExcel:[],
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
            barChartProps:{},
            pieChartProps:{},
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
            if(res.status == 200){
                this.success('操作成功')
                this.$emit('answer',res.data[0])
                this.$store.commit('setShowAnswerDetail',false);
                
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
            this.$store.commit('setChartView',!this.getChartView)
        },
        getRandomInt () {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        },
        dataExport(){
            
            let answerViewData = []
            let element = {}
            let content = this.postDetails.addData.content
            let index = 0
             
            element.标题 = `标题：${this.postDetails.addData.title}`
            answerViewData.push(element)
            element = {}
            element.说明 = `说明：${this.postDetails.addData.description}`
            answerViewData.push(element)
            element = {}
            element.起止时间 = `起止时间：${this.TimeView(this.postDetails.created_at)}至${this.TimeView(this.postDetails.addData.deadline)}`
            answerViewData.push(element)
            element = {}
            answerViewData.push(element)
            for(let i=0;i<content.singleContentDataArr.length;i++){
                element = {}
                index = index + i +1
                element.题目 = `${index}.${content.singleContentDataArr[i][0].title}（单选题）`
                element.选项 = []
                element.总数量 = []
                element.总占比 = []
                for(let j=1;j<content.singleContentDataArr[i].length;j++){
                    
                    element.选项.push(`${this.alphabet[j-1]}. ${content.singleContentDataArr[i][j].title}`)
                    element.总数量.push(content.singleContentDataArr[i][j].checkCnt?1:0)
                    element.总占比.push(`${(content.singleContentDataArr[i][j].checkCnt?content.singleContentDataArr[i][j].checkCnt:0/content.singleContentDataArr[i][0].allCnt)*100}%`)
                
                }
                answerViewData.push(element)
            }
            for(let i=0;i<content.multiContentDataArr.length;i++){
                element = {}
                index = index + i +1
                element.题目 = `${index}.${content.multiContentDataArr[i][0].title}（多选题）`
                element.选项 = []
                element.总数量 = []
                element.总占比 = []
                for(let j=1;j<content.multiContentDataArr[i].length;j++){
                    element.选项.push(`${this.alphabet[j-1]}. ${content.multiContentDataArr[i][j].title}`)
                    element.总数量.push(content.multiContentDataArr[i][j].checkCnt?1:0)
                    element.总占比.push(`${(content.multiContentDataArr[i][j].checkCnt?content.multiContentDataArr[i][j].checkCnt:0/content.multiContentDataArr[i][0].allCnt)*100}%`)
                }
                answerViewData.push(element)
            }
            for(let i=0;i<content.questionAnswerDataArr.length;i++){
                element = {}
                index = index + i +1
                element.题目 = `${index}.${content.questionAnswerDataArr[i][0].title}（解答题）`
                element.选项 = "/"
                element.总数量 = 0
                element.总占比 = "/"
                answerViewData.push(element)
            }
            for(let i=0;i<content.statisticsDataArr.length;i++){
                element = {}
                index = index + i +1
                element.题目 = `${index}.${content.statisticsDataArr[i][0].title}（统计题）`
                element.平均分 = `${content.statisticsDataArr[i][0].value/content.statisticsDataArr[i][0].cnt}`
                element.总分 = `${content.statisticsDataArr[i][0].value}`
                answerViewData.push(element)
            }
            for(let i=0;i<content.statisticsDataArr.length;i++){
                element = {}
                index = index + i + 1
                element.题目 = `${index}.${content.scoringQuestoinsDataArr[i][0].title}（评分题）`
                element.平均分 = `${content.scoringQuestoinsDataArr[i][0].value/content.scoringQuestoinsDataArr[i][0].cnt}`
                element.总分 = `${content.scoringQuestoinsDataArr[i][0].value}`
                answerViewData.push(element)
            }
            // this.dataForExcel = answerViewData
            let questionnaireForExcel = XLSX.utils.json_to_sheet(answerViewData,{skipHeader:true})
            let wb = XLSX.utils.book_new() //make Workbook of Excel
            XLSX.utils.book_append_sheet(wb, questionnaireForExcel, '问卷')
            // export Excel file
            XLSX.writeFile(wb, '问卷数据.xlsx') // name of the file is 'book.xlsx'
        },
        async addVoting(){
            let data = this.postDetails.addData.content.votingDataArr
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
             if(res.status == 200){
                this.success('操作成功')
                this.$emit('answer',res.data[0])
                this.$store.commit('setShowAnswerDetail',false);
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
    }
}
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>