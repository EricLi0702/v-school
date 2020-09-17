<template>
    <div>
        <div v-for ="(subMenu , i) in menuListsJson" :key="i">
            <div class="category-title">
                <span>{{subMenu.categoryTitle}}</span>
            </div>
            <div v-for="(itemList,j) in subMenu.items" :key="j" class="es-item">
                <div class="es-item-left">
                    <span>{{itemList.itemName}}</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="itemList.itemStatus" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import menuListsJson from '../../json/chungHua/从化第四中学-学校空间-应用中-modal心.json'
export default {
    data(){
        return{
            menuListsJson,
            currentMenu:[],
        }
    },
    async created(){
        this.currentMenu = this.menuListsJson
        const res = await this.callApi('get','/api/applicationCenter')
        if(res.status == 200){
            this.currentMenu = res.data
        }
        console.log(this.currentMenu)
    }
}
</script>