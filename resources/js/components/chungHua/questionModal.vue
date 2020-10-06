<template>
    <div class="app-box">
        <div v-if="currentPath.query.questionType == undefined">
            <div class="app-group" v-for="(question,i) in questionView.question" :key="i">
                <div class="app-title">{{question.type}}</div>
                <div class="app-list">
                    <div class="app-block" v-for="(menu,j) in question.menuList" :key="j">
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
        <div v-else>
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
            
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
    }
}
</script>