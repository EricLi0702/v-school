<template>
    <div>
        <div v-for="(_class,i) in classes" :key="i" @click="selClass(_class)">
            <router-link :to="`${currentPath.path}?modalName=${grade}&className=${_class.class}`">
                <div class="es-item">
                    <div class="es-item-left">
                        <div class="es-item-info">
                        {{_class.class}}
                        </div>
                    </div>
                    <div class="es-item-right">
                        <div class="ellipsis">{{`老师${_class.teacherCnt},学生${_class.studentCnt}`}}</div>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
        </div>
        <Modal
            footer-hide
            draggable
            v-model="classModal"
            :title="grade"
            :styles="{top:'75px',left:'-90px'}"
        >
            <a @click="$router.go(-1)"><Icon type="ios-arrow-back" /></a>
            <!-- <div class="es-app-detail-header">
                <Input prefix="ios-search" placeholder="搜索"/>
            </div> -->
            <perfect-scrollbar>
                <div class="p-modal-scroll">
                    <classViewComponent></classViewComponent>
                </div>
            </perfect-scrollbar>
        </Modal>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
import classViewComponent from './classView'
export default {
    props:['grade'],
    components:{
        classViewComponent
    },
    data(){
        return{
            classes:[],
            classModal:false,


        }
    },
    computed:{
        ...mapGetters([
            'getGradeModal'
        ]),
        currentPath(){
            // console.log(this.$route)
            return this.$route
        }
    },
    watch:{
        getGradeModal(value){
    
        }
    },
    mounted(){

    },
    created(){
        console.log(this.grade)
        axios.get("/api/gradeClass",{
            params:{
                grade:this.grade,
            }
        }).then(res=>{
            this.classes = res.data
        })
    },
    methods:{
        selClass(item){
            console.log(item)
            this.classModal = true;
            this.$store.commit('setGradeModal',false);
            this.$store.commit('setClassTabs',true);
        }
    }
    
}
</script>
<style scoped>
.ivu-modal-content {
    width: 1016px!important;
    height: 88vh!important;
}
</style>