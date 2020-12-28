<template>
    <div class="w-100 es-view">
        <div class="container content-container">
            <div class="_title0 pt-3 row">
                <p class="col-12 mb-2">角色管理</p> 
                <Select v-model="roleId" placeholder="" class="col-12 col-md-6 mb-2" @on-change="changeRole">
                    <Option v-for="role in roles" :key="role.id" :value="role.id" >{{role.roleName}}</Option>
                </Select>
                <Select v-model="userId" placeholder="" class="col-12 col-md-6 mb-2" @on-change="changeUser">
                    <Option v-for="user in userList" :key="user.id" :value="user.id" >{{user.name}}</Option>
                </Select>
            </div>
            <div class="p-scroll-72">
                <div class="_overflow_table_div">
                    <table class="table">
                        <tr>
                            <th>号码</th>
                            <th>名称</th>
                            <th>电话号码</th>
                            <th>IMEI</th>
                            <th>读</th>
                        </tr>
                        <tr v-for="(student,i) in studentList" :key="i" v-if="studentList.length>0">
                            <td>{{i+1}}</td>
                            <td>{{student.name}}</td>
                            <td>{{student.phoneNumber}}</td>
                            <td>{{student.imei}}</td>
                            <td><i-switch true-color="#13ce66" v-model="student.status" /></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
         <div class="float-right">
            <Button class="mr-4 mt-2" type="primary" :loading="isLoading" :disabled="isLoading" @click="saveImei">保存</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            roles:[],
            roleId:null,
            userList:[],
            userId:null,
            studentList:[
                {
                    imei:""
                }
            ],
            isLoading:false,
            activeUsers:[],
        }
    },
    async created(){
        const res = await this.callApi('get','/api/role');
        if(res.status == 200){
            this.roles = res.data;
            if(res.data.length){
                this.roleId = res.data[0].id;
            }else{
            }    
        }
        const stuedent = await this.callApi('get','/api/student');
        if(res.status == 200){
            this.studentList = stuedent.data
            for(let i=0;i<this.studentList.length;i++){
                this.$set(this.studentList[i],'status',false)
            }
        }
        this.changeRole()
    },
    methods:{
        changeRole(){
            axios.get('/api/userByRole',{params:{id:this.roleId}})
                .then(res=>{
                    if(res.data.length>0){
                        this.userList = res.data
                        this.userId = res.data[0].id
                        this.changeUser()
                    }else{
                        this.userList = []
                        this.userId = null
                    }
                })
                .catch(err=>{
                    console.log(err)
                })
        },
        changeUser(){
            for(let i=0;i<this.studentList.length;i++){
                this.studentList[i].status = false
            }
            let index = this.userList.findIndex(user=>user.id == this.userId);
            let imei = this.userList[index].imei
            if(imei != null){
                for(let i=0;i<imei.imeiList.length;i++){
                    // this.studentList[imei.imeiList[i]].status = true
                    let index = this.studentList.findIndex(stud=>stud.imei == imei.imeiList[i].imei)
                    this.studentList[index].status = true
                }
            }else{
                console.log('no imei')
            }
        },
        async saveImei(){
            for(let i=0;i<this.studentList.length;i++){
                if(this.studentList[i].status == true){
                    let element = {}
                    element.imei = this.studentList[i].imei
                    element.name = this.studentList[i].name
                    this.activeUsers.push(element)
                }
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/imeiList',{imeiList:this.activeUsers,userId:this.userId})
            this.isLoading = false
            if(res.status == 201 || res.status == 200){
                this.activeUsers = []
                this.success('操作成功')
                this.changeRole()
            }
        }
    }
}
</script>

<style>

</style>