<template>
    <div>
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
</template>

<script>
export default {
    data(){
        return{
            users:[],
            stuRate:0,
            parRate:0,
            techRate:0
        }
    },
    async created(){
        const res = await this.callApi('get','/api/users')
        if(res.status == 200){
            this.users = res.data
        }
    }
}
</script>

<style>

</style>