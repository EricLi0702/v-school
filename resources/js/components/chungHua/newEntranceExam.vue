<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <div class="es-item">
                <div class="es-item-left">
                    <span>各班级分别导入</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="eachClass" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>考试名称</span>
                </div>
                <div class="es-item-right">
                    <Input v-model="examName" class="rightToLeft" placeholder="选填" style="width: 300px" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>考试时间</span>
                </div>
                <div class="es-item-right">
                    <DatePicker type="date" v-model="examTime" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=联考&addQuestion=成绩展示`">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>成绩展示</span>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="category-title"></div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>导入文件</span>
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
        </div>
        <div v-else>
            <div class="es-item">
                <div class="es-item-left">
                    <span>排名</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.rank" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>平均分</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.averageScore" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>标准分</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.standardScore" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>方差</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.variance" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>标准差</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.standardDeviation" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>分数</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.fraction" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <span>增幅</span>
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="performanceDisplay.increase" />
                </div>
            </div> 
        </div>
    </div>
    
</template>

<script>
export default {
    computed:{
        currentPath(){
            return this.$route
        }
    },
    data(){
        return{
            eachClass:false,
            examName:'',
            examTime:'',
            performanceDisplay:{
                rank:false,
                averageScore:false,
                standardScore:false,
                variance:false,
                standardDeviation:false,
                fraction:false,
                increase:false
            }
        }
    }    
}
</script>