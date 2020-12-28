<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal" ><Icon type="md-add"/> 添加</Button>
            <div class="float-right">
                <Button type="info" class="addbtn m-2" @click="userExport"><Icon type="ios-cloud-download-outline" /> 输出</Button>
                <Button type="info" class="addbtn m-2" @click="userImport"><Icon type="ios-cloud-upload-outline" /> 输入</Button>
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
                        <td>{{i+1}}</td>
                        <td class="_table_name">{{user.name}}</td>
                        <td>{{user.phoneNumber}}</td>
                        <td>{{roles[user.roleId-1].roleName}}</td>
                        <td>{{TimeView(user.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(user,i)">编辑</Button>
                            <Button type="primary" size="small" @click="allow(user)">{{user.isActived == 0?'激活':'禁用'}}</Button>
                            <Button type="error" size="small" @click="showDeletingModal(user,i)" :loading="user.isDeleting">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="新增人员"
                scrollable
                :styles="{top:'75px',left:'-90px'}"
                @on-visible-change="changeVisibleAddModal"
            >
                <div class="row m-0 p-0">
                    <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                <p class="min-width-fit-content text-right pr-2">人员类型 : </p>
                                <Select v-model="modalData.roleId" placeholder="请输入人员类型" @on-change="registerUserSelectType">
                                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                                </Select>
                            </div>
                        </div>
                        <div v-if="isRegisterStudent" class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">人员姓名 : </p>
                                    <Input type="text" v-model="modalData.name" placeholder="请输入人员姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">民族 : </p>
                                    <Input type="text" v-model="modalData.nation" placeholder="请输入民族"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">身份证号 : </p>
                                    <Input type="text" v-model="modalData.cardNum" placeholder="请输入身份证号"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                    <Select v-model="modalData.gender" placeholder="请输入性别">
                                        <Option value="M">男</Option>
                                        <Option value="F">女</Option>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                    <Input type="text" v-model="modalData.phoneNumber" placeholder="请输入电话号码"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                    <Input type="password" password v-model="modalData.password" placeholder="请输入使用密码"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">户籍地址 : </p>
                                    <Select v-model="modalData.residenceAddress.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(modalData.residenceAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.residenceAddress.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(modalData.residenceAddress.city)" placeholder="--市--">
                                        <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(modalData.residenceAddress.region)" placeholder="--区--">
                                        <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <div class="col-12 mb-2" style="padding-left:90px;">
                                    <Input type="text" v-model="modalData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家庭地址 : </p>
                                    <Select v-model="modalData.familyAddress.province" class="mr-2" @on-change="selectedProvinceOfFamilyAddress(modalData.familyAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.familyAddress.city" class="mr-2" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(modalData.familyAddress.city)" placeholder="--市--">
                                        <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(modalData.familyAddress.region)" placeholder="--区--">
                                        <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <div class="col-12 mb-2" style="padding-left:90px;">
                                    <Input type="text" v-model="modalData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                                </div>
                            </div>
                        </div>
                        <div v-else class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">人员姓名(sammie) : </p>
                                    <Input type="text" v-model="modalData.name" placeholder="请输入人员姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">民族 : </p>
                                    <Input type="text" v-model="modalData.nation" placeholder="请输入民族"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">身份证号 : </p>
                                    <Input type="text" v-model="modalData.cardNum" placeholder="请输入身份证号"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                    <Select v-model="modalData.gender" placeholder="请输入性别">
                                        <Option value="M">男</Option>
                                        <Option value="F">女</Option>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                    <Input type="text" v-model="modalData.phoneNumber" placeholder="请输入电话号码"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                    <Input type="password" password v-model="modalData.password" placeholder="请输入使用密码"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">户籍地址 : </p>
                                    <Select v-model="modalData.residenceAddress.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(modalData.residenceAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.residenceAddress.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(modalData.residenceAddress.city)" placeholder="--市--">
                                        <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(modalData.residenceAddress.region)" placeholder="--区--">
                                        <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <div class="col-12 mb-2" style="padding-left:90px;">
                                    <Input type="text" v-model="modalData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家庭地址 : </p>
                                    <Select v-model="modalData.familyAddress.province" class="mr-2" @on-change="selectedProvinceOfFamilyAddress(modalData.familyAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.familyAddress.city" class="mr-2" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(modalData.familyAddress.city)" placeholder="--市--">
                                        <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                    <Select v-model="modalData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(modalData.familyAddress.region)" placeholder="--区--">
                                        <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <div class="col-12 mb-2" style="padding-left:90px;">
                                    <Input type="text" v-model="modalData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <avatar v-if="modalData.name !== '' && modalData.imgUrl == null" :size="100" :username="modalData.name" class="mx-auto" :rounded="false"></avatar>
                                <img v-else-if="modalData.name == '' && modalData.imgUrl == null" src="/img/icon/anonymous_avatar.png" alt="" style="width:100px; height: 100px;">
                                <img v-else-if="modalData.imgUrl !== null" :src="modalData.imgUrl" alt="" style="width:100px; height: 100px;">
                                <Icon v-if="modalData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="modalData.imgUrl = null" class="position-absolute" style="top:-1px;right:19px; cursor:pointer;" />
                                <Upload
                                    ref="editDataImage"
                                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                    :on-success="handleSuccessAvatarImage"
                                    :on-error="handleErrorAvatarImage"
                                    :format="['jpg','jpeg','png']"
                                    :max-size="10240"
                                    :on-format-error="handleFormatErrorAvatarImage"
                                    :on-exceeded-size="handleMaxSizeAvatarImage"
                                    action="/api/category/upload">
                                        <Button type="primary" style="width: 97%;">
                                            <Icon type="ios-person"></Icon>
                                            上传头像
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div class="es-item-tooltip">
                        <div>导入说明</div> 
                        <div>1、必须按正确的格式将数据填入模板 <a href="/download/doc/users-collection.xlsx" class="text-color" download>（ 下载模板 ）</a></div>
                        <div>2、文件格式必须为xls、xlsx、doc。</div>
                    </div>
            </Modal>
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem';
import cityListJson from '!raw-loader!./cityLaw.txt';
import Avatar from 'vue-avatar'
export default {
    components:{
        menuItem,
        Avatar,
    },
    data () {
        return {
            modalData:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
                gender:null,
                nation : '',
                cardNum : '',
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                residenceAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                imgUrl : null,
            },
            registerStudentData : {
                name : '',
                phoneNumber : '',
                cardNum : '',
                fatherName : '',
                fatherPhone : '',
                fatherJob : null,
                birthday : '',
                gender : null,
                class : null,
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                introduce : '',
                imgUrl : null,
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
            provinceListJsonArr:[],
            madeJsonFromString : [],
            willBeCityDataOfResidenceAddress : null,
            willBeCityDataOfFamilyAddress : null,
            willBeRegionDataOfResidenceAddress : null,
            willBeRegionDataOfFamilyAddress : null,
            isRegisterStudent : false,
        }
    },
    async created(){
        this.provinceListJsonArr = cityListJson.split("#");
        for (let i = 0; i < this.provinceListJsonArr.length; i++) {
            let provinceObj = {
                value : 1,
                label : "",
                city : []
            }
            let province = this.provinceListJsonArr[i].split("$")[0];
            provinceObj.value = province.split("-")[0];
            provinceObj.label = province.split("-")[1];
            this.madeJsonFromString.push(provinceObj);
            let TArea = this.provinceListJsonArr[i].split("$")[1].split("|");
            for(let j = 0 ; j < TArea.length ; j++){
                let cityObj = {
                    value : 1,
                    label : "",
                    region : []
                }
                let cityArr = TArea[j].split(",");
                cityObj.value = cityArr[0].split("-")[0];
                cityObj.label = cityArr[0].split("-")[1];
                for( let k = 1 ; k < cityArr.length ; k++){
                    let regionObj = {
                        value : 1, 
                        label : "",
                    }
                    regionObj.value = cityArr[k].split("-")[0];
                    regionObj.label = cityArr[k].split("-")[1];
                    cityObj.region.push(regionObj);
                }
                this.madeJsonFromString[i].city.push(cityObj);
            }
        }
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
            console.log(this.modalData);
            //name validation
            if(this.modalData.name.trim() == ''){
                return this.error("请输入姓名");
            }
            //nation validation
            if(this.modalData.nation.trim() == ''){
                return this.error("请输入民族");
            }
            //card number validation
            if(this.modalData.cardNum.trim() == ''){
                return this.error('请输入身份证号');
            }
            if(/^\d*$/.test(this.modalData.cardNum) == false){
                return this.error('请输入正确的身份证号');
            }
            //role id validation
            if(this.modalData.roleId == null){
                return this.error('请输入人员类型');
            }
            //gender validation
            if(this.modalData.gender == null){
                return this.error('请输入性别');
            }
            //phone number validation
            if(this.modalData.phoneNumber.trim() == ''){
                return this.error('请输入电话号码');
            }
            if(/^\d*$/.test(this.modalData.phoneNumber) == false){
                return this.error('请输入正确的电话号码');
            }
            //password validation
            if(this.modalData.password.trim() == ''){
                return this.error('请输入使用密码')
            }
            //check if password contain number
            if(/\d/.test(this.modalData.password) == false){
                return this.error('密码应包含数字')
            }
            //check if password contain uppercase
            if(/[A-Z]/.test(this.modalData.password) == false){
                return this.error('密码应至少包含1个大写字母')
            }
            //check if password contain special character
            if(/[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.modalData.password) == false){
                return this.error('密码应包含至少1个特殊字符')
            }
            //check if length of password is more than 8
            if(this.modalData.password.length < 8){
                return this.error('密码至少应包含8个字符')
            }
            //family address validation
            if( this.modalData.familyAddress.province == null ||
                this.modalData.familyAddress.city == null ||
                this.modalData.familyAddress.region == null ||
                this.modalData.familyAddress.detail == ''){
                    return this.error('请输入户籍地址');
            }
            //residence address validation
            if( this.modalData.residenceAddress.province == null ||
                this.modalData.residenceAddress.city == null ||
                this.modalData.residenceAddress.region == null ||
                this.modalData.residenceAddress.detail == ''){
                    return this.error('请输入家庭地址');
            }

            
            this.isAdding = true;
            const res = await this.callApi('post', '/api/addUsers',this.modalData)
            if(res.status == 201){
                this.users.unshift(res.data);
                this.success('管理员用户已成功添加！');
                this.addModal = false;
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
            // if(res ==1){
            //     this.success('操作成功')
            //     this.uploadModal = false
            // }
            if(res.status == 400){
                this.error(res.phoneNumber+res.msg)
                return
            }
            this.success('操作成功')
            this.uploadModal = false
        },
        handleError (res, file) {
            this.error('导入出错')
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
                    // this.messages.push(res.data.message);
                });
            }
        },
        cancel(){

        },

        selectedProvinceOfResidenceAddress(val){
            for( let i = 0 ; i < this.madeJsonFromString.length ; i++){
                if( val == this.madeJsonFromString[i].value ){
                    this.willBeCityDataOfResidenceAddress = this.madeJsonFromString[i].city;
                    this.modalData.residenceAddress.city = null;
                    this.willBeRegionDataOfResidenceAddress = null;
                    this.modalData.residenceAddress.region = null;
                }
            }
            
        },
        selectedCityOfResidenceAddress(val){
            if (val != undefined){
                for( let i = 0 ; i < this.willBeCityDataOfResidenceAddress.length ; i++){
                    if( val == this.willBeCityDataOfResidenceAddress[i].value){
                        this.willBeRegionDataOfResidenceAddress = this.willBeCityDataOfResidenceAddress[i].region;
                    }
                }
            }
        },
        selectedRegionOfResidenceAddress(val){

        },

        selectedProvinceOfFamilyAddress(val){
            for( let i = 0 ; i < this.madeJsonFromString.length ; i++){
                if( val == this.madeJsonFromString[i].value ){
                    this.willBeCityDataOfFamilyAddress = this.madeJsonFromString[i].city;
                    this.modalData.familyAddress.city = null;
                    this.willBeRegionDataOfFamilyAddress = null;
                    this.modalData.familyAddress.region = null;
                }
            }
            
        },
        selectedCityOfFamilyAddress(val){
            if (val != undefined){
                for( let i = 0 ; i < this.willBeCityDataOfFamilyAddress.length ; i++){
                    if( val == this.willBeCityDataOfFamilyAddress[i].value){
                        this.willBeRegionDataOfFamilyAddress = this.willBeCityDataOfFamilyAddress[i].region;
                    }
                }
            }
        },
        selectedRegionOfFamilyAddress(val){

        },

        handleSuccessAvatarImage (res, file) {
            res = `/uploads/${res}`
            this.modalData.imgUrl = res;
        },
        handleErrorAvatarImage (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatErrorAvatarImage (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 不正确，请选择jpg或png.'
            });
        },
        handleMaxSizeAvatarImage (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
            });
        },
        changeVisibleAddModal(val){
            if(val == false){
                this.modalData = {
                    name:'',
                    phoneNumber:'',
                    password:'',
                    roleId:null,
                    gender:null,
                    nation : '',
                    cardNum : '',
                    familyAddress : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    residenceAddress : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    imgUrl : null,
                }
                this.willBeCityDataOfResidenceAddress = null;
                this.willBeCityDataOfFamilyAddress = null;
                this.willBeRegionDataOfResidenceAddress = null;
                this.willBeRegionDataOfFamilyAddress = null;
            }
        },

        registerUserSelectType(val){
            if(val == 5){
                this.isRegisterStudent = true;
            }
            else{
                this.isRegisterStudent = false;
            }

            this.modalData = {
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
                gender:null,
                nation : '',
                cardNum : '',
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                residenceAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                imgUrl : null,
            };
        }
    }
}
</script>