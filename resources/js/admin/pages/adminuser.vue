<template>
    <div class="w-100 es-view mt-2">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal"  v-if="isWritePermitted"><Icon type="md-add"/> 添加</Button>
            <div class="float-right">
                <Button type="info" class="addbtn m-2" @click="userExport"  v-if="isWritePermitted"><Icon type="ios-cloud-download-outline" /> 输出</Button>
                <Button type="info" class="addbtn m-2" @click="userImport"  v-if="isWritePermitted"><Icon type="ios-cloud-upload-outline" /> 输入</Button>
            </div>
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>号码</th>
                        <th>名称</th>
                        <th>电话号码</th>
                        <th>角色</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(user,i) in users" :key="i" v-if="users.length">
                        <td>{{user.id}}</td>
                        <td class="_table_name">{{user.name}}</td>
                        <td>{{user.phoneNumber}}</td>
                        <td>{{roles[user.roleId-1].roleName}}</td>
                        <td>{{TimeView(user.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(user,i)" v-if="isUpdatePermitted">编辑</Button>
                            <Button type="primary" size="small" @click="allow(user)" v-if="isUpdatePermitted">{{user.isActived == 0?'激活':'禁用'}}</Button>
                            <Button type="error" size="small" @click="showDeletingModal(user,i)" :loading="user.isDeleting" v-if="isDeletePermitted">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add admin"
                draggable 
                scrollable
            >
                <Input type="text" v-model="modalData.name" placeholder="全名" class="mb-2" />
                <Input type="text" v-model="modalData.phoneNumber" placeholder="电话号码" class="mb-2" />
                <Input type="password" v-model="modalData.password" placeholder="密码" class="mb-2" />
                <Select v-model="modalData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                    
                </Select>
                <div slot="footer">
                    <Button type="default" @click="addModal=false">取消</Button>
                    <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="编辑个人资料"
                draggable 
                scrollable
            >
                <Input type="text" v-model="editData.name" placeholder="全名" class="mb-2" />
                <Input type="text" v-model="editData.phoneNumber" placeholder="电话号码" class="mb-2" />
                <Input type="password" v-model="editData.password" placeholder="密码" class="mb-2" />
                <Select v-model="editData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal class="delete-modal" v-model="showDeleteModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>删除确认</span>
                </p>
                <div style="text-align:center">
                    <p>你会删除吗？</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deleteTag">删除</Button>
                </div>
            </Modal>
            <!-- <Page :total="100" /> -->
            <Modal
                v-model="uploadModal"
                class="uploadModal"
                title="导入习题"
                footer-hide
                :styles="{top:'140px',left:'64px'}">
                    <Upload
                        ref="otherUploads"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        type="drag"
                        :on-success="otherSuccess"
                        :on-error="handleError"
                        :format="['xls','xlsx']"
                        :max-size="524288"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/api/fileUpload/userImport">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>将文件拖到此处，或 <span class="text-color">点击上传</span></p>
                        </div>
                    </Upload>
                    <!-- <div class="es-item-tooltip">
                        <div>导入说明</div> 
                        <div>1、必须按正确的格式将数据填入模板 <a href="/download/doc/praxisTemplate.doc" class="text-color" download>（ 下载模板 ）</a></div>
                        <div>2、文件格式必须为xls、xlsx、doc。</div>
                    </div> -->
            </Modal>
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
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
            },
            addModal:false,
            isAdding:false,
            users:[],
            editModal:false,
            editData:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            roles:[],
            uploadModal:false,
            token:window.Laravel.csrfToken,
            uploadExcelFile:null,
        }
    },
    async created(){
        const [res,resRole] = await Promise.all([
            this.callApi('get','/api/users'),
            this.callApi('get','/api/role')
        ])
        if(res.status == 200){
            this.users = res.data;
        }else{
            this.swr();
        }
        if(resRole.status == 200){
            this.roles = resRole.data;
        }else{
            this.swr();
        }
    },
    methods:{
        showModal(){
            this.addModal = true;
        },
        async addAdmin(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/addUsers',this.modalData)
            if(res.status == 201){
                this.users.unshift(res.data);
                this.success('管理员用户已成功添加！');
                this.addModal = false;
                this.modalData.name = '';
                this.modalData.phoneNumber = '';
                this.modalData.password = '';
                this.modalData.roleId = null;
                this.isAdding = false

            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
                }else{
                    this.swr()
                }
               
            }
            this.isAdding = false;
        },

       async editUser(){
           this.isAdding = true;
            
            const res = await this.callApi('put', '/api/users',this.editData)
           if(res.status === 200){
               this.users[this.index].name = this.editData.name;
               this.users[this.index].phoneNumber = this.editData.phoneNumber;
               this.users[this.index].roleId = this.editData.roleId;
               this.success('管理员用户已成功添加！');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(user,index){
            let obj = {
                id:user.id,
                name:user.name,
                phoneNumber:user.phoneNumber,
                roleId:user.roleId,
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','/api/users',this.deleteItem);
            if(res.status == 200){
                this.users.splice(this.deletingIndex,1);
                this.success('用户已成功删除！');
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
        },
        async allow(user){
            if(user.isActived == 0){
                user.isActived = 1
            }else if(user.isActived == 1){
                user.isActived = 0
            }
            const res = await this.callApi('put','/api/profile',{isActived:user.isActived,userId:user.id})
            if(res.status == 200){
                this.success('操作成功')
            }
        },
        async userExport(){
            location.href="file-export"
        },
        async userImport(){
            this.uploadModal = true
        },
        otherSuccess (res, file) {
            // let url = `/uploads/other/${res.fileName}`;
            // this.$set(res,'imgUrl',url)
            // this.questionData.otherUrl.push(res);
            console.log(res)
            if(res ==1){
                this.success('操作成功')
                this.uploadModal = false
            }
        },
        handleError (res, file) {
            this.error('导入出错')
            console.log(res)
            // this.$Notice.warning({
            //     title:'文件格式不正确',
            //     desc:`${file.errors.file.length ? file.errors.file[0] : '出了些问题！'}`
            // })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式 ' + file.name + '错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + '太大，不超过512M。'
            });
        },
        handleImageUpload(file){
            this.uploadExcelFile = file;
            console.log(this.uploadExcelFile)
            return false;
        },
        ok(){
            if(this.uploadExcelFile){
                let formdata = new FormData();
                formdata.append('file',this.uploadExcelFile)
                axios.post(`/api/fileUpload/userImport`, formdata ,{
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    }
                })
                .then((res) => {
                    if(res.errors){
                    this.$Notice.warning({
                        title: 'Something went wrong',
                        desc: res.errors
                    });
                    }
                    console.log(res)
                    // this.messages.push(res.data.message);
                });
            }
        },
        cancel(){

        }
    }
}
</script>