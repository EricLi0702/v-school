<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930 d-flex justify-content-md-between align-items-center">
            <Button type="success" class="addbtn m-2" @click="showModal" ><Icon type="md-add"/> 添加</Button>
            <div class="d-flex justify-content-start align-items-center mx-auto">
                <p class="min-width-fit-content-app text-right pr-2" placeholder="请输入性別">班级 : </p>
                <Select v-model="selectedClassName" placeholder="请输入班级" @on-change="changeShowStudentClassVal" default-label="全部">
                    <Option value="0">全部</Option>
                    <OptionGroup v-for="grade in gradeList" :key="grade.id" :label="grade.gradeName">
                        <Option v-for="lesson in grade.lessons" :value="lesson.id" :key="lesson.id">{{ lesson.lessonName }}</Option>
                    </OptionGroup>
                </Select>
            </div>
            <div class="float-right">
                <Button type="info" class="addbtn m-2" @click="userExport"><Icon type="ios-cloud-download-outline" /> 输出</Button>
                <Button type="info" class="addbtn m-2" @click="userImport"><Icon type="ios-cloud-upload-outline" /> 输入</Button>
            </div>
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <Table :loading="studentList.length == 0"  border :columns="studentColumn" :data="studentList" height="750"></Table>
            </div>

            <!-- introduce modal -->
            <Modal 
                v-model="introduceStudentModal" 
                width="360"
                footer-hide
                class-name= "show-medal-detail-modal"
                @on-visible-change="initeMedalData"
                :mask-closable="true"
                v-if="!$isMobile()"
            >
                <div style="text-align:center">
                    <div class="medal-type-area-of-modal text-center p-3">
                        <h5>{{introduceStudentObj.studentName}}</h5>
                    </div>
                    <div class="medal-type-area-of-modal text-center p-3">
                        <span>{{introduceStudentObj.introduce}}</span>
                    </div>
                </div>
                <div slot="footer">
                </div>
            </Modal>
            <Modal 
                v-model="introduceStudentModal" 
                width="360"
                footer-hide
                class-name= "show-medal-detail-modal-app"
                @on-visible-change="initeMedalData"
                :mask-closable="true"
                v-else
            >
                <div style="text-align:center">
                    <div class="medal-type-area-of-modal text-center p-3">
                        <h5>{{introduceStudentObj.studentName}}</h5>
                    </div>
                    <div class="medal-type-area-of-modal text-center p-3">
                        <span>{{introduceStudentObj.introduce}}</span>
                    </div>
                </div>
                <div slot="footer">
                </div>
            </Modal>
            

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
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">学生姓名 : </p>
                                    <Input type="text" v-model="modalData.name" placeholder="请输入学生姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">学生学号 : </p>
                                    <Input type="text" v-model="modalData.cardNum" placeholder="请输入学生学号"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长姓名 : </p>
                                    <Input type="text" v-model="modalData.fatherName" placeholder="请输入家长姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长电话 : </p>
                                    <Input type="text" v-model="modalData.fatherPhone" placeholder="请输入家长电话"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长身份 : </p>
                                    <Select v-model="modalData.fatherJob" placeholder="请输入家长身份">
                                        <Option value="farmer">农民</Option>
                                        <Option value="worker">工人</Option>
                                        <Option value="developer">开发商</Option>
                                    </Select>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">班级 : </p>
                                    <Select v-model="modalData.lessonId" placeholder="请输入班级" @on-change="changeClassVal">
                                        <OptionGroup v-for="grade in gradeList" :key="grade.id" :label="grade.gradeName">
                                            <Option v-for="lesson in grade.lessons" :value="lesson.id" :key="lesson.id">{{ lesson.lessonName }}</Option>
                                        </OptionGroup>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                    <Input type="text" v-model="modalData.phoneNumber" placeholder="请输入电话号码"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                    <Select v-model="modalData.gender" placeholder="请输入性别">
                                        <Option value="M">男</Option>
                                        <Option value="F">女</Option>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">生日 : </p>
                                    <DatePicker v-model="modalData.birthday" type="date" placeholder="请输入生日"></DatePicker>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                    <Input type="password" password v-model="modalData.password" placeholder="请输入使用密码"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">IMEI : </p>
                                    <Input type="text" v-model="modalData.imei" placeholder="请输入IMEI"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <div class="position-relative" style="width:fit-content; margin: 0 auto; ">
                                    <avatar v-if="modalData.name !== '' && modalData.imgUrl == null" :size="100" :username="modalData.name" class="mx-auto" :rounded="false"></avatar>
                                    <img v-else-if="modalData.name == '' && modalData.imgUrl == null" src="/img/icon/anonymous_avatar.png" alt="" style="width:100px; height: 100px;">
                                    <img v-else-if="modalData.imgUrl !== null" :src="modalData.imgUrl" alt="" style="width:100px; height: 100px;">
                                    <Icon v-if="modalData.imgUrl !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="modalData.imgUrl = null" class="position-absolute" style="top:0px;right:0px; cursor:pointer;" />
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
                                            <Icon type="ios-person"></Icon>
                                            上传头像
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-3 order-md-3" v-if="!$isMobile()">
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
                    <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                <Select v-model="modalData.familyAddress.province" @on-change="selectedProvinceOfFamilyAddress(modalData.familyAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="modalData.familyAddress.city" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(modalData.familyAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="modalData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(modalData.familyAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="modalData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-4 order-md-4">
                        <div class="col-12 mb-2 d-flex align-items-center">
                            <p class="min-width-fit-content text-right pr-2">个人介绍 : </p>
                            <Input type="textarea" v-model="modalData.introduce" placeholder="个人介绍" />
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
                title="学生编辑"
                :styles="{top:'75px',left:'-90px'}"
                scrollable
                @on-visible-change="changeVisibleEditModal"
            >
                <div class="row m-0 p-0">
                    <div class="col-md-9 order-2 order-md-1 m-0 p-0">
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">学生姓名 : </p>
                                    <Input type="text" v-model="editData.name" placeholder="请输入学生姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">学生学号 : </p>
                                    <Input type="text" v-model="editData.cardNum" placeholder="请输入学生学号"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长姓名 : </p>
                                    <Input type="text" v-model="editData.fatherName" placeholder="请输入家长姓名"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长电话 : </p>
                                    <Input type="text" v-model="editData.fatherPhone" placeholder="请输入家长电话"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">家长身份 : </p>
                                    <Select v-model="editData.fatherJob" placeholder="请输入家长身份">
                                        <Option value="farmer">农民</Option>
                                        <Option value="worker">工人</Option>
                                        <Option value="developer">开发商</Option>
                                    </Select>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">班级 : </p>
                                    <Select v-model="editData.lessonId" placeholder="请输入班级" @on-change="changeClassVal">
                                        <OptionGroup v-for="grade in gradeList" :key="grade.id" :label="grade.gradeName">
                                            <Option v-for="lesson in grade.lessons" :value="lesson.id" :key="lesson.id">{{ lesson.lessonName }}</Option>
                                        </OptionGroup>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">电话号码 : </p>
                                    <Input type="text" v-model="editData.phoneNumber" placeholder="请输入电话号码"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2" placeholder="请输入性別">性別 : </p>
                                    <Select v-model="editData.gender" placeholder="请输入性别">
                                        <Option value="M">男</Option>
                                        <Option value="F">女</Option>
                                    </Select>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">生日 : </p>
                                    <DatePicker v-model="editData.birthday" type="date" placeholder="请输入生日"></DatePicker>
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">使用密码 : </p>
                                    <Input type="password" password v-model="editData.password" placeholder="请输入使用密码"/>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-start align-items-center mb-2">
                                    <p class="min-width-fit-content text-right pr-2">IMEI : </p>
                                    <Input type="text" v-model="editData.imei" placeholder="请输入IMEI"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 order-1 order-md-2">
                        <div class="row m-0 p-0">
                            <div class="col-12 mx-auto p-md-0 p-4 text-center">
                                <div class="position-relative" style="width:fit-content; margin: 0 auto; ">
                                    <avatar v-if="editData.name !== '' && editData.userAvatar == null" :size="100" :username="editData.name" class="mx-auto" :rounded="false"></avatar>
                                    <img v-else-if="editData.name == '' && editData.userAvatar == null" src="/img/icon/anonymous_avatar.png" alt="" style="width:100px; height: 100px;">
                                    <img v-else-if="editData.userAvatar !== null" :src="editData.userAvatar" alt="" style="width:100px; height: 100px;">
                                    <Icon v-if="editData.userAvatar !== null" size="25" type="md-close-circle" color="#2D8CF0" @click="editData.userAvatar = null" class="position-absolute" style="top:0px;right:0px; cursor:pointer;" />
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
                                            <Icon type="ios-person"></Icon>
                                            上传头像
                                        </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-3 order-md-3" v-if="!$isMobile()">
                        <div class="col-12 d-flex justify-content-start align-items-center mb-2">
                            <p class="min-width-fit-content text-right pr-2">家庭地址 : </p>
                            <Select v-model="editData.familyAddress.province" class="mr-2" @on-change="selectedProvinceOfFamilyAddress(editData.familyAddress.province)" placeholder="--省--">
                                <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editData.familyAddress.city" class="mr-2" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(editData.familyAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select v-model="editData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(editData.familyAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div v-else class="col-md-12 m-0 p-0 row order-3 order-md-3" >
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center mb-2" >
                                <p class="min-width-fit-content text-right pr-2">学校地址 : </p>
                                <Select v-model="editData.familyAddress.province" @on-change="selectedProvinceOfFamilyAddress(editData.familyAddress.province)" placeholder="--省--">
                                    <Option v-for="item in madeJsonFromString" :value="item.value" :key="item.value">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editData.familyAddress.city" :disabled="willBeCityDataOfFamilyAddress == null" @on-change="selectedCityOfFamilyAddress(editData.familyAddress.city)" placeholder="--市--">
                                <Option v-for="item in willBeCityDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                            <Select style="padding-left: 75px;" class="mb-2"  v-model="editData.familyAddress.region" :disabled="willBeRegionDataOfFamilyAddress == null" @on-change="selectedRegionOfFamilyAddress(editData.familyAddress.region)" placeholder="--区--">
                                <Option v-for="item in willBeRegionDataOfFamilyAddress" :value="item.value" :key="item.value">{{ item.label }}</Option>
                            </Select>
                        </div>
                        <div class="col-12 mb-2" style="padding-left:90px;">
                            <Input type="text" v-model="editData.familyAddress.detail" :disabled="willBeRegionDataOfFamilyAddress == null" placeholder="详細地址"/>
                        </div>
                    </div>
                    <div class="col-md-12 m-0 p-0 row order-4 order-md-4">
                        <div class="col-12 mb-2 d-flex align-items-center">
                            <p class="min-width-fit-content text-right pr-2">个人介绍 : </p>
                            <Input type="textarea" v-model="editData.introduce" placeholder="个人介绍" />
                        </div>
                    </div>
                </div>

                <div slot="footer">
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
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
import image from '../../components/chungHua/image.vue';
export default {
    components:{
        menuItem,
        Avatar,
    },
    data () {
        return {
            // modalData:{
            //     name:'',
            //     phoneNumber:'',
            //     password:'',
            //     roleId:null,
            //     gender:null,
            //     nation : '',
            //     cardNum : '',
            //     familyAddress : {
            //         province : null,
            //         city : null, 
            //         region : null,
            //         detail : '',
            //     },
            //     residenceAddress : {
            //         province : null,
            //         city : null, 
            //         region : null,
            //         detail : '',
            //     },
            //     imgUrl : null,
            // },
            modalData : {
                name : '',
                phoneNumber : '',
                cardNum : '',
                fatherName : '',
                fatherPhone : '',
                fatherJob : null,
                birthday : '',
                gender : null,
                lessonId : null,
                gradeId : null,
                password : '',
                imei : '',
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
                name : '',
                phoneNumber : '',
                cardNum : '',
                fatherName : '',
                fatherPhone : '',
                fatherJob : null,
                birthday : '',
                gender : null,
                lessonId : null,
                gradeId : null,
                password : '',
                imei : '',
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : '',
                },
                introduce : '',
                userAvatar : null,
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
            schoolList : [],
            gradeList : [],
            lessonList : [],
            studentList : [],
            studentListLaw : [],
            selectedClassName : "全部",
            introduceStudentObj : {
                studentName : null,
                introduce : null
            },
            introduceStudentModal : false,
            studentColumn: [
                {
                    title: "号码",
                    key: 'id',
                    width: 60,
                },
                {
                    title: "学生姓名",
                    key: 'name',
                    width: 150,
                },
                {
                    title: "用户头像",
                    key: 'userAvatar',
                    width: 150,
                    render: (h, params) => {
                        if(params.row.userAvatar == null){
                            return h(Avatar, {
                                props: {
                                    username: params.row.name,
                                    size: 100,
                                }
                            })
                        }
                        else{
                            return h(image, {
                                props: {
                                    imgUrl: params.row.userAvatar,
                                    rounded : true,
                                }
                            })
                        }
                    }
                },
                {
                    title: "学生学号",
                    key: 'cardNum',
                    width: 150
                },
                {
                    title: "电话号码",
                    key: 'phoneNumber',
                    width: 150
                },
                {
                    title: "IMEI",
                    key: 'imei',
                    width: 150
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
                    title: "家长姓名",
                    key: 'fatherName',
                    width: 150
                },
                {
                    title: "家长电话",
                    key: 'fatherPhone',
                    width: 150
                },
                {
                    title: "家长身份",
                    key: 'fatherJob',
                    width: 90,
                    render: (h, params) => {
                        if(params.row.fatherJob == "farmer"){
                            return h("Tag", {
                                props: {
                                    color: "green",
                                }
                            }, "农民")
                        }
                        else if(params.row.fatherJob == "worker"){
                            return h("Tag", {
                                props: {
                                    color: "gold",
                                }
                            }, "工人")
                        }
                        else{
                            return h("Tag", {
                                props: {
                                    color: "magenta",
                                }
                            }, "开发商")
                        }
                    }
                },
                {
                    title: "生日",
                    key: 'birthday',
                    width: 120,
                    render: (h, params) => {
                        return h('div', [
                            h('p', {}, params.row.birthday.split(' ')[0]), 
                        ]);
                    }
                },
                {
                    title: "家庭地址",
                    key: 'familyAddress',
                    width: 200,
                },
                {
                    title: "个人介绍",
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
                                        this.introduceStudentObj = {
                                            studentName : params.row.name,
                                            introduce : params.row.introduce
                                        }
                                        this.showIntroduceOfStudentModal();
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
                    width: 90,
                    render: (h, params) => {
                        if(params.row.isActived == 0){
                            return h('div', [
                                h('Icon', {
                                    props: {
                                        type: 'md-create',
                                        size: '25',
                                        color: '#44B4E2'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        cursor: 'pointer'
                                    },
                                    on: {
                                        click: () => {
                                            this.showEditModal(params.row,params.index)
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
                                        marginRight: '5px',
                                        cursor: 'pointer'
                                    },
                                    on: {
                                        click: () => {
                                            this.showDeletingModal(params.row, params.index);
                                        }
                                    }
                                }),
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
                                            this.allow(params.row);
                                        }
                                    }
                                }, "激活"),
                            ]);
                        }
                        else{
                            return h('div', [
                                h('Icon', {
                                    props: {
                                        type: 'md-create',
                                        size: '25',
                                        color: '#44B4E2'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        cursor: 'pointer'
                                    },
                                    on: {
                                        click: () => {
                                            this.showEditModal(params.row,params.index)
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
                                        marginRight: '5px',
                                        cursor: 'pointer'
                                    },
                                    on: {
                                        click: () => {
                                            this.showDeletingModal(params.row, params.index);
                                        }
                                    }
                                }),
                                h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.allow(params.row);
                                        }
                                    }
                                }, "禁用"),
                            ]);
                        }
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
        const [res] = await Promise.all([
            // this.callApi('get','/api/users'),
            this.callApi('get','/api/student')
        ])
        if(res.status == 200){
            this.studentListLaw = JSON.parse(JSON.stringify(res.data));
            this.studentList = res.data;
            for(let i = 0 ; i < this.studentList.length ; i++){
                this.studentList[i].familyAddress = this.convertAddress(this.studentList[i].familyAddress);
            }
        }else{
            this.swr();
        }
        // if(resRole.status == 200){
        //     this.roles = resRole.data;
        // }else{
        //     this.swr();
        // }

        const [schoolTree,lessonList] = await Promise.all([
            this.callApi('get','/api/schoolTree'),
            this.callApi('get','/api/lesson'),
        ])
        if(schoolTree.status == 200){
            this.schoolList = schoolTree.data
            console.log("@@@", this.schoolList);
            this.gradeList = this.schoolList[0].grades
        }
        if(lessonList.status == 200){
            this.lessonList = lessonList.data
        }
        
        if(this.$store.state.user.schoolId !== null){
            let schoolId = this.$store.state.user.schoolId
            for(let i = 0 ; i < this.schoolList.length ; i++){
                if(schoolId == this.schoolList[i]){
                    this.gradeList = this.schoolList[i].grades;
                }
            }
        }
    },
    methods:{
        showModal(){
            this.addModal = true;
        },

        async addAdmin(){
            console.log("this.modalData",this.modalData) ;
            //name validation
            if(this.modalData.name.trim() == ''){
                return this.error("请输入姓名");
            }
            //name validation
            if(this.modalData.fatherName.trim() == ''){
                return this.error("请输入家长姓名");
            }
            //name validation
            if(this.modalData.fatherPhone.trim() == ''){
                return this.error("请输入家长电话");
            }
            //name validation
            if(this.modalData.fatherJob.trim() == ''){
                return this.error("请输入家长身份");
            }
            //name validation
            if(this.modalData.birthday == ''){
                return this.error("请输入生日");
            }
            //name validation
            if(this.modalData.lessonId == null){
                return this.error("请输入班级");
            }
            //card number validation
            if(this.modalData.cardNum.trim() == ''){
                return this.error('请输入身份证号');
            }
            if(/^\d*$/.test(this.modalData.cardNum) == false){
                return this.error('请输入正确的身份证号');
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
            //phone number validation
            if(this.modalData.imei.trim() == ''){
                return this.error('请输入imei');
            }
            if(/^\d*$/.test(this.modalData.imei) == false){
                return this.error('请输入正确的imei');
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
            
            this.isAdding = true;
            const res = await this.callApi('post', '/api/addStudent',this.modalData)
            if(res.status == 201){
                res.data.familyAddress = this.convertAddress(res.data.familyAddress);
                res.data.birthday = res.data.birthday.date;
                this.studentList.push(res.data);
                this.success('管理员用户已成功添加！');
                this.addModal = false;
                this.isAdding = false;
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
           //name validation
            if(this.editData.name.trim() == ''){
                return this.error("请输入姓名");
            }
            //name validation
            if(this.editData.fatherName.trim() == ''){
                return this.error("请输入家长姓名");
            }
            //name validation
            if(this.editData.fatherPhone.trim() == ''){
                return this.error("请输入家长电话");
            }
            //name validation
            if(this.editData.fatherJob.trim() == ''){
                return this.error("请输入家长身份");
            }
            //name validation
            if(this.editData.birthday == ''){
                return this.error("请输入生日");
            }
            //name validation
            if(this.editData.lessonId == null){
                return this.error("请输入班级");
            }
            //card number validation
            if(this.editData.cardNum.trim() == ''){
                return this.error('请输入身份证号');
            }
            if(/^\d*$/.test(this.editData.cardNum) == false){
                return this.error('请输入正确的身份证号');
            }
            //gender validation
            if(this.editData.gender == null){
                return this.error('请输入性别');
            }
            //phone number validation
            if(this.editData.phoneNumber.trim() == ''){
                return this.error('请输入电话号码');
            }
            if(/^\d*$/.test(this.editData.phoneNumber) == false){
                return this.error('请输入正确的电话号码');
            }
            //phone number validation
            if(this.editData.imei.trim() == ''){
                return this.error('请输入imei');
            }
            if(/^\d*$/.test(this.editData.imei) == false){
                return this.error('请输入正确的imei');
            }
            //password validation
            if(this.editData.password.trim() == ''){
                return this.error('请输入使用密码')
            }
            //check if password contain number
            if(/\d/.test(this.editData.password) == false){
                return this.error('密码应包含数字')
            }
            //check if password contain uppercase
            if(/[A-Z]/.test(this.editData.password) == false){
                return this.error('密码应至少包含1个大写字母')
            }
            //check if password contain special character
            if(/[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.editData.password) == false){
                return this.error('密码应包含至少1个特殊字符')
            }
            //check if length of password is more than 8
            if(this.editData.password.length < 8){
                return this.error('密码至少应包含8个字符')
            }
            //family address validation
            if( this.editData.familyAddress.province == null ||
                this.editData.familyAddress.city == null ||
                this.editData.familyAddress.region == null ||
                this.editData.familyAddress.detail == ''){
                    return this.error('请输入户籍地址');
            }

            this.isAdding = true;
            
            const res = await this.callApi('put', '/api/addStudent',this.editData)
            if(res.status === 200){
                this.studentList[this.index].name = this.editData.name;
                this.studentList[this.index].phoneNumber = this.editData.phoneNumber;
                this.studentList[this.index].cardNum = this.editData.cardNum;
                this.studentList[this.index].imei = this.editData.imei;
                this.studentList[this.index].fatherName = this.editData.fatherName;
                this.studentList[this.index].fatherPhone = this.editData.fatherPhone;
                this.studentList[this.index].fatherJob = this.editData.fatherJob;
                this.studentList[this.index].birthday = this.TimeView(this.editData.birthday);
                this.studentList[this.index].gender = this.editData.gender;
                this.studentList[this.index].familyAddress = this.convertAddress(JSON.stringify(this.editData.familyAddress));
                this.studentList[this.index].introduce = this.editData.introduce;
                this.studentList[this.index].userAvatar = this.editData.userAvatar;
                this.success('学生信息已成功更新。');
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


        showEditModal( student , index ){
            let obj = {
                id : student.id,
                name : student.name,
                phoneNumber : student.phoneNumber.toString(),
                cardNum : student.cardNum.toString(),
                fatherName : student.fatherName,
                fatherPhone : student.fatherPhone.toString(),
                imei : student.imei.toString(),
                fatherJob : student.fatherJob,
                birthday : student.birthday,
                gender : student.gender,
                lessonId : student.lessonId,
                gradeId : student.gradeId,
                password : student.password,
                familyAddress : {
                    province : null,
                    city : null, 
                    region : null,
                    detail : student.familyAddress.split(' ')[3],
                },
                introduce : student.introduce,
                userAvatar : student.userAvatar,
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
                this.studentList.splice(this.deletingIndex,1);
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
            }else{
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
        handleSuccessAvatarImageEdit (res, file) {
            res = `/uploads/${res}`
            this.editData.userAvatar = res;
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
                    name : '',
                    phoneNumber : '',
                    cardNum : '',
                    fatherName : '',
                    fatherPhone : '',
                    imei : '',
                    fatherJob : null,
                    birthday : '',
                    gender : null,
                    lessonId : null,
                    gradeId : null,
                    password : '',
                    familyAddress : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    introduce : '',
                    imgUrl : null,
                }
                this.willBeCityDataOfResidenceAddress = null;
                this.willBeCityDataOfFamilyAddress = null;
                this.willBeRegionDataOfResidenceAddress = null;
                this.willBeRegionDataOfFamilyAddress = null;
            }
        },
        changeVisibleEditModal(val){
            if(val == false){
                this.editData = {
                    name : '',
                    phoneNumber : '',
                    cardNum : '',
                    fatherName : '',
                    fatherPhone : '',
                    fatherJob : null,
                    birthday : '',
                    gender : null,
                    imei : '',
                    lessonId : null,
                    gradeId : null,
                    password : '',
                    familyAddress : {
                        province : null,
                        city : null, 
                        region : null,
                        detail : '',
                    },
                    introduce : '',
                    userAvatar : null,
                }
                this.willBeCityDataOfResidenceAddress = null;
                this.willBeCityDataOfFamilyAddress = null;
                this.willBeRegionDataOfResidenceAddress = null;
                this.willBeRegionDataOfFamilyAddress = null;
            }
        },

        changeClassVal(val){
            this.modalData.lessonId = val;
            for(let i = 0; i < this.gradeList.length ; i++){
                for(let j = 0; j < this.gradeList[i].lessons.length ; j++){
                    if(val == this.gradeList[i].lessons[j].id){
                        this.modalData.gradeId = this.gradeList[i].id;
                        return;
                    }
                }
            }
        },

        async changeShowStudentClassVal(val){
            if(val == 0){
                const res = await this.callApi('get','/api/student')
                if(res.status == 200){
                    this.studentList = res.data;
                    for(let i = 0; i < this.studentList.length ; i++){
                        this.studentList[i].familyAddress = this.convertAddress(this.studentList[i].familyAddress)
                    }
                }else{
                    this.swr();
                }
            }
            else {
                let payload ={
                    lessonId : val,
                    gradeId : null
                }
                for(let i = 0; i < this.gradeList.length ; i++){
                    for(let j = 0; j < this.gradeList[i].lessons.length ; j++){
                        if(val == this.gradeList[i].lessons[j].id){
                            payload.gradeId = this.gradeList[i].id;
                        }
                    }
                }
    
                await axios.get('/api/studentBylessonId',{params:{lessonId:payload.lessonId, gradeId:payload.gradeId}})
                        .then(res=>{
                            if(res.data.length > 0){
                                this.studentList = res.data;
                                for(let i = 0; i < this.studentList.length ; i++){
                                    this.studentList[i].familyAddress = this.convertAddress(this.studentList[i].familyAddress)
                                }
                            }else{
                                this.studentList = []
                            }
                        })
                        .catch(err=>{
                            console.log(err)
                        })
            }
        },

        showIntroduceOfStudentModal(){
            this.introduceStudentModal = true;
        },

        initeMedalData(val){
            if(val == false){
                this.introduceStudentObj = {
                    studentName : null,
                    introduce : null
                },
                this.introduceStudentModal = false;
            }
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
    }
}
</script>