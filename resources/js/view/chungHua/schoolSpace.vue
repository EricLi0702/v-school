<template>
    <div class="w-100">
        <Tabs name="schoolSpace" :animated="false">
            <TabPane label="最新">
                <perfect-scrollbar>
                    <div class="p-3">
                        <go-top></go-top>
                        <List item-layout="vertical">
                            <div class="p-scroll">
                            <ListItem v-for="item in questionnaireLists" :key="item.id">
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
                                            <Icon type="ios-chatbubbles-outline" style="cursor:pointer" size="24"/>
                                        </li>
                                        <li class="float-right mr-3">
                                            <Icon type="md-heart" v-if="item.isLiked == true"  @click="clickLike(item)" style="color:#19be6b;cursor:pointer" size="24"/>
                                            <Icon type="md-heart-outline" v-else @click="clickLike(item)" size="24" style="cursor:pointer" class="iconHover"/>
                                            <span style="font-size:20px" class="iconHover" v-if="item.likeCnt != 0">{{item.likeCnt}}</span>
                                        </li>
                                    </template>
                                </ListItemMeta>
                            </ListItem>
                            <Modal
                                footer-hide
                                draggable
                                :title="`${postModalTitle}详情`"
                                :value="answerDetailModal"
                                :styles="{top:'75px',left:'-90px'}"
                                @on-cancel="cancel"
                            >
                                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <perfect-scrollbar>
                                    <div class="p-modal-scroll">
                                        <postDetails :postDetails="postProps" :viewType="viewType"></postDetails>
                                    </div>
                                </perfect-scrollbar>
                            </Modal>
                            <Modal
                                footer-hide
                                draggable
                                :title="`${postModalTitle}详情`"
                                :value="viewDetailModal"
                                :styles="{top:'75px',left:'-90px'}"
                                @on-cancel="cancel"
                            >
                                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <perfect-scrollbar>
                                    <div class="p-modal-scroll">
                                        <postDetails :postDetails="postProps" :viewType="viewType"></postDetails>
                                    </div>
                                </perfect-scrollbar>
                            </Modal>
                            </div>
                        </List>
                    </div>
                </perfect-scrollbar>    
            </TabPane>
            <TabPane label="应用">
                <perfect-scrollbar>
                    <div class="p-3">
                        <div class="p-scroll">
                            <div  v-for="(menu,i) in menuLists.application" :key="i">
                                <div class="mt-2 text-align-left">
                                    <label>{{menu.title}}</label>
                                </div>
                                <Row type="flex" justify="space-between" class="code-row-bg">
                                    <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                        <router-link :to="`${currentPath.path}?applicationName=${subMenu.label}`"><div @click="displayModal(subMenu)">
                                            <img :src="subMenu.imgurl" alt="">
                                            <span>{{subMenu.label}}</span>
                                        </div></router-link>
                                    </Col>
                                </Row>
                            </div>
                            <Modal
                                footer-hide
                                draggable
                                :value="getModalView"
                                :title="queryTitle"
                                :styles="{top:'75px',left:'-90px'}"
                                @on-cancel="cancel"
                            >
                                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <div class="es-app-detail-header">
                                    <Input prefix="ios-search" placeholder="搜索"/>
                                    <div class="operate-item">
                                        <Tooltip content="Bottom Center text" placement="bottom">
                                            <img src="/img/icon/ico_report.png" alt="">
                                        </Tooltip>

                                        <Tooltip content="Bottom Center text" placement="bottom">
                                            <img src="/img/icon/ico_app_set.png" alt="">
                                        </Tooltip>
                                        <Button class="btnclass ml-2" @click="addModal"><Icon type="md-add" /> 发布 </Button>
                                    </div>
                                </div>
                                <perfect-scrollbar>
                                    <div class="p-modal-scroll">
                                        <applicationViewComponent :currentPath="currentPath"></applicationViewComponent>
                                    </div>
                                </perfect-scrollbar>
                            </Modal>    
                        </div>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <TabPane label="成员">
                <perfect-scrollbar>
                    <div class="p-3">
                        <div class="p-scroll">
                            <div  v-for="(menu,i) in menuLists.member" :key="i">
                                <Row type="flex" justify="space-between" class="code-row-bg">
                                    <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                        <router-link :to="`${currentPath.path}?modalName=${subMenu.label}`">
                                            <div @click="displayMember(subMenu)">
                                                <img :src="subMenu.imgurl" alt="">
                                                <span>{{subMenu.label}}</span>
                                            </div>
                                        </router-link>
                                    </Col>
                                </Row>
                            </div>
                            <div id="gradeList">
                                <div v-for="(subGrade,j) in gradeList" :key="j">
                                    <router-link :to="`${currentPath.path}?modalName=${subGrade.id}`">
                                    <!-- <router-link :to="{ name: 'schoolSpace', params: { name:'成员'}, query:{modalName:subGrade.grade}}"> -->
                                        <div  class="es-item"  @click="displayMember(subGrade)">
                                            <div class="es-item-left">
                                                <img :src="subGrade.imgUrl" alt="">
                                                <div class="es-item-info">
                                                    <div class="title">{{subGrade.gradeName}}</div>
                                                    <div class="main">{{`班级${subGrade.classCnt},老师${subGrade.teacherCnt},学生${subGrade.studentCnt}`}}</div>
                                                </div>
                                            </div>
                                            <div class="es-item-right">
                                                <Icon type="ios-arrow-forward" />
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                            <!-- <div v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <router-link :to="`${currentPath.path}?modalName=${subMenu.label}`">
                                    <div class="es-item"  @click="displayMember(subMenu)">
                                        <div class="es-item-left">
                                            <img :src="subMenu.imgurl" alt="">
                                            <div class="es-item-info">
                                                <div class="title">高一年级</div>
                                                <div class="main">班级8,老师24,学生0</div>
                                            </div>
                                        </div>
                                        <div class="es-item-right">
                                            <Icon type="ios-arrow-forward" />
                                        </div>
                                    </div>
                                </router-link>
                            </div> -->
                            
                            <Modal
                                footer-hide
                                draggable
                                :value="getMemberView"
                                :title="memberTitle"
                                :styles="{top:'75px',left:memberLeft}"
                                :class="{classModal:getClassView}"
                                @on-cancel="cancel"
                            >
                                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                                <div class="es-app-detail-header">
                                    <Input prefix="ios-search" placeholder="搜索"/>
                                </div>
                                <perfect-scrollbar>
                                    <div class="p-modal-scroll">
                                        <memberViewComponent></memberViewComponent>
                                    </div>
                                </perfect-scrollbar>
                            </Modal>
                        </div>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <TabPane label="关于">
                <div v-for="(menu,i) in menuLists.about" :key="i">
                    <div v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                        <div class="es-item">
                            {{subMenu.label}}
                        </div>
                    </div>

                </div>
            </TabPane>
            <TabPane label="提示">
                <perfect-scrollbar>
                    <div class="p-3">
                        <div class="p-scroll">
                            <notConnect></notConnect>
                            <!-- <baidumap></baidumap> -->
                        </div>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <template slot="extra">
                <Button class="btnclass" @click="questionModal"><Icon type="md-add" /> 发布 </Button>
            </template>
            <Modal
                footer-hide
                draggable
                v-model="getShowQuestionModal"
                title="发布"
                :styles="{top:'75px',left:'-90px'}"
                @on-cancel="cancel"
            >
                <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
                <perfect-scrollbar>
                    <div class="p-modal-scroll">
                        <quesetionViewComponent></quesetionViewComponent>
                    </div>
                </perfect-scrollbar>
            </Modal>
        </Tabs>
    </div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import menuLists from '../../json/chungHua/从化第四中学-学校空间.json';
import GoTop from '@inotom/vue-go-top';
import baidumap from '../../components/pages/baidumap'
import notConnect from '../../components/pages/notConnect';
import applicationViewComponent from '../../components/chungHua/applicationView';
import memberViewComponent from '../../components/chungHua/memberView';
import quesetionViewComponent from '../../components/chungHua/questionModal'
import postDetails from '../../components/chungHua/postDetails'
// import viewDetails from '../../components/chungHua/viewItemComponent'
export default {
    components: {
        GoTop,
        notConnect,
        applicationViewComponent,
        memberViewComponent,
        baidumap,
        quesetionViewComponent,
        postDetails,
        // viewDetails
    },
    computed:{
        currentPath(){
            return this.$route
        },
        ...mapGetters([
            'getModalView','getClassView','getMemberView','getActionView','getShowQuestionModal'
        ]),
    },
    watch:{
        currentPath(value){
            console.log('current path:',value);
            if(value.query.modalName == undefined){
                this.$store.commit('setMemberView',false)
            }else{
                this.$store.commit('setMemberView',true)
                this.memberLeft = '-90px';
            }
            if(value.query.className == undefined){
                this.$store.commit('setClassView',false)
                this.memberLeft = '-90px';
            }
            else{
                this.$store.commit('setClassView',true)
                this.memberLeft = '-224px'
            }
            if(value.query.actionName == undefined){
                this.$store.commit('setActionView',false)
            }else{
                this.memberLeft = '-90px'
                this.$store.commit('setClassView',false);
            }
        },
    },
    data () {
        return {
            data: [],
            menuLists,
            showModal:false,
            modal_loading:false,
            currentTime:null,
            isLiked:false,
            isDisabled:false,
            questionnaireLists:[],
            modalMenu:[],
            base_url:'',
            queryModal:false,
            queryTitle:'',
            memberModal:false,
            memberTitle:'',
            gradeList:[],
            memberLeft:'-90px',
            content:null,
            viewType:'',
            answerDetailModal:false,
            viewDetailModal:false,
            postProps:null,
            postModalTitle:'',
            viewProps:null,
            haveAnswerFlag:null
        }
    },
    mounted(){
        this.base_url = window.Laravel.base_url;
    },
    async created(){
        // console.log('!!!!!!!!!!!!',this.$route.query)
        // if(this.$route.qeury){
        //     this.$router.push(this.$route.path)
        // }
        this.$router.push(this.$route.path)
        this.currentTime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        console.log(this.currentTime)
        const [questionnaireLists,grade] = await Promise.all([
            this.callApi('get','/api/questionnaire'),
            this.callApi('get','/api/getGrade'),
        ])
        if(questionnaireLists.status == 200){
            this.questionnaireLists = questionnaireLists.data;
            for(let i=0;i<this.questionnaireLists.length;i++){
                this.questionnaireLists[i].addData = JSON.parse(this.questionnaireLists[i].addData)
                if(this.questionnaireLists[i].answerUserList){
                    let answerUserList = this.questionnaireLists[i].answerUserList.split(",")
                    this.$set(this.questionnaireLists[i],'readCnt',answerUserList.length)
                    for(let j=0;j< answerUserList.length;j++){
                        if(parseInt(answerUserList[j]) == this.$store.state.user.id){
                            this.questionnaireLists[i].answerUserList = parseInt(answerUserList[j])
                            break
                        }else{
                            this.questionnaireLists[i].answerUserList = null
                        }
                    }
                }
            }
            console.log(this.questionnaireLists)
        }
        if(grade.status == 200){
            
            this.gradeList = grade.data
        }
    },
    methods:{
       addModal(){
           this.showModal = true;
       },
       questionModal(){
           this.$store.commit('setShowQuestionModal',true);
       },
       async clickLike(item){
           if(this.isDisabled)return
           this.isDisabled = true;

           this.isLiked = !item.isLiked;
           if(this.isLiked){
               item.isLiked = this.isLiked;
               item.likeCnt += 1;
           }else{
               item.isLiked = this.isLiked;
               item.likeCnt -= 1;
           }
           const res = await this.callApi('put','api/isLiked',{id:item.id,isLiked:this.isLiked});
            if(res.status == 200){
                this.success(res.data.msg)
                // item.isLiked = this.isLiked;
                // if(res.data.isLiked){
                //     item.likeCnt += 1;
                // }else{
                //     item.likeCnt -= 1;
                // }
            }else{
                this.swr();
            }
            this.isDisabled = false; 
       },
        displayModal(item){
            this.queryTitle = item.label;
            this.$store.commit('setModalView',true);           
        },
        displayMember(item){
            if(item.label === undefined){
                this.memberTitle = item.gradeName;
            }else{
                this.memberTitle = item.label;
            }
            this.$store.commit('setGradeModal',true);
        },
        cancel(){
            this.$store.commit('setMemberView',false);
            this.$store.commit('setGradeModal',false);
            this.$store.commit('setClassView',false);
            this.$store.commit('setModalView',false);
            this.$store.commit('setShowQuestionModal',false);
            this.answerDetailModal = false;
            this.viewDetailModal = false;
            if(this.$route.qeury != undefined)
                this.$router.push(this.$route.path)
        },
        async showViewDetails(item){
            this.answerDetailModal = true;
            this.postModalTitle = item.content.contentName
            let bulletinId = item.id
            // let userId = this.$store.state.user.id;
            
            await axios.get('/api/answerBulletin',{
                params:{
                    bulletinId:bulletinId,
                    // userId:userId
                }
            }).then(res=>{
                console.log('answerData',res.data)
                for(let i=0;i<res.data.length;i++){
                    let answerData = JSON.parse(res.data[i].answerData);
                    let singleContentDataArr = answerData.content.singleContentDataArr
                    
                    if(singleContentDataArr){
                        for(let j=0;j<singleContentDataArr.length;j++){
                            for(let k=1;k<singleContentDataArr[j].length;k++){
                                if(singleContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.singleContentDataArr[j][k].checkCnt == undefined){
                                        
                                        this.$set(item.addData.content.singleContentDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.singleContentDataArr[j][k].checkCnt +=1
                                        
                                    }
                                    if(item.addData.content.singleContentDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.singleContentDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.singleContentDataArr[j][0].allCnt +=1
                                    }
                                   
                                }
                            }
                        }
                    }
                    if(answerData.content.multiContentDataArr){
                        for(let j=0;j<answerData.content.multiContentDataArr.length;j++){
                            for(let k=1;k<answerData.content.multiContentDataArr[j].length;k++){
                                if(answerData.content.multiContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.multiContentDataArr[j][k].checkCnt == undefined){
                                        
                                        this.$set(item.addData.content.multiContentDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.multiContentDataArr[j][k].checkCnt +=1
                                        
                                    }
                                    if(item.addData.content.multiContentDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.multiContentDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.multiContentDataArr[j][0].allCnt +=1
                                    }
                                   
                                }
                            }
                        }
                    }
                    if(answerData.content.questionAnswerDataArr){
                        for(let j=0;j<answerData.content.questionAnswerDataArr.length;j++){
                            if(answerData.content.questionAnswerDataArr[j][0].isActive){
                                if(item.addData.content.questionAnswerDataArr[j][0].isActive == undefined){
                                    this.$set(item.addData.content.questionAnswerDataArr[j][0],'isActive',answerData.content.questionAnswerDataArr[j][0].isActive)
                                }else{
                                    item.addData.content.questionAnswerDataArr[j][0].isActive += answerData.content.questionAnswerDataArr[j][0].isActive
                                }
                            }
                        }
                    }
                    if(answerData.content.statisticsDataArr){
                        for(let j=0;j<answerData.content.statisticsDataArr.length;j++){
                           let value = answerData.content.statisticsDataArr[j][0].isActive
                           if(item.addData.content.statisticsDataArr[j][0].value == undefined){
                               this.$set(item.addData.content.statisticsDataArr[j][0],'value',value)
                               this.$set(item.addData.content.statisticsDataArr[j][0],'cnt',1)
                           }else{
                               item.addData.content.statisticsDataArr[j][0].value += value;
                               item.addData.content.statisticsDataArr[j][0].cnt += 1;
                           }
                        }
                    }
                    if(answerData.content.scoringQuestoinsDataArr){
                        for(let j=0;j<answerData.content.scoringQuestoinsDataArr.length;j++){
                           let value = answerData.content.scoringQuestoinsDataArr[j][0].isActive
                           if(item.addData.content.scoringQuestoinsDataArr[j][0].value == undefined){
                               this.$set(item.addData.content.scoringQuestoinsDataArr[j][0],'value',value)
                               this.$set(item.addData.content.scoringQuestoinsDataArr[j][0],'cnt',1)
                           }else{
                               item.addData.content.scoringQuestoinsDataArr[j][0].value += value;
                               item.addData.content.scoringQuestoinsDataArr[j][0].cnt += 1;
                           }
                        }
                    }
                    
                    let votingContentDataArr = answerData.content.votingDataArr
                    console.log('!!!!',answerData,votingContentDataArr)
                    if(votingContentDataArr){
                        for(let j=0;j<votingContentDataArr.length;j++){
                            for(let k=1;k<votingContentDataArr[j].length;k++){
                                if(votingContentDataArr[j][k].isActive == true){
                                    if(item.addData.content.votingDataArr[j][k].checkCnt == undefined){
                                        
                                        this.$set(item.addData.content.votingDataArr[j][k],'checkCnt',1);     
                                    }else{
                                        item.addData.content.votingDataArr[j][k].checkCnt +=1
                                        
                                    }
                                    if(item.addData.content.votingDataArr[j][0].allCnt == undefined){
                                        this.$set(item.addData.content.votingDataArr[j][0],'allCnt',1);     
                                    }else{
                                        item.addData.content.votingDataArr[j][0].allCnt +=1
                                    }
                                   
                                }
                            }
                        }
                    }
                }
            })
            this.postProps = item;
            this.viewType = 'view'
            console.log(item)
        },
        showAnswerDetails(item){
            this.viewDetailModal = true;
            this.postProps = item;
            this.postModalTitle = this.postProps.content.contentName
            this.viewType = 'answer'
            console.log(item)
        },
    }
}
</script>

<style>
.ivu-menu-horizontal{
    height: 50px;
    line-height:50px;
}
.ivu-modal-header{
    background-color:#2d8cf0;
}
.btnclass{
    background: #2d8cf0!important;
    color: #fff!important;
    border-color: #2d8cf0!important;
}

.ivu-input-wrapper input {
    background:#f3f3f3;
}

.operate-item{
    margin-left: 20px;
    align-items: center;
    display: -webkit-flex;
}
.operate-item img{
    width: 20px;
    height: 20px;
    margin-left: 15px;
}
</style>