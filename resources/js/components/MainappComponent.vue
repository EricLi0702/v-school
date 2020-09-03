<template>
  <div :class="{['container mt-80']:$store.state.user}">
    <div :class="{['d-flex _box_shadow sub-container']:$store.state.user}">
        <div v-if="$store.state.user">
            <div class="header">
                <div class="_2menu _box_shadow">
                    <div class="container">
                        <a class=" float-right" href="/logout">{{user.name}}</a>
                    </div>
                </div>
            </div>
            <div class="_1side_menu" >
                <div class="_1side_menu_content">
                    <div class="_1side_menu_list">
                        <ul class="_1side_menu_list_ul">
                            <Menu :open-names="['1']">
                                <Submenu name="1">
                                    <template slot="title">
                                        <Icon type="ios-analytics" />
                                        Navigation One
                                    </template>
                                    <MenuItem v-for="(menuItem,i) in permission" :key="i" v-if="permission.length && menuItem.read" :name="i">
                                        <router-link :to="menuItem.name">{{ menuItem.resourceName }}</router-link>
                                    </MenuItem>
                                    <!-- <MenuItem name="1-1"><router-link to="/">Tag</router-link></MenuItem>
                                    <MenuItem name="1-2"><router-link to="/category">Category</router-link></MenuItem>
                                    <MenuItem name="1-3"><router-link to="/adminuser">Admin Users</router-link></MenuItem>
                                    <MenuItem name="1-4"><router-link to="/role">Role Management</router-link></MenuItem>
                                    <MenuItem name="1-5"><router-link to="/assignRole">Assign Role</router-link></MenuItem> -->
                                </Submenu>
                                <Submenu name="2">
                                    <template slot="title">
                                        <Icon type="ios-filing" />
                                        Navigation Two
                                    </template>
                                    <MenuItem name="2-1">Option 5</MenuItem>
                                    <MenuItem name="2-2">Option 6</MenuItem>
                                    <!-- <Submenu name="3">
                                        <template slot="title">Submenu</template>
                                        <MenuItem name="3-1">Option 7</MenuItem>
                                        <MenuItem name="3-2">Option 8</MenuItem>
                                    </Submenu> -->
                                </Submenu>
                                <Submenu name="4">
                                    <template slot="title">
                                        <Icon type="ios-cog" />
                                        Navigation Three
                                    </template>
                                    <MenuItem name="4-1">Option 9</MenuItem>
                                    <MenuItem name="4-2">Option 10</MenuItem>
                                    <MenuItem name="4-3">Option 11</MenuItem>
                                    <MenuItem name="4-4">Option 12</MenuItem>
                                </Submenu>
                            </Menu>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <router-view/>
    </div>

    <div class="footer">
        <p class="text-center">copyright &#169; All reserved school</p>
    </div>
    
  </div>
</template>
<script>
export default {
    props:['user','permission'],
    data(){
        return{
            isLoggedin:false,
            // loginView:false,
        }
    },
    created(){
        this.$store.commit('setUpdateUser',this.user);
        this.$store.commit('setUserPermission',this.permission);
    }
}
</script>


