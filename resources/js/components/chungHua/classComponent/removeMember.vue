<template>
    <div>
        <div class="contact-user es-item es-item-list" v-for="contact in contacts" :key="contact.phoneNumber" @click="selUser(contact)">
            <div class="es-item-left">
                <Checkbox v-model="contact.active"></Checkbox>
                <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                <div class="es-item-info">
                    <div class="title">
                        <span>{{contact.name}}</span>
                    </div>
                    <div class="main">
                        <span>{{contact.phoneNumber}}</span>
                    </div>
                </div>
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward"></Icon>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="delMember" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar'
export default {
    components:{
        Avatar,
    },
    data(){
        return{
            contacts:[],
            isLoading:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        axios.get('/api/lessonMember',{params:{
            id:this.currentPath.params.className
        }}).then(res=>{
            this.contacts = res.data
            for(let i=0;i<this.contacts.length;i++){
                this.$set(this.contacts[i],'active',false)
            }
        }).catch(err=>{
            console.log(err)
        })
    },
    methods:{
        async delMember(){
            console.log(this.contacts)
            let delusers = []
            for(let i=0;i<this.contacts.length;i++){
                if(this.contacts[i].active == true){
                    delusers.push(this.contacts[i].id)
                }
            }
            console.log(delusers)
            this.isLoading = true
            const res = await this.callApi('delete','/api/member',{id:delusers})
            this.isLoading = false
            console.log(res)
            if(res.status == 200){
                this.success('操作成功')
                let contacts = this.contacts
                this.contacts = []
                for(let i=0;i<contacts.length;i++){
                    if(contacts[i].active == false){
                        this.contacts.push(contacts[i])
                    }
                }

            }
            // this.$router.push({path:this.currentPath.path})
        },
        selUser(contact){
            contact.active = ! contact.active
        }
    }
}
</script>

<style>

</style>