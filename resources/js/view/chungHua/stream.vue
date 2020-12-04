<template>
    <div class="w-100 es-view mt-2">
        <div class="es-item">
            <Button class="btnclass" @click="addStream" v-if="isWritePermitted"><Icon type="md-add" /> 发布 </Button>
        </div>
        
        <div v-for="(item,index) in streamData" :key="index">
            {{item}}
        </div>
        <Modal
            footer-hide
            :value="newStream"
            title="发布"
            :styles="{top:'75px',left:'-90px'}"
            :mask-closable="false"
            class-name="question-view-modal"
        >
            <a @click="$router.go(-1)"><Icon class="question-view-modal-back-icon" type="ios-arrow-back" /></a>
            <div class="p-modal-scroll">
                <streamComponent></streamComponent>
            </div>
        </Modal>
    </div>
</template>

<script>
import streamComponent from '../../components/chungHua/streamComponent'
export default {
    components:{
        streamComponent,
    },
    data(){
        return{
            streamData:[],
            count:1,
            newData:{
                type:'',
                text:'',
                imgUrl:[],
                videoUrl:[],
                tiemRange:[],
            },
            newStream:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        currentPath:{
            handler(val){
                console.log(val)
                if(val.query.addData){
                    this.newStream = false
                    console.log(this.streamData)
                    console.log(val.query.addData)
                    // this.streamData.unshift(val.query.addData)
                }
            },
            deep:true
        }
    },
    async created(){
        if(JSON.stringify(this.currentPath.query) != '{}'){
            this.$router.push(this.$route.path)
        }
        const res = await this.callApi('get','/api/streamData')
        console.log(res)
        if(res.status == 200){
            for(let i=0;i<res.data.length;i++){
                this.streamData.push(JSON.parse(res.data[i].addData))
            }
        }
        console.log(this.streamData)
    },
    methods:{
        addStream(){
            this.newStream = true
            this.$router.push({path:this.$route.path,query:{postView:true}})
        },
        childData(val){
            console.log(val)
            this.newData = val
        },
        async submit(){
            const res = await this.callApi('post','stramData',this.newData)
        }
    }
}
</script>

<style>

</style>