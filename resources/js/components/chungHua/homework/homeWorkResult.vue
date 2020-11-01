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
                <div class="category-title"></div>
                <div class="common-praxis">
                    <div v-for="(questionData,i) in propsData.addData.questionDataArr" :key="i">
                        <div class="single" v-if="questionData.selQuestion == '单选题' || questionData.selQuestion == '多选题' || questionData.selQuestion == '填空题'">
                            <div class="title-view">
                                {{i+1}}.
                                <span>
                                    [ {{questionData.selQuestion}} ]
                                    <p v-html="propsData.addData.contentData"></p>
                                </span>
                            </div>
                            <div class="d-flex option-box" v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                <div class="es-item">
                                    <div class="es-item-left" :class="sentence.active ? 'text-color' : 'text-danger'">
                                        <span v-if="questionData.selQuestion == '单选题' || questionData.selQuestion == '多选题'">{{alphabet[j]}}.</span>
                                        <span v-else-if="questionData.selQuestion == '填空题'">第{{j+1}}空.</span>
                                        <span class="pl-2" v-html="sentence.contentData"></span>
                                    </div>
                                    <div class="es-item-right" :class="sentence.active ? 'text-color' : 'text-danger'">
                                        <span> 0人，0% </span>
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </div>
                            <div class="eye-readed" @click="showAnswer(questionData)">查看答案<Icon type="ios-eye-outline" /></div>
                        </div>
                        <div class="qa" v-else-if="questionData.selQuestion == '解答题'">
                            <div class="title-view">
                                {{i+1}}.
                                <span>
                                    [ {{questionData.selQuestion}} ]
                                    <p v-html="propsData.addData.contentData"></p>
                                </span>
                            </div>
                            <div class="es-item">
                                <div class="es-item-left">
                                    已批
                                </div>
                                <div class="es-item-right">
                                    0份，正确率0.0%
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
                            <div class="es-item">
                                <div class="es-item-left">
                                    待批
                                </div>
                                <div class="es-item-right">
                                    0份
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
                        </div>
                        <div class="judge" v-else-if="questionData.selQuestion == '判断题'">
                            <div class="title-view">
                                {{i+1}}.
                                <span>
                                    [ {{questionData.selQuestion}} ]
                                    <p v-html="propsData.addData.contentData"></p>
                                </span>
                            </div>
                            <div class="d-flex option-box" v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                <div class="es-item">
                                    <div class="es-item-left" :class="sentence.active ? 'text-color' : 'text-danger'">
                                        {{sentence.contentData}}
                                    </div>
                                    <div class="es-item-right">
                                        0人，0% 
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </div>
                            <div class="eye-readed" @click="showAnswer(questionData)">查看答案<Icon type="ios-eye-outline" /></div>
                        </div>
                        <div class="matching" v-else-if="questionData.selQuestion == '连线题'">
                            <div class="title-view">
                                {{i+1}}.
                                <span>
                                    [ {{questionData.selQuestion}} ]
                                    <p v-html="propsData.addData.contentData"></p>
                                </span>
                            </div>
                            <div class="matching-options">
                                <div class="matching-options-left">
                                    <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                        {{sentence.first.val}}
                                    </div>
                                </div>
                                <div class="matching-options-line">

                                </div>
                                <div class="matching-options-right">
                                    <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                        {{sentence.second.val}}
                                    </div>
                                </div>
                            </div>
                            <router-link :to="{path:currentPath.path,query:{questionType:'连线题',correctType:'答对'}}">
                                <div class="es-item">
                                    <div class="es-item-left">
                                        <div>答对</div>
                                    </div>
                                    <div class="es-item-right">
                                        <div>
                                            0人，0.0%
                                        </div>
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </router-link>
                            <router-link :to="{path:currentPath.path,query:{questionType:'连线题',correctType:'答错'}}">
                                <div class="es-item">
                                    <div class="es-item-left">
                                        答错
                                    </div>
                                    <div class="es-item-right">
                                        <div>
                                            0人，0.0%
                                        </div>
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </router-link>
                        </div>
                        <div class="text-sort" v-else-if="questionData.selQuestion == '文字排序题'">
                            <div class="title-view">
                                {{i+1}}.
                                <span>
                                    [ {{questionData.selQuestion}} ]
                                    <p v-html="propsData.addData.contentData"></p>
                                </span>
                            </div>
                            <div class="text-sort-options">
                                <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                    {{sentence.contentData.val}}
                                </div>
                            </div>
                            <router-link :to="{path:currentPath.path,query:{questionType:'文字排序题',correctType:'答对'}}">
                                <div class="es-item">
                                    <div class="es-item-left">
                                    <div>
                                        答对
                                        </div> 
                                    </div>
                                    <div class="es-item-right">
                                    <div>
                                        0人，0.0%
                                        </div> 
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </router-link>
                            <router-link :to="{path:currentPath.path,query:{questionType:'文字排序题',correctType:'答错'}}">
                                <div class="es-item">
                                    <div class="es-item-left">
                                    <div>
                                        答错
                                        </div> 
                                    </div>
                                    <div class="es-item-right">
                                    <div>
                                        0人，0.0%
                                        </div> 
                                        <Icon type="ios-arrow-forward" />
                                    </div>
                                </div>
                            </router-link>
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
            <div class="common-praxis">
                <div v-for="(questionData,i) in propsData.addData.questionDataArr" :key="i">
                    <div class="single" v-if="questionData.selQuestion == '单选题' ||  questionData.selQuestion == '多选题' || questionData.selQuestion == '填空题' || questionData.selQuestion == '判断题'">
                        <div class="title-view">
                            <span>
                                [ {{questionData.selQuestion}} ]
                                <p v-html="propsData.addData.contentData"></p>
                            </span>
                        </div>
                        <div class="d-flex option-box" v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                            <div class="es-item">
                                <div class="es-item-left">
                                    <span v-if="questionData.selQuestion == '单选题' || questionData.selQuestion == '多选题'">{{alphabet[j]}}.</span>
                                    <span v-else-if="questionData.selQuestion == '填空题'">第{{j+1}}空.</span>
                                    <span class="pl-2" v-html="sentence.contentData"></span>
                                    <span v-if="questionData.selQuestion == '判断题'">{{sentence.contentData}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="category-title" v-if="questionData.selQuestion == '单选题' || questionData.selQuestion == '多选题' || questionData.selQuestion == '填空题' || questionData.selQuestion == '判断题'">正确答案</div>
                        <div class="anal-answer" v-if="questionData.selQuestion == '单选题' || questionData.selQuestion == '多选题'">{{questionData.answerData}}</div>
                        <div class="anal-answer" v-if="questionData.selQuestion == '填空题'">
                            <div class="d-flex option-box" v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                第{{j+1}}空:<span class="pl-2" v-html="sentence.contentData"></span><br/>
                            </div>
                        </div>
                        <div v-if=" questionData.selQuestion == '判断题'">{{questionData.answerData}}</div>
                        <div class="category-title">解析</div>
                        <div class="anal-content" v-html="questionData.analysisData"></div>
                    </div>
                    <div class="matching" v-if="questionData.selQuestion == '连线题'">
                        <div class="title-view">
                            <span>
                                [ {{questionData.selQuestion}} ]
                                <p v-html="propsData.addData.contentData"></p>
                            </span>
                        </div>
                        <div class="matching-options">
                            <div class="matching-options-left">
                                <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                    {{sentence.first.val}}
                                </div>
                            </div>
                            <div class="matching-options-line"></div>
                            <div class="matching-options-right">
                                <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                    {{sentence.second.val}}
                                </div>
                            </div>
                        </div>
                        <div class="category-title">正确答案</div>
                        <div style="margin-top: 10px;padding: 0px 10px;">
                            <div class="matching-options">
                                <div class="matching-options-left">
                                    <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                        {{sentence.first}}
                                    </div>
                                </div>
                                <div class="matching-options-line">

                                </div>
                                <div class="matching-options-right">
                                    <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                        {{sentence.second}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="analy-answer">

                        </div>
                        <div class="category-title">解析</div>
                        <div class="anal-content" v-html="questionData.analysisData"></div>
                    </div>
                    <div class="text-sort" v-if="questionData.selQuestion == '文字排序题'">
                        <div class="title-view">
                            <span>
                                [ {{questionData.selQuestion}} ]
                                <p v-html="propsData.addData.contentData"></p>
                            </span>
                        </div>
                        <div class="text-sort-options">
                            <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                {{sentence.contentData.val}}
                            </div>
                        </div>
                        <div class="category-title">
                            正确答案
                        </div>
                        <div class="analy-options">
                            <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                {{sentence.contentData.val}}
                            </div>
                        </div>
                        <div class="category-title">解析</div>
                        <div class="analy-content" v-html="questionData.analysisData"></div>
                    </div>
                </div>
            </div>
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
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        propsData:{
            handler(val){
                console.log(val)
            },
            deep:true
        }
    },
    methods:{
        showAnswer(questionData){
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
            let answerData = questionData.answerData
            console.log(answerData)
            if(Array.isArray(answerData) == true){
                for(let i=0;i<answerData.length;i++){
                    let index = this.alphabet.indexOf(answerData[i])
                    console.log(index)
                    this.activeAnswer(index,questionData)
                }
            }else{
                let index = this.alphabet.indexOf(answerData)
                this.activeAnswer(index,questionData)
            }
            console.log(questionData.questionDataArr)
        },
        activeAnswer(index,questionData){
            // for(let i=0;i<questionData.questionDataArr.length;i++){
                // if(i == index){
                    if(questionData.questionDataArr[index].active == undefined){
                        this.$set(questionData.questionDataArr[index],'active',true)
                    }else if(questionData.questionDataArr[index].active == true){
                        questionData.questionDataArr[index].active = false
                    }else if(questionData.questionDataArr[index].active == false){
                        questionData.questionDataArr[index].active = true
                    }else{
                        questionData.questionDataArr[index].active == undefined
                    }
                // }else{
                //     if(questionData.questionDataArr[i].disActive == undefined){
                //         this.$set(questionData.questionDataArr[i],'disActive',true)
                //     }else if(questionData.questionDataArr[i].disActive == true){
                //         questionData.questionDataArr[i].disActive = false
                //     }else if(questionData.questionDataArr[i].disActive == false){
                //         questionData.questionDataArr[i].disActive = true
                //     }else{
                //         questionData.questionDataArr[i].disActive == undefined
                //     }
                // }
            // }
        }
    }
}
</script>

<style>

</style>