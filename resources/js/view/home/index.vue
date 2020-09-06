<template>
    <div class="w-100">
        <Tabs :animated="false">
            <TabPane label="标签一">
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
                                            <span style="font-size:20px" class="iconHover">{{item.likeCnt}}</span>
                                        </li>
                                    </template>
                                </ListItemMeta>
                            </ListItem>
                            </div>
                        </List>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <TabPane label="标签二">
                <perfect-scrollbar>
                    <div class="p-3">
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

                                        </div>
                                        <Input suffix="ios-search" placeholder="Enter text" style="width: auto" />
                                    </Modal>
                                </Col>
                            </Row>
                        </div>
                    </div>
                </perfect-scrollbar>
            </TabPane>
            <TabPane label="标签三">标签三的内容</TabPane>
            <template slot="extra">
                <Button class="btnclass" @click="addModal"><Icon type="md-add" /> 发布 </Button>
            </template>
        </Tabs>
    </div>
</template>
<script>
import menuLists from '../../json/chungHua/从化第四中学-学校空间.json';
import GoTop from '@inotom/vue-go-top';
export default {
    components: {
        GoTop,
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
        }
    },
    async created(){
        this.currenttime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        const res = await this.callApi('get','api/allPost');
        if(res.status == 200){
            // console.log(res)
            console.log(res.data);
            this.data = res.data;
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
            // alert('modal test');
            console.log(item);
            item.active = !item.active
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
        width:720px!important;
        height: 842px!important;
        /* left:615px!important; */
        /* top:75px; */
}
.ivu-input-wrapper{
    width:85%!important;
}
.ivu-input-wrapper input {
    background:#f3f3f3;
}
.p-scroll{
    height:79vh!important;
    /* overflow: auto;     */
}
</style>