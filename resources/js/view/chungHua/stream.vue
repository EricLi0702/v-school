<template>
    <div class="w-100 es-view mt-2">
        <div class="es-item">
            <Button class="btnclass" @click="addStream" v-if="isWritePermitted"><Icon type="md-add" /> 发布 </Button>
        </div>
        <div class="es-item" v-for="(data,i) in streamData" :key="i">
            <div class="es-item-left">
                {{data.text}}
            </div>
            <div class="es-item-right">
                {{data.tiemRange[0]}}~{{data.tiemRange[[1]]}}
            </div>
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
                    this.streamData.unshift(res.query.addData)
                }
            },
            deep:true
        }
    },
    async created(){
        const res = await this.callApi('get','/api/streamData')
        if(res.status == 200){
            this.streamData = res.data
        }
    },
    methods:{
        addStream(){
            this.newStream = true
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