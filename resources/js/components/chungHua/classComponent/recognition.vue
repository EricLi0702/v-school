<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,selType:'表彰类型'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        表彰类型
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.type != ''">{{addData.type}}</span>
                        必选
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
            </router-link>
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,selType:'表彰对象'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        表彰对象
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.students.length>0">{{addData.students.length}}位成员</span>
                        <span v-else>必选</span> 
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
            </router-link>
            
            <div class="es-item">
                <div class="es-item-left">
                    表彰称号
                </div>
                <div class="es-item-right">
                    <Input v-model="addData.awardTitle" class="customInput w-100" placeholder="选填(最多8个字)" maxlength="8" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    颁发日期
                </div>
                <div class="es-item-right">
                    <DatePicker type="date" :options="options"  v-model="addData.publishDate" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <textarea v-model="addData.description" class="text-content" style="height:280px" cols="30" rows="10" placeholder="标题" ></textarea>
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,selType:'表彰类型'}}">
                <div class="es-item">

                </div>
            </router-link>
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,selType:'模板'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        模板
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
            </router-link>
        </div>
        <div v-else-if="currentPath.query.selType == '表彰类型'">
            <div class="es-item" @click="selRecType('班级')">
                班级
            </div>
            <div class="es-item" @click="selRecType('校级')">
                校级
            </div>
            <div class="es-item" @click="selRecType('区级')">
                区级
            </div>
            <div class="es-item" @click="selRecType('市级')">
                市级
            </div>
            <div class="es-item" @click="selRecType('省级')">
                省级
            </div>
            <div class="es-item" @click="selRecType('国家级')">
                国家级
            </div>
        </div>
        <div v-else-if="currentPath.query.selType == '表彰对象'">
            <contactComponent @selectedUser="selUser"></contactComponent>
        </div>
        <div v-else-if="currentPath.query.selType == '模板'" class="template-list">
            <div class="tl-item" v-for="(imgStyle,i) in tempImg" :key="i">
                <div class="commendation" @click="selImage(imgStyle)">
                    <img src="/img/icon/medal_empty.png" alt="" :style="imgStyle.recImg">
                    <div class="cmdn-content">
                        <div class="cmdn-name scrollBar">
                            <span v-for="(name,j) in addData.students" :key="j">{{name}},</span>
                        </div>
                        <div class="cmdn-word">{{addData.description}}</div>
                        <div class="cmdn-title">{{addData.awardTitle}}</div>
                    </div>
                    <div class="cmdn-tag">
                        <div>{{className}}</div>
                        <div>{{TimeView(addData.publishDate)}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
import contactComponent from '../contactComponent'
import template from '../../../json/chungHua/medalView.json'
export default {
    components:{
        contactComponent,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    data(){
        return{
            addData:{
                type:'',
                students:[],
                awardTitle:'',
                publishDate:'',
                description:'',
                imgUrl:''
            },
            tempImg:template.recognition,
            options:{
                disableDate(date){
                    return date && date.valueOf() > Date.now();
                }
            },
            className:'',
            isLoading:false
        }
    },
    async created(){
        await axios.get('/api/oneLesson',{params:{id:this.currentPath.params.className}})
            .then(res=>{
                this.className = res.data[0].lessonName
                console.log(this.className)
            })
        console.log(this.tempImg)
    },
    methods:{
        selUser(val){
            this.addData.students = val
        },
        selImage(obj){
            this.addData.imgUrl = obj.imgUrl
            this.$router.push({path:currentPath.path,query:{questionType:currentPath.query.questionType}})
        },
        selRecType(str){
            this.addData.type = str
            this.$router.push({path:currentPath.path,query:{questionType:currentPath.query.questionType}})
        },
        submit(){
            console.log(this.addData)
        }
    }
}
</script>

<style>

</style>