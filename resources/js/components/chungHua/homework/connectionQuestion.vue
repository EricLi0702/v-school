<template>
  <div>
      <div class="matching-item">
          <div style="display: flex; justify-content: space-between; padding:10px">
             <p>连线{{index+1}}</p> 
             <p v-if="index>1" class="text-color" @click="removeItem(index)">删除</p>
          </div>
          <div class="d-flex">
              <div style="width:10%; padding:10px;">左选项</div> 
              <Input v-model="element.first.val" class="customInput pt-11px" :placeholder="element.title" />
          </div>
          <div class="d-flex">
              <div style="width:10%; padding:10px;">左选项</div>
              <Input v-model="element.second.val" class="customInput pt-11px" :placeholder="element.title" />
          </div>
      </div>
  </div>
</template>

<script>
export default {
    props:['index','element'],
    methods:{
        removeItem(index){
            let removeItem = {}
            this.$set(removeItem,'index',index)
            this.$emit('removeItem',removeItem)
        }
    }
}
</script>

<style>

</style>