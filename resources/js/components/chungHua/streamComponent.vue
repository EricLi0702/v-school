<template>
    <div class="mt-3">
        <div class="es-item">
            <div class="es-item-left">
                <RadioGroup v-model="data.type">
                    <Radio label="text">
                        <span>Text</span>
                    </Radio>
                    <Radio label="image">
                        <span>Image</span>
                    </Radio>
                    <Radio label="video">
                        <span>Video</span>
                    </Radio>
                </RadioGroup>
            </div>
            <div class="es-item-right">
                <TimePicker v-model="data.timeRange" format="HH:mm" type="timerange" placement="bottom-end" placeholder="Select time" style="width: 168px"></TimePicker>
            </div>
        </div>
        <div v-if="data.type == 'text'">
            <textarea v-model="data.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
        </div>
        <div v-else-if="data.type == 'image'">
            <Upload
                multiple
                type="drag"
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
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag images here to upload</p>
                </div>
            </Upload>
            <div class="image-block">
                <div class="image-upload-list" v-for="(imgUrl,i) in data.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="data.type == 'video'">
            <Upload
                multiple
                type="drag"
                ref="videoUploads"
                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                :on-success="videoSuccess"
                :on-error="handleError"
                :format="['mp4']"
                :max-size="524288"
                :show-upload-list="false"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/api/fileUpload/video"    
            >
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag videos here to upload</p>
                </div>
            </Upload>
            <div class="file-item row" v-if="data.videoUrl.length">
                <div class="col-4" v-for="(videoUrl,j) in data.videoUrl" :key="j">
                    <div class="image-upload-list float-left">
                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('video',videoUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2">
                        <div class="text-break">{{videoUrl.fileOriName}}</div>
                        <div class="text-secondary">{{videoUrl.fileSize}}</div>
                    </div>
                </div>
                <div class="remark"></div>
            </div>
        </div>
        <div class="es-model-operate question-modal-footer-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
export default {
    props:['propsData'],
    data(){
        return{
            data:{
                type:'text',
                text:'',
                imgUrl:[],
                videoUrl:[],
                timeRange:[]
            },
            token:window.Laravel.csrfToken,
            isLoading:false,
        }
    },
    created(){
        // console.log(this.data)
        // console.log(this.i)
        this.data = this.propsData
        console.log('propsData',this.data)
    },
    watch:{
       currentPath:{
           handler(val){
               if(val.query.postView == 'update'){
                   this.data = this.propsData
               }
           },
           deep:true
       }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    mounted(){
        // this.$watch('data',function(){
        //     this.$emit('streamData',this.data);
        // },{deep:true})
    },
    methods:{
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.data.imgUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'文件格式不正确',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式 ' + file.name + ' 错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过512M。'
            });
        },
        videoSuccess(res,file){
            let url = `/uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.data.videoUrl.push(res);
        },
        async deleteFile(type,fileName){
            let filePath = '';
            if(type == 'image'){
                filePath = fileName
            }else {
                filePath = fileName.imgUrl
            }
            const res = await this.callApi('delete','/api/fileUpload/file',{fileName:filePath});
            if(res.status == 200){
                if(type == 'image'){
                    this.data.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.data.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.data.videoUrl.pop(fileName)
                }
            }
            if(res.status != 200){
                this.swr();
            }
        },
        async submit(){
            console.log(this.data.timeRange)
            if(this.data.timeRange[0] == ''){
                return this.error('时间范围是必需的')
            }
            if(this.data.timeRange[0] == this.data.timeRange[1]){
                // if(this.data.timeRange[0] != '00:00'){
                    return this.error('时间范围是必需的')
                // }
            }
            if(this.data.text == '' && this.data.imgUrl.length == 0 && this.data.videoUrl.length == 0){
                return this.error('内容为必填项')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/streamData',{data:this.data})
            this.isLoading = false
            if(res.status == 201){
                this.data = {
                    type:'text',
                    text:'',
                    imgUrl:[],
                    videoUrl:[],
                    timeRange:['00:00','00:00']
                }
                console.log('res',res)
                this.success('操作成功')
                res.data.addData = JSON.parse(res.data.addData)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else if(res.status == 400){
                this.error(res.data)
                this.data.timeRange = []
            }
        }
    }
}
</script>

<style>

</style>