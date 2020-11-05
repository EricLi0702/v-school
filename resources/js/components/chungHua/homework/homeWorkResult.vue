<template>
    <div>
        <div v-if="currentPath.query.questionType == undefined">
            <div class="es-item">
                <div class="es-item-left">
                    平均正确率 0.0%
                </div>
                <div class="es-item-right">
                    0/4 已答
                </div>
            </div>
            <div class="praxis-review-box">
                
                <div class="common-praxis">
                    <div v-for="(questionData,i) in propsData.addData.addDataList" :key="i">
                        <div class="category-title"></div>
                        <div class="single" v-if="questionData.selQuestion == '单选题'">
                            <div class="title-view">
                                {{i+1}}.[{{questionData.selQuestion}}]
                                <br />
                                <p v-html="questionData.contentData"></p>
                            </div>
                            <div class="option-box" v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                <div class="es-item" :class="{'text-color':sentence.active}">
                                    <div class="es-item-left">
                                        {{alphabet[i]}}.
                                        <p v-html="sentence.contentData"></p>
                                    </div>
                                    <div class="es-item-right">
                                        0人，0%
                                        <Icon type="ios-arrow-forward"></Icon>
                                    </div>
                                </div>
                            </div>
                            <div class="eye-readed" @click="showAnswer(questionData)">
                                查看答案
                                <Icon type="ios-eye-outline" />
                            </div>
                        </div>                       
                        <router-link :to="{path:currentPath.path,query:{questionType:'answerDetails'}}">
                            <div class="analy-enter">查看解析</div>
                        </router-link>
                        <div class="single-readCount">已答0</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.questionType == 'answerDetails'">
            
        </div>
        <div v-else-if="currentPath.query.questionType == '连线题' || currentPath.query.questionType == '文字排序题'">
            <notConnect></notConnect>
        </div>
    </div>
</template>

<script>
import notConnect from '../../pages/notConnect'
export default {
    props:['propsData'],
    components:{
        notConnect
    },
    data(){
        return{

        }
    },
    created(){
        console.log(this.propsData)
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        propsData:{
            handler(val){
                console.log('++++')
                console.log(val)
            },
            deep:true
        }
    },
    methods:{
        showAnswer(questionData){
            // debugger
            if(questionData.selQuestion == "单选题"){
                let answerData = questionData.answerData
                let index = this.alphabet.indexOf(answerData)
                this.activeAnswer(index,questionData)
                return
            }
            if(questionData.selQuestion == "多选题"){
                let answerData = questionData.answerDataArr
                for(let i=0;i<answerData.length;i++){
                    let index = this.alphabet.indexOf(answerData[i])
                    this.activeAnswer(index,questionData)
                }
                return
            }
            if(questionData.selQuestion == '填空题'){
                for(let i=0;i<questionData.questionDataArr.length;i++){
                    this.activeAnswer(i,questionData)
                }
                return
            }
            if(questionData.selQuestion == '判断题'){
                let answerData = questionData.answerData
                let index = this.alphabet.indexOf(answerData)
                this.activeAnswer(index,questionData)
                return
            }
            console.log(questionData.questionDataArr)
        },
        activeAnswer(index,questionData){
            if(questionData.questionDataArr[index].active == undefined){
                this.$set(questionData.questionDataArr[index],'active',true)
            }else if(questionData.questionDataArr[index].active == true){
                questionData.questionDataArr[index].active = false
            }else if(questionData.questionDataArr[index].active == false){
                questionData.questionDataArr[index].active = true
            }else{
                questionData.questionDataArr[index].active == undefined
            }
        },
        // viewAnswer(questionData){
        //     if(questionData.selQuestion == "单选题"){
        //         let answerData = questionData.answerData
        //         let index = this.alphabet.indexOf(answerData)
        //         this.activeAnswer(index,questionData)
        //         return
        //     } 
        // }
    }
}
</script>

<style>

</style>