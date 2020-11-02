<template>
    <div>
        <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
        <div class="es-app-detail-header">
            <Input prefix="ios-search" placeholder="搜索"/>
            <div class="operate-item">
                <Tooltip content="报表" placement="bottom">
                    <img src="/img/icon/ico_report.png" alt="">
                </Tooltip>

                <Tooltip content="报表" placement="bottom">
                    <img src="/img/icon/ico_app_set.png" alt="">
                </Tooltip>
                <Button class="btnclass ml-2" @click="addModal"><Icon type="md-add" /> 发布 </Button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        addModal(){
            this.$router.push(`${this.$route.path}?applicationType=${this.currentPath.query.applicationType}&questionType=${this.currentPath.query.applicationType}`)
        }
    }
}
</script>