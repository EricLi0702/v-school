<template>
    <div>
        <div class="es-item">
            <Checkbox
                :value="checkSchool"
                @click.prevent.native="handleCheckSchool"
            >{{schoolList.schoolName}}</Checkbox>
        </div>
        <div class="category-title"></div>

        <CheckboxGroup v-model="checkGradeName" @on-change="checkAllGradeChange">
            <fragment v-for="grade in schoolList.grades" :key="grade.id">
                <div class="es-item"><Checkbox @click.prevent.native="handleCheckGrade(grade)" :value="checkGradeFlag[grade.id]" :label="grade.gradeName">{{grade.gradeName}}</Checkbox></div>
                <CheckboxGroup v-model="checkLessonName" @on-change="checkAllLessonChange">
                <fragment v-for="lesson in grade.lessons" :key="lesson.id">
                    <div class="es-item"><Checkbox  @click.prevent.native="handleCheckLesson(grade,lesson)" :value="checkLessonFlag[lesson.id]" :label="lesson.lessonName">{{lesson.lessonName}}</Checkbox></div>
                </fragment>
                </CheckboxGroup>    
            </fragment>
        </CheckboxGroup>
        <!-- <vsa-list>
            <CheckboxGroup v-model="checkGradeName" @on-change="checkAllGradeChange">
                <vsa-item v-for="grade in schoolList.grades" :key="grade.id">
                    <vsa-heading>
                        <div class="es-item"><Checkbox @click.prevent.native="handleCheckGrade(grade)" :value="checkGradeFlag[grade.id]" :label="grade.gradeName">{{grade.gradeName}}</Checkbox></div>
                    </vsa-heading>
                    <CheckboxGroup v-model="checkLessonName" @on-change="checkAllLessonChange">
                    <vsa-content>
                    <fragment v-for="lesson in grade.lessons" :key="lesson.id">
                        <div class="es-item"><Checkbox  @click.prevent.native="handleCheckLesson(grade,lesson)" :value="checkLessonFlag[lesson.id]" :label="lesson.lessonName">{{lesson.lessonName}}</Checkbox></div>
                    </fragment>
                    </vsa-content>
                    </CheckboxGroup>    
                </vsa-item>
            </CheckboxGroup>
        </vsa-list> -->
        <div class="es-model-operate">
            <Button type="primary" @click="submit">提交</Button>
        </div>
    </div>
</template>
<script>
    // import {
    // VsaList,
    // VsaItem,
    // VsaHeading,
    // VsaContent,
    // VsaIcon
    // } from 'vue-simple-accordion';
    // import 'vue-simple-accordion/dist/vue-simple-accordion.css';
    export default {
        props:['type'],
        components: {
            // VsaList,
            // VsaItem,
            // VsaHeading,
            // VsaContent,
            // VsaIcon
        },
        data () {
            return {
                indeterminate: true,
                schoolList:[],
                checkSchool:false,
                checkGradeName:[],
                checkGradeFlag:[],
                checkLessonName:[],
                checkLessonFlag:[],
            }
        },
        async created(){
            const res = await this.callApi('get','/api/allLesson');
            console.log('!!!!!',res)
            if(res.status == 200){
                this.schoolList = res.data[0]
            }
        },
        methods: {
            handleCheckSchool(){
                this.checkSchool = !this.checkSchool
                if(this.checkSchool){
                    for(let i=0;i<this.schoolList.grades.length;i++){
                        this.checkGradeName.push(this.schoolList.grades[i].gradeName)
                        this.checkGradeFlag[this.schoolList.grades[i].id] = true
                        for(let j=0;j<this.schoolList.grades[i].lessons.length;j++){
                            if(this.isLessonName(this.schoolList.grades[i].lessons[j].lessonName) == 0){
                                this.checkLessonName.push(this.schoolList.grades[i].lessons[j].lessonName)
                            }
                            this.checkLessonFlag[this.schoolList.grades[i].lessons[j].id] = true
                        }
                    }
                }else{
                    this.checkGradeName = []
                    this.checkLessonName = []
                    for(let i=0;i<this.schoolList.grades.length;i++){
                        this.checkGradeFlag[this.schoolList.grades[i].id] = false
                        for(let j=0;j<this.schoolList.grades[i].lessons.length;j++){
                            this.checkLessonFlag[this.schoolList.grades[i].lessons[j].id] = false
                        }
                    }
                }
            },
            handleCheckGrade(grade){
                
                this.checkGradeFlag[grade.id] = !this.checkGradeFlag[grade.id]
                if(this.checkGradeFlag[grade.id]){
                    for(let i=0;i<grade.lessons.length;i++){
                        if(this.isLessonName(grade.lessons[i].lessonName) == 0){
                            this.checkLessonName.push(grade.lessons[i].lessonName)
                        }
                        this.checkLessonFlag[grade.lessons[i].id] = true
                    }
                    if(this.isGradeName(grade.gradeName) == 0){
                        this.checkGradeName.push(grade.gradeName)
                    }
                    if(this.checkGradeName.length == this.schoolList.grades.length){
                        this.checkSchool = true
                    }
                }else{
                    for(let i=0;i<grade.lessons.length;i++){
                        let index = this.checkLessonName.indexOf(grade.lessons[i].lessonName)
                        if(index > -1){
                            this.checkLessonName.splice(index,1)
                        }
                        this.checkLessonFlag[grade.lessons[i].id] = false
                    }
                    let index = this.checkGradeName.indexOf(grade.gradeName)
                    if(index > -1){
                        this.checkGradeName.splice(index,1)
                    }
                    this.checkSchool = false
                }
            },
            handleCheckLesson(grade,lesson){
                this.checkLessonFlag[lesson.id] = !this.checkLessonFlag[lesson.id]
                console.log(this.checkLessonFlag)
                console.log(grade)
                if(this.checkLessonFlag[lesson.id]){
                    if(this.isLessonName(lesson.lessonName) == 0){
                        this.checkLessonName.push(lesson.lessonName)
                    }
                    for(let i=0;i<grade.lessons.length;i++){
                        if(this.checkLessonFlag[grade.lessons[i].id] == false || this.checkLessonFlag[grade.lessons[i].id] == null ){
                           return
                        }
                    }
                    if(this.isGradeName(grade.gradeName) == 0){
                        this.checkGradeName.push(grade.gradeName)
                    }
                    if(this.checkGradeName.length == this.schoolList.grades.length){
                        this.checkSchool = true
                    }
                }else{
                    let index = this.checkLessonName.indexOf(lesson.lessonName)
                    if(index > -1){
                        this.checkLessonName.splice(index,1)
                    }
                    let gradeId = this.checkGradeName.indexOf(grade.gradeName)
                    if(gradeId > -1){
                        this.checkGradeName.splice(gradeId,1)
                        this.checkGradeFlag[grade.id] = false
                    }
                    this.checkSchool = false;

                }
            },
            isGradeName(gradeName){
                let index = this.checkGradeName.indexOf(gradeName)
                if(index > -1){
                    return 1
                }else{
                    return 0
                }
            },
            isLessonName(lessonName){
                let index = this.checkLessonName.indexOf(lessonName)
                if(index > -1){
                    return 1
                }else{
                    return 0
                }
            },
            checkAllGradeChange(){
                console.log('checkAllGradeChange')
            },
            checkAllLessonChange(){
                console.log('checkAllLessonChange')
            },
            submit(){
                // console.log(this.type);
                this.$router.push({path:`${this.$route.path}?questionType=${this.type}`,query:{viewList:this.checkLessonName}})
            }
        }
    }
</script>
