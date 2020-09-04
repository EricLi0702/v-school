<template>
    <div class="w-100">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Tabs :animated="false">
                <TabPane label="标签一">
                    
                    <div class="container content-container">
                        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                        <perfect-scrollbar>
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
                                                    <Icon type="ios-chatbubbles-outline" size="24"/>
                                                </li>
                                                <li class="float-right mr-3">
                                                    <Icon type="md-heart-outline" @click="clickLike(item)" size="24" class="iconHover"/>
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
                            
                            <div  v-for="(menu,i) in menuLists" :key="i">
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
                    <Button type="success" @click="addModal"><Icon type="md-add" /> 发布 </Button>
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
import menuLists from './homeMenu.json';
export default {
    components: {
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
           console.log('addModal true');
           this.showModal = true;
       },
       async clickLike(item){
           if(this.isDisabled)return
           this.isDisabled = true;
           console.log('#####',item);
           this.isLiked = !item.isLiked;
           console.log('isliked',this.isLiked);
            const res = await this.callApi('put','api/isLiked',{id:item.id,isLiked:this.isLiked});
            if(res.status == 200){
                console.log(res.data);
                this.success(res.data.msg)
                item.isLiked = this.isLiked;
                if(res.data.isLiked){
                    item.likeCnt += 1;
                }else{
                    item.likeCnt -= 1;
                }
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
</style>