<template>
    
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
    
</template>

<script>
export default {
    data(){
        return{
            data:[]
        }
    },
    async created(){
        const res = await this.callApi('get','allPost')
        if(res.status == 200){
            this.data = res.data
        }
    }
}
</script>