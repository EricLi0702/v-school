<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == '调查范围'">
                <Menu>
                    <Submenu name="1">
                        <template slot="title">
                            <Checkbox>
                                内容管理
                            </Checkbox>
                        </template>
                        <MenuItem name="1-1"><Checkbox>文章管理</Checkbox></MenuItem>
                    </Submenu>
                </Menu>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '应用模板'">
                <div class="apps-template">
                    <div class="template-item" v-if="templateData.length">
                        <img src="" alt="" class="picture">
                        <p class="text"></p>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=add`">
                        <div class="template-item-add">
                            <Icon type="ios-add" size="120" color="#DEDEDE"/>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'add'">
            <div>
                <div class="es-item">
                    <div class="es-item-left">
                        模板名称
                    </div>
                    <div class="es-item-right">
                        <Input v-model="addData.templateName" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
                    </div>
                </div>
                
                    <div class="es-item">
                        
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','jpeg','png']"
                                :max-size="2048"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="api/category/upload">
                                    <span>模板封面</span>
                            </Upload>
                        </div>
                        <div class="es-item-right">
                            <img :src="addData.imgUrl" alt="" style="width:40px;" v-if="addData.imgUrl">
                            <span v-else>必填</span>
                            <Icon type="ios-arrow-forward" />
                        </div>
                        
                    </div>
                 
                <div class="category-title"></div>
                <div class="es-item">
                    <Input v-model="addData.title" class="customInput w-100" placeholder="标题"/>
                </div>
                <div class="es-item">
                    <Input v-model="addData.description" class="customInput w-100" placeholder="说明（选填）"/>
                </div>
                <div class="category-title"></div>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=单选题`">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Icon type="ios-add"/>
                            <span>单选题</span>
                        </div>
                        <div class="es-item-right"></div>
                    </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=多选题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>多选题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=问答题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>问答题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=统计题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>统计题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=评分题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>评分题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
            
        </div>
        <div id="单选题" v-else-if="currentPath.query.template == '单选题'">
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                <div class="image-block">
                    <img src="" alt="">
                </div>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div class="es-item" @click="addContent">
                <div class="es-item-left">
                    <Icon type="ios-add"/>
                    <span>添加选项</span>
                </div>
            </div>
            <!-- <div class="es-model-operate">
                <Button type="primary">提交</Button>
            </div> -->
        </div>
        <div id="多选题" v-else-if="currentPath.query.template == '多选题'">
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="选项内容"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
            </div>
            <div class="es-item" @click="addContent">
                <div class="es-item-left">
                    <Icon type="ios-add"/>
                    <span>添加选项</span>
                </div>
            </div>
        </div>
        <div id="问答题" v-else-if="currentPath.query.template == '问答题'">
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
        </div>
        <div id="统计题" v-else-if="currentPath.query.template == '统计题'">
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left">
                        数字范围
                    </div>
                    <div class="es-item-right">
                        <Input v-model="from" class="customInput" style="width:50px;" placeholder="起始值"/>至 
                        <Input v-model="to" class="customInput" style="width:50px;" placeholder="结束值"/>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-lef">
                        单位
                    </div>
                    <div class="es-item-right">
                        <Input v-model="unit" class="customInput rightToLeft" placeholder="必填"/>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
        </div>
        <div id="评分题" v-else-if="currentPath.query.template == '评分题'">
            <div>
                <textarea name="" id="" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
                <div class="ke-custom-toolbar">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['jpg','gif','png']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/image">
                                    <img src="/img/icon/photo.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/other">
                                    <img src="/img/icon/file.png" alt="" class="uploadicon">
                            </Upload>
                            <Upload
                                ref="uploads"
                                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                                :on-success="handleSuccess"
                                :on-error="handleError"
                                :format="['mp4']"
                                :max-size="2048"
                                :show-upload-list="false"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                action="/api/fileUpload/video">
                                    <img src="/img/icon/video.png" alt="" class="uploadicon">
                            </Upload>
                        </div>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        最高分
                    </div>
                    <div class="es-item-right">
                        <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                            <a href="javascript:void(0)">
                                {{maxMinute}}
                                <Icon type="ios-arrow-forward" />
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="2">2</DropdownItem>
                                <DropdownItem name="3">3</DropdownItem>
                                <DropdownItem name="4">4</DropdownItem>
                                <DropdownItem name="5">5</DropdownItem>
                                <DropdownItem name="6">6</DropdownItem>
                                <DropdownItem name="7">7</DropdownItem>
                                <DropdownItem name="8">8</DropdownItem>
                                <DropdownItem name="9">9</DropdownItem>
                                <DropdownItem name="10">10</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            templateData:[],
            token:'',
            addData:{
                templateName:'',
                imgUrl:'',
                title:'',
                description:'',

            },
            from:'',
            to:'',
            unit:'',
            maxMinute:2,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken;
        // const [school,grade,lesson] = await Promise.all([
        //     this.callApi('get','/api/surveySchool'),
        //     this.callApi('get','/api/surveyGrade'),
        //     this.callApi('get','/api/surveyLesson'),
        // ])
        // this.$set(addData,'isDeleting',true);
        const lesson = await this.callApi('get','/api/surveyLesson')
        if(lesson.status == 200){
            console.log('!!!!!!!!!',lesson.data);
        }
    },
    methods:{
        handleSuccess (res, file) {
            console.log('@@@@@@@@',res);
            res = `/uploads/${res}`
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            console.log('##########',res);
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            console.log('$$$$$$$$$$');
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            console.log('%%%%%%%')
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        addContent(){

        },
        visible($event){
            this.maxMinute = $event;
        }
    }
}
</script>