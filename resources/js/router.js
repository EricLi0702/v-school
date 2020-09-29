import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
//admin project pages
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminuser from './admin/pages/adminuser'
import role from './admin/pages/role'
import login from './admin/pages/login'
import assignRole from './admin/pages/assignRole'
import blog from './admin/pages/blog'
import School from './admin/pages/school'
import Grade from './admin/pages/grade'
import Lesson from './admin/pages/lesson'
//chungHua
import schoolSpace from './view/chungHua/schoolSpace'
import allTeacher from './view/chungHua/allTeacher'
import middle21 from './view/chungHua/middle21'
import middle22 from './view/chungHua/middle22'
import middle23 from './view/chungHua/middle23'
import middle24 from './view/chungHua/middle24'
import middle25 from './view/chungHua/middle25'
import middle26 from './view/chungHua/middle26'

import middle31 from './view/chungHua/middle31'
import middle32 from './view/chungHua/middle32'
import middle33 from './view/chungHua/middle33'
import middle34 from './view/chungHua/middle34'
import middle35 from './view/chungHua/middle35'
import middle36 from './view/chungHua/middle36'

import high21 from './view/chungHua/high21'
import high22 from './view/chungHua/high22'
import high23 from './view/chungHua/high23'
import high24 from './view/chungHua/high24'
import high25 from './view/chungHua/high25'
import high26 from './view/chungHua/high26'

import high31 from './view/chungHua/high31'
import high32 from './view/chungHua/high32'
import high33 from './view/chungHua/high33'
import high34 from './view/chungHua/high34'
import high35 from './view/chungHua/high35'
import high36 from './view/chungHua/high36'

import vSchoolSpace from './view/vSchool/schoolSpace'
import vAllTeachers from './view/vSchool/allTeachers'
import safetyEducationCourse from './view/vSchool/safetyEducation'
import eighthGradeMathematics from './view/vSchool/eighthGrade'
import thirdGradeMathematics from './view/vSchool/thirdGrade'
import geographyExerciseBank from './view/vSchool/geographyExercise'
import Analects from './view/vSchool/analects'
import mathematics from './view/vSchool/mathematics'
import mathematicalRational from './view/vSchool/mathematicalRational'
import fifthGradeMathematics from './view/vSchool/fifthGrade'
import bigClassBigClass1 from './view/vSchool/bigClassBig1'
import Class1 from './view/vSchool/class1'
import saturdayTrainingSpace from './view/vSchool/saturdayTraining'
import class2ForThreeYears from './view/vSchool/class2ForThree'
import class3ForThreeYears from './view/vSchool/class3ForThree'
import classNineOfNineYears from './view/vSchool/classNineOfNine'
import gradeThree from './view/vSchool/gradeThree'
import grade3Class2 from './view/vSchool/grade3Class2'
import hi from './view/vSchool/hi'
import baidumap from './components/pages/baidumap'
import notConnect from './components/pages/notConnect'
import childPost from './components/chungHua/childPost'
const routes = [
    
    {
        path: '/',
        component: schoolSpace,
        // name:dashboard
    },
    //user management
    {
        path: '/login',
        component: login,
        name:'login'
    },
    {
        path: '/adminuser/:name',
        component: adminuser,
        name:'adminuser'
    },
    {
        path: '/role/:name',
        component: role,
        name:'role'
    },
    {
        path: '/assignRole/:name',
        component: assignRole,
        name:'assignRole'
    },

    {
        path: '/tags/:name',
        component: tags,
        name:'tags'
    },

    {
        path: '/category/:name',
        component: category,
        name:'category'
    },
    {
        path: '/blog/:name',
        component: blog,
        name:'blog'
    },
    {
        path: '/School/:name',
        component: School,
        name:'School'
    },
    {
        path: '/Grade/:name',
        component: Grade,
        name:'Grade'
    },
    {
        path: '/Lesson/:name',
        component: Lesson,
        name:'Lesson'
    },

    //chungHua link
    {
        path:'/schoolSpace/:name',
        component:schoolSpace,
        name:'schoolSpace',
        props(route) {
            return {  myprop: route.query.myprop }
          }
    },
    {
        path:'/allTeachers/:name',
        component:allTeacher,
        name:'allTeacher'
    },
    {
        path:'/middle21/:name',
        component:middle21,
        name:'middle21'
    },
    {
        path:'/middle22/:name',
        component:middle22,
        name:'middle22'
    },
    {
        path:'/middle23/:name',
        component:middle23,
        name:'middle23'
    },
    {
        path:'/middle24/:name',
        component:middle24,
        name:'middle24'
    },
    {
        path:'/middle25/:name',
        component:middle25,
        name:'middle25'
    },
    {
        path:'/middle26/:name',
        component:middle26,
        name:'middle26'
    },
    {
        path:'/middle31/:name',
        component:middle31,
        name:'middle31'
    },
    {
        path:'/middle32/:name',
        component:middle32,
        name:'middle32'
    },
    {
        path:'/middle33/:name',
        component:middle33,
        name:'middle33'
    },
    {
        path:'/middle34/:name',
        component:middle34,
        name:'middle34'
    },
    {
        path:'/middle35/:name',
        component:middle35,
        name:'middle35'
    },
    {
        path:'/middle36/:name',
        component:middle36,
        name:'middle36'
    },

    {
        path:'/high21/:name',
        component:high21,
        name:'high21'
    },
    {
        path:'/high22/:name',
        component:high22,
        name:'high22'
    },
    {
        path:'/high23/:name',
        component:high23,
        name:'high23'
    },
    {
        path:'/high24/:name',
        component:high24,
        name:'high24'
    },
    {
        path:'/high25/:name',
        component:high25,
        name:'high25'
    },
    {
        path:'/high26/:name',
        component:high26,
        name:'high26'
    },
    {
        path:'/high31/:name',
        component:high31,
        name:'high31'
    },
    {
        path:'/high32/:name',
        component:high32,
        name:'high32'
    },
    {
        path:'/high33/:name',
        component:high33,
        name:'high33'
    },
    {
        path:'/high34/:name',
        component:high34,
        name:'high34'
    },
    {
        path:'/high35/:name',
        component:high35,
        name:'high35'
    },
    {
        path:'/high36/:name',
        component:high36,
        name:'high36'
    },
    {
        path:'/vSchoolSpace/:name',
        component:vSchoolSpace,
        name:'vSchoolSpace'
    },
    {
        path:'/vAllTeachers/:name',
        component:vAllTeachers,
        name:'vAllTeachers'
    },
    {
        path:'/safetyEducationCourse/:name',
        component:safetyEducationCourse,
        name:'safetyEducationCourse'
    },
    {
        path:'/eighthGradeMathematics/:name',
        component:eighthGradeMathematics,
        name:'eighthGradeMathematics'
    },
    {
        path:'/thirdGradeMathematics/:name',
        component:thirdGradeMathematics,
        name:'thirdGradeMathematics'
    },
    {
        path:'/geographyExerciseBank/:name',
        component:geographyExerciseBank,
        name:'geographyExerciseBank'
    },
    {
        path:'/Analects',
        component:Analects,
        name:'Analects'
    },
    {
        path:'/mathematics/:name',
        component:mathematics,
        name:'mathematics'
    },
    {
        path:'/mathematicalRational/:name',
        component:mathematicalRational,
        name:'mathematicalRational'
    },
    {
        path:'/fifthGradeMathematics/:name',
        component:fifthGradeMathematics,
        name:'fifthGradeMathematics'
    },
    {
        path:'/bigClassBigClass1/:name',
        component:bigClassBigClass1,
        name:'bigClassBigClass1'
    },
    {
        path:'/Class1/:name',
        component:Class1,
        name:'Class1'
    },
    {
        path:'/saturdayTrainingSpace/:name',
        component:saturdayTrainingSpace,
        name:'saturdayTrainingSpace'
    },
    {
        path:'/class2ForThreeYears/:name',
        component:class2ForThreeYears,
        name:'class2ForThreeYears'
    },
    {
        path:'/class3ForThreeYears/:name',
        component:class3ForThreeYears,
        name:'class3ForThreeYears'
    },
    {
        path:'/classNineOfNineYears/:name',
        component:classNineOfNineYears,
        name:'classNineOfNineYears'
    },
    {
        path:'/gradeThree/:name',
        component:gradeThree,
        name:'gradeThree'
    },
    {
        path:'/grade3Class2/:name',
        component:grade3Class2,
        name:'grade3Class2'
    },
    {
        path:'/hi/:name',
        component:hi,
        name:'hi'
    },
    {
        path:"/baidumap/:name",
        component:baidumap,
        name:"baidumap"
    },
]

export default new Router({
    mode: 'hash',
    routes
})