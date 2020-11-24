<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <contactComponent @selectedUser="selUser"></contactComponent>
        </div>
        <div v-else-if="currentPath.query.selType == 'student'">
            <div class="surplus">
                积分剩余：暂无限制
            </div>
            <div class="user-item">
                <div>评价对象：</div>
                <span v-for="user in userList" :key="user.id" class="and text-color">@{{user}}</span>
            </div>
        </div>
        <div class="ml-item">
            <div class="md-dimension">每天评比</div>
            <div class="md-content">
                <div class="md-block">
                    <div style="display:flex;flex-direction:colum;">
                        <div class="medal-04">
                            <div class="md-score-line"></div>
                            <img src="" alt="" class="md-logo">
                            <div class="md-word-bg"></div>
                            <div class="md-content">
                                <span class="md-name"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import contactComponent from '../contactComponent'
export default {
    components:{
        contactComponent
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        console.log(this.currentPath)
    },
    data(){
        return{
            userList:[]
        }
    },
    methods:{
        selUser(val){
            this.userList = val;
            console.log(this.userList)
        }
    } 
}
</script>

<style>

</style>