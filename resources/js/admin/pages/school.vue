<template>
    <div class="w-100 es-view">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        
        
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>SchoolName</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(school,i) in schoolList" :key="i" v-if="schoolList.length">
                        <td>{{school.id}}</td>
                        <td class="_table_name">{{school.schoolName}}</td>
                        <td>{{school.created_at}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(school,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(school,i)" :loading="school.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add school"
            >
                <Input v-model="modalData.schoolName" placeholder="Enter something..." style="width: 300px" />
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add school'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit school"
            >
                <Input v-model="editData.schoolName" placeholder="Enter something..." style="width: 300px" />
                <div slot="footer">
                    <Button type="default" @click="editModal=false">Close</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit school'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal v-model="showDeleteModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Will you delete it?</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deleteTag">Delete</Button>
                </div>
            </Modal>
            <!-- <Page :total="100" /> -->
        </div>
        
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem'
export default {
    components:{
        menuItem
    },
    data () {
        return {
            modalData:{
                schoolName:'',
            },
            addModal:false,
            isAdding:false,
            schoolList:[],
            editModal:false,
            editData:{
                schoolName:''
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1
        }
    },
    async created(){
        const res = await this.callApi('get','api/school');
        if(res.status == 200){
            this.schoolList = res.data;
        }
    },
    methods:{
       addModalemit(value){
           
           this.addModal = value;
       },
       async addTag(){
            this.isAdding = true;
                const res = await this.callApi('post', '/api/school',this.modalData)
            if(res.status === 201){
                this.schoolList.unshift(res.data);
                this.success('学校已成功添加！');
                this.addModal = false;
                this.modalData.schoolName = '';
            }else{
                if(res.status === 422){
                    if(res.data.errors.schoolName){
                        this.info(res.data.errors.schoolName[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
            this.isAdding = false;
       },

       async editTag(){
           this.isAdding = true;
            const res = await this.callApi('put', 'api/school',this.editData)
           if(res.status === 200){
               this.schoolList[this.index].schoolName = this.editData.schoolName;
               this.success('学校已成功添加！');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   if(res.data.errors.schoolName){
                       this.info(res.data.errors.schoolName[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(school,index){
            let obj = {
                id:school.id,
                schoolName:school.schoolName
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','api/school',this.deleteItem);
            if(res.status == 200){
                this.schoolList.splice(this.deletingIndex,1);
                this.success('标记已成功删除！');
            }else{
                this.swr();
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(tag,i){
            // if(!confirm('Are you sure you want to delete this tag?')) return
            this.deleteItem = tag;
            this.deletingIndex = i;
            this.showDeleteModal = true;
        }
    }
}
</script>