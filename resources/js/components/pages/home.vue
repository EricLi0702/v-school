<template>
    <div class="w-100">
        
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <!-- <menuItem
                @addModalemit = "addModalemit"
            /> -->
            <Tabs value="name1">
                <TabPane label="最新" name="name1">
                    <div class="content-container">
                        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                        <List item-layout="vertical">
                            <ListItem v-for="item in data" :key="item.title">
                                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20">
                                <ListItemMeta :avatar="item.avatar" :title="item.title" :description="item.description" />
                                {{ item.content }}
                                <img src="https://www.linkpicture.com/view.php?img=LPic5f5129c35799d1477167511" style="width: 280px">
                                </div>
                            </ListItem>
                        </List>

                    </div>
                </TabPane>
                <TabPane label="应用" name="name2">标签二的内容</TabPane>
                <TabPane label="成员" name="name3">标签三的内容</TabPane>
                <TabPane label="关于" name="name4">标签三的内容</TabPane>
                <TabPane label="提示" name="name5">标签三的内容</TabPane>
            </Tabs>
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
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
                {
                    title: 'This is title 3',
                    description: 'This is description, this is description, this is description.',
                    avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
                    content: 'This is the content, this is the content, this is the content, this is the content.'
                },
            ],
        }
    },
    async created(){
      
    },
    methods:{
       addModalemit(value){
           console.log('##########',value);
           this.addModal = value;
       },
    }
}
</script>