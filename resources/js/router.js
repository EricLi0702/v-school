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
import mobilePost from './mobile/post.vue'
import mobilePostView from './components/chungHua/postDetailView'
import notConnect from './components/pages/notConnect'
import childPost from './components/chungHua/childPost'
import profile from './components/profile/profile'
import chatComponent from './components/pages/chat/mobile/chatAddress'
import attendanceOverAll from './components/attendance/statistics'
import attendanceDetail from './components/attendance/detail'
const routes = [
    
    {
        path: '/',
        component: schoolSpace,
        // name:dashboard
    },
    {
        path:'/mobile',component:schoolSpace,
            
    },
    {
        path:'/profile',component:profile
    },
    {
        path:'/chat', component:chatComponent
    },
    // {path:'/mobile/post',component:mobilePost},
    {path:'/mobile/postView',component:mobilePostView},
    
    //user management
    {
        path: '/login',
        component: login,
        name:'login'
    },
    {
        path: '/adminuser',
        component: adminuser,
        name:'adminuser'
    },
    {
        path: '/role',
        component: role,
        name:'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name:'assignRole'
    },

    {
        path: '/tags',
        component: tags,
        name:'tags'
    },

    {
        path: '/category',
        component: category,
        name:'category'
    },
    {
        path: '/blog',
        component: blog,
        name:'blog'
    },
    {
        path: '/School',
        component: School,
        name:'School'
    },
    {
        path: '/Grade',
        component: Grade,
        name:'Grade'
    },
    {
        path: '/Lesson',
        component: Lesson,
        name:'Lesson'
    },

    //chungHua link
    {
        path:'/schoolSpace',
        component:schoolSpace,
        name:'schoolSpace',
        props(route) {
            return {  myprop: route.query.myprop }
          }
    },
    {
        path:'/allTeachers',
        component:allTeacher,
        name:'allTeacher'
    },
    {
        path:'/middle21',
        component:middle21,
        name:'middle21'
    },
    {
        path:'/middle22',
        component:middle22,
        name:'middle22'
    },
    {
        path:'/middle23',
        component:middle23,
        name:'middle23'
    },
    {
        path:'/middle24',
        component:middle24,
        name:'middle24'
    },
    {
        path:'/middle25',
        component:middle25,
        name:'middle25'
    },
    {
        path:'/middle26',
        component:middle26,
        name:'middle26'
    },
    {
        path:'/middle31',
        component:middle31,
        name:'middle31'
    },
    {
        path:'/middle32',
        component:middle32,
        name:'middle32'
    },
    {
        path:'/middle33',
        component:middle33,
        name:'middle33'
    },
    {
        path:'/middle34',
        component:middle34,
        name:'middle34'
    },
    {
        path:'/middle35',
        component:middle35,
        name:'middle35'
    },
    {
        path:'/middle36',
        component:middle36,
        name:'middle36'
    },

    {
        path:'/high21',
        component:high21,
        name:'high21'
    },
    {
        path:'/high22',
        component:high22,
        name:'high22'
    },
    {
        path:'/high23',
        component:high23,
        name:'high23'
    },
    {
        path:'/high24',
        component:high24,
        name:'high24'
    },
    {
        path:'/high25',
        component:high25,
        name:'high25'
    },
    {
        path:'/high26',
        component:high26,
        name:'high26'
    },
    {
        path:'/high31',
        component:high31,
        name:'high31'
    },
    {
        path:'/high32',
        component:high32,
        name:'high32'
    },
    {
        path:'/high33',
        component:high33,
        name:'high33'
    },
    {
        path:'/high34',
        component:high34,
        name:'high34'
    },
    {
        path:'/high35',
        component:high35,
        name:'high35'
    },
    {
        path:'/high36',
        component:high36,
        name:'high36'
    },
    {
        path:'/vSchoolSpace',
        component:vSchoolSpace,
        name:'vSchoolSpace'
    },
    {
        path:'/vAllTeachers',
        component:vAllTeachers,
        name:'vAllTeachers'
    },
    {
        path:'/safetyEducationCourse',
        component:safetyEducationCourse,
        name:'safetyEducationCourse'
    },
    {
        path:'/eighthGradeMathematics',
        component:eighthGradeMathematics,
        name:'eighthGradeMathematics'
    },
    {
        path:'/thirdGradeMathematics',
        component:thirdGradeMathematics,
        name:'thirdGradeMathematics'
    },
    {
        path:'/geographyExerciseBank',
        component:geographyExerciseBank,
        name:'geographyExerciseBank'
    },
    {
        path:'/Analects',
        component:Analects,
        name:'Analects'
    },
    {
        path:'/mathematics',
        component:mathematics,
        name:'mathematics'
    },
    {
        path:'/mathematicalRational',
        component:mathematicalRational,
        name:'mathematicalRational'
    },
    {
        path:'/fifthGradeMathematics',
        component:fifthGradeMathematics,
        name:'fifthGradeMathematics'
    },
    {
        path:'/bigClassBigClass1',
        component:bigClassBigClass1,
        name:'bigClassBigClass1'
    },
    {
        path:'/Class1',
        component:Class1,
        name:'Class1'
    },
    {
        path:'/saturdayTrainingSpace',
        component:saturdayTrainingSpace,
        name:'saturdayTrainingSpace'
    },
    {
        path:'/class2ForThreeYears',
        component:class2ForThreeYears,
        name:'class2ForThreeYears'
    },
    {
        path:'/class3ForThreeYears',
        component:class3ForThreeYears,
        name:'class3ForThreeYears'
    },
    {
        path:'/classNineOfNineYears',
        component:classNineOfNineYears,
        name:'classNineOfNineYears'
    },
    {
        path:'/gradeThree',
        component:gradeThree,
        name:'gradeThree'
    },
    {
        path:'/grade3Class2',
        component:grade3Class2,
        name:'grade3Class2'
    },
    {
        path:'/hi',
        component:hi,
        name:'hi'
    },
    {
        path:"/baidumap",
        component:baidumap,
        name:"baidumap"
    },
]

export default new Router({
    mode: 'history',
    routes
})