<template>
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
        <!-- <Page :total="100" /> -->
    </div>
</template>

<script>
export default {
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
            ]
        }
    }
}
</script>


			