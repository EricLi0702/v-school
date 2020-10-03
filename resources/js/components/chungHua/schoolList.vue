<template>
    <div>
        <div class="es-item">
            <Checkbox
                :value="checkSchool"
                @click.prevent.native="handleCheckSchool"
            >{{schoolList.schoolName}}</Checkbox>
        </div>
        <CheckboxGroup v-model="checkGradeName" @on-change="checkAllGradeChange">
            <fragment v-for="grade in schoolList.grades" :key="grade.id">
                <div class="es-item"><Checkbox @click.prevent.native="handleCheckGrade(grade)" :value="checkGradeFlag[grade.id]" :label="grade.gradeName">{{grade.gradeName}}</Checkbox></div>
                <CheckboxGroup v-model="checkLessonName" @on-change="checkAllLessonChange">
                <fragment v-for="lesson in grade.lessons" :key="lesson.id">
                    <div class="es-item"><Checkbox  @click.prevent.native="handleCheckLesson(lesson)" :value="checkLessonFlag[lesson.id]" :label="lesson.lessonName">{{lesson.lessonName}}</Checkbox></div>
                </fragment>
                </CheckboxGroup>    
            </fragment>
        </CheckboxGroup>
        <div class="es-model-operate">
            <Button type="primary" @click="submit">提交</Button>
        </div>
    </div>
</template>
<script>
    export default {
        props:['type'],
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
                console.log(this.checkGradeName)
                this.checkGradeFlag[grade.id] = !this.checkGradeFlag[grade.id]
                if(this.checkGradeFlag[grade.id]){
                    for(let i=0;i<grade.lessons.length;i++){
                        if(this.isLessonName(grade.lessons[i].lessonName) == 0){
                            this.checkLessonName.push(grade.lessons[i].lessonName)
                        }
                        this.checkLessonFlag[grade.lessons[i].id] = true
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
                    this.checkSchool = false
                }
            },
            handleCheckLesson(lesson){
                this.checkLessonFlag[lesson.id] = !this.checkLessonFlag[lesson.id]
                if(this.checkLessonFlag[lesson.id]){
                    if(this.isLessonName(lesson.lessonName) == 0){
                        this.checkLessonName.push(lesson.lessonName)
                    }

                }else{
                    let index = this.checkLessonName.indexOf(lesson.lessonName)
                    if(index > -1){
                        this.checkLessonName.splice(index,1)
                    }
                    lesson
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
