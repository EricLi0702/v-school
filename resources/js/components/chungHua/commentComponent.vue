<template>
    <div style="position:relative">
        <div v-if="item" style="height:57vh;overflow:auto;">
            <List item-layout="vertical">
                <ListItem>
                    <ListItemMeta :avatar="item.content.imgUrl" :title="`${item.content.contentName}▪${item.user.name}`">
                        <template slot="description">
                            <div v-if="item.contentType == 1">
                                <li>问卷标题: {{item.addData.title}}</li>
                                <li>问卷说明：{{item.addData.description}}</li>
                                <li>问卷形式： <span v-if="item.addData.questionnaireFlag">匿名问卷</span><span v-else>公开问卷</span></li>
                            </div>
                            <div v-else-if="item.contentType == 2">
                                <li>投票内容：{{item.addData.content.votingDataArr[0][0].title}}</li>
                                <li>投票形式：<span v-if="item.addData.anonyVote">匿名投票</span>
                                            <span v-else>公开投票</span>
                                </li>
                                <li>投票上限：{{item.addData.maxVote}}项</li>
                            </div>
                            <li>截止时间：{{TimeView(item.addData.deadline)}}</li>
                            <li class="moreDetails">
                                <span @click="showViewDetails(item)">查看详情</span>
                                <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                            </li>
                            <li class="float-left">
                                已阅:<span v-if="item.readCnt">{{item.readCnt}}</span><span v-else>0</span>
                            </li>
                            <li class="float-right">
                                <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20" />
                                <span style="font-size:17px" class="iconHover" v-if="item.comments.length > 0">{{item.comments.length}}</span>
                            </li>
                            <li class="float-right mr-3">
                                <Icon type="md-heart" v-if="item.isLiked == true"  @click="clickLike(item,0)" style="color:#19be6b;cursor:pointer" size="20"/>
                                <Icon type="md-heart-outline" v-else @click="clickLike(item,1)" size="20" style="cursor:pointer" class="iconHover"/>
                                <span style="font-size:17px" class="iconHover" v-if="item.likes.length>0">{{item.likes.length}}</span>
                            </li>
                        </template>
                    </ListItemMeta>
                </ListItem>
            </List>
            <div class="category-title"></div>
            <div class="es-item" v-for="(comment,i) in item.comments" :key="i">
                <div class="es-item-left">
                    <div>
                        <img src="/img/icon/def_avatar.png" alt="" class="avatar">
                    </div>
                    <div class="es-item-info">
                        <div class="title">{{comment.user.name}}</div>
                        <div class="main comment">{{comment.comment}}</div>
                    </div>
                </div>
                <div class="es-item-right">
                    <div class="es-item-detail">
                        <div class="remark" v-if="comment.created_at">{{TimeView(comment.created_at)}}</div>
                        <div class="describe"></div>
                    </div>
                    <Icon v-if="comment.userId == $store.state.user.id" type="ios-remove-circle-outline" @click="delComment(comment,i)"/>
                </div>
            </div> 
        </div>
        <div class="emoji-area-popup">
            <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
        </div>
        <div class="send-block">
            <div class="emoji-div"><img src="/img/icon/emoji.png" alt="" class="uploadicon"  @click="toggleEmo"></div>
            <div><textarea v-model="commentText" @keydown.enter.exact.prevent @keyup.enter.exact="submitComment(item)" @keydown.enter.shift.exact="newline" name="" id="" rows="4" class="custom-textarea" placeholder="输入内容"></textarea></div>
            <div class="send-item">
                <span class="toolbar-remark"> 按回车发送，shift+回车换行 </span>
                <Button :disabled="isLoading" :loading="isLoading"  class="btnclass" @click="submitComment(item)">发送</Button>
            </div>
        </div>
        
    </div>
</template>

<script>
import { Picker } from 'emoji-mart-vue'
export default {
    components:{
        Picker
    },
    props:['item'],
    created(){
        
    },
    data(){
        return{
            commentText:'',
            commentList:[],
            commentData:{
                bulletinId:null,
                userId:null,
                comment:null
            },
            emoStatus:false,
            likeCnt:0,
            isLiked:false,
            isLoading:false,
        }
    },
    methods:{
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.commentText){
                this.commentText = e.native
            }else{
                this.commentText = this.commentText + e.native
            }
        },
        async submitComment(item){
            if(this.commentText.trim().length == 0){
                return
            }
            if(this.isLoading == true){
                return
            }
            this.isLoading = true
            this.commentData.bulletinId = item.id
            this.commentData.userId = this.$store.state.user.id
            this.commentData.comment = this.commentText
            const res = await this.callApi('post','/api/comment',this.commentData)
            if(res.status == 201){
                console.log('comment response',res.data)
                this.item.comments.unshift(res.data[0])
                this.commentText = '';
            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }    
            this.emoStatus = false
            this.isLoading = false
        },
        async delComment(comment,index){
            const res = await this.callApi('delete','/api/comment',{id:comment.id})
            if(res.status == 200){
                this.item.comments.splice(index,1);
            }
        },
        newline(){
            this.commentText = `${this.commentText}\n`
            console.log('newline')
        },
        showViewDetails(item){

        },
        showAnswerDetails(item){

        },
        comment(item){

        },
       async clickLike(item,type){
            if(this.isLiked == true){
               return
           }
           this.isLiked = true
           if(type == 1){
               const res = await this.callApi('post','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                if(res.status == 201){
                    this.item.likes.unshift(res.data[0])
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',true)
                    else
                        item.isLiked = true;
                }
           }else if(type == 0){
               const res = await this.callApi('delete','/api/like',{userId:this.$store.state.user.id,bulletinId:item.id})
                if(res.status == 200){
                    this.item.likes.splice(0,1)
                    if(item.isLiked == undefined)
                        this.$set(item,'isLiked',false)
                    else
                        item.isLiked = false;
                }
           }
           this.isLiked = false    
        }
    }
}
</script>

<style scoped>
.emoji-mart{
  height:330px!important;
  position: absolute;
  bottom:167px;
}
</style>