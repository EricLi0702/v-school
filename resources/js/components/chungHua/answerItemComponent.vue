<template>
    <div>
        <div v-for="(questionData,i) in addData" :key="i">
            <div class="category-title"></div>
            <div v-for="(sentence,j) in questionData" :key="j" class="question-item">
                <div class="qi-content" v-if="j == 0">
                    <div class="qi-name">{{i+1}}.{{sentence.title}}<span v-if="type">（{{type}}）</span></div>
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
                    <div v-if="type == '问答题'">
                        <textarea name="" id="" v-model="questionAnswerData.description" class="text-content smaller-h pt-4 border-right-0 border-top-0 border-left-0 border-bottom" cols="30" rows="10" placeholder="输入答案" v-on:input="signalChange(sentence)" ></textarea>
                        <div class="image-item row m-0 p-0" v-if="questionAnswerData.imgUrl">
                            <div class="image-block">
                                <div class="image-upload-list" v-for="(imgUrl,i) in questionAnswerData.imgUrl" :key="i">
                                    <img :src="imgUrl" alt="">
                                    <div class="demo-upload-list-cover">
                                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ke-custom-toolbar pl-0">
                            <div class="vx-item pl-0">
                                <div class="vx-item-left">
                                    <Upload
                                        ref="imageUploads"
                                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                        :on-success="imageSuccess"
                                        :on-error="handleError"
                                        :format="['jpg','gif','png']"
                                        :max-size="10240"
                                        :show-upload-list="false"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize"
                                        action="/api/fileUpload/image">
                                            <img src="/img/icon/photo.png" alt="" class="uploadicon">
                                    </Upload>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="type == '统计题'" class="d-flex">
                        <InputNumber v-model="statisticsData" :min="parseInt(sentence.from)" v-on:input="changeEvent(sentence)" :max="parseInt(sentence.to)" :placeholder="`${sentence.from}~${sentence.to}`" class="numInput w-90"/>
                        <span class="unit">{{sentence.unit}}</span>
                    </div>
                    <div v-if="type == '评分题'">
                        <div v-for="(minute,i) in parseInt(sentence.maxMinute)" :key="i" class="op-score" :class="{selMinute: minute===selMinute}" @click="selScoring(sentence,minute)">
                            {{minute}}
                        </div>
                    </div>
                </div>
                <div class="qi-options" v-else>
                    <div class="w-100">
                        <div v-if="type == '单选题'" style="height:100%; display:flex;">
                            <div :class="{selAnswer: sentence.isActive }" class=" op-item choice" @click="selSingleAnswer(questionData,sentence,j)" style="height:100%"> 
                                <div  class="answer op-num">{{alphabet[j-1]}}</div>
                                <div class="op-content">{{sentence.title}}</div>
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
                        <div v-if="type == '多选题'" style="height:100%; display:flex;">
                            <div :class="{selAnswer: sentence.isActive }" class=" op-item choice" @click="selMultiAnswer(sentence)" style="height:100%"> 
                                <div  class="answer op-num">{{alphabet[j-1]}}</div>
                                <div class="op-content">{{sentence.title}}</div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['addData','type'],
    created(){
        this.token = window.Laravel.csrfToken;
    },
    data(){
        return{
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
            answerData:{
                singleAnswerData:[],
                multiAnswerData:[],
                questionAnswerData:[],
                statisticsAnswerData:[],
                scoringAnswerData:[]
            },
            questionAnswerData:{
                description:'',
                imgUrl:[]
            },
            statisticsData:null,
            selMinute:null,
            token:'',
            isActive:false,
            sentenceData:null,
            questionCnt:0,
        }
    },
    created(){

    },
    watch:{
    },
    methods:{
        checkQuestionData(){

            // this.questionCnt = this.addData.length;
        },
        selSingleAnswer(questionData,sentence,currentIndex){
            this.checkQuestionData()
            for(let i=1;i<questionData.length;i++){
                if(i != currentIndex){
                    delete questionData[i].isActive
                }
            }
            if(sentence.isActive == undefined){
                this.$set(sentence,'isActive',true)
            }else{
                sentence.isActive = !sentence.isActive
            }
        },
        selMultiAnswer(answer){
            if(answer.isActive == undefined){
                this.$set(answer,'isActive',true)
            }else{
                answer.isActive = !answer.isActive
            }
        },
        signalChange(sentence){
            if(sentence.answerData == undefined){
                this.$set(sentence,'isActive',this.questionAnswerData)
            }else{
                sentence.isActive = this.questionAnswerData
            }
        },
        changeEvent(sentence){
            if(sentence.answerData == undefined){
                this.$set(sentence,'isActive',this.statisticsData)
            }else{
                sentence.isActive = this.statisticsData
            }
        },
        selScoring(sentence,minute){
            if(this.selMinute == minute){
                this.selMinute = null
            }else{
                this.selMinute = minute;
            }
            if(sentence.selMinute == undefined){
                this.$set(sentence,'isActive',this.selMinute)
            }else{
                sentence.isActive = this.selMinute
            }
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.questionAnswerData.imgUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        async deleteFile(type,fileName){
            const res = await this.callApi('delete','/api/fileUpload/file',{fileName:fileName});
            if(res.status == 200){
                this.questionAnswerData.imgUrl.pop(fileName)
            }
            if(res.status != 200){
                this.swr();
            }
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
        
    }
}
</script>