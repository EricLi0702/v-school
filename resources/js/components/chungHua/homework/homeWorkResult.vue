<template>
    <div>
        <div v-if="$store.state.user.role.roleName != '学生'">
            <div v-if="currentPath.query.questionType == undefined">
                <div class="vx-item">
                    <div class="vx-item-left">
                        平均正确率 0.0%
                    </div>
                    <div class="vx-item-right">
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
                                    <div class="es-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}" @click="showAnswerUsers(sentence)">
                                        <div class="es-item-left"  :class="{'text-color': sentence.answer}">
                                            {{alphabet[i]}}.
                                            <p v-html="sentence.contentData"></p>
                                        </div>
                                        <div class="es-item-right">
                                            <span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span> 人，
                                            <span v-if="questionData.allCnt">{{parseFloat((sentence.answerCnt?sentence.answerCnt:0)/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
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
                                    <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}" @click="showAnswerUsers(sentence)">
                                        <div class="vx-item-left"  :class="{'text-color': sentence.answer}">
                                            {{alphabet[i]}}.
                                            <p v-html="sentence.contentData"></p>
                                        </div>
                                        <div class="vx-item-right">
                                            <span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span>人，
                                            <span v-if="questionData.allCnt">{{parseFloat((sentence.answerCnt?sentence.answerCnt:0)/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
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
                                    <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                        <div class="vx-item-left">
                                            第{{i+1}}空. {{sentence.contentData}}
                                        </div>
                                        <div class="vx-item-right">
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
                                <div class="vx-item">
                                    <div class="vx-item-left">
                                        已批
                                    </div>
                                    <div class="vx-item-right">
                                        0份，正确率0.0%
                                        <Icon type="ios-arrow-forward"></Icon>
                                    </div>
                                </div>
                                <div class="vx-item">
                                    <div class="vx-item-left">
                                        待批
                                    </div>
                                    <div class="vx-item-right">
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
                                <div class="vx-item" :class="{'text-color':questionData.activeA,'text-danger':questionData.deactiveA}">
                                    <div class="vx-item-left">
                                        正确
                                    </div>
                                    <div class="vx-item-right">
                                        <span v-if="questionData.answerACnt">{{questionData.answerACnt}}</span><span v-else>0</span> 人，
                                        <span v-if="questionData.allCnt">{{parseFloat((questionData.answerACnt?questionData.answerACnt:0)/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
                                        <Icon type="ios-arrow-forward"></Icon>
                                    </div>
                                </div>
                                <div class="vx-item" :class="{'text-color':questionData.activeB,'text-danger':questionData.deactiveB}">
                                    <div class="vx-item-left">
                                        错误
                                    </div>
                                    <div class="vx-item-right">
                                        <span v-if="questionData.answerBCnt">{{questionData.answerBCnt}}</span><span v-else>0</span> 人，
                                        <span v-if="questionData.allCnt">{{parseFloat((questionData.answerBCnt?questionData.answerBCnt:0)/questionData.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
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
                                                    <span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span> 人,
                                                    <span v-if="questionDataArr.allCnt">{{parseFloat((sentence.answerCnt?sentence.answerCnt:0)/questionDataArr.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
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
                                            <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                                <div class="vx-item-left">
                                                    {{alphabet[i]}}.
                                                    <p v-html="sentence.contentData"></p>
                                                </div>
                                                <div class="vx-item-right">
                                                    <span v-if="sentence.answerCnt">{{sentence.answerCnt}}</span><span v-else>0</span> 人，
                                                    <span v-if="questionDataArr.allCnt">{{parseFloat((sentence.answerCnt?sentence.answerCnt:0)/questionDataArr.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
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
                                            <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                                <div class="vx-item-left">
                                                    第{{i+1}}空. {{sentence.contentData}}
                                                </div>
                                                <div class="vx-item-right">
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
                                        <div class="vx-item">
                                            <div class="vx-item-left">
                                                已批
                                            </div>
                                            <div class="vx-item-right">
                                                0份，正确率0.0%
                                                <Icon type="ios-arrow-forward"></Icon>
                                            </div>
                                        </div>
                                        <div class="vx-item">
                                            <div class="vx-item-left">
                                                待批
                                            </div>
                                            <div class="vx-item-right">
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
                                        <div class="vx-item" :class="{'text-color':questionDataArr.activeA,'text-danger':questionDataArr.deactiveA}">
                                            <div class="vx-item-left">
                                                正确
                                            </div>
                                            <div class="vx-item-right">
                                                <span v-if="questionDataArr.answerACnt">{{questionDataArr.answerACnt}}</span><span v-else>0</span> 人，
                                                <span v-if="questionDataArr.allCnt">{{parseFloat((questionDataArr.answerACnt?questionDataArr.answerACnt:0)/questionDataArr.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
                                                <Icon type="ios-arrow-forward"></Icon>
                                            </div>
                                        </div>
                                        <div class="vx-item" :class="{'text-color':questionDataArr.activeB,'text-danger':questionDataArr.deactiveB}">
                                            <div class="vx-item-left">
                                                错误
                                            </div>
                                            <div class="vx-item-right">
                                                <span v-if="questionDataArr.answerBCnt">{{questionDataArr.answerBCnt}}</span><span v-else>0</span>人，
                                                <span v-if="questionDataArr.allCnt">{{parseFloat((questionDataArr.answerBCnt?questionDataArr.answerBCnt:0)/questionDataArr.allCnt*100).toFixed(2)}}</span><span v-else>0</span> %
                                                <Icon type="ios-arrow-forward"></Icon>
                                            </div>
                                        </div>
                                        <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                            查看答案
                                            <Icon type="ios-eye-outline" />
                                        </div>
                                    </div>
                                    <div class="matching" v-else-if="questionDataArr.selQuestion == '连线题'">
                                        <div class="title-view d-block">
                                            <div>
                                                {{j+1}}.[{{questionDataArr.selQuestion}}]
                                            </div>
                                            <br/>
                                            <div v-html="questionDataArr.contentData"></div>
                                        </div>
                                        <div class="matching-options">
                                            <div class="matching-options-left">
                                                <div v-for="(sentence,k) in questionDataArr.questionDataArr" :key="k">
                                                    {{sentence.first.val}}
                                                </div>
                                            </div>
                                            <div class="matching-options-line">

                                            </div>
                                            <div class="matching-options-right">
                                                <div v-for="(sentence,k) in questionData.questionDataArr" :key="k">
                                                    {{sentence.second.val}}
                                                </div>
                                            </div>
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
                                <div class="matching-options">
                                    <div class="matching-options-left">
                                        <div v-for="(sentence,a) in questionData.questionDataArr" :key="a">
                                            {{sentence.first.val}}
                                        </div>
                                    </div>
                                    <div class="matching-options-line">

                                    </div>
                                    <div class="matching-options-right">
                                        <div v-for="(sentence,b) in questionData.questionDataArr" :key="b">
                                            {{sentence.second.val}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-sort" v-else-if="questionData.selQuestion == '文字排序题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionData.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionData.contentData"></div>
                                </div>
                                <div class="text-sort-options">
                                    <div v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                        {{sentence.contentData.val}}
                                    </div>
                                </div>
                                <div class="vx-item">
                                    <div class="vx-item-left">答对</div>
                                    <div class="vx-item-right">
                                        0人，0.0%
                                        <Icon type="ios-arrow-forward"></Icon>
                                    </div>
                                </div>
                                <div class="vx-item">
                                    <div class="vx-item-left">答错</div>
                                    <div class="vx-item-right">
                                        0人，0.0%
                                        <Icon type="ios-arrow-forward"></Icon>
                                    </div>
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
                            <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                <div class="vx-item-left">
                                    {{alphabet[i]}}.
                                    <p v-html="sentence.contentData"></p>
                                </div>
                            </div>
                        </div>
                        <div class="category-title">正确答案</div>
                        <div>{{questionData.answerData}}</div>
                        <div class="category-title">解析</div>
                        <div v-html="questionData.analysisData"></div>
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
                            <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                <div class="vx-item-left">
                                    {{alphabet[i]}}.
                                    <p v-html="sentence.contentData"></p>
                                </div>
                            </div>
                        </div>
                        <div class="category-title">正确答案</div>
                        <div>{{questionData.answerDataArr}}</div>
                        <div class="category-title">解析</div>
                        <div v-html="questionData.analysisData"></div>
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
                            <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                <div class="vx-item-left">
                                    第{{i+1}}空. {{sentence.contentData}}
                                </div>
                            </div>
                        </div>
                        <div class="category-title">
                            正确答案
                        </div>
                        <div v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                            <div>第{{i+1}}空. {{sentence.contentData}}</div>
                        </div>
                        <div class="category-title">解析</div>
                        <div v-html="questionData.analysisData"></div>
                    </div> 
                    <div class="qa" v-else-if="questionData.selQuestion == '解答题'">
                        <div class="title-view d-block">
                            <div>
                                {{i+1}}.[{{questionData.selQuestion}}]
                            </div>
                            <br/>
                            <div v-html="questionData.contentData"></div>
                        </div>
                        <div class="category-title">解析</div>
                        <div v-html="questionData.analysisData"></div>
                    </div>
                    <div class="judge" v-else-if="questionData.selQuestion == '判断题'">
                        <div class="title-view d-block">
                            <div>
                                {{i+1}}.[{{questionData.selQuestion}}]
                            </div>
                            <br/>
                            <div v-html="questionData.contentData"></div>
                        </div>
                        <div class="vx-item">
                            正确
                        </div>
                        <div class="vx-item">
                            错误
                        </div>
                        <div class="category-title">正确答案</div>
                        <div>{{questionData.answerData}}</div>
                        <div class="category-title">解析</div>
                        <div v-html="questionData.analysisData"></div>
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
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br />
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                    <div class="vx-item">
                                        <div class="vx-item-left">
                                            {{alphabet[i]}}.
                                            <p v-html="sentence.contentData"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="category-title">正确答案</div>
                                <div>{{questionDataArr.answerData}}</div>
                                <div class="category-title">解析</div>
                                <div v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="multi-section" v-else-if="questionDataArr.selQuestion == '多选题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br />
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                    <div class="vx-item">
                                        <div class="vx-item-left">
                                            {{alphabet[i]}}.
                                            <p v-html="sentence.contentData"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="category-title">正确答案</div>
                                <div>{{questionDataArr.answerDataArr}}</div>
                                <div class="category-title">解析</div>
                                <div v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="fill" v-else-if="questionDataArr.selQuestion == '填空题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="option-box" v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                    <div class="vx-item">
                                        <div class="vx-item-left">
                                            第{{i+1}}空. {{sentence.contentData}}
                                        </div>
                                    </div>
                                </div>
                                <div class="category-title">
                                    正确答案
                                </div>
                                <div v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                    <div>第{{i+1}}空. {{sentence.contentData}}</div>
                                </div>
                                <div class="category-title">解析</div>
                                <div v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="qa" v-else-if="questionDataArr.selQuestion == '解答题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="category-title">解析</div>
                                <div v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="judge" v-else-if="questionDataArr.selQuestion == '判断题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="vx-item">
                                    正确
                                </div>
                                <div class="vx-item">
                                    错误
                                </div>
                                <div class="category-title">正确答案</div>
                                <div>{{questionDataArr.answerData}}</div>
                                <div class="category-title">解析</div>
                                <div v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="matching" v-else-if="questionDataArr.selQuestion == '连线题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{j+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="matching-options">
                                    <div class="matching-options-left">
                                        <div v-for="(sentence,n) in questionDataArr.questionDataArr" :key="n">
                                            {{sentence.first.val}}
                                        </div>
                                    </div>
                                    <div class="matching-options-line">

                                    </div>
                                    <div class="matching-options-right">
                                        <div v-for="(sentence,o) in questionDataArr.questionDataArr" :key="o">
                                            {{sentence.second.val}}
                                        </div>
                                    </div>
                                </div>
                                <div class="category-title">正确答案</div>
                                <div class="matching-options">
                                    <div class="matching-options-left">
                                        <div v-for="(sentence,p) in questionDataArr.questionDataArr" :key="p">
                                            {{sentence.first.val}}
                                        </div>
                                    </div>
                                    <div class="matching-options-line">

                                    </div>
                                    <div class="matching-options-right">
                                        <div v-for="(sentence,k) in questionDataArr.questionDataArr" :key="k">
                                            {{sentence.second.val}}
                                        </div>
                                    </div>
                                </div>
                                <div class="category-title">解析</div>
                                <div class="analy-content" v-html="questionDataArr.analysisData"></div>
                            </div>
                            <div class="text-sort" v-else-if="questionDataArr.selQuestion == '文字排序题'">
                                <div class="title-view d-block">
                                    <div>
                                        {{i+1}}.[{{questionDataArr.selQuestion}}]
                                    </div>
                                    <br/>
                                    <div v-html="questionDataArr.contentData"></div>
                                </div>
                                <div class="text-sort-options">
                                    <div v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                        {{sentence.contentData.val}}
                                    </div>
                                </div>
                                <div class="category-title">正确答案</div>
                                <div class="text-sort-options">
                                    <div v-for="(sentence,j) in questionDataArr.questionDataArr" :key="j">
                                        {{sentence.contentData.val}}
                                    </div>
                                </div>
                                <div class="category-title">解析</div>
                                <div class="analy-content" v-html="questionDataArr.analysisData"></div>
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
                        <div class="matching-options">
                            <div class="matching-options-left">
                                <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                    {{sentence.first.val}}
                                </div>
                            </div>
                            <div class="matching-options-line">

                            </div>
                            <div class="matching-options-right">
                                <div v-for="(sentence,k) in questionData.questionDataArr" :key="k">
                                    {{sentence.second.val}}
                                </div>
                            </div>
                        </div>
                        <div class="category-title">正确答案</div>
                        <div class="matching-options">
                            <div class="matching-options-left">
                                <div v-for="(sentence,l) in questionData.questionDataArr" :key="l">
                                    {{sentence.first.val}}
                                </div>
                            </div>
                            <div class="matching-options-line">

                            </div>
                            <div class="matching-options-right">
                                <div v-for="(sentence,m) in questionData.questionDataArr" :key="m">
                                    {{sentence.second.val}}
                                </div>
                            </div>
                        </div>
                        <div class="category-title">解析</div>
                        <div class="analy-content" v-html="questionData.analysisData"></div>
                    </div>
                    <div class="text-sort" v-else-if="questionData.selQuestion == '文字排序题'">
                        <div class="title-view d-block">
                            <div>
                                {{i+1}}.[{{questionData.selQuestion}}]
                            </div>
                            <br/>
                            <div v-html="questionData.contentData"></div>
                        </div>
                        <div class="text-sort-options">
                            <div v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                {{sentence.contentData.val}}
                            </div>
                        </div>
                        <div class="category-title">正确答案</div>
                        <div class="text-sort-options">
                            <div v-for="(sentence,j) in questionData.questionDataArr" :key="j">
                                {{sentence.contentData.val}}
                            </div>
                        </div>
                        <div class="category-title">解析</div>
                        <div class="analy-content" v-html="questionData.analysisData"></div>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.questionType == '连线题' || currentPath.query.questionType == '文字排序题'">
                <notConnect></notConnect>
            </div>
            <div v-else-if="currentPath.query.questionType == 'answerUsers'">
                <div class="vx-item" v-for="user in currentPath.query.userList" :key="user.id" v-if="currentPath.query.userList.length>0">
                    <div class="vx-item-left">
                        {{user.name}}
                    </div>
                    <div class="vx-item-right">
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
                <div class="vx-item" v-else>
                    无
                </div>
            </div>
        </div>
        <div  class="praxis-review-box" v-else>
            <div class="common-praxis">
                <div v-if="currentPath.query.questionType == undefined">
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
                                <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}" @click="selSingleAnswer(questionData.questionDataArr,sentence,i)">
                                    <div class="vx-item-left">
                                        {{alphabet[i]}}.
                                        <p v-html="sentence.contentData"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="eye-readed" @click="showAnswer(questionData)">
                                查看答案
                                <Icon type="ios-eye-outline" />
                            </div> -->
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
                                <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}" @click="selMultiAnswer(sentence)">
                                    <div class="vx-item-left" >
                                        {{alphabet[i]}}.
                                        <p v-html="sentence.contentData"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="eye-readed" @click="showAnswer(questionData)">
                                查看答案
                                <Icon type="ios-eye-outline" />
                            </div> -->
                        </div>
                        <div class="qa" v-else-if="questionData.selQuestion == '解答题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <textarea name="" id="" v-model="questionData.studentAnswer" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                        </div>
                        <div class="judge" v-else-if="questionData.selQuestion == '判断题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="vx-item" :class="{'text-color':questionData.activeA,'text-danger':questionData.deactiveA,'active-answer':questionData.answerA}" @click="selJudgeAnswer(questionData,'A')">
                                <div class="vx-item-left">
                                    正确
                                </div>
                            </div>
                            <div class="vx-item" :class="{'text-color':questionData.activeB,'text-danger':questionData.deactiveB,'active-answer':questionData.answerB}" @click="selJudgeAnswer(questionData,'B')">
                                <div class="vx-item-left">
                                    错误
                                </div>
                            </div>
                            <!-- <div class="eye-readed" @click="showAnswer(questionData)">
                                查看答案
                                <Icon type="ios-eye-outline" />
                            </div> -->
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
                                        <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}"  @click="selSingleAnswer(questionDataArr.questionDataArr,sentence,i)">
                                            <div class="vx-item-left">
                                                {{alphabet[i]}}.
                                                <p v-html="sentence.contentData"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div> -->
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
                                        <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}"  @click="selMultiAnswer(sentence)">
                                            <div class="vx-item-left">
                                                {{alphabet[i]}}.
                                                <p v-html="sentence.contentData"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div> -->
                                </div>
                                <!-- <div class="fill" v-else-if="questionDataArr.selQuestion == '填空题'">
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
                                </div> -->
                                <div class="qa" v-else-if="questionDataArr.selQuestion == '解答题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <textarea name="" id="" v-model="questionDataArr.studentAnswer" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                                </div>
                                <div class="judge" v-else-if="questionDataArr.selQuestion == '判断题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="vx-item" :class="{'text-color':questionDataArr.activeA,'text-danger':questionDataArr.deactiveA,'active-answer':questionDataArr.answerA}" @click="selJudgeAnswer(questionDataArr,'A')">
                                        <div class="vx-item-left">
                                            正确
                                        </div>
                                    </div>
                                    <div class="vx-item" :class="{'text-color':questionDataArr.activeB,'text-danger':questionDataArr.deactiveB,'active-answer':questionDataArr.answerB}" @click="selJudgeAnswer(questionDataArr,'B')">
                                        <div class="vx-item-left">
                                            错误
                                        </div>
                                    </div>
                                    <!-- <div class="eye-readed" @click="showAnswer(questionDataArr)">
                                        查看答案
                                        <Icon type="ios-eye-outline" />
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <router-link :to="{path:currentPath.path,query:{questionType:'answerDetails'}}" v-if="isAnswered">
                        <div class="analy-enter es-item mt-3">查看解析</div>
                    </router-link>
                </div>
                <div v-else-if="currentPath.query.questionType == 'answerDetails'">
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
                                <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}">
                                    <div class="vx-item-left">
                                        {{alphabet[i]}}.
                                        <p v-html="sentence.contentData"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="category-title">正确答案</div>
                            <div>{{questionData.answerData}}</div>
                            <div class="category-title">解析</div>
                            <div v-html="questionData.analysisData"></div>
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
                                <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}">
                                    <div class="vx-item-left">
                                        {{alphabet[i]}}.
                                        <p v-html="sentence.contentData"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="category-title">正确答案</div>
                            <div>{{questionData.answerDataArr}}</div>
                            <div class="category-title">解析</div>
                            <div v-html="questionData.analysisData"></div>
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
                                <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive}">
                                    <div class="vx-item-left">
                                        第{{i+1}}空. {{sentence.contentData}}
                                    </div>
                                </div>
                            </div>
                            <div class="category-title">
                                正确答案
                            </div>
                            <div v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                <div>第{{i+1}}空. {{sentence.contentData}}</div>
                            </div>
                            <div class="category-title">解析</div>
                            <div v-html="questionData.analysisData"></div>
                        </div> 
                        <div class="qa" v-else-if="questionData.selQuestion == '解答题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="category-title">解析</div>
                            <div v-html="questionData.analysisData"></div>
                        </div>
                        <div class="judge" v-else-if="questionData.selQuestion == '判断题'">
                            <div class="title-view d-block">
                                <div>
                                    {{i+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="vx-item" :class="{'active-answer':questionData.answerA}">
                                正确
                            </div>
                            <div class="vx-item" :class="{'active-answer':questionData.answerB}">
                                错误
                            </div>
                            <div class="category-title">正确答案</div>
                            <div>{{questionData.answerData}}</div>
                            <div class="category-title">解析</div>
                            <div v-html="questionData.analysisData"></div>
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
                                        <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}">
                                            <div class="vx-item-left">
                                                {{alphabet[i]}}.
                                                <p v-html="sentence.contentData"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-title">正确答案</div>
                                    <div>{{questionDataArr.answerData}}</div>
                                    <div class="category-title">解析</div>
                                    <div v-html="questionDataArr.analysisData"></div>
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
                                        <div class="vx-item" :class="{'text-color':sentence.active,'text-danger':sentence.deactive,'active-answer': sentence.answer}">
                                            <div class="vx-item-left">
                                                {{alphabet[i]}}.
                                                <p v-html="sentence.contentData"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-title">正确答案</div>
                                    <div>{{questionDataArr.answerDataArr}}</div>
                                    <div class="category-title">解析</div>
                                    <div v-html="questionDataArr.analysisData"></div>
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
                                        <div class="vx-item">
                                            <div class="vx-item-left">
                                                第{{i+1}}空. {{sentence.contentData}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-title">
                                        正确答案
                                    </div>
                                    <div v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                        <div>第{{i+1}}空. {{sentence.contentData}}</div>
                                    </div>
                                    <div class="category-title">解析</div>
                                    <div v-html="questionDataArr.analysisData"></div>
                                </div>
                                <div class="qa" v-else-if="questionDataArr.selQuestion == '解答题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="category-title">解析</div>
                                    <div v-html="questionDataArr.analysisData"></div>
                                </div>
                                <div class="judge" v-else-if="questionDataArr.selQuestion == '判断题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="vx-item" :class="{'active-answer':questionDataArr.answerA}">
                                        正确
                                    </div>
                                    <div class="vx-item" :class="{'active-answer':questionDataArr.answerB}">
                                        错误
                                    </div>
                                    <div class="category-title">正确答案</div>
                                    <div>{{questionDataArr.answerData}}</div>
                                    <div class="category-title">解析</div>
                                    <div v-html="questionDataArr.analysisData"></div>
                                </div>
                                <div class="matching" v-else-if="questionDataArr.selQuestion == '连线题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="matching-options">
                                        <div class="matching-options-left">
                                            <div v-for="(sentence,n) in questionDataArr.questionDataArr" :key="n">
                                                {{sentence.first.val}}
                                            </div>
                                        </div>
                                        <div class="matching-options-line">

                                        </div>
                                        <div class="matching-options-right">
                                            <div v-for="(sentence,o) in questionDataArr.questionDataArr" :key="o">
                                                {{sentence.second.val}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-title">正确答案</div>
                                    <div class="matching-options">
                                        <div class="matching-options-left">
                                            <div v-for="(sentence,p) in questionDataArr.questionDataArr" :key="p">
                                                {{sentence.first.val}}
                                            </div>
                                        </div>
                                        <div class="matching-options-line">

                                        </div>
                                        <div class="matching-options-right">
                                            <div v-for="(sentence,k) in questionDataArr.questionDataArr" :key="k">
                                                {{sentence.second.val}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-title">解析</div>
                                    <div class="analy-content" v-html="questionDataArr.analysisData"></div>
                                </div>
                                <div class="text-sort" v-else-if="questionDataArr.selQuestion == '文字排序题'">
                                    <div class="title-view d-block">
                                        <div>
                                            {{j+1}}.[{{questionDataArr.selQuestion}}]
                                        </div>
                                        <br/>
                                        <div v-html="questionDataArr.contentData"></div>
                                    </div>
                                    <div class="text-sort-options">
                                        <div v-for="(sentence,i) in questionDataArr.questionDataArr" :key="i">
                                            {{sentence.contentData.val}}
                                        </div>
                                    </div>
                                    <div class="category-title">正确答案</div>
                                    <div class="text-sort-options">
                                        <div v-for="(sentence,j) in questionDataArr.questionDataArr" :key="j">
                                            {{sentence.contentData.val}}
                                        </div>
                                    </div>
                                    <div class="category-title">解析</div>
                                    <div class="analy-content" v-html="questionDataArr.analysisData"></div>
                                </div>
                            </div>
                        </div>
                        <div class="matching" v-else-if="questionData.selQuestion == '连线题'">
                            <div class="title-view d-block">
                                <div>
                                    {{j+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
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
                                    <div v-for="(sentence,k) in questionData.questionDataArr" :key="k">
                                        {{sentence.second.val}}
                                    </div>
                                </div>
                            </div>
                            <div class="category-title">正确答案</div>
                            <div class="matching-options">
                                <div class="matching-options-left">
                                    <div v-for="(sentence,l) in questionData.questionDataArr" :key="l">
                                        {{sentence.first.val}}
                                    </div>
                                </div>
                                <div class="matching-options-line">

                                </div>
                                <div class="matching-options-right">
                                    <div v-for="(sentence,m) in questionData.questionDataArr" :key="m">
                                        {{sentence.second.val}}
                                    </div>
                                </div>
                            </div>
                            <div class="category-title">解析</div>
                            <div class="analy-content" v-html="questionData.analysisData"></div>
                        </div>
                        <div class="text-sort" v-else-if="questionData.selQuestion == '文字排序题'">
                            <div class="title-view d-block">
                                <div>
                                    {{j+1}}.[{{questionData.selQuestion}}]
                                </div>
                                <br/>
                                <div v-html="questionData.contentData"></div>
                            </div>
                            <div class="text-sort-options">
                                <div v-for="(sentence,i) in questionData.questionDataArr" :key="i">
                                    {{sentence.contentData.val}}
                                </div>
                            </div>
                            <div class="category-title">正确答案</div>
                            <div class="text-sort-options">
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
            <div class="es-model-operate" id="publish" v-if="isAnswered == false">
                <Button type="primary" @click="addPublishingRules" :disabled="isAdding" :loading="isAdding">提交</Button>
            </div>
        </div>
    </div>
</template>

<script>
import notConnect from '../../pages/notConnect'
export default {
    props:['propsData','viewType'],
    components:{
        notConnect
    },
    data(){
        return{
            isAdding:false,
            isAnswered:false,
        }
    },
    created(){
        console.log(this.propsData)
        console.log(this.viewType)
        if(this.viewType == 'view'){
            this.isAnswered = true
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
            // if(questionData.selQuestion == '判断题'){
            //     let answerData = questionData.answerData
            //     let index = this.alphabet.indexOf(answerData)
            //     this.activeAnswer(index,questionData.questionDataArr)
            // }
            this.deactiveAnswer(questionData.questionDataArr)
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
        },
        selSingleAnswer(questionData,sentence,currentIndex){
            for(let i=0;i<questionData.length;i++){
                if(i != currentIndex){
                    delete questionData[i].answer
                }else{
                    if(sentence.answer == undefined){
                        this.$set(sentence,'answer',true)
                    }else{
                        sentence.answer = !sentence.answer
                    }
                }
            }
        },
        selMultiAnswer(sentence){
            if(sentence.answer == undefined){
                this.$set(sentence,'answer',true)
            }else{
                sentence.answer = !sentence.answer
            }
        },
        selJudgeAnswer(questionData,selected){
            if(selected == 'A'){
                if(questionData.answerA == undefined){
                    this.$set(questionData,'answerA',true)
                }else if(questionData.answerA == true){
                    questionData.answerA = false
                }else if(questionData.answerA == false){
                    questionData.answerA = true
                }else{
                    questionData.answerA = undefined
                }
                questionData.answerB = undefined
            }else{
                if(questionData.answerB == undefined){
                    this.$set(questionData,'answerB',true)
                }else if(questionData.answerB == true){
                    questionData.answerB = false
                }else if(questionData.answerB == false){
                    questionData.answerB = true
                }else{
                    questionData.answerB = undefined
                }
                questionData.answerA = undefined
                
            }
        },
        async addPublishingRules(){
            let result = this.validateAnswer(this.propsData.addData)
            console.log(result)
            if(result != 'success'){
               return this.error(result)
            }
            // return
            this.isAdding = true
            let userId = this.$store.state.user.id;
            let bulletinId = this.propsData.id
            let postData = this.propsData.addData
            const res = await this.callApi('post','/api/homeworkResult',{postData:postData,userId:userId,bulletinId:bulletinId})
            if(res.status == 201){
                this.success('类别已成功添加！')
                this.isAnswered = true
            }
            this.isAdding = false
        },
        validateAnswer(addData){
            console.log(addData)
            
            let homework = addData.addDataList
            for(let i=0;i<homework.length;i++){
                let singleCnt = 0;
                let multiCnt = 0
                let qaCnt = 0
                let judgeCnt = 0
                let compose = {
                    singleCnt:0,
                    multiCnt:0,
                    qaCnt:0,
                    judgeCnt:0
                }
                if(homework[i].selQuestion == "单选题"){
                    for(let j=0;j<homework[i].questionDataArr.length;j++){
                        if(homework[i].questionDataArr[j].answer == true){
                            singleCnt++
                        }
                    }
                    if(singleCnt == 0){
                        return '单选题'
                    }
                }
                else if(homework[i].selQuestion == "多选题"){
                    for(let j=0;j<homework[i].questionDataArr.length;j++){
                        if(homework[i].questionDataArr[j].answer == true){
                            multiCnt++
                        }
                    }
                    if(multiCnt == 0){
                        return '多选题'
                    }
                }
                else if(homework[i].selQuestion == "解答题"){
                    if(homework[i].studentAnswer == undefined || homework[i].studentAnswer.trim() == ''){
                        return "解答题"
                    }
                }
                else if(homework[i].selQuestion == "判断题"){
                    if(homework[i].answerA == true || homework[i].answerB == true){
                        judgeCnt++
                    }
                    if(judgeCnt == 0){
                        return "判断题"
                    }
                }
                else if(homework[i].selQuestion == "综合题"){
                    for(let j=0;j<homework[i].questionDataArr.length;j++){
                        let comprehension = homework[i].questionDataArr[j]
                        if(comprehension.selQuestion == "单选题"){
                            for(let j=0;j<comprehension.questionDataArr.length;j++){
                                if(comprehension.questionDataArr[j].answer == true){
                                    singleCnt++
                                }
                            }
                            if(singleCnt == 0){
                                return '单选题'
                            }
                        }
                        else if(comprehension.selQuestion == "多选题"){
                            for(let j=0;j<comprehension.questionDataArr.length;j++){
                                if(comprehension.questionDataArr[j].answer == true){
                                    multiCnt++
                                }
                            }
                            if(multiCnt == 0){
                                return '多选题'
                            }
                        }
                        else if(comprehension.selQuestion == "解答题"){
                            if(comprehension.studentAnswer == undefined || comprehension.studentAnswer.trim() == ''){
                                return "解答题"
                            }
                        }
                        else if(comprehension.selQuestion == "判断题"){
                            if(comprehension.answerA == true || comprehension.answerB == true){
                                judgeCnt++
                            }
                            if(judgeCnt == 0){
                                return "判断题"
                            }
                        }
                    }
                }


            }
            return "success"
        },
        async showAnswerUsers(sentence){
            console.log(sentence)
            let userList = []
            if(sentence.answerUsers){
                console.log(sentence.answerUsers)
                for(let i=0;i<sentence.answerUsers.length;i++){
                    let userId = sentence.answerUsers[i]
                    console.log(userId)
                    await axios.get('/api/userById',{params:{id:userId}})
                                .then(res=>{
                                    console.log(res)
                                    userList.push(res.data[0])
                                })
                                .catch(err=>{
                                    console.log(err)
                                })
                }
            }else{
                this.info('no answer users')
            }
            this.$router.push({path:this.currentPath.path,query:{postView:true,questionType:'answerUsers',userList:userList}})
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    }
}
</script>

<style>

</style>