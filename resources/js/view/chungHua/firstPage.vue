<template>
    <div>
        <div class="w-100 row m-0 p-4 grid" v-if="!$isMobile()">
            <figure v-for="school in getUserPermission" :key="school.id" class="col-12 col-md-4 eschool-item-select effect-apollo" @click="selSchool(school)">
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

    async mounted(){
       
    },

    async created(){

    },
    methods:{
        selSchool(school){
            this.$router.push({path:`/schoolSpace/${school.schoolId}`})
        }
    }
}
</script>
