<template>
    <div class="w-100 row m-0 p-4 grid">
        <figure v-for="school in schoolList" :key="school.id" class="col-12 col-md-4 eschool-item-select effect-apollo" @click="selSchool(school)">
            <img class="card-image p-3 w-100" :src="school.imgUrl">
            <figcaption>
                <h2 class="m-3">{{school.schoolName}}</h2>
            </figcaption>
        </figure>
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
