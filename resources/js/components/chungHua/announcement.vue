<template>
    <div>
        <div class="es-item">
            <div class="es-item-left w-100">
                <Input v-model="addData.title" class="customInput" placeholder="公告标题"/>
            </div>
            <div class="es-item-right">
                
            </div>
        </div>
        <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=调查范围`">
            <div class="es-item">
                <div class="es-item-left w-100">
                    落款名称
                </div>
                <div class="es-item-right">
                    <span>test</span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
        </router-link>
        <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=调查范围`">
            <div class="es-item">
                <div class="es-item-left">
                    调查范围
                </div>
                <div class="es-item-right">
                    <span>必填</span>
                    <Icon type="ios-arrow-forward" /> 
                </div>
            </div>
        </router-link>
        <div class="es-item">
            <div class="es-item-left">
                签名反馈
            </div>
            <div class="es-item-right">
                <i-switch true-color="#13ce66" v-model="addData.scopeFlag" />
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
                scopeFlag:false,
                content:'公告内容'
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    }
}
</script>