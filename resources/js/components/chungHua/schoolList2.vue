<template>
    <div>
        <div class="es-item" v-for="(lesson,i) in schoolList" :key="i" @click="selLesson(lesson.lessonName)">
            <div class="es-item-left">
                {{lesson.lessonName}}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            schoolList:[]
        }
    },
    async created(){
        const res = await this.callApi('get','/api/lesson');
        console.log('!!!!!',res)
        if(res.status == 200){
            this.schoolList = res.data
        }
    },
    methods:{
        selLesson(lesson){
            this.$emit('selLesson',lesson)
            this.$router.push(`${this.$route.path}?questionType=场所预约`)
        }
    }
}
</script>