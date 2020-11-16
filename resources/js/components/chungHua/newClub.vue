<template>
    <div>
        <div class="es-item">
            <div class="es-item-left d-block">
                <Input v-model="addData.clubName" placeholder="" />
                <Upload
                    ref="editDataImage"
                    type="drag"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="10240"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="/api/category/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>单击或拖动文件以上传</p>
                    </div>
                </Upload>
                <div class="demo-upload-list" v-if="addData.imgUrl">
                    <img :src="addData.imgUrl" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>
            </div>
            <div class="es-item-right">
                <Button type="primary" @click="addClub"><Icon type="md-add" /> 添加</Button>
            </div>
        </div>
        <div class="es-item" v-for="club in clubList" :key="club.id">
            <div class="es-item-left">
                {{club.name}}
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward"></Icon>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                clubName:'',
                imgUrl:'',
                schoolName:''
            },
            clubList:[],
            token:window.Laravel.csrfToken
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
    // axios.get('/api/getClubList',{params:{
        //     id:
        // }})
    },
    methods:{
        async addClub(){
            this.addData.schoolName = this.currentPath.params.schoolName
            const res = await this.callApi('post','/api/club',this.addData)
            console.log(res)
        },
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 不正确，请选择jpg或png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
            });
        },

        async deleteImage(isAdd = true){
            let image = ''
            image = this.addData.imgUrl;
            this.addData.imgUrl = '';
            this.$refs.uploads.clearFiles();
            const res = await this.callApi('delete', '/api/category/upload',{imageName:image})
            if(res.status!=200){
                this.addData.imgUrl = image
                this.swr()
            }
        },
    }
}
</script>

<style>

</style>