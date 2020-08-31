<template>
    <div class="w-100">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        <div class="container content-container">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <List item-layout="vertical">
                <ListItem v-for="item in data" :key="item.title">
                    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20">
                    <ListItemMeta :avatar="item.avatar" :title="item.title" :description="item.description" />
                    {{ item.content }}
                    <img src="https://dev-file.iviewui.com/5wxHCQMUyrauMCGSVEYVxHR5JmvS7DpH/large" style="width: 280px">
                    </div>
                </ListItem>
            </List>
            <p class="h3 text-center">Last element</p>
            <Modal
                v-model="addModal"
                title="Add tag"
                :mask-closable="false"
            >
                <Input v-model="modalData.tagName" placeholder="Enter something..." style="width: 300px" />
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addTag">Add Tag</Button>
                </div>
            </Modal>
            <!-- <Page :total="100" /> -->
        </div>
    </div>
</template>
<script>
import menuItem from './basic/menuItem'
export default {
    components:{
        menuItem
    },
    data () {
        return {
            data: [
                {
                    title: 'This is title 1',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 2',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                }
            ],
            modalData:{
                tagName:'',
            },
            addModal:false,
        }
    },
    async created(){
        const res = await this.callApi('post','app/create_tag',{tagName:'testtag'});
        console.log('@@@@@@@@@@@@@@@@@@@@@@@@@@',res)
        if(res.status == 200){
            // console.log(res)
        }
    },
    methods:{
       addModalemit(value){
           console.log('##########',value);
           this.addModal = value;
       },
       addTag(){
           if(this.modalData.tagName.trim()==''){
               return this.e('tagName is required');
           }
       }
    }
}
</script>