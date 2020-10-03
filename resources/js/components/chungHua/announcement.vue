<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <div class="es-item">
                <div class="es-item-left w-100">
                    <Input v-model="addData.title" class="customInput" placeholder="公告标题"/>
                </div>
                <div class="es-item-right">
                    
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=调查范围`">
                <div class="es-item">
                    <div class="es-item-left">
                        落款名称
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.signName != ''">{{addData.signName}}</span>
                        <span v-else>{{$store.state.user.name}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=展示范围`">
                <div class="es-item">
                    <div class="es-item-left">
                        展示范围
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.viewList">{{addData.viewList.length}}个群组</span>
                        <span v-else>必填</span>
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
                <vue-editor v-model="addData.content" placeholder="公告内容"></vue-editor>
            </div>
            
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '调查范围'">
            <div class="es-item" @click="signName($store.state.user.name)">
                {{$store.state.user.name}}
            </div>
            <div class="category-title"></div>
            <div class="es-item" @click="signName('从化第四中学')">
                从化第四中学
            </div>
            <div class="es-item" @click="signName('全体老师')">
                全体老师
            </div>
            <div class="es-item" @click="signName('宿舍管理员')">
                宿舍管理员
            </div>
            <div class="es-item" @click="addName">
                <div class="es-item-left"><Icon type="md-add" />自定义落款</div>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '展示范围'">
            <schoolList :type="'公告'"></schoolList>
        </div>
        
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
import schoolList from './schoolList'
export default {
    components: {
        VueEditor,
        schoolList
    },
    data(){
        return{
            addData:{
                title:'',
                signName:'',
                viewList:[],
                scopeFlag:false,
                content:''
            },
            isLoading:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.viewList !=''){
                    this.addData.viewList = val.query.viewList
                }
                if(val.query.signName !=''){
                    this.addData.signName = val.query.signName
                }
            },
            deep:true
        }
    },
    methods:{
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('')
            }
            
            this.isLoading = true;
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:5})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        handleCheckAll () {
            if (this.indeterminate) {
                this.checkAll = false;
            } else {
                this.checkAll = !this.checkAll;
            }
            this.indeterminate = false;

            if (this.checkAll) {
                this.checkAllGroup = ['香蕉', '苹果', '西瓜'];
            } else {
                this.checkAllGroup = [];
            }
        },
        checkAllGroupChange (data) {
            if (data.length === 3) {
                this.indeterminate = false;
                this.checkAll = true;
            } else if (data.length > 0) {
                this.indeterminate = true;
                this.checkAll = false;
            } else {
                this.indeterminate = false;
                this.checkAll = false;
            }
        },
        signName(name){
            console.log(name);
            this.$router.push({path:`${this.$route.path}?questionType=公告`,query:{signName:name}})
        },
        addName(){

        }
    }
}
</script>