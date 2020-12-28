<template>
    <div>
        <div class="w-100 row m-0 p-4 grid" v-if="!$isMobile()">
            <figure v-for="school in schoolList" :key="school.id" class="col-12 col-md-4 eschool-item-select effect-apollo" @click="selSchool(school)">
                <img class="card-image p-3 w-100" :src="school.imgUrl">
                <figcaption>
                    <h2 class="m-3">{{school.schoolName}}</h2>
                </figcaption>
            </figure>
        </div>
        <div v-else class="h-100 w-100">
            <div class="fp-top-bg-container">
                <img src="/img/login.jpg" alt="" class="w-100">
            </div>
            <div class="app-main-school-menu w-100" v-if="$store.state.user">
                <Menu class="w-100">
                    <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i" v-if="permissionList.schoolName.read">
                    <!-- <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i" > -->
                        <template slot="title">
                            <Icon type="ios-analytics" />
                            {{permissionList.schoolName.resourceName}}
                        </template>
                        <MenuItem  :name="`${i}-${j}`" :to="`/${menuItem.name}`" v-for="(menuItem,j) in permissionList.data" :key="j" v-if="permissionList.data.length && menuItem.read">
                        <!-- <MenuItem  :name="`${i}-${j}`" :to="`/${menuItem.name}`" v-for="(menuItem,j) in permissionList.data" :key="j" > -->
                            {{ menuItem.resourceName }}
                        </MenuItem>
                    </Submenu>
                </Menu>
            </div>
            
        </div>
    </div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'

export default {
    data(){
        return {
            schoolList:[],
            permission:null,
        }
    },
    computed:{
        ...mapGetters([
            'getUserPermission'
        ])
    },

    mounted(){
        this.permission = this.getUserPermission;
        console.log('**********************',this.getUserPermission)
    },

    async created(){

    },
    methods:{
        selSchool(school){
            // let index = this.$store.state.userPermission.findIndex(userPermission=>userPermission.schoolName.resourceName == school.schoolName);
            // if(index != -1){
            //     if(this.$store.state.userPermission[index].schoolName.read == true){
            //         for(let i=0;i<this.$store.state.userPermission[index].data.length;i++){
            //             if(this.$store.state.userPermission[index].data[i].read == true){
            //                 this.$router.push({path:this.$store.state.userPermission[index].data[i].name})
            //                 return
            //             }
            //         }
            //     }else{
            //         this.error('权限错误')
            //     }
            // }else{
            //     this.error('权限错误')
            // }
            this.$router.push({path:`/schoolSpace/${school.id}`})
        }
    }
}
</script>
