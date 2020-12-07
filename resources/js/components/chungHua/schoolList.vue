<template>
    <div class="select-class-target">
        <div v-if="isloadingSchool" class="row justify-content-center pt-3 m-0" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
        </div>
        <div v-if="!isloadingSchool" class="vx-item is-click" v-on:click="handleCheckSchool">
            <Checkbox
                v-model="checkSchool"
            >{{schoolList.schoolName}}</Checkbox>
        </div>
        <div v-if="!isloadingSchool" class="category-title"></div>

        <CheckboxGroup v-if="!isloadingSchool" v-model="checkGradeName" @on-change="checkAllGradeChange">
            
            <fragment v-for="grade in schoolList.grades" :key="grade.id">
                <div class="vx-item is-click" v-on:click="handleCheckGrade(grade)"><Checkbox v-model="checkGradeFlag[grade.id]" :label="grade.gradeName">{{grade.gradeName}}</Checkbox></div>
                <CheckboxGroup v-model="checkLessonName" @on-change="checkAllLessonChange">
                <fragment v-for="lesson in grade.lessons" :key="lesson.id">
                    <div class="vx-item is-click pl-5" v-on:click="handleCheckLesson(grade,lesson)"><Checkbox v-model="checkLessonFlag[lesson.id]" :label="lesson.id">{{lesson.lessonName}}</Checkbox></div>
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
        components: {
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
                isloadingSchool : false,
            }
        },
        computed:{
            currentPath(){
                return this.$route
            }
        },
        async created(){
            this.isloadingSchool = true;
            const res = await this.callApi('get','/api/allLesson');
            if(res.status == 200){
                this.isloadingSchool = false;
                if(this.currentPath.params.schoolName){
                    for(let i=0;i<res.data.length;i++){
                        if(res.data[i].id == this.currentPath.params.schoolName){
                            this.schoolList = res.data[i]
                        }
                    }
                }
                if(this.currentPath.params.className){
                    for(let i=0;i<res.data.length;i++){
                        for(let j=0;j<res.data[i].grades.length;j++){
                            for(let k=0;k<res.data[i].grades[j].lessons.length;k++){
                                if(this.currentPath.params.className == res.data[i].grades[j].lessons[k].id){
                                    this.schoolList = res.data[i]
                                }   
                            }
                        }
                    }
                }
                // this.schoolList = res.data[0]
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
                            if(this.isLessonName(this.schoolList.grades[i].lessons[j].id) == 0){
                                this.checkLessonName.push(this.schoolList.grades[i].lessons[j].id)
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
                        if(this.isLessonName(grade.lessons[i].id) == 0){
                            this.checkLessonName.push(grade.lessons[i].id)
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
                        let index = this.checkLessonName.indexOf(grade.lessons[i].id)
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
                if(this.checkLessonFlag[lesson.id]){
                    if(this.isLessonName(lesson.id) == 0){
                        this.checkLessonName.push(lesson.id)
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
                    let index = this.checkLessonName.indexOf(lesson.id)
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
            },
            checkAllLessonChange(){
            },
            submit(){                
                this.checkLessonName.push(this.schoolList.id);
                if(this.type == '养成打卡'){
                    this.$emit('viewList',this.checkLessonName)
                    this.$router.push({path:`${this.$route.path}?applicationType=${this.type}&questionType=${this.type}`})
                }else{
                    this.$router.push({path:`${this.$route.path}?applicationType=${this.type}&questionType=${this.type}`,query:{viewList:this.checkLessonName}})
                }
            }
        }
    }
</script>
