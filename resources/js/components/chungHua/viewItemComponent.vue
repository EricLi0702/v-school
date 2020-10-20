<template>
    <div>
        <div v-if="currentPath.query.showAnswer == undefined">
            <div  v-for="(questionData,i) in addData" :key="i">
                <div class="category-title"></div>
                <div v-for="(sentence,j) in questionData" :key="j">
                    <div class="es-item" v-if="j == 0">
                        <div class="w-100">
                            <div>{{i+1}}.{{sentence.title}}（{{type}}）</div>
                            <div class="media row m-0">
                                <div class="image-item col-12" v-if="sentence.imgUrl.length">
                                    <div class="image-block row">
                                        <div class="image-upload-list col-1" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                            <img :src="imgUrl" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="file-item row col-12" v-if="sentence.otherUrl.length">
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
                                <div class="file-item row col-12" v-if="sentence.videoUrl.length">
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
                            <div v-if="type == '问答题'">
                                <!-- <div class="es-item">
                                    <div class="es-item-left">
                                        <router-link :to="`${currentPath.path}?showAnswer=问答题`">
                                            <div class="text-color">已答内容</div>
                                        </router-link>
                                    </div>
                                </div> -->
                                <!-- <div class="es-item">
                                    {{sentence.title}}
                                </div> -->
                            </div>
                            <div v-else-if="type == '统计题'">
                                <div class="es-item">
                                    <div class="es-item-left">
                                        <div class="text-color" v-if="sentence.value">
                                            平均{{sentence.value/sentence.cnt}}，总计{{sentence.value}}
                                        </div>
                                        <div v-else class="text-color">
                                            平均0，总计0
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="type == '评分题'">
                                <div v-if="sentence.value" class="text-color">
                                    平均{{sentence.value/sentence.cnt}}，总计{{sentence.value}}
                                </div>
                                <div v-else class="text-color">
                                    平均0分，总计0分
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div v-else>
                        <div class="es-item">
                            <div class="w-100">
                                <div v-if="type == '单选题' || type == '多选题'" style="height:100%">
                                    <div style="display:flex;height:100%"> 
                                        <div :class="{selAnswer: sentence.checkCnt }" ></div>
                                        <div>
                                            <div>{{alphabet[j-1]}} : {{sentence.title}}</div>
                                            <div v-if="sentence.checkCnt">{{sentence.checkCnt}}人，{{sentence.checkCnt*(100/questionData[0].allCnt)}}%</div>
                                            <div v-else>0人，0%</div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="type == '问答题'">
                                    <div class="es-item">{{sentence.title}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="media row m-0">
                            <div class="image-item col-12" v-if="sentence.imgUrl.length">
                                <div class="image-block row">
                                    <div class="image-upload-list col-1" v-for="(imgUrl,k) in sentence.imgUrl" :key="k">
                                        <img :src="imgUrl" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="file-item row col-12" v-if="sentence.otherUrl.length">
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
                            <div class="file-item row col-12" v-if="sentence.videoUrl.length">
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
                <div v-if="type == '单选题' && $store.state.chartView == true">
                    <pieChart :data="questionData"></pieChart>
                </div>
                <div v-else-if="type == '多选题' &&  $store.state.chartView == true">
                    <barChart :data="questionData"></barChart>
                </div>
                <div v-else-if="type == '问答题'">

                </div>
                <div v-else-if="type == '统计题'">

                </div>
                <div v-else-if="type == '评分题'">

                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.showAnswer == '问答题'">
            <div class="es-item">
                
            </div>
        </div>
    </div>
</template>

<script>
import barChart from './barChart'
import pieChart from './pieChart'
export default {
    props:['addData','type'],
    components:{
        barChart,
        pieChart,
    },
    data(){
        return{
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
            barChartProps:{},
            pieChartProps:{},
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    }    
}
</script>