<template>
    <div class="w-100">
        <Tabs :animated="false">
            <TabPane label="最新">
                <perfect-scrollbar>
                    <div class="p-3" >
                        <go-top></go-top>
                        <List item-layout="vertical">
                            <div class="p-scroll" infinite-wrapper id="pScroll">
                            <videoUploading />
                            <liveStreaming />
                            <setLiveStreaming />
                            <viewAndEditLiveLecture />
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
                            <InfiniteLoading 
                                @infinite="infiniteHandler"
                                spinner="bubbles"
                                :forceUseInfiniteWrapper="true"
                                force-use-infinite-wrapper="#pScroll"
                            >
                                <div slot="no-more">Whooops! No any more data</div>
                            </InfiniteLoading>
                            
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
                                        <div @click="test(subMenu)">
                                            <img :src="subMenu.imgurl" alt="">
                                            <span>{{subMenu.label}}</span>
                                        </div>
                                        <Modal
                                            footer-hide
                                            draggable
                                            v-model="subMenu.active"
                                            :title="subMenu.label"
                                            :styles="{top:'75px',left:'-90px'}"
                                        >
                                            <div class="es-app-detail-header">
                                                <Input prefix="ios-search" placeholder="搜索"/>
                                                <div class="operate-item">
                                                    <Tooltip content="Bottom Center text" placement="bottom">
                                                        <img src="/img/icon/ico_report.png" alt="" @click="test">
                                                    </Tooltip>

                                                    <Tooltip content="Bottom Center text" placement="bottom">
                                                        <img src="/img/icon/ico_app_set.png" alt="" @click="test">
                                                    </Tooltip>

                                                </div>
                                            </div>
                                            
                                        </Modal>
                                    </Col>
                                </Row>
                            </div>
                        </div>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <TabPane label="成员">
                <perfect-scrollbar>
                    <div class="p-3">
                        <div class="p-scroll">
                            <div  v-for="(menu,i) in menuLists.member" :key="i">

                                <Row type="flex" justify="space-between" class="code-row-bg" v-if="i == 0">
                                    <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                        <div @click="test(subMenu)">
                                            <img :src="subMenu.imgurl" alt="">
                                            <span>{{subMenu.label}}</span>
                                        </div>
                                        <Modal
                                            footer-hide
                                            draggable
                                            v-model="subMenu.active"
                                            :title="subMenu.label"
                                            :styles="{top:'75px',left:'-90px'}"
                                        >
                                            <div class="es-app-detail-header">
                                                <Input prefix="ios-search" placeholder="搜索"/>
                                                <div class="operate-item">
                                                    <Tooltip content="Bottom Center text" placement="bottom">
                                                        <img src="/img/icon/ico_report.png" alt="" @click="test">
                                                    </Tooltip>

                                                    <Tooltip content="Bottom Center text" placement="bottom">
                                                        <img src="/img/icon/ico_app_set.png" alt="" @click="test">
                                                    </Tooltip>

                                                </div>
                                            </div>
                                            
                                        </Modal>
                                    </Col>
                                </Row>
                                <div v-else-if="i < 6" class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
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
                                <div v-else class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
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
                            </div>
                            <div class="es-item" v-for="(contact,k) in contacts" :key="k">
                                <div class="es-item-left">
                                    <Avatar src="https://i.loli.net/2017/08/21/599a521472424.jpg" v-if="contact.userAvatar" />
                                    <Avatar icon="ios-person" v-else/>
                                    <div class="es-item-info">
                                        <div class="title">{{contact.name}}</div>
                                        <div class="main">{{contact.phoneNumber}}</div>
                                    </div>
                                </div>
                                <div class="es-item-right">
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>
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
import menuLists from '../../json/vSchool/V校实验学校-学校空间.json';
import GoTop from '@inotom/vue-go-top';
import videoUploading from './videoUploading'
import liveStreaming from './liveStreaming'
import viewAndEditLiveLecture from './viewAndEditLiveLecture'
import setLiveStreaming from './setLiveStreaming'
import notConnect from '../../components/pages/notConnect';
//infinit
import InfiniteLoading from 'vue-infinite-loading';
export default {
    components: {
        GoTop,
        notConnect,
        videoUploading,
        liveStreaming,
        setLiveStreaming,
        viewAndEditLiveLecture,
        InfiniteLoading,
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
            contacts:[],
            //infinitScrollDatas
            page: 1,
            lastPage: 0,
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken
        this.currenttime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        // const res = await this.callApi('get','api/allPost');
        // if(res.status == 200){
        //     this.data = res.data.data;
        // }
        const con = await this.callApi('get','api/contact');
        if(con.status == 200){
            //console.log('contact info',con.data)
            //console.log(con.data);
            this.contacts = con.data
        }

    },
    methods:{
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
            // alert('modal test');
            //console.log(item);
            item.active = !item.active
        },
        addModal(){
            this.showModal = true;
        },
        infiniteHandler($state){
            let timeOut = 0;
            
            if (this.page > 1) {
                timeOut = 1000;
            }
            setTimeout(() => {
                let vm = this;
                window.axios.get('api/allPost?page='+this.page).then(({ data }) => {
                    vm.lastPage = data.last_page;
                    $.each(data.data, function(key, value){
                        vm.data.push(value);        
                    });
                    if (vm.page - 1 === vm.lastPage) {
                        $state.complete();
                    }
                    else {
                        $state.loaded();
                    }
                this.page = this.page + 1;
                });
            }, timeOut);
            console.log('@@@@@@@@',this.data)
        },
    }
}
</script>
