<template>
    <div class="app-box">
        <div v-if="currentPath.query.questionType == undefined">

            <div class="app-group" v-for="(question,i) in questionView.question" :key="i">
                <div v-if="!$isMobile()">
                    <div v-if="question.type != '消息'">
                        <div class="app-title">{{question.type}}</div>
                        <div class="app-list">
                            <div class="app-block" v-for="(menu,j) in question.menuList" :key="j" v-if="checkPermission(menu.name) == true">
                                <router-link :to="`${currentPath.path}?questionType=${menu.name}`">
                                    <img class="avatar" :src="menu.imgUrl" alt="">
                                    <div class="app-name">
                                        <span>{{menu.name}}</span>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                        <div class="category-title"></div>
                    </div>
                </div>
                <div  v-else>
                    <div class="app-title">{{question.type}}</div>
                    <div class="app-list">
                        <div class="app-block" v-for="(menu,j) in question.menuList" :key="j"  v-if="checkPermission(menu.name) == true">
                            <router-link :to="`${currentPath.path}?questionType=${menu.name}`">
                                <img class="avatar" :src="menu.imgUrl" alt="">
                                <div class="app-name">
                                    <span>{{menu.name}}</span>
                                </div>
                            </router-link>
                        </div>
                    </div>
                    <div class="category-title"></div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="es-item" v-if="$isMobile()">
                <span class="text-color" style="margin: 3px auto;">{{currentPath.query.questionType}}</span>
            </div>
            <a v-else @click="$router.go(-1)" class="text-color"><Icon type="ios-arrow-back question-view-modal-back-icon" /></a>
            <questionDetail></questionDetail>
        </div>
    </div>
</template>
<script>
import questionView from '../../json/chungHua/questionView.json'
import questionDetail from './questionDetail'
export default {
    components:{
        questionDetail,
    },
    data(){
        return{
            questionView,
            permission:[]    
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        
    },
    mounted(){
        if(this.currentPath.params.className == undefined){
            this.permission = this.$store.state.user.role.permission[0].children[0].children
        }else{
            this.permission = this.$store.state.user.role.permission[0].children[1].children
        }
    },
    methods:{
        checkPermission(name){
            let index = this.permission.findIndex(perCheck=>perCheck.title == name)
            if(this.permission[index].checked == true){
                return true
            }else{
                return false
            }
        }
    }
}
</script>