<template>
<div>
    <div v-if="currentPath.query.postDetail == undefined">
        <!-- <router-link :to="`${currentPath.path}?postDetail=已反馈`"> -->
            <div class="vx-item is-click" @click="homeVisitResult">
                <div class="vx-item-left">
                    <img :src="data.user.userAvatar" class="avatar" alt="" v-if="data && data.user.userAvatar">
                    <Avatar icon="ios-person"  v-else/>
                    <span class="ml-1">{{data.user.name}}</span>
                </div>
                <div class="vx-item-right">
                    已反馈{{data.answers.length}}人
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
        <!-- </router-link> -->
        <div class="category-title"></div>
        <div class="vx-item" v-for="answerUser in data.addData.userInfo" :key="answerUser.id">
            <div class="vx-item-left">
                <img :src="answerUser.userAvatar" class="avatar" alt="" v-if="answerUser && answerUser.userAvatar">
                <Avatar icon="ios-person"  v-else/>
                <span class="ml-1">{{answerUser.name}}</span>
            </div>
            <div class="vx-item-right">
                待反馈
            </div>
        </div>
    </div>
    <div v-else-if="currentPath.query.postDetail == '已反馈'">
        <questionItemComponent
            :addData="calcData"
            :viewType="viewType"
        ></questionItemComponent>
    </div>
</div>
</template>
<script>
import questionItemComponent from './questionItemComponent'
export default {
    props:['propsData','viewType'],
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
                },
                calcData:{}
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
        this.data = this.propsData
        console.log(this.data)
    },
    methods:{
        async homeVisitResult(){
            let description = JSON.parse(JSON.stringify(this.data.addData.description))
            await axios.get('/api/homeVisitAnswer',{params:{bulletinId:this.data.id}})
                        .then(res=>{
                            for(let i=0;i<res.data.length;i++){
                                let answerData = JSON.parse(res.data[i].answerData)
                                let userId = res.data[i].user.name
                                for(let i=0;i<answerData.length;i++){
                                    for(let j=1;j<answerData[i].length;j++){
                                        let sentence = answerData[i][j];
                                        if(sentence.active == true){
                                            if(description[i].allCnt == undefined){
                                                this.$set(description[i],'allCnt',1)
                                            }else{
                                                description[i].allCnt++
                                            }
                                            if(description[i][j].answerUsers == undefined){
                                                let answerUsers = []
                                                description[i][j]['answerUsers'] = answerUsers
                                                description[i][j].answerUsers.push(userId)
                                            }else{
                                                description[i][j].answerUsers.push(userId)
                                            }
                                            if(description[i][j].answerCnt == undefined){
                                                this.$set(description[i][j],'answerCnt',1)
                                            }else{
                                                description[i][j].answerCnt++
                                            }
                                        }
                                    }
                                }
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
            this.calcData = description
            this.$router.push({path:this.currentPath.path,query:{postDetail:'已反馈'}})
        }
    }

}
</script>