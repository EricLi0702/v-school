<template>
<div>
    <div v-if="currentPath.query.postDetail == undefined">
        <router-link :to="`${currentPath.path}?postDetail=已反馈`">
            <div class="es-item">
                <div class="es-item-left">
                    <img :src="data.user.userAvatar" class="avatar" alt="" v-if="data && data.user.userAvatar">
                    <Avatar icon="ios-person"  v-else/>
                    <span class="ml-1">{{data.user.name}}</span>
                </div>
                <div class="es-item-right">
                    已反馈0人
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
        </router-link>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                <img :src="data.addData.userInfo.userAvatar" class="avatar" alt="" v-if="data && data.user.userAvatar">
                <Avatar icon="ios-person"  v-else/>
                <span class="ml-1">{{data.addData.userInfo.name}}</span>
            </div>
            <div class="es-item-right">
                待反馈
            </div>
        </div>
    </div>
    <div v-else-if="currentPath.query.postDetail == '已反馈'">
        <questionItemComponent
            :addData="data.addData.description"
        ></questionItemComponent>
    </div>
</div>
</template>
<script>
import questionItemComponent from './questionItemComponent'
export default {
    props:['propsData'],
    components:{
        questionItemComponent,
    },
    data(){
        return{
            data:{
                addData:{
                    description:'',
                    userInfo:{
                        userAvatar:'',
                        name:'',
                    }
                },
                user:{
                    name
                }
            }
        }
    },
    mounted(){
        
    },
    computed:{
        currentPath(){
           return this.$route
        }
    },
    watch:{
        propsData:{
            handler(val){
                this.data = this.propsData
            },
            deep:true
        }
    }

}
</script>