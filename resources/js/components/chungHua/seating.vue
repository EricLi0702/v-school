<template>
    <div>

        <Row type="flex" justify="space-between" class="code-row-bg" v-for="(row,i) in rows" :key="i">
            <Col span="5" class="seating" v-for="(colum,j) in colums" :key="j">
            </Col>
        </Row>
        <div class="sl-platform">
            讲台
        </div>
        <div class="es-model-operate">
            <Button type="default" size="large" @click="submit">Submit</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            rows:[1,2,3,4,5,6,7,8],
            colums:[1,2,3,4],
        }
    }
}
</script>
<style scoped>
 .seating{
     background:#49B4EA;
     height: 65px;
 }
 .sl-platform{
    width: 300px;
    height: 40px;
    position: absolute;
    left: 50%;
    margin-left: -150px;
    background-size: 100% 100%;
    bottom: 8px;
    text-align: center;
    line-height: 40px;
    font-size: 14px;
    color: #444;
    background:#C8C8C8;
    top:64vh;
 }
</style>