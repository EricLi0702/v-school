<template>
    <div class="es-index">
        <div class="es-header"  v-if="$store.state.user">
            <div class="es-container row">
                <div class="es-header-logo">
                    <img class="header-logo-img" src="img/logo.png"/>
                </div>
                <div class="es-header-main">
                    <Input suffix="ios-search" placeholder="Enter text" style="width: auto" />
                </div>
                <div class="es-header-profile">
                    <Avatar icon="ios-person" />
                    <span>{{user.name}}</span>
                    <span><a href="/logout" style="color:#cccaca">退出</a></span>
                </div>
            </div>
        </div>
        <div :class="{['es-container container-shadow']:$store.state.user}">
            <div class="es-menu" v-if="$store.state.user">
                <Menu :open-names="['1']">
                    <Submenu name="1">
                        <template slot="title">
                            <Icon type="ios-analytics" />
                            Navigation One
                        </template>
                        <MenuItem v-for="(menuItem,i) in permission" :key="i" v-if="permission.length && menuItem.read" :name="i">
                            <router-link :to="menuItem.name">{{ menuItem.resourceName }}</router-link>
                        </MenuItem>
                    </Submenu>
                </Menu>
            </div>
            <div :class="{'es-router':$store.state.user}">
                <router-view/>
            </div>
        </div>
        <div class="es-footer">
            copyright &#169; All reserved school
        </div>
  </div>
</template>
<script>
import fab from 'vue-fab'
export default {
    props:['user','permission'],
    components:{
        fab,
    },
    data(){
        return{
            isLoggedin:false,
            // loginView:false,
            bgColor: '#2d8cf0',
            bottomRight:'bottom-right',
            topLeft:"top-left",
            fabActions: [
                {
                    name: 'cache',
                    icon: 'cached'
                },
                {
                    name: 'alertMe',
                    icon: 'add_alert'
                }
            ]

        }
    },
    created(){
        this.$store.commit('setUpdateUser',this.user);
        this.$store.commit('setUserPermission',this.permission);
    },
    methods:{
      cache(){
          console.log('Cache Cleared');
      },
      alert(){
          alert('Clicked on alert icon');
      }
  }
}
</script>


