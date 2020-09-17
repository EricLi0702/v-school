<template>
    <div>
        <div class="es-item" v-for="(lesson,i) in lessonList" :key="i">
            <div class="es-left-item">
                {{lesson.lessonName}}
            </div>
            <div class="es-right-item">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            lessonList:[]
        }
    },
    async created(){
        const res = await this.callApi('get','/api/allLesson');
        if(res.status == 200){
            this.lessonList= res.data;
            console.log('test',res.data)
        }
    }
}
</script>