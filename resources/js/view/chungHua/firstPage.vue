<template>
    <div class="w-100">
        <Tabs :animated="false">
            <TabPane label="最新">
            </TabPane>
            <TabPane label="应用">
            </TabPane>
            <TabPane label="成员">
            </TabPane>
            <TabPane label="关于">
            </TabPane>
            <TabPane label="提示">
            </TabPane>
            <template slot="extra">
                <Button class="btnclass" @click="addModal"><Icon type="md-add" /> 发布 </Button>
            </template>
        </Tabs>
    </div>
</template>
<script>
import menuLists from '../../json/chungHua/从化第四中学-初二1班.json';
import GoTop from '@inotom/vue-go-top';
import notConnect from '../../components/pages/notConnect';
import lodash from 'lodash';
import contactComponent from '../../components/contactComponent'
export default {
    components: {
        GoTop,
        notConnect,
        contactComponent
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
            contactsName:[],
        }
    },
    async created(){
        this.currenttime = new Date().toJSON().slice(0,10).replace(/-/g,'/');
        const res = await this.callApi('get','/api/allPost');
        if(res.status == 200){
            //console.log(res.data);
            this.data = res.data;
        }
        const con = await this.callApi('get','/api/contact');
        if(con.status == 200){
            // //console.log('contact info',con.data)
            this.contacts = con.data.user;
            this.contactsName = con.data.userName;
            //console.log('$$$$$$$',this.contacts)
            //console.log('#######',this.contactsName)
            // //console.log(con.data);
            // this.contacts = con.data
        }
    },
    computed:{
        grouped(){
            return lodash.groupBy(this.contactsName,(item)=>{
                return item.name.charAt(0)
            })
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
           const res = await this.callApi('put','/api/isLiked',{id:item.id,isLiked:this.isLiked});
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
    }
}
</script>
