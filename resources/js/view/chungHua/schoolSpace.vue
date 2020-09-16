<template>
    <div class="w-100">
        <Tabs name="schoolSpace" :animated="false">
            <TabPane label="最新">
                <perfect-scrollbar>
                    <div class="p-3">
                        <go-top></go-top>
                        <List item-layout="vertical">
                            <div class="p-scroll">
                            <ListItem v-for="item in data" :key="item.title">
                                <ListItemMeta :avatar="item.img" :title="item.title">
                                    <template slot="description">
                                        <li>{{item.content}}</li>
                                        <li>2nd Exam name:math</li>
                                        <li>Upload deadline:{{currenttime}}</li>
                                        <li><img :src="item.img" style="width: 280px"></li>
                                        <li class="float-left">
                                            Read:{{item.readCnt}}
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
                                        <router-link :to="`${currentPath.path}?modalName=${subMenu.label}`"><div @click="displayModal(subMenu)">
                                            <img :src="subMenu.imgurl" alt="">
                                            <span>{{subMenu.label}}</span>
                                        </div></router-link>
                                    </Col>
                                </Row>
                            </div>
                            <Modal
                                footer-hide
                                draggable
                                v-model="queryModal"
                                :title="queryTitle"
                                :styles="{top:'75px',left:'-90px'}"
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
                                        <modalViewComponent :currentPath="currentPath"></modalViewComponent>
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
                            <div>
                                <div v-for="subGrade in gradeList" :key="subGrade.grade">
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
                <Button class="btnclass" @click="addModal"><Icon type="md-add" /> 发布 </Button>
            </template>
        </Tabs>
    </div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'
import menuLists from '../../json/chungHua/从化第四中学-学校空间.json';
import GoTop from '@inotom/vue-go-top';
import baidumap from '../../components/pages/baidumap'
import notConnect from '../../components/pages/notConnect';
import modalViewComponent from '../../components/chungHua/modalView';
import memberViewComponent from '../../components/chungHua/memberView';
export default {
    components: {
        GoTop,
        notConnect,
        modalViewComponent,
        memberViewComponent,
        baidumap,
    },
    computed:{
        currentPath(){
            return this.$route
        },
        ...mapGetters([
            'getClassView','getMemberView','getActionView'
        ])
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
            currenttime:null,
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
        }
    },
    mounted(){
        this.base_url = window.Laravel.base_url;
    },
    async created(){
        this.$router.push(this.$route.path)
        console.log('store',this.getClassView,this.getMemberView)
        this.currenttime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        const [allPost,questionnaireLists,grade] = await Promise.all([
            this.callApi('get','/api/allPost'),
            this.callApi('get','/api/questionnaireLists'),
            this.callApi('get','/api/getGrade'),
        ])
        if(allPost.status == 200){
            this.data = allPost.data;
        }
        if(questionnaireLists.status == 200){
            this.questionnaireLists = questionnaireLists.data;
        }
        if(grade.status == 200){
            this.gradeList = grade.data
        }
    },
    methods:{
       addModal(){
           this.showModal = true;
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
        test(item){
            item.active = !item.active
        },
        displayModal(item){
            this.queryModal = true
            this.queryTitle = item.label
            this.modalMenu = item
        },
        displayMember(item){
            this.memberModal = true;
            if(item.label === undefined){
                this.memberTitle = item.gradeName;
            }else{
                this.memberTitle = item.label;
            }
            this.$store.commit('setGradeModal',true);
            this.modalMenu = item;
        },
        cancel(){
            this.$store.commit('setMemberView',false);
            this.$store.commit('setClassView',false);
            this.$router.push(this.$route.path)
        }
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
.ivu-modal-content{
        /* width:720px!important;
        height: 88vh!important; */
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