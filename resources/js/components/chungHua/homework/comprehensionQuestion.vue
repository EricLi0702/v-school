<template>
  <div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="questionType($event)">
                    <a href="javascript:void(0)">
                        <div>{{addData.selQuestion}}<Icon type="ios-arrow-down" /></div>
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="单选题">单选题</DropdownItem>
                        <DropdownItem name="多选题">多选题</DropdownItem>
                        <DropdownItem name="填空题">填空题</DropdownItem>
                        <DropdownItem name="解答题">解答题</DropdownItem>
                        <DropdownItem name="判断题">判断题</DropdownItem>
                        <DropdownItem name="连线题">连线题</DropdownItem>
                        <DropdownItem name="文字排序题">文字排序题</DropdownItem>
                        <DropdownItem name="图片排序题">图片排序题</DropdownItem>
                        <DropdownItem name="填空题">填空题</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
            <div class="es-item-right">
               <span class="text-color">删除</span> 
            </div>
        </div>
        <div>
            <vue-editor v-model="addData.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
        </div>
        <div class="es-item">
            <div class="es-item-left w-100" @click="showAnalysis = !showAnalysis">
                <div>解析：</div>
                <div v-html="addData.analysisData" v-if="addData.analysisData"></div>
                <div v-else>请输入解析内容</div>
            </div>
        </div>
        <div class="homework-editor">
            <vue-editor v-model="addData.analysisData" placeholder="请输入解析内容" v-if="showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
        </div>
  </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
export default {
    components:{
        VueEditor
    },
    data(){
        return{
            addData:{
                selQuestion:'解答题',
                contentData:"",
            },
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            showAnalysis:false,
        }
    }
}
</script>

<style>

</style>