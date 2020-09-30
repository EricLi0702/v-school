<template>
    <div>
        <div v-if="item">
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
                            <li>截止时间：{{item.addData.deadline}}</li>
                            <li class="moreDetails">
                                <span @click="showViewDetails(item)">查看详情</span>
                                <span v-if="item.answerUserList == null" @click="showAnswerDetails(item)"> | 开始作答</span>
                            </li>
                            <li class="float-left">
                                已阅:<span v-if="item.readCnt">{{item.readCnt}}</span><span v-else>0</span>
                            </li>
                            <li class="float-right">
                                <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="20" @click="comment(item)"/>
                                <span style="font-size:20px" class="iconHover" v-if="item.likeCnt != 0">{{item.likeCnt}}</span>
                            </li>
                            <li class="float-right mr-3">
                                <Icon type="md-heart" v-if="item.isLiked == true"  @click="clickLike(item)" style="color:#19be6b;cursor:pointer" size="20"/>
                                <Icon type="md-heart-outline" v-else @click="clickLike(item)" size="20" style="cursor:pointer" class="iconHover"/>
                                <span style="font-size:20px" class="iconHover" v-if="item.likeCnt != 0">{{item.likeCnt}}</span>
                            </li>
                        </template>
                    </ListItemMeta>
                </ListItem>
            </List>
            <div class="category-title"></div>
            <div class="send-block">
                <div class="emoji-div"><img src="/img/icon/emoji.png" alt="" class="uploadicon"  @click="toggleEmo"></div>
                <div><textarea v-model="commentText" @keydown.enter.exact.prevent @keyup.enter.exact="submitComment" @keydown.enter.shift.exact="newline" name="" id="" rows="4" class="custom-textarea"></textarea></div>
                <div class="send-item">
                    <span class="toolbar-remark"> 按回车发送，shift+回车换行 </span>
                    <Button class="btnclass" @click="submitComment(item)">发送</Button>
                </div>
            </div>
            <div class="emoji-area-popup">
                <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
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
        console.log('*********',this.item)
    },
    data(){
        return{
            commentText:'',
            emoStatus:false,
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
            console.log(this.commentText)
            let bulletinId = item.id;
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/comment',{bulletinId:bulletinId,userId:userId,comment:this.commentText})
            
            this.emoStatus = false
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
        clickLike(item){

        }
    }
}
</script>