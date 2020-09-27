<template>
    <div>
        <div v-for="(questionData,i) in addData" :key="i">
            <div class="category-title"></div>
            <div v-for="(sentence,j) in questionData" :key="j">
                <div class="es-item" v-if="j == 0">
                    <div class="w-100">
                        <div>{{i+1}}.{{sentence.title}}（{{type}}）</div>
                        <div class="media row">
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
                            <textarea name="" id="" v-model="AnswerData.description" class="text-content" cols="30" rows="10" placeholder="输入答案" v-on:input="signalChange(sentence)" ></textarea>
                            <div class="image-item" v-if="AnswerData.imgUrl">
                                <div class="image-block">
                                    <div class="image-upload-list" v-for="(imgUrl,i) in AnswerData.imgUrl" :key="i">
                                        <img :src="imgUrl" alt="">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ke-custom-toolbar">
                                <div class="es-item">
                                    <div class="es-item-left">
                                        <Upload
                                            ref="imageUploads"
                                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                            :on-success="imageSuccess"
                                            :on-error="handleError"
                                            :format="['jpg','gif','png']"
                                            :max-size="2048"
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
                </div>
                <div class="es-item" v-else>
                    <div class="w-100">
                        <div v-if="type == '单选题'" style="height:100%">
                            <div @click="selSingleAnswer(questionData,sentence,j)" style="display:flex;height:100%"> <div :class="{selAnswer: sentence.isActive }" ></div>{{alphabet[j-1]}} : {{sentence.title}}</div>
                            <div class="media row">
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
                        <div v-if="type == '多选题'" style="height:100%">
                            <div @click="selMultiAnswer(sentence)" style="display:flex;height:100%"> <div :class="{selAnswer: sentence.isActive  }" ></div>{{alphabet[j-1]}} : {{sentence.title}}</div>
                            <div class="media row">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['addData','type','viewType'],
    created(){
        this.token = window.Laravel.csrfToken;
    },
    data(){
        return{
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
            AnswerData:{
                description:'',
                imgUrl:[]
            },
            statisticsData:0,
            selMinute:null,
            token:'',
            isActive:false,
            sentenceData:null
        }
    },
    created(){

    },
    watch:{
        AnswerData:{
            handler(val){
                // this.$emit('questionAnswerData',val);
                // if(sentence.answerData == undefined){
                //     this.$set(sentence,'answerData',val)
                // }else{
                //     sentence.answerData = val
                // }
            },
            deep:true
        },
        statisticsData(val){
            // this.$emit('statisticsData',val)
            // if(sentence.answerData == undefined){
            //     this.$set(sentence,'answerData',val)
            // }else{
            //     sentence.answerData = val
            // }
        }
    },
    methods:{
        selSingleAnswer(questionData,answer,currentIndex){
            for(let i=1;i<questionData.length;i++){
                if(i != currentIndex){
                    delete questionData[i].isActive
                }
            }
            if(answer.isActive == undefined){
                this.$set(answer,'isActive',true)
            }else{
                answer.isActive = !answer.isActive
            }
        },
        selMultiAnswer(answer){
            if(answer.isActive == undefined){
                this.$set(answer,'isActive',true)
            }else{
                answer.isActive = !answer.isActive
            }
        },
        selScoring(sentence,minute){
            if(this.selMinute == minute){
                this.selMinute = null
            }else{
                this.selMinute = minute;
            }
            if(sentence.selMinute == undefined){
                this.$set(sentence,'selMinute',this.selMinute)
            }else{
                sentence.selMinute = this.selMinute
            }
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.AnswerData.imgUrl.push(res);
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
                this.AnswerData.imgUrl.pop(fileName)
            }
            if(res.status != 200){
                this.swr();
            }
        },
        signalChange(sentence){
            if(sentence.answerData == undefined){
                this.$set(sentence,'answerData',this.AnswerData)
            }else{
                sentence.answerData = this.AnswerData
            }
        },
        changeEvent(sentence){
            if(sentence.answerData == undefined){
                this.$set(sentence,'answerData',this.statisticsData)
            }else{
                sentence.answerData = this.statisticsData
            }
        }
    }
}
</script>