<template>
    <div>
        <template  v-if="currentPath.query.className == undefined">
            <div v-for="(_class,i) in classes" :key="i" @click="selClass(_class)">
                <router-link :to="`${currentPath.path}?modalName=${grade}&className=${_class.lessonName}`">
                    <div class="es-item">
                        <div class="es-item-left">
                            <div class="es-item-info">
                            {{_class.lessonName}}
                            </div>
                        </div>
                        <div class="es-item-right">
                            <div class="ellipsis">{{`老师${_class.teacherCnt},学生${_class.studentCnt}`}}</div>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
            </div>
        </template>
        <template v-else>
            <classViewComponent></classViewComponent>
        </template>
    </div>
</template>

<script>
import classViewComponent from './classView'
export default {
    props:['grade'],
    components:{
        classViewComponent
    },
    data(){
        return{
            classes:[],
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        grade(value){
            if(value){
                axios.get("/api/getLesson",{
                    params:{
                        grade:value,
                    }
                }).then(res=>{
                    this.classes = res.data
                })
            }
        }
    },
    created(){
    },
    mounted(){
    },
    methods:{
        selClass(item){
            this.$store.commit('setClassView',true);
        }
    }
    
}
</script>
