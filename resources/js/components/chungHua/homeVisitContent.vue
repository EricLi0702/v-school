<template>
<div>
    <div v-if="currentPath.query.postDetail == undefined">
        <!-- <router-link :to="`${currentPath.path}?postDetail=已反馈`"> -->
            <div class="es-item" @click="homeVisitResult">
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
        <!-- </router-link> -->
        <div class="category-title"></div>
        <div class="es-item" v-for="answerUser in data.addData.userInfo" :key="answerUser.id">
            <div class="es-item-left">
                <img :src="answerUser.userAvatar" class="avatar" alt="" v-if="answerUser && answerUser.userAvatar">
                <Avatar icon="ios-person"  v-else/>
                <span class="ml-1">{{answerUser.name}}</span>
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
    // watch:{
    //     propsData:{
    //         handler(val){
    //             this.data = this.propsData
    //         },
    //         deep:true
    //     }
    // },
    created(){
        console.log(this.propsData)
        this.data = this.propsData
    },
    methods:{
        async homeVisitResult(){
            console.log(this.data)
            await axios.get('/api/answerBulletin',{params:{bulletinId:this.data.id}})
                        .then(res=>{
                            // console.log(res)
                            for(let i=0;i<res.data.length;i++){
                                console.log(res.data[i])
                                let answerData = JSON.parse(res.data[i].answerData)
                                console.log(answerData)
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
            return
            this.$router.push({path:currentPath.path,query:{postDetail:'已反馈'}})
        }
    }

}
</script>