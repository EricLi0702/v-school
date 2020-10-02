<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <span>栏目</span>
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" @on-click="chooseType($event)">
                    <a href="javascript:void(0)">
                        {{addData.type}}
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="预防秋季传染病安全">预防秋季传染病安全</DropdownItem>
                        <DropdownItem name="119消防主题日">119消防主题日</DropdownItem>
                        <DropdownItem name="课间安全">课间安全</DropdownItem>
                        <DropdownItem name="洪水防范 ">洪水防范 </DropdownItem>
                        <DropdownItem name="健康宣传 ">健康宣传 </DropdownItem>
                        <DropdownItem name="食品安全 ">食品安全 </DropdownItem>
                        <DropdownItem name="预防电信网络诈骗 ">预防电信网络诈骗 </DropdownItem>
                        <DropdownItem name="疫情防疫宣传 ">疫情防疫宣传 </DropdownItem>
                        <DropdownItem name="校园安全 ">校园安全 </DropdownItem>
                        <DropdownItem name="楹联基地 ">楹联基地 </DropdownItem>
                        <DropdownItem name="防溺水 ">防溺水 </DropdownItem>
                        <DropdownItem name="假期安全 ">假期安全 </DropdownItem>
                        <DropdownItem name="家园共育 ">家园共育 </DropdownItem>
                        <DropdownItem name="6.防洪防水措施 ">6.防洪防水措施 </DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left w-100">
                <Input v-model="addData.title" class="customInput" placeholder="标题"/>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                <Upload
                    ref="uploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :show-upload-list="false"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="api/fileUpload/image">
                        <span>封面</span>
                </Upload>
            </div>
            <div class="es-item-right">
                <img :src="addData.imgUrl" alt="" style="width:40px;height:30px" v-if="addData.imgUrl">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div>
            <vue-editor v-model="addData.content"></vue-editor>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
export default {
    components:{
        VueEditor
    },
    data(){
        return{
            addData:{
                type:'预防秋季传染病安全',
                title:'',
                imgUrl:'',
                content:''
            },
            token:'',
            isLoading:false
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    methods:{
        chooseType($event){
            this.addData.type = $event;
        },
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl = res;
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
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('')
            }
            if(this.addData.imgUrl == ''){
                return this.error('')
            }
            if(this.addData.content == ''){
                return this.error('')
            }
            this.isLoading = true;
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:10})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        }
    }
}
</script>