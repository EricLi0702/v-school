<template>
    <div class="w-100 h-100">
        
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10  h-100">
            <Tabs :animated="false">
                <TabPane label="标签一">
                    
                    <div>
                        
                        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                        <perfect-scrollbar>
                            <go-top></go-top>
                            <ScrollTopArrow></ScrollTopArrow>
                            <List item-layout="vertical">
                                <ListItem v-for="item in data" :key="item.title">
                                    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20">
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
                                    </div>
                                </ListItem>
                            </List>
                        </perfect-scrollbar>
                    </div>
                    
                </TabPane>
                <TabPane label="标签二">
                    <perfect-scrollbar>
                        <div class="p-3">
                            
                            <div  v-for="(menu,i) in menuLists.application" :key="i">
                                <div class="mt-2">
                                    <label>{{menu.title}}</label>
                                </div>
                                <Row type="flex" justify="space-between" class="code-row-bg">
                                    <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                        <img :src="subMenu.imgurl" alt="">
                                        <span>{{subMenu.label}}</span>
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
       
        <Modal
            v-model="showModal"
            title="<"
           >
            <p>Content of dialog</p>
            <p>Content of dialog</p>
            <p>Content of dialog</p>
        </Modal>
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
       }
    }
}
</script>

<style scoped>
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
</style>