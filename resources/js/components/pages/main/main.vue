<template>
    
    <div class="h-100 w-100">
        <div class="fp-top-bg-container">
            <img src="/img/login.jpg" alt="" class="w-100">
        </div>
        <div class="app-main-school-menu w-100" v-if="$store.state.user">
                    <!-- <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i" v-if="permissionList.schoolName.read">
                        <template slot="title">
                            <Icon type="ios-analytics" />
                            {{permissionList.schoolName.resourceName}}
                        </template>
                        <MenuItem  :name="`${i}-${j}`" :to="`/${menuItem.name}`" v-for="(menuItem,j) in permissionList.data" :key="j" v-if="permissionList.data.length && menuItem.read">
                            {{ menuItem.resourceName }}
                        </MenuItem>
                    </Submenu> -->

            <div class="text-center p-2 bg-light-gray">
                <p>admin</p>
            </div>
            <div class="app-fp-main-menu-cont">
                <div class="vx-item">
                    someting
                </div>
                <div class="vx-item">
                    someting
                </div>
                <div class="vx-item">
                    someting
                </div>
            </div>
            <div class="text-center p-2 bg-light-gray">
                <p>schools</p>
            </div>
            <div class="app-fp-main-menu-cont">
                <div class="vx-item">
                    someting
                </div>
                <div class="vx-item">
                    someting
                </div>
                <div class="vx-item">
                    someting
                </div>
            </div>
        </div>
        <div class="container-fluid app-footer-navigate-container bg-light-gray m-0 p-0">
            <div class="row m-0 p-0">
                <div class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                    <Icon size="25" color="#FFFFFF" type="ios-people" class="p-2" />
                </div>
                <div class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                    <Icon size="25" color="#FFFFFF" type="ios-people" class="p-2" />
                </div>
                <div class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                    <Icon size="25" color="#FFFFFF" type="ios-people" class="p-2" />
                </div>
            </div>
        </div>
    </div>
</template>