<template>
    <div>
        
        <div v-if="type == 'rating'">
            <div class="grade-table-box">
                <div class="grade-table-title">
                    <div class="studentName">学生昵称</div>
                    <div>家长评价</div>
                    <div>老师评价</div>
                </div>
            </div>
            <table class="table table-bordered">
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="text-center">
                            {{user.name}}
                        </td>
                        <td class="text-center">
                            <Rate v-model="parRate" />
                        </td>
                        <td class="text-center">
                            <Rate v-model="techRate" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else-if="viewType== 'answer'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">{{propsData.addData.text}}</div>
                <div class="category-title"></div>
            </div>
            <contentComponent @contentData="homeworkResult"></contentComponent>
            <div class="es-model-operate">
                <Button type="success" size="large" @click="submit" :loading="isAdding" :disabled="isAdding">提交</Button>
            </div>
        </div>
        <div v-else-if="viewType='studentType' || viewType == 'teacherType'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">{{propsData.addData.text}}</div>
                <div class="category-title"></div>
            </div>
            <div v-for="homework in answerData" :key="homework.id">
                <div class="category-title">{{homework.user.name}}</div>
                <div class="vx-item">
                    {{homework.answerData.title}}
                </div>
            </div>
        </div>
        <!-- <div v-else-if="viewType='teacherType'">
            <div>
                <div class="category-title"></div>
                <div class="vx-item">{{propsData.addData.text}}</div>
                <div class="category-title"></div>
            </div>
        </div> -->
    </div>
</template>

<script>
import contentComponent from '../contentComponent'
export default {
    components:{
        contentComponent,
    },
    props:['propsData','viewType'],
    data(){
        return{
            users:[],
            stuRate:0,
            parRate:0,
            techRate:0,
            type:'',
            homework:null,
            isAdding:false,
            answerData:[],
        }
    },
    async created(){
        // const res = await this.callApi('get','/api/users')
        // if(res.status == 200){
        //     this.users = res.data
        // }
        console.log(this.propsData)
        if(this.viewType == 'studentView'){
            console.log(this.viewType)
            console.log(this.propsData)
            await axios.get('/api/getMyAnswerBulletin',{params:{bulletinId:this.propsData.id,userId:this.$store.state.user.id}})
                        .then(res=>{
                            console.log(res)
                            this.answerData = res.data[0]
                            for(let i=0;i<this.answerData.length;i++){
                                this.answerData[i].answerData = JSON.parse(this.answerData[i].answerData)
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
        }
        if(this.viewType == 'teacherView'){
            console.log(this.viewType)
            console.log(this.propsData)
            await axios.get('/api/homeVisitAnswer',{params:{bulletinId:this.propsData.id}})
                        .then(res=>{
                            console.log(res)
                            this.answerData = res.data
                            for(let i=0;i<this.answerData.length;i++){
                                this.answerData[i].answerData = JSON.parse(this.answerData[i].answerData)
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
        }
    },
    methods:{
        homeworkResult(val){
            this.homework = val
        },
        async submit(){
            console.log(this.homework)
            if(this.homework == null){
                return this.error('')
            }
            this.isAdding = true
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.homework,userId:userId,bulletinId:this.propsData.id})
            if(res.status == 200){
                this.success('操作成功')
                console.log(res)
                this.$store.commit('setPostDetailsView',false)
            }
            this.isAdding = false
        },

        test(){
            // let items = {}
            // for(let i=0;i<85;i++){
            //     let array = []
            //     items.i = array
            // }
            // console.log(items)
        }
    }
}
</script>

<style>

</style>