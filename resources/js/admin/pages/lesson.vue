<template>
    <div class="w-100">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        
        
        <div class="container content-container">
            <perfect-scrollbar>
            <div class="_overflow_table_div">
                <table class="_table">
                    <tr>
                        <th>ID</th>
                        <th>SchoolName</th>
                        <th>SchoolType</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(tag,i) in tags" :key="i" v-if="tags.length">
                        <td>{{tag.id}}</td>
                        <td class="_table_name">{{tag.tagName}}</td>
                        <td class="_table_name">{{tag.tagType}}</td>
                        <td>{{tag.created_at}}</td>
                        <td>
                            <Button type="info" size="small" @click="showEditModal(tag,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(tag,i)" :loading="tag.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>
            </perfect-scrollbar>

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add tag"
            >
                <Input v-model="modalData.tagName" placeholder="Enter something..." style="width: 300px" />
                <Select v-model="modalData.tagType" style="width:200px">
                    <Option>高</Option>
                    <Option>初</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add tag'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit tag"
            >
                <Input v-model="editData.tagName" placeholder="Enter something..." style="width: 300px" />
                 <Select v-model="modalData.tagType" style="width:200px">
                    <Option>高</Option>
                    <Option>初</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">Close</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit tag'}}</Button>
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
            // data: [
            //     {
            //         title: 'This is title 1',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     },
            //     {
            //         title: 'This is title 2',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     },
            //     {
            //         title: 'This is title 3',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     }
            // ],
            modalData:{
                tagName:'',
                tagType:'',
            },
            addModal:false,
            isAdding:false,
            tags:[],
            editModal:false,
            editData:{
                tagName:'',
                tagType:''
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1
        }
    },
    async created(){
        const res = await this.callApi('get','api/tag');
        if(res.status == 200){
            // //console.log(res)
            this.tags = res.data;
            //console.log(res);
        }
    },
    methods:{
       addModalemit(value){
           //console.log('##########',value);
           this.addModal = value;
       },
       async addTag(){
            if(this.modalData.tagName.trim()==''){
                return this.error('SchoolName is required');
            }
            if(this.modalData.tagType.trim()==''){
                return this.error('SchoolType is required');
            }
            this.isAdding = true;
                const res = await this.callApi('post', '/api/school',this.modalData)
            if(res.status === 201){
                this.tags.unshift(res.data);
                this.success('School has been added successfully!');
                this.addModal = false;
                this.modalData.tagName = '';
            }else{
                if(res.status === 422){
                    if(res.data.errors.tagName){
                        this.info(res.data.errors.tagName[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
            this.isAdding = false;
       },

       async editTag(){
           this.isAdding = true;
           if(this.editData.tagName.trim()==''){
               return this.error('SchoolName is required');
           }
           if(this.editData.tagType.trim()==''){
               return this.error('SchoolType is required');
           }
            const res = await this.callApi('put', 'api/school',this.editData)
           if(res.status === 200){
               this.tags[this.index].tagName = this.editData.tagName;
               this.success('School has been added successfully!');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   if(res.data.errors.tagName){
                       this.info(res.data.errors.tagName[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(tag,index){
            let obj = {
                id:tag.id,
                tagName:tag.tagName
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
                this.tags.splice(this.deletingIndex,1);
                this.success('Tag has been deleted successfully!');
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