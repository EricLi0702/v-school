<template>
    <div class="w-100 es-view">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
            <div class="_overflow _table_div">
                <editor
                    ref="editor"
                    autofocus
                    holder-id="codex-editor"
                    save-button0d="save-button"
                    :init-data="initData"
                    @save="onSave"
                />
                <Button @click="save">Save the data</Button>
            </div>
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem';

export default {
    components:{
        menuItem,
    },
    data () {
        return {
            
            addData:{
                iconImage:'',
                categoryName:''
            },
            initData:null,
           config: {
                image: {
                // Like in https://github.com/editor-js/image#config-params
                    field: 'image',
                    types: 'image/*',
                },
            }
        }
    },
    async created(){
    
    },
    methods:{
       addModalemit(value){
           this.addModal = value;
       },
       onSave(response){
       },
       save(){
       },

    },
    computed : {
        
    },

    watch : {
        
    }
    
}
</script>