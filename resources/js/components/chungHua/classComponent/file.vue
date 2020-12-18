<template>
    <div class="container-fluid">
        <div v-if="fileData.length" class="row m-0 p-0 d-flex align-items-center">
            <div v-for="(file,i) in fileData" :key="i" class="post-file-item col-lg-4 col-12 p-0 px-2 mb-2">
                <div class="d-flex align-items-center p-1 bg-light-gray position-relative">
                    <Icon class="mr-2" size="35" type="ios-document-outline" />
                    <div class="file-info">
                        <p><strong>{{file.fileOriName}}</strong></p>
                        <p><small>{{file.fileSize}}</small></p>
                    </div>
                    <a class="download-icon" :href="`${baseUrl}/uploads/other/${file.fileName}`" :download="file.fileOriName">
                        <Icon size="25" class="msg-file-download-ico msg-file-download-ico-send" type="md-download" />
                    </a>
                </div>
            </div>
        </div>
        <div v-else-if="noFile" class="row">
            <notConnect></notConnect>
        </div>
        <div v-else class="row justify-content-center pt-3" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px; height:30px;" alt="">
        </div>
    </div>
</template>

<script>
import notConnect from '../../pages/notConnect'

export default {
    components:{
        notConnect,
    },
    data(){
        return{
            fileData: [],
            noFile : false,
            baseUrl:window.Laravel.base_url,
        }
    },
    mounted(){
        this.getFile();
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },

    methods:{
        async getFile(){
            let classId = this.currentPath.params.className;
            await axios.get('/api/grade/file', {params: { id: classId }})
            .then(res=>{
                if(res.data.fileData.length > 0){
                    this.fileData = res.data.fileData;
                }
                else{
                    this.noFile = true;
                }
            }) 
            .catch(err=>{
                console.log(err.response);
            })
        },
    }
}
</script>