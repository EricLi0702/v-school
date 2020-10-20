<template>
    <div>
        <div v-if="propsData == '封面'">
            <div class="bannar">
                <img src="/img/icon/22.jpg" alt="">
                <img src="/img/icon/timg.jpg" alt="">
            </div>
        </div>
        <div v-else-if="propsData == '详细地址'">
            <div class="es-item">
                <div class="es-item-left">
                    <div class="es-item-info">
                        <div class="title">
                            天河客运站
                        </div>
                        <div class="main">
                            广东省广州市天河区
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="propsData == '管理员'">
            
        </div>
    </div>
</template>

<script>
export default {
    props:['propsData'],
    watch:{
        propsData(val){
            console.log('++++++',val)
        }
    }
}
</script>