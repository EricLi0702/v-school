<template>
  <div>
    <div v-if="element">
      <div v-if="element.type == '单选题' || element.type == '多选题'">
        <div class="es-item position-relative"  >
            <Icon v-if="index>0" type="ios-close" class="customIcon position-absolute" @click="removeQuestion(index)" />
            <div class="es-item-left w-100" @click="showEditor">
                <span>{{alphabet[index]}}.  </span>
                <div v-html="element.contentData" v-if="element.contentData"></div>
                <div v-else>{{element.title}}</div>
            </div>
        </div>
        <div class="homework-editor">
          <vue-editor v-model="element.contentData" placeholder="请输入解析内容" v-if="showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
        </div>
      </div>
      <div v-else-if="element.type == '填空题'">
        <div class="es-item">
          <div class="es-item-left w-50">
            <span>第{{index+1}}空</span>
          </div>
          <div class="es-item-right w-50">
            <Input v-model="element.contentData" class="customInput rightToLeft" placeholder="(请设置答案(必填"/>
            <Icon v-if="index>0" type="ios-close" class="customIcon" style="transform:unset" @click="removeQuestion(index)" />
          </div>
        </div>
      </div>
      <div v-else-if="element.type == '文字排序题'">
        <div class="es-item">
          <div class="es-item-left w-50">
            <span>选项{{index+1}}</span>
          </div>
          <div class="es-item-right w-50">
            <Input v-model="element.contentData.val" class="customInput rightToLeft" placeholder="(请输入选项内容(必填"/>
            <Icon v-if="index>0" type="ios-close" class="customIcon" style="transform:unset" @click="removeQuestion(index)" />
          </div>
        </div>
      </div>
    </div> 
    
  </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
export default {
  props:['index','element'],
  components:{
    VueEditor
  },
  data: ()=>({
    showAnalysis:false,
    customToolbar:[
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "code-block"]
    ],
    contentData:'',
    alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
  }),
  methods:{
    showEditor(){
      this.showAnalysis = !this.showAnalysis
    },
    removeQuestion(index){
      let removeItem = {}
      this.$set(removeItem,'index',index)
      this.$emit('removeItem',removeItem)
    }
  }
}
</script>

<style>

</style>