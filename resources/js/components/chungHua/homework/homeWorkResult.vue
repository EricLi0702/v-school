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
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br />
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="option-box" v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
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
                        <div class="multi-section" v-else-if="questionData.selQuestion == '多选题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br />
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="option-box" v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
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
                        <div class="fill" v-else-if="questionData.selQuestion == '填空题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="option-box" v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                    <div class="es-item-left">
                                        第{{i+1}}空. {{sentence.contentData}}
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
                        <div class="qa" v-else-if="questionData.selQuestion == '解答题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="es-item">
                                <div class="es-item-left">
                                    已批
                                </div>
                                <div class="es-item-right">
                                    0份，正确率0.0%
                                    <Icon type="ios-arrow-forward"></Icon>
                                </div>
                            </div>
                            <div class="es-item">
                                <div class="es-item-left">
                                    待批
                                </div>
                                <div class="es-item-right">
                                    0份
                                    <Icon type="ios-arrow-forward"></Icon>
                                </div>
                            </div>
                        </div>
                        <div class="judge" v-else-if="questionData.selQuestion == '判断题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="es-item" :class="{'text-color':questionData.activeA,'text-danger':questionData.deactiveA}">
                                <div class="es-item-left">
                                    正确
                                </div>
                                <div class="es-item-right">
                                    0人，0%
                                    <Icon type="ios-arrow-forward"></Icon>
                                </div>
                            </div>
                            <div class="es-item" :class="{'text-color':questionData.activeB,'text-danger':questionData.deactiveB}">
                                <div class="es-item-left">
                                    错误
                                </div>
                                <div class="es-item-right">
                                    0人，0%
                                    <Icon type="ios-arrow-forward"></Icon>
                                </div>
                            </div>
                            <div class="eye-readed" @click="showAnswer(questionData)">
                                查看答案
                                <Icon type="ios-eye-outline" />
                            </div>
                        </div>
                        <div class="compose" v-else-if="questionData.selQuestion == '综合题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="border mb-1" v-for="(questionDataArr,j) in questionData.questionDataArr" :key="j">
                                <div class="single" v-if="questionDataArr.selQuestion == '单选题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br />
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                        <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
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
                                    <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div>
                                </div>
                                <div class="multi-section" v-else-if="questionDataArr.selQuestion == '多选题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br />
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                        <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
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
                                    <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div>
                                </div>
                                <div class="fill" v-else-if="questionDataArr.selQuestion == '填空题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                        <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                            <div class="es-item-left">
                                                第{{i+1}}空. {{sentence.contentData}}
                                            </div>
                                            <div class="es-item-right">
                                                0人，0%
                                                <Icon type="ios-arrow-forward"></Icon>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div>
                                </div>
                                <div class="qa" v-else-if="questionDataArr.selQuestion == '解答题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="es-item">
                                        <div class="es-item-left">
                                            已批
                                        </div>
                                        <div class="es-item-right">
                                            0份，正确率0.0%
                                            <Icon type="ios-arrow-forward"></Icon>
                                        </div>
                                    </div>
                                    <div class="es-item">
                                        <div class="es-item-left">
                                            待批
                                        </div>
                                        <div class="es-item-right">
                                            0份
                                            <Icon type="ios-arrow-forward"></Icon>
                                        </div>
                                    </div>
                                </div>
                                <div class="judge" v-else-if="questionDataArr.selQuestion == '判断题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="es-item" :class="{'text-color':questionDataArr.activeA,'text-danger':questionDataArr.deactiveA}">
                                        <div class="es-item-left">
                                            正确
                                        </div>
                                        <div class="es-item-right">
                                            0人，0%
                                            <Icon type="ios-arrow-forward"></Icon>
                                        </div>
                                    </div>
                                    <div class="es-item" :class="{'text-color':questionDataArr.activeB,'text-danger':questionDataArr.deactiveB}">
                                        <div class="es-item-left">
                                            错误
                                        </div>
                                        <div class="es-item-right">
                                            0人，0%
                                            <Icon type="ios-arrow-forward"></Icon>
                                        </div>
                                    </div>
                                    <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="matching" v-else-if="questionData.selQuestion == '连线题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
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
            if(questionData.selQuestion == "单选题"){//1
                let answerData = questionData.answerData
                let index = this.alphabet.indexOf(answerData)
                this.activeAnswer(index,questionData.questionDataArr)
            }
            if(questionData.selQuestion == "多选题"){//2
                let answerData = questionData.answerDataArr
                for(let i=0;i<answerData.length;i++){
                    let index = this.alphabet.indexOf(answerData[i])
                    this.activeAnswer(index,questionData.questionDataArr)
                }
            }
            if(questionData.selQuestion == '填空题'){//3
                for(let i=0;i<questionData.questionDataArr.length;i++){
                    this.activeAnswer(i,questionData.questionDataArr)
                }
            }
            if(questionData.selQuestion == '判断题'){//5
                if(questionData.answerData == 'A'){
                    if(questionData.activeA == undefined){
                        this.$set(questionData,'activeA',true)
                    }else if(questionData.activeA == true){
                        questionData.activeA = false
                    }else if(questionData.activeA == false){
                        questionData.activeA = true
                    }else{
                        questionData.activeA = undefined
                    }
                    if(questionData.deactiveB == undefined){
                        this.$set(questionData,'deactiveB',true)
                    }else if(questionData.deactiveB == true){
                        questionData.deactiveB = false
                    }else if(questionData.deactiveB == false){
                        questionData.deactiveB = true
                    }else{
                        questionData.deactiveB = undefined
                    }
                }else{
                    if(questionData.activeB == undefined){
                        this.$set(questionData,'activeB',true)
                    }else if(questionData.activeB == true){
                        questionData.activeB = false
                    }else if(questionData.activeB == false){
                        questionData.activeB = true
                    }else{
                        questionData.activeB = undefined
                    }
                    if(questionData.deactiveA == undefined){
                        this.$set(questionData,'deactiveA',true)
                    }else if(questionData.deactiveA == true){
                        questionData.deactiveA = false
                    }else if(questionData.deactiveA == false){
                        questionData.deactiveA = true
                    }else{
                        questionData.deactiveA = undefined
                    }
                }
            }
            if(questionData.selQuestion == '判断题'){
                let answerData = questionData.answerData
                let index = this.alphabet.indexOf(answerData)
                this.activeAnswer(index,questionData.questionDataArr)
            }
            this.deactiveAnswer(questionData.questionDataArr)
            console.log(questionData.questionDataArr)
        },
        activeAnswer(index,questionDataArr){
            if(questionDataArr[index].active == undefined){
                this.$set(questionDataArr[index],'active',true)
            }else if(questionDataArr[index].active == true){
                questionDataArr[index].active = false
            }else if(questionDataArr[index].active == false){
                questionDataArr[index].active = true
            }else{
                questionDataArr[index].active == undefined
            }
        },
        deactiveAnswer(questionDataArr){
            for(let i=0;i<questionDataArr.length;i++){
                if(questionDataArr[i].active == undefined){
                    if(questionDataArr[i].deactive == undefined){
                        this.$set(questionDataArr[i],'deactive',true)
                    }else if(questionDataArr[i].deactive == true){
                        questionDataArr[i].deactive = false
                    }else if(questionDataArr[i].deactive == false){
                        questionDataArr[i].deactive = true
                    }else{
                        questionDataArr[i].deactive = undefined
                    }
                }
            }
        }
    }
}
</script>

<style>

</style>