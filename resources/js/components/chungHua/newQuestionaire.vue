<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板`">
                <div class="category-title template">
                    <Icon type="ios-list-box-outline" />
                    <span>可用模板5，草稿0</span>
                </div>
            </router-link>
            <div class="questionaire-create">
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input v-model="title" class="customInput" placeholder="标题"/>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input v-model="description" class="customInput" placeholder="标题"/>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=调查范围`">
                    <div class="es-item">
                        <div class="es-item-left">
                            调查范围
                        </div>
                        <div class="es-item-right">
                            <span>必填</span>
                            <Icon type="ios-arrow-forward" /> 
                        </div>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        截止时间
                    </div>
                    <div class="es-item-right">
                        <DatePicker type="datetime" v-model="deadline" placeholder="选填" ></DatePicker>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        匿名问卷
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="questionaireFlag" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        答卷人可见结果
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="resultFlag" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        外部人员可作答
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="answerFlag" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add" />
                        <span>单选题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add" />
                        <span>多选题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add" />
                        <span>问答题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add" />
                        <span>统计题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add" />
                        <span>评分题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-model-operate">
                    <Button type="info">提交</Button>
                    <Button type="primary">存草稿</Button>
                </div>
            </div>
        </div>
        <div v-else>
            <addQuestion></addQuestion>
        </div>
    </div>
</template>

<script>
import addQuestion from './addQuestion'
export default {
    components:{
        addQuestion
    },
    data(){
        return{
            title:'',
            description:'',
            deadline:'',
            questionaireFlag:true,
            resultFlag:true,
            answerFlag:false
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    }
}
</script>