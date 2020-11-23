<template>
    <div class="w-100 d-flex">
        <div v-for="school in schoolList" :key="school.id" class="m-3 border border-primary" @click="selSchool(school)">
            <div style="text-align:center">
                <img class="card-image" :src="school.imgUrl">
                <h3 class="mt-3">{{school.schoolName}}</h3>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data(){
        return {
            schoolList:[]
        }
    },
    async created(){
        const res = await this.callApi('get','/api/school')
        if(res.status == 200){
            console.log(res.data)
            this.schoolList = res.data
        }
    },
    methods:{
        selSchool(school){
            let index = this.$store.state.userPermission.findIndex(userPermission=>userPermission.schoolName.resourceName == school.schoolName);
            if(this.$store.state.userPermission[index].schoolName.read == true){
                for(let i=0;i<this.$store.state.userPermission[index].data.length;i++){
                    if(this.$store.state.userPermission[index].data[i].read == true){
                        this.$router.push({path:this.$store.state.userPermission[index].data[i].name})
                        return
                    }
                }
            }else{
                this.error('权限错误')
            }
        }
    }
}
</script>
