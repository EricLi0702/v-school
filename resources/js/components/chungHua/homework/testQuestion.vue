<template>
    <div>
        <div>
            <div class="category-title"></div>
            <div class="es-item">{{propsData.addData.text}}</div>
            <div class="category-title"></div>
        </div>
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
        <div v-else-if="type== 'answer'">
            <contentComponent @contentData="homeworkResult"></contentComponent>
        </div>
        <div class="es-model-operate">
            <Button type="success" size="large" @click="submit" :loading="isAdding" :disabled="isAdding">提交</Button>
        </div>
    </div>
</template>

<script>
import contentComponent from '../contentComponent'
export default {
    components:{
        contentComponent,
    },
    props:['propsData'],
    data(){
        return{
            users:[],
            stuRate:0,
            parRate:0,
            techRate:0,
            type:'answer',
            homework:null,
            isAdding:false,
        }
    },
    async created(){
        // const res = await this.callApi('get','/api/users')
        // if(res.status == 200){
        //     this.users = res.data
        // }
        console.log(this.propsData)
    },
    methods:{
        homeworkResult(val){
            this.homework = val
        },
        submit(){
            console.log(this.homework)
        }
    }
}
</script>

<style>

</style>