<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                栏目
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left w-100">
                <Input v-model="addData.title" class="customInput" placeholder="标题"/>
            </div>
            <div class="es-item-right"></div>
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
                <span v-else>必填</span>
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div>
            <vue-editor v-model="addData.content"></vue-editor>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
export default {
    components: {
        VueEditor
    },
    data(){
        return{
            addData:{
                title:'',
                categoryName:'',
                imgUrl:'',
                content:'动态内容'
            },
            token:'',
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    methods:{
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
    }
}
</script>