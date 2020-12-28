<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal"><Icon type="md-add" /> 添加</Button>
        </div>
        
        
        <div class="container content-container">
            <div class="_overflow_table_div">
                <!-- <table class="table">
                    <tr>
                        <th>号码</th>
                        <th>图标图像</th>
                        <th>机构名称</th>
                        <th>组织机构代码</th>
                        <th>固定电话</th>
                        <th>邮编</th>
                        <th>学校负责人</th>
                        <th>学校地址</th>
                        <th>学校简介</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(school,i) in schoolList" :key="i" v-if="schoolList.length">
                        <td>{{i+1}}</td>
                        <td class="table-image">
                            <img :src="school.imgUrl" alt="" />
                        </td>
                        <td class="_table_name">{{school.schoolName}}</td>
                        <td class="_table_name">{{school.code}}</td>
                        <td class="_table_name">{{school.phoneNum}}</td>
                        <td class="_table_name">{{school.zipCode}}</td>
                        <td class="_table_name">{{school.head}}</td>
                        <td class="_table_name">{{school.address}}</td>
                        <td class="_table_name"> <p class="school-desc-view-table">{{school.introduce}}</p> </td>
                        
                        <td class="d-flex">
<<<<<<< HEAD
                            <Button type="info" size="small" @click="showEditModal(school,i)">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(school,i)" :loading="school.isDeleting">删除</Button>
=======
                            <Button type="success" size="small" @click="addManagerOfSchool(school.id, i)">添加经理</Button>
                            <Button type="info" size="small" @click="showEditModal(school,i)" v-if="isUpdatePermitted">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(school,i)" :loading="school.isDeleting" v-if="isDeletePermitted">删除</Button>
>>>>>>> e368630a7efec64bfb27e840b94f05816b6a944d
                        </td>
                    </tr>
                </table> -->
                <Table :loading="schoolList.length == 0"  border :columns="columns2" :data="schoolList" height="750"></Table>
                <!-- add manager model -->
                <Modal
                    v-model="addManagerModal"
                    title="新增人员"
                    scrollable
                    :styles="{top:'75px',left:'-90px'}"
                    @on-visible-change="changeVisibleAddManagerModal"
                >
                    <div class="row m-0 p-0">
                        <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                            <div class="container-fluid m-0 p-0">
                                <div class="row m-0 p-0">
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2">人员姓名 : </p>
                                        <Input type="text" v-model="managerData.name" placeholder="请输入人员姓名"/>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2">民族 : </p>
                                        <Input type="text" v-model="managerData.nation" placeholder="请输入民族"/>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2">身份证号 : </p>
                                        <Input type="text" v-model="managerData.cardNum" placeholder="请输入身份证号"/>
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                        <Select v-model="managerData.gender" placeholder="请输入性别">
                                            <Option value="M">男</Option>
                                            <Option value="F">女</Option>
                                        </Select>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                        <Input type="text" v-model="managerData.phoneNumber" placeholder="请输入电话号码"/>
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                        <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                        <Input type="password" password v-model="managerData.password" placeholder="请输入使用密码"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 order-1 order-md-2">
                            <div class="row m-0 p-0">
                                <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                    <avatar v-if="managerData.name !== '' && managerData.imgUrl == null" :size="100" :username="managerData.name" class="mx-auto" :rounded="false"></avatar>
                                    <img v-else-if="managerData.name == '' && managerData.imgUrl == null" src="/img/icon/anonymous_avatar.png" alt="" style="width:100px; height: 100px;">
                                    <img v-else-if="managerData.imgUrl !== null" :src="managerData.imgUrl" alt="" style="width:100px; height: 100px;">
                                    <Icon v-if="managerData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="managerData.imgUrl = null" class="position-absolute" style="top:-1px;right:19px; cursor:pointer;" />
                                    <Upload
                                        ref="editDataImage"
                                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                        :on-success="handleSuccessManagerAvatarImage"
                                        :on-error="handleErrorAvatarImage"
                                        :format="['jpg','jpeg','png']"
                                        :max-size="10240"
                                        :on-format-error="handleFormatErrorAvatarImage"
                                        :on-exceeded-size="handleMaxSizeAvatarImage"
                                        :show-upload-list="false"
                                        action="/api/category/upload">
                                            <Button type="primary" style="width: 97%;">
                                                <Icon type="ios-person"></Icon>
                                                上传头像
                                            </Button>
                                    </Upload>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 m-0 p-0 row order-3 order-md-3"  v-if="!$isMobile()">
                            <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                <p class="min-width-fit-content text-right pr-2">户籍地址 : </p>
                                <Select v-model="managerData.residenceAddress.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(managerData.residenceAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select v-model="managerData.residenceAddress.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(managerData.residenceAddress.city)" placeholder="--市--">
                                    <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select v-model="managerData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(managerData.residenceAddress.region)" placeholder="--区--">
                                    <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            
                            <div class="col-12 mb-2" style="padding-left:90px;">
                                <Input type="text" v-model="managerData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                            </div>
                        </div>
                        <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                            <div class="col-12">
                                <div class="d-flex justify-content-center align-items-center mb-2" >
                                    <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                    <Select v-model="managerData.residenceAddress.province" @on-change="selectedProvinceOfResidenceAddress(managerData.residenceAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <Select style="padding-left: 75px;" class="mb-2"  v-model="managerData.residenceAddress.city" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(managerData.residenceAddress.city)" placeholder="--市--">
                                    <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select style="padding-left: 75px;" class="mb-2"  v-model="managerData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(managerData.residenceAddress.region)" placeholder="--区--">
                                    <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-12 mb-2" style="padding-left:90px;">
                                <Input type="text" v-model="managerData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                            </div>
                        </div>
                        <div class="col-md-12 m-0 p-0 row order-4 order-md-4" v-if="!$isMobile()">
                            <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                <p class="min-width-fit-content text-right pr-2">家庭地址 : </p>
                                <Select v-model="managerData.familyAddress.province" class="mr-2" @on-change="selectedProvinceOfFamilyAddress(managerData.familyAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select v-model="managerData.familyAddress.city" class="mr-2" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(managerData.familyAddress.city)" placeholder="--市--">
                                    <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select v-model="managerData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(managerData.familyAddress.region)" placeholder="--区--">
                                    <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-12 mb-2" style="padding-left:90px;">
                                <Input type="text" v-model="managerData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                            </div>
                        </div>
                        <div v-else class="col-md-12 m-0 p-0 row order-4 order-md-4" >
                            <div class="col-12">
                                <div class="d-flex justify-content-center align-items-center mb-2" >
                                    <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                    <Select v-model="managerData.familyAddress.province" @on-change="selectedProvinceOfFamilyAddress(managerData.familyAddress.province)" placeholder="--省--">
                                        <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                    </Select>
                                </div>
                                <Select style="padding-left: 75px;" class="mb-2"  v-model="managerData.familyAddress.city" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(managerData.familyAddress.city)" placeholder="--市--">
                                    <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                                <Select style="padding-left: 75px;" class="mb-2"  v-model="managerData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(managerData.familyAddress.region)" placeholder="--区--">
                                    <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-12 mb-2" style="padding-left:90px;">
                                <Input type="text" v-model="managerData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                            </div>
                        </div>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="addManagerModal=false">取消</Button>
                        <Button type="primary" @click="addManager" :disabled="isAddingManager" :loading="isAddingManager">{{isAddingManager ? '提交...': '提交'}}</Button>
                    </div>
                </Modal>
            </div>

            <!-- edit manager model -->
            <Modal
                v-model="editManagerModal"
                title="新增人员"
                scrollable
                :styles="{top:'75px',left:'-90px'}"
                @on-visible-change="changeVisibleEditManagerModal"
            >
                <div class="row m-0 p-0">
                    <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">人员姓名 : </p>
                                    <Input type="text" v-model="editManagerData.name" placeholder="请输入人员姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">民族 : </p>
                                    <Input type="text" v-model="editManagerData.nation" placeholder="请输入民族"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">身份证号 : </p>
                                    <Input type="text" v-model="editManagerData.cardNum" placeholder="请输入身份证号"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                    <Select v-model="editManagerData.gender" placeholder="请输入性别">
                                        <Option value="M">男</Option>
                                        <Option value="F">女</Option>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                    <Input type="text" v-model="editManagerData.phoneNumber" placeholder="请输入电话号码"/>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                    <Input type="password" password v-model="editManagerData.password" placeholder="请输入使用密码"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <avatar v-if="editManagerData.name !== '' && editManagerData.imgUrl == null" :size="100" :username="editManagerData.name" class="mx-auto" :rounded="false"></avatar>
                                <img v-else-if="editManagerData.name == '' && editManagerData.imgUrl == null" src="/img/icon/anonymous_avatar.png" alt="" style="width:100px; height: 100px;">
                                <img v-else-if="editManagerData.imgUrl !== null" :src="editManagerData.imgUrl" alt="" style="width:100px; height: 100px;">
                                <Icon v-if="editManagerData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="editManagerData.imgUrl = null" class="position-absolute" style="top:-1px;right:19px; cursor:pointer;" />
                                <Upload
                                    ref="editDataImage"
                                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                    :on-success="handleSuccessAvatarImageEditManager"
                                    :on-error="handleErrorAvatarImage"
                                    :format="['jpg','jpeg','png']"
                                    :max-size="10240"
                                    :on-format-error="handleFormatErrorAvatarImage"
                                    :on-exceeded-size="handleMaxSizeAvatarImage"
                                    :show-upload-list="false"
                                    action="/api/category/upload">
                                        <Button type="primary" style="width: 97%;">
                                            <Icon type="ios-person"></Icon>
                                            上传头像
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-3 order-md-3"  v-if="!$isMobile()">
                        <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                            <p class="min-width-fit-content text-right pr-2">户籍地址 : </p>
                            <Select v-model="editManagerData.residenceAddress.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(editManagerData.residenceAddress.province)" placeholder="--省--">
                                <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editManagerData.residenceAddress.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(editManagerData.residenceAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editManagerData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(editManagerData.residenceAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editManagerData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                <Select v-model="editManagerData.residenceAddress.province" @on-change="selectedProvinceOfResidenceAddress(editManagerData.residenceAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editManagerData.residenceAddress.city" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(editManagerData.residenceAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editManagerData.residenceAddress.region" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(editManagerData.residenceAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editManagerData.residenceAddress.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-4 order-md-4" v-if="!$isMobile()">
                        <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                            <p class="min-width-fit-content text-right pr-2">家庭地址 : </p>
                            <Select v-model="editManagerData.familyAddress.province" class="mr-2" @on-change="selectedProvinceOfFamilyAddress(editManagerData.familyAddress.province)" placeholder="--省--">
                                <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editManagerData.familyAddress.city" class="mr-2" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(editManagerData.familyAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editManagerData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(editManagerData.familyAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editManagerData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div v-else class="col-md-12 m-0 p-0 row order-4 order-md-4" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                <Select v-model="editManagerData.familyAddress.province" @on-change="selectedProvinceOfFamilyAddress(editManagerData.familyAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editManagerData.familyAddress.city" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(editManagerData.familyAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editManagerData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(editManagerData.familyAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editManagerData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <Button type="default" @click="editManagerModal=false">取消</Button>
                    <Button type="primary" @click="editManager" :disabled="isEditingManager" :loading="isEditingManager">{{isEditingManager ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- add school model -->
            <Modal
                v-model="addModal"
                title="新增学校"
                :styles="{top:'75px',left:'-90px'}"
                @on-visible-change="changeVisibleAddModal"
            >
                <div class="row m-0 p-0">
                    <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">机构名称 : </p>
                                    <Input type="text" v-model="addData.name" placeholder="请输入机构名称"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">组织机构代码 : </p>
                                    <Input type="text" v-model="addData.code" placeholder="请输入组织机构代码"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">固定电话 : </p>
                                    <Input type="text" v-model="addData.phoneNum" placeholder="请输入固定电话"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">邮编 : </p>
                                    <Input type="text" v-model="addData.zipCode" placeholder="请输入邮编"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">学校负责人 : </p>
                                    <Input type="text" v-model="addData.head" placeholder="请输入学校负责人"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <div class="position-relative" style="width:fit-content; margin: 0 auto; ">
                                    <!-- <avatar v-if="addData.name !== '' && addData.imgUrl == null" :size="127" :username="addData.name" class="mx-auto" :rounded="false"></avatar> -->
                                    <img v-if="addData.imgUrl == null" src="/img/icon/schoolGrayImage2.png" alt="" style="width:127px; height: 127px;">
                                    <img v-else-if="addData.imgUrl !== null" :src="addData.imgUrl" alt="" style="width:127px; height: 127px;">
                                    <Icon v-if="addData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="addData.imgUrl = null" class="position-absolute" style="top:0px;right:0px; cursor:pointer;" />
                                </div>
                                <Upload
                                    ref="editDataImage"
                                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                    :on-success="handleSuccessAvatarImage"
                                    :on-error="handleErrorAvatarImage"
                                    :format="['jpg','jpeg','png']"
                                    :max-size="10240"
                                    :on-format-error="handleFormatErrorAvatarImage"
                                    :on-exceeded-size="handleMaxSizeAvatarImage"
                                    :show-upload-list="false"
                                    action="/api/category/upload">
                                        <Button type="primary" style="width: 97%;">
                                            <Icon type="md-school" />
                                            上传学校图片
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-3 order-md-3" v-if="!$isMobile()">
                        <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                            <p class="min-width-fit-content-111 text-right pr-2">学校地址 : </p>
                            <Select v-model="addData.address.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(addData.address.province)" placeholder="--省--">
                                <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="addData.address.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(addData.address.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="addData.address.region" class="" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(addData.address.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:120px;">
                            <Input type="text" v-model="addData.address.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content-111 text-right pr-2">学校地址 : </p>
                                <Select v-model="addData.address.province" @on-change="selectedProvinceOfSchoolAddress(addData.address.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 105px;" class="mb-2" v-model="addData.address.city"  :disabled="willBeCityDataOfSchoolAddress == null" @on-change="selectedCityOfSchoolAddress(addData.address.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfSchoolAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 105px;" class="mb-2" v-model="addData.address.region" :disabled="willBeRegionDataOfSchoolAddress == null" @on-change="selectedRegionOfSchoolAddress(addData.address.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfSchoolAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:120px;">
                            <Input type="text" v-model="addData.address.detail" :disabled="willBeRegionDataOfSchoolAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-4 order-md-4">
                        <div class="col-12 mb-2 d-flex align-items-center">
                            <p class="min-width-fit-content-111 text-right pr-2">学校简介 : </p>
                            <Input type="textarea" v-model="addData.introduce" placeholder="学校简介" />
                        </div>
                    </div>
                </div>

                <div slot="footer">
                    <Button type="default" @click="addModal=false">取消</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="编辑学校"
                :styles="{top:'75px',left:'-90px'}"
                @on-visible-change="changeVisibleEditModal"
            >
                <div class="row m-0 p-0">
                    <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">机构名称 : </p>
                                    <Input type="text" v-model="editData.schoolName" placeholder="请输入机构名称"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">组织机构代码 : </p>
                                    <Input type="text" v-model="editData.code" placeholder="请输入组织机构代码"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">固定电话 : </p>
                                    <Input type="text" v-model="editData.phoneNum" placeholder="请输入固定电话"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">邮编 : </p>
                                    <Input type="text" v-model="editData.zipCode" placeholder="请输入邮编"/>
                                </div>
                                <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content-111 text-right pr-2">学校负责人 : </p>
                                    <Input type="text" v-model="editData.head" placeholder="请输入学校负责人"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <div class="position-relative" style="width:fit-content; margin: 0 auto; ">
                                    <!-- <avatar v-if="editData.name !== '' && editData.imgUrl == null" :size="127" :username="editData.name" class="mx-auto" :rounded="false"></avatar> -->
                                    <img v-if="editData.imgUrl == null" src="/img/icon/schoolGrayImage2.png" alt="" style="width:127px; height: 127px;">
                                    <img v-else-if="editData.imgUrl !== null" :src="editData.imgUrl" alt="" style="width:127px; height: 127px;">
                                    <Icon v-if="editData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="editData.imgUrl = null" class="position-absolute" style="top:0px;right:0px; cursor:pointer;" />
                                </div>
                                <Upload
                                    ref="editDataImage"
                                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                    :on-success="handleSuccessAvatarImageEdit"
                                    :on-error="handleErrorAvatarImage"
                                    :format="['jpg','jpeg','png']"
                                    :max-size="10240"
                                    :on-format-error="handleFormatErrorAvatarImage"
                                    :on-exceeded-size="handleMaxSizeAvatarImage"
                                    :show-upload-list="false"
                                    action="/api/category/upload">
                                        <Button type="primary" style="width: 97%;">
                                            <Icon type="md-school" />
                                            上传学校图片
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-3 order-md-3" v-if="!$isMobile()">
                        <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                            <p class="min-width-fit-content-111 text-right pr-2">学校地址 : </p>
                            <Select v-model="editData.address.province" class="mr-2" @on-change="selectedProvinceOfResidenceAddress(editData.address.province)" placeholder="--省--">
                                <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editData.address.city" class="mr-2" :disabled="willBeCityDataOfResidenceAddress == null" @on-change="selectedCityOfResidenceAddress(editData.address.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editData.address.region" class="" :disabled="willBeRegionDataOfResidenceAddress == null" @on-change="selectedRegionOfResidenceAddress(editData.address.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfResidenceAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:120px;">
                            <Input type="text" v-model="editData.address.detail" :disabled="willBeRegionDataOfResidenceAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content-111 text-right pr-2">学校地址 : </p>
                                <Select v-model="editData.address.province" @on-change="selectedProvinceOfSchoolAddress(editData.address.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 105px;" class="mb-2" v-model="editData.address.city"  :disabled="willBeCityDataOfSchoolAddress == null" @on-change="selectedCityOfSchoolAddress(editData.address.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfSchoolAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 105px;" class="mb-2" v-model="editData.address.region" :disabled="willBeRegionDataOfSchoolAddress == null" @on-change="selectedRegionOfSchoolAddress(editData.address.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfSchoolAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:120px;">
                            <Input type="text" v-model="editData.address.detail" :disabled="willBeRegionDataOfSchoolAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-4 order-md-4">
                        <div class="col-12 mb-2 d-flex align-items-center">
                            <p class="min-width-fit-content-111 text-right pr-2">学校简介 : </p>
                            <Input type="textarea" v-model="editData.introduce" placeholder="学校简介" />
                        </div>
                    </div>
                </div>

                <div slot="footer">
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal 
                v-model="showDeleteModal"
                width="360"
                class-name="delete-bullet-modal vertical-center"    

            >
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>你确定要删除吗？</span>
                </p>
                <div style="text-align:center" class="px-3">
                    <p>删除后，与此相关的所有数据将自动删除。</p>
                    <p>你会删除吗？</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deleteTag">删除</Button>
                </div>
            </Modal>
            <!-- delete manager model -->
            <Modal 
                v-model="showDeleteManagerModal"
                width="360"
                class-name="delete-bullet-modal vertical-center"    

            >
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>你确定要删除吗？</span>
                </p>
                <div style="text-align:center" class="px-3">
                    <p>删除后，与此相关的所有数据将自动删除。</p>
                    <p>你会删除吗？</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeletingManager" @click="deleteManager">删除</Button>
                </div>
            </Modal>
            <!-- show managerModal -->
            <Modal
                v-model="schoolManagerListModal"
                title="经理名单"
                :styles="{top:'75px',left:'-90px'}"
                footer-hide
            >
                <div v-if="isNoManager">no manager</div>
                <Table v-else :loading="isGettingManagerData"  border :columns="managerColumn" :data="managerList" height="750"></Table>
            </Modal>
            <!-- <Page :total="100" /> -->

            <!-- introduce modal -->
            <Modal 
                v-model="introduceSchoolModal" 
                width="360"
                footer-hide
                class-name= "show-medal-detail-modal"
                @on-visible-change="initeMedalData"
                :mask-closable="true"
                v-if="!$isMobile()"
            >
                <div style="text-align:center">
                    <div class="medal-type-area-of-modal text-center p-3">
                        <h5>{{introduceSchoolObj.schoolName}}</h5>
                    </div>
                    <div class="medal-type-area-of-modal text-center p-3">
                        <span>{{introduceSchoolObj.introduce}}</span>
                    </div>
                </div>
                <div slot="footer">
                </div>
            </Modal>
            <Modal 
                v-model="introduceSchoolModal" 
                width="360"
                footer-hide
                class-name= "show-medal-detail-modal-app"
                @on-visible-change="initeMedalData"
                :mask-closable="true"
                v-else
            >
                <div style="text-align:center">
                    <div class="medal-type-area-of-modal text-center p-3">
                        <h5>{{introduceSchoolObj.schoolName}}</h5>
                    </div>
                    <div class="medal-type-area-of-modal text-center p-3">
                        <span>{{introduceSchoolObj.introduce}}</span>
                    </div>
                </div>
                <div slot="footer">
                </div>
            </Modal>
        </div>
        
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem'
import cityListJson from '!raw-loader!./cityLaw.txt';
import Avatar from 'vue-avatar'
import hiVue from '../../view/vSchool/hi.vue';
import image from '../../components/chungHua/image.vue';
export default {
    components:{
        menuItem,
        Avatar,
    },
    data () {
        return {
            addData:{
                name : '',
                code : '',
                phoneNum : '',
                zipCode : '',
                head : '',
                address : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                introduce : '',
                imgUrl: null,
            },
            managerData:{
                name:'',
                phoneNumber:'',
                password:'',
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
            editManagerData:{
                name:'',
                phoneNumber:'',
                password:'',
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
            addModal:false,
            addManagerModal:false,
            isAdding:false,
            isAddingManager:false,
            schoolList:[],
            schoolListRaw : [],
            editModal:false,
            editData:{
                schoolName:'',
                code : '',
                phoneNum : '',
                zipCode : '',
                head : '',
                address : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                introduce : '',
                imgUrl: null,
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            isIconImageNew:false,
            isEditingItem:false,
            token : window.Laravel.csrfToken,
            provinceListJsonArr:[],
            madeJsonFromString : [],
            willBeCityDataOfSchoolAddress : null,
            willBeRegionDataOfSchoolAddress : null,
            willBeCityDataOfResidenceAddress : null,
            willBeCityDataOfFamilyAddress : null,
            willBeRegionDataOfResidenceAddress : null,
            willBeRegionDataOfFamilyAddress : null,
            schoolIdOfAddedManager : null,
            introduceSchoolModal : false,
            introduceSchoolObj : {
                schoolName : null,
                introduce : null
            },
            schoolManagerListModal : false,
            isGettingManagerData : false,
            managerList : [],
            isNoManager : false,
            deleteManagerData : {},
            deleteManagerIndex : -1,
            showDeleteManagerModal : false,
            isDeletingManager : false,
            editManagerModal : false,
            editManagerIndex : -1,
            isEditingManager : false,
            columns2: [
                {
                    title: "号码",
                    key: 'id',
                    width: 60,
                },
                {
                    title: "图标图像",
                    key: 'imgUrl',
                    width: 150,
                    render: (h, params) => {
                        return h(image, {
                            props: {
                                imgUrl: params.row.imgUrl
                            }
                        })
                    }
                },
                {
                    title: "机构名称",
                    key: 'schoolName',
                    width: 150
                },
                {
                    title: "组织机构代码",
                    key: 'code',
                    width: 150
                },
                {
                    title: "固定电话",
                    key: 'phoneNum',
                    width: 150
                },
                {
                    title: "邮编",
                    key: 'zipCode',
                    width: 150
                },
                {
                    title: "学校负责人",
                    key: 'head',
                    width: 150
                },
                {
                    title: "学校地址",
                    key: 'address',
                    width: 200,
                },
                {
                    title: "学校简介",
                    key: 'introduce',
                    width: 100,
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'success',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.introduceSchoolObj = {
                                            schoolName : params.row.schoolName,
                                            introduce : params.row.introduce
                                        }
                                        this.showIntroduceOfSchoolModal();
                                    }
                                }
                            }, "览"), 
                        ]);
                    }
                },
                {
                    title: "行动",
                    key: 'action',
                    fixed: 'right',
                    width: 130,
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'success',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.addManagerOfSchool(params.row.id, params.index)
                                    }
                                }
                            }, "添加经理"), 

                            

                            h('Icon', {
                                props: {
                                    type: 'md-create',
                                    size: '25',
                                    color: '#44B4E2'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.showEditModal(params.row, params.index);
                                    }
                                }
                            }),
                            h('Icon', {
                                props: {
                                    type: 'ios-trash',
                                    size: '25',
                                    color: '#FD0000'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.showDeletingModal(params.row, params.index);
                                    }
                                }
                            }),

                            h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.showSchoolManagerListModal(params.row)
                                    }
                                }
                            }, "查看经理列表"), 
                            
                        ]);
                    }
                }
            ],
            managerColumn: [
                {
                    title: "号码",
                    key: 'id',
                    width: 60,
                },
                {
                    title: "人员姓名",
                    key: 'name',
                    width: 150,
                },
                {
                    title: "用户头像",
                    key: 'imgUrl',
                    width: 150,
                    render: (h, params) => {
                        if(params.row.imgUrl == null){
                            return h(Avatar, {
                                props: {
                                    username: params.row.name,
                                    size: 100,
                                    rounded : false
                                }
                            })
                        }
                        else{
                            return h(image, {
                                props: {
                                    imgUrl: params.row.imgUrl
                                }
                            })
                        }
                    }
                },
                {
                    title: "电话号码",
                    key: 'phoneNumber',
                    width: 150
                },
                {
                    title: "身份证号",
                    key: 'cardNum',
                    width: 150
                },
                {
                    title: "民族",
                    key: 'nation',
                    width: 100
                },
                {
                    title: "性別",
                    key: 'gender',
                    width: 75,
                    render: (h, params) => {
                        if(params.row.gender == "M"){
                            return h("Tag", {
                                props: {
                                    color: "green",
                                }
                            }, "男")
                        }
                        else{
                            return h("Tag", {
                                props: {
                                    color: "magenta",
                                }
                            }, "女")
                        }
                    }
                },
                {
                    title: "学校地址",
                    key: 'familyAddress',
                    width: 200,
                },
                {
                    title: "学校地址",
                    key: 'residenceAddress',
                    width: 200,
                },
                {
                    title: "行动",
                    key: 'action',
                    fixed: 'right',
                    width: 90,
                    render: (h, params) => {
                        return h('div', [
                            h('Icon', {
                                props: {
                                    type: 'md-create',
                                    size: '25',
                                    color: '#44B4E2'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.showEditManagerModal(params.row, params.index);
                                    }
                                }
                            }),
                            h('Icon', {
                                props: {
                                    type: 'ios-trash',
                                    size: '25',
                                    color: '#FD0000'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.showDeletingManagerModal(params.row, params.index);
                                    }
                                }
                            }),
                        ]);
                    }
                }
            ]
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
        const res = await this.callApi('get','/api/school');
        if(res.status == 200){
            this.schoolList = res.data;
            for(let i = 0 ; i < this.schoolList.length ; i++){
                let clonedVal = JSON.parse(JSON.stringify(this.schoolList[i]));
                this.schoolListRaw.push(clonedVal); 
                this.schoolList[i].address = this.convertAddress(this.schoolList[i].address);
            }
        }
    },
    methods:{
        showModal(){
            this.addModal = true;
        },
        async addTag(){
            if(this.addData.name.trim() == ''){
                return this.error("请输入机构名称");
            }
            if(this.addData.code.trim() == ''){
                return this.error("请输入组织机构代码");
            }
            if(/^\d*$/.test(this.addData.code) == false){
                return this.error('请输入正确的组织机构代码');
            }
            if(this.addData.phoneNum.trim() == ''){
                return this.error("请输入固定电话");
            }
            if(/^\d*$/.test(this.addData.phoneNum) == false){
                return this.error('请输入正确的固定电话');
            }
            if(this.addData.zipCode.trim() == ''){
                return this.error("请输入邮编");
            }
            if(/^\d*$/.test(this.addData.zipCode) == false){
                return this.error('请输入正确的邮编');
            }
            if(this.addData.head.trim() == ''){
                return this.error("请输入学校负责人");
            }
            if( this.addData.address.province == null ||
                this.addData.address.city == null ||
                this.addData.address.region == null ||
                this.addData.address.detail.trim() == ''){
                return this.error("请输入学校地址");
            }
            if(this.addData.introduce.trim() == ''){
                return this.error("请输入学校简介");
            }
            if(this.addData.imgUrl == null){
                return this.error("请输入学校图片");
            }
            this.isAdding = true;
            const res = await this.callApi('post', '/api/school',this.addData)
            if(res.status === 201){
                this.schoolListRaw.push(res.data.school);
                res.data.school.address = this.convertAddress(res.data.school.address)
                this.schoolList.push(res.data.school);
                this.success('学校已成功添加！');
                this.addModal = false;
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
            if(this.editData.schoolName.trim() == ''){
                return this.error("请输入机构名称");
            }
            if(this.editData.code.trim() == ''){
                return this.error("请输入组织机构代码");
            }
            if(/^\d*$/.test(this.editData.code) == false){
                return this.error('请输入正确的组织机构代码');
            }
            if(this.editData.phoneNum.trim() == ''){
                return this.error("请输入固定电话");
            }
            if(/^\d*$/.test(this.editData.phoneNum) == false){
                return this.error('请输入正确的固定电话');
            }
            if(this.editData.zipCode.trim() == ''){
                return this.error("请输入邮编");
            }
            if(/^\d*$/.test(this.editData.zipCode) == false){
                return this.error('请输入正确的邮编');
            }
            if(this.editData.head.trim() == ''){
                return this.error("请输入学校负责人");
            }
            if( this.editData.address.province == null ||
                this.editData.address.city == null ||
                this.editData.address.region == null ||
                this.editData.address.detail.trim() == ''){
                return this.error("请输入学校地址");
            }
            if(this.editData.introduce.trim() == ''){
                return this.error("请输入学校简介");
            }
            if(this.editData.imgUrl == null){
                return this.error("请输入学校图片");
            }
            this.isAdding = true;
            const res = await this.callApi('put', '/api/school',this.editData)
            if(res.data.msg === 1 ){
                this.schoolList[this.index].schoolName = this.editData.schoolName;
                this.schoolList[this.index].code = this.editData.code;
                this.schoolList[this.index].phoneNum = this.editData.phoneNum;
                this.schoolList[this.index].zipCode = this.editData.zipCode;
                this.schoolList[this.index].head = this.editData.head;
                this.schoolList[this.index].introduce = this.editData.introduce;
                this.schoolList[this.index].imgUrl = this.editData.imgUrl;
                this.schoolList[this.index].address = this.convertAddress(JSON.stringify(this.editData.address));
                this.success('学校已成功添加！');
                this.editModal = false;
                this.index = -1;
                
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
                schoolName:school.schoolName,
                code : school.code.toString(),
                phoneNum : school.phoneNum.toString(),
                zipCode : school.zipCode.toString(),
                head : school.head,
                address : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : school.address.split(' ')[3],
                },
                introduce : school.introduce,
                imgUrl:school.imgUrl
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','/api/school',this.deleteItem);
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
        },
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if(this.editModal){
                return this.editData.imgUrl = res;
            }
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 不正确，请选择jpg或png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
            });
        },

        async deleteImage(isAdd = true){
            let image = ''
            if(!isAdd){//for edit iconimage delete
                this.isIconImageNew = true
                image = this.editData.imgUrl;
                this.editData.imgUrl = '';
                this.addData.imgUrl = '';
                this.$refs.editDataImage.clearFiles();
            }else {
                image = this.addData.imgUrl;
                this.addData.imgUrl = '';
                this.$refs.uploads.clearFiles();
            }
            const res = await this.callApi('delete', '/api/category/upload',{imageName:image})
            if(res.status!=200){
                this.addData.imgUrl = image
                this.swr()
            }
        },
        changeVisibleAddModal(val){
            if(val == false){
                //inital datas
                this.addData = {
                    name : '',
                    code : '',
                    phoneNum : '',
                    zipCode : '',
                    head : '',
                    address : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    introduce : '',
                    imgUrl: null,
                }
                this.willBeCityDataOfSchoolAddress = null;
                this.willBeRegionDataOfSchoolAddress = null;
            }
        },
        changeVisibleEditModal(val){
            if(val == false){
                //inital datas
                this.editData = {
                    schoolName : '',
                    code : '',
                    phoneNum : '',
                    zipCode : '',
                    head : '',
                    address : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    introduce : '',
                    imgUrl: null,
                }
                this.willBeCityDataOfSchoolAddress = null;
                this.willBeRegionDataOfSchoolAddress = null;
            }
        },
        changeVisibleEditManagerModal(val){
            if(val == false){
                //inital datas
                this.editManagerData = {
                    name:'',
                    phoneNumber:'',
                    password:'',
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
        changeVisibleAddManagerModal(val){
            if(val == false){
                this.managerData = {
                    name:'',
                    phoneNumber:'',
                    password:'',
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
        selectedProvinceOfSchoolAddress(val){
            for( let i = 0 ; i < this.madeJsonFromString.length ; i++){
                if( val == this.madeJsonFromString[i].value ){
                    this.willBeCityDataOfSchoolAddress = this.madeJsonFromString[i].city;
                    this.addData.address.city = null;
                    this.willBeRegionDataOfSchoolAddress = null;
                    this.addData.address.region = null;
                }
            }
            
        },
        selectedCityOfSchoolAddress(val){
            if (val != undefined){
                for( let i = 0 ; i < this.willBeCityDataOfSchoolAddress.length ; i++){
                    if( val == this.willBeCityDataOfSchoolAddress[i].value){
                        this.willBeRegionDataOfSchoolAddress = this.willBeCityDataOfSchoolAddress[i].region;
                    }
                }
            }
        },
        selectedRegionOfSchoolAddress(val){

        },
        selectedProvinceOfResidenceAddress(val){
            for( let i = 0 ; i < this.madeJsonFromString.length ; i++){
                if( val == this.madeJsonFromString[i].value ){
                    this.willBeCityDataOfResidenceAddress = this.madeJsonFromString[i].city;
                    this.managerData.residenceAddress.city = null;
                    this.willBeRegionDataOfResidenceAddress = null;
                    this.managerData.residenceAddress.region = null;
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
                    this.managerData.familyAddress.city = null;
                    this.willBeRegionDataOfFamilyAddress = null;
                    this.managerData.familyAddress.region = null;
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
            this.addData.imgUrl = res;
        },
        handleSuccessAvatarImageEdit (res, file) {
            res = `/uploads/${res}`
            this.editData.imgUrl = res;
        },
        handleSuccessAvatarImageEditManager (res, file) {
            res = `/uploads/${res}`
            this.editManagerData.imgUrl = res;
        },
        handleSuccessManagerAvatarImage (res, file) {
            res = `/uploads/${res}`
            this.managerData.imgUrl = res;
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

        async addManager(){
            console.log(this.managerData);
            //name validation
            if(this.managerData.name.trim() == ''){
                return this.error("请输入姓名");
            }
            //nation validation
            if(this.managerData.nation.trim() == ''){
                return this.error("请输入民族");
            }
            //card number validation
            if(this.managerData.cardNum.trim() == ''){
                return this.error('请输入身份证号');
            }
            if(/^\d*$/.test(this.managerData.cardNum) == false){
                return this.error('请输入正确的身份证号');
            }
            //gender validation
            if(this.managerData.gender == null){
                return this.error('请输入性别');
            }
            //phone number validation
            if(this.managerData.phoneNumber.trim() == ''){
                return this.error('请输入电话号码');
            }
            if(/^\d*$/.test(this.managerData.phoneNumber) == false){
                return this.error('请输入正确的电话号码');
            }
            //password validation
            if(this.managerData.password.trim() == ''){
                return this.error('请输入使用密码')
            }
            //check if password contain number
            if(/\d/.test(this.managerData.password) == false){
                return this.error('密码应包含数字')
            }
            //check if password contain uppercase
            if(/[A-Z]/.test(this.managerData.password) == false){
                return this.error('密码应至少包含1个大写字母')
            }
            //check if password contain special character
            if(/[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.managerData.password) == false){
                return this.error('密码应包含至少1个特殊字符')
            }
            //check if length of password is more than 8
            if(this.managerData.password.length < 8){
                return this.error('密码至少应包含8个字符')
            }
            //family address validation
            if( this.managerData.familyAddress.province == null ||
                this.managerData.familyAddress.city == null ||
                this.managerData.familyAddress.region == null ||
                this.managerData.familyAddress.detail == ''){
                    return this.error('请输入户籍地址');
            }
            //residence address validation
            if( this.managerData.residenceAddress.province == null ||
                this.managerData.residenceAddress.city == null ||
                this.managerData.residenceAddress.region == null ||
                this.managerData.residenceAddress.detail == ''){
                    return this.error('请输入家庭地址');
            }

            let payload = {
                managerData : this.managerData,
                schoolId : this.schoolIdOfAddedManager
            }
            this.isAddingManager = true;
            const res = await this.callApi('post', '/api/school/manager',payload)
            if(res.status == 201){
                // this.users.unshift(res.data);
                this.success('管理员用户已成功添加！');
                this.addManagerModal = false;
                this.isAddingManager = false;
                this.schoolIdOfAddedManager = null;
                console.log(res.data);
            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
                }else{
                    this.swr()
                }
               
            }
            this.isAddingManager = false;
        },

        addManagerOfSchool(schoolId, i){
            this.schoolIdOfAddedManager = schoolId;
            this.addManagerModal = true;
        },

        convertAddress(address){
            address = JSON.parse(address);
            let province = '';
            let city = '';
            let region = '';
            for(let i = 0 ; i < this.madeJsonFromString.length ; i++){
                if( address.province == this.madeJsonFromString[i].value ){
                    province = this.madeJsonFromString[i].label;
                    for(let j = 0 ; j < this.madeJsonFromString[i].city.length ; j++){
                        if( address.city == this.madeJsonFromString[i].city[j].value ){
                            city = this.madeJsonFromString[i].city[j].label;
                            for(let k = 0 ; k < this.madeJsonFromString[i].city[j].region.length ; k++){
                                if( address.region == this.madeJsonFromString[i].city[j].region[k].value ){
                                    region = this.madeJsonFromString[i].city[j].region[k].label;
                                }
                            }
                        }
                    }
                }
            }
            return province + ' ' + city + ' ' + region + ' ' + address.detail;
        },

        showIntroduceOfSchoolModal(){
            this.introduceSchoolModal = true;
        },

        initeMedalData(val){
            if(val == false){
                this.introduceSchoolObj = {
                    schoolName : null,
                    introduce : null
                },
                this.introduceSchoolModal = false;
            }
        },

        async showSchoolManagerListModal(school){
            this.schoolManagerListModal = true;
            this.isGettingManagerData = true;
            await axios.get('/api/school/manager',{params:{id:school.id}})
                .then(res=>{
                    this.managerList = res.data.managerList;
                    for(let i = 0 ; i < this.managerList.length ; i++){
                        this.managerList[i].familyAddress = this.convertAddress(this.managerList[i].familyAddress);
                        this.managerList[i].residenceAddress = this.convertAddress(this.managerList[i].residenceAddress);
                    }
                })
                .catch(err=>{
                    console.log(err)
                })
            this.isGettingManagerData = false;
        },

        showEditManagerModal(manager, index){
            console.log("%%%", manager, index)
            let obj = {
                id:manager.id,
                name:manager.name,
                phoneNumber : manager.phoneNumber.toString(),
                cardNum : manager.cardNum.toString(),
                nation : manager.nation,
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : manager.familyAddress.split(' ')[3],
                },
                residenceAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : manager.residenceAddress.split(' ')[3],
                },
                introduce : manager.introduce,
                imgUrl:manager.imgUrl
            }
            this.editManagerData = obj;
            this.editManagerModal = true;
            this.editManagerIndex = index;
        },
        
        showDeletingManagerModal(manager, index){
            this.deleteManagerData = manager;
            this.deleteManagerIndex = index;
            this.showDeleteManagerModal = true;
            console.log("$$$", manager, index)
        },

        async deleteManager(){
            this.isDeletingManager = true;
            let payload = {
                id : this.deleteManagerData.id
            }
            const res = await this.callApi('delete','/api/school/manager',payload);
            if(res.status == 200){
                this.managerList.splice(this.deleteManagerIndex,1);
                this.success('标记已成功删除！');
            }else{
                this.swr();
            }
            this.isDeletingManager = false;
            this.showDeleteManagerModal = false;
        },

        async editManager(){
            console.log(this.editManagerData);
            //name validation
            if(this.editManagerData.name.trim() == ''){
                return this.error("请输入姓名");
            }
            //nation validation
            if(this.editManagerData.nation.trim() == ''){
                return this.error("请输入民族");
            }
            //card number validation
            if(this.editManagerData.cardNum.trim() == ''){
                return this.error('请输入身份证号');
            }
            if(/^\d*$/.test(this.editManagerData.cardNum) == false){
                return this.error('请输入正确的身份证号');
            }
            //gender validation
            if(this.editManagerData.gender == null){
                return this.error('请输入性别');
            }
            //phone number validation
            if(this.editManagerData.phoneNumber.trim() == ''){
                return this.error('请输入电话号码');
            }
            if(/^\d*$/.test(this.editManagerData.phoneNumber) == false){
                return this.error('请输入正确的电话号码');
            }
            //password validation
            if(this.editManagerData.password.trim() == ''){
                return this.error('请输入使用密码')
            }
            //check if password contain number
            if(/\d/.test(this.editManagerData.password) == false){
                return this.error('密码应包含数字')
            }
            //check if password contain uppercase
            if(/[A-Z]/.test(this.editManagerData.password) == false){
                return this.error('密码应至少包含1个大写字母')
            }
            //check if password contain special character
            if(/[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.editManagerData.password) == false){
                return this.error('密码应包含至少1个特殊字符')
            }
            //check if length of password is more than 8
            if(this.editManagerData.password.length < 8){
                return this.error('密码至少应包含8个字符')
            }
            //family address validation
            if( this.editManagerData.familyAddress.province == null ||
                this.editManagerData.familyAddress.city == null ||
                this.editManagerData.familyAddress.region == null ||
                this.editManagerData.familyAddress.detail == ''){
                    return this.error('请输入户籍地址');
            }
            //residence address validation
            if( this.editManagerData.residenceAddress.province == null ||
                this.editManagerData.residenceAddress.city == null ||
                this.editManagerData.residenceAddress.region == null ||
                this.editManagerData.residenceAddress.detail == ''){
                    return this.error('请输入家庭地址');
            }
            this.isEditingManager = true;
            const res = await this.callApi('put', '/api/school/manager',this.editManagerData)
            if(res.data.msg === 1 ){
                this.managerList[this.editManagerIndex].name = this.editManagerData.name;
                this.managerList[this.editManagerIndex].password = this.editManagerData.password;
                this.managerList[this.editManagerIndex].phoneNumber = this.editManagerData.phoneNumber;
                this.managerList[this.editManagerIndex].gender = this.editManagerData.gender;
                this.managerList[this.editManagerIndex].nation = this.editManagerData.nation;
                this.managerList[this.editManagerIndex].cardNum = this.editManagerData.cardNum;
                this.managerList[this.editManagerIndex].imgUrl = this.editManagerData.imgUrl;
                this.managerList[this.editManagerIndex].familyAddress = this.convertAddress(JSON.stringify(this.editManagerData.familyAddress));
                this.managerList[this.editManagerIndex].residenceAddress = this.convertAddress(JSON.stringify(this.editManagerData.residenceAddress));
                this.success('学校已成功添加！');
                this.editManagerModal = false;
                this.editManagerIndex = -1;
                
            }else{
                if(res.status == 422){
                    if(res.data.errors.schoolName){
                        this.info(res.data.errors.schoolName[0]);
                    }
                }else{
                    this.swr()
                }
               
            }
            this.isEditingManager = false;
        },

        async editTag(){
            if(this.editData.schoolName.trim() == ''){
                return this.error("请输入机构名称");
            }
            if(this.editData.code.trim() == ''){
                return this.error("请输入组织机构代码");
            }
            if(/^\d*$/.test(this.editData.code) == false){
                return this.error('请输入正确的组织机构代码');
            }
            if(this.editData.phoneNum.trim() == ''){
                return this.error("请输入固定电话");
            }
            if(/^\d*$/.test(this.editData.phoneNum) == false){
                return this.error('请输入正确的固定电话');
            }
            if(this.editData.zipCode.trim() == ''){
                return this.error("请输入邮编");
            }
            if(/^\d*$/.test(this.editData.zipCode) == false){
                return this.error('请输入正确的邮编');
            }
            if(this.editData.head.trim() == ''){
                return this.error("请输入学校负责人");
            }
            if( this.editData.address.province == null ||
                this.editData.address.city == null ||
                this.editData.address.region == null ||
                this.editData.address.detail.trim() == ''){
                return this.error("请输入学校地址");
            }
            if(this.editData.introduce.trim() == ''){
                return this.error("请输入学校简介");
            }
            if(this.editData.imgUrl == null){
                return this.error("请输入学校图片");
            }
            this.isAdding = true;
            const res = await this.callApi('put', '/api/school',this.editData)
            if(res.data.msg === 1 ){
                this.schoolList[this.index].schoolName = this.editData.schoolName;
                this.schoolList[this.index].code = this.editData.code;
                this.schoolList[this.index].phoneNum = this.editData.phoneNum;
                this.schoolList[this.index].zipCode = this.editData.zipCode;
                this.schoolList[this.index].head = this.editData.head;
                this.schoolList[this.index].introduce = this.editData.introduce;
                this.schoolList[this.index].imgUrl = this.editData.imgUrl;
                this.schoolList[this.index].address = this.convertAddress(JSON.stringify(this.editData.address));
                this.success('学校已成功添加！');
                this.editModal = false;
                this.index = -1;
                
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

    }
}
</script>