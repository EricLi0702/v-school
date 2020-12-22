<template>
    <div>
        <template  v-if="currentPath.query.className == undefined">
            <div v-for="(_class,i) in classes" :key="i" @click="selClass(_class)">
                <router-link :to="`${currentPath.path}?gradeName=${grade}&className=${_class.id}`">
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            {{_class.lessonName}}
                        </div>
                        <div class="vx-item-right">
                            <div class="ellipsis">{{`老师${_class.teacherCnt},学生${_class.studentCnt}`}}</div>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
            </div>
        </template>
        <template v-else>
            <classViewComponent :classData="classData"></classViewComponent>
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
            classData:null,
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
            this.classData = item
            this.$store.commit('setGradeInfo',item)
            // this.$store.commit('setClassView',true);
        }
    }
    
}
</script>
