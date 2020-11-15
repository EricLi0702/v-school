<template>
    <div>
        <!-- delete model -->
        <Modal 
            :value="getDeleteModalObj.showDeleteModal"
            :mask-closable ="false"
            :closable="false"
            width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>删除确认</span>
            </p>
            <div style="text-align:center">
                <p>你会删除吗?</p>
            </div>
            <div slot="footer">
                <Button type="default" size="large" @click="closeModal">取消</Button>
                <Button type="error" size="large" :loading="isDeleting" @click="deleteCategory">提交</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    data(){
        return{
            isDeleting:false,
        }
    },
    methods:{
        async deleteCategory(){
            this.isDeleting = true;
            const res = await this.callApi('delete',this.getDeleteModalObj.deleteUrl,this.getDeleteModalObj.data);
            if(res.status == 200){
                this.success('类别已成功删除！');
                this.$store.commit('setDeleteModal',true);
            }else{
                this.swr();
                this.$store.commit('setDeleteModal',false);
            }
            this.isDeleting = false;
            
        },
        closeModal(){
            this.$store.commit('setDeleteModal',false);
        }
    },
    computed:{
        ...mapGetters([
            'getDeleteModalObj'
        ])
    },
    watch:{
        getDeleteModalObj(value){
        }
    }
}
</script>