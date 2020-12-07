<template>
    <div class="p-0">
        <router-link :to="{path:currentPath.path,query:{profile:'教师发展档案'}}">
            <div class="vx-item is-click">
                <div class="vx-item-left">基本信息</div>
                <div class="vx-item-right">0%<Icon type="ios-arrow-forward" /></div>
            </div>
        </router-link>

        <router-link :to="{path:currentPath.path,query:{profile:'教师发展档案'}}">
            <div class="vx-item is-click">
                <div class="vx-item-left">教学过程</div>
                <div class="vx-item-right">0%<Icon type="ios-arrow-forward" /></div>
            </div>
        </router-link>

        <router-link :to="{path:currentPath.path,query:{profile:'教师发展档案'}}">
            <div class="vx-item is-click">
                <div class="vx-item-left">教学成绩</div>
                <div class="vx-item-right">0%<Icon type="ios-arrow-forward" /></div>
            </div>
        </router-link>

        <router-link :to="{path:currentPath.path,query:{profile:'教师发展档案'}}">
            <div class="vx-item is-click">
                <div class="vx-item-left">荣誉表彰</div>
                <div class="vx-item-right">0%<Icon type="ios-arrow-forward" /></div>
            </div>
        </router-link>

        <router-link :to="{path:currentPath.path,query:{profile:'教师发展档案'}}">
            <div class="vx-item is-click">
                <div class="vx-item-left">学术科研</div>
                <div class="vx-item-right">0%<Icon type="ios-arrow-forward" /></div>
            </div>
        </router-link>
    </div>
</template>

<script>
export default {
    computed:{
        currentPath(){
            return this.$route;
        }
    }
}
</script>

<style>

</style>