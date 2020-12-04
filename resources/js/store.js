import { findLastKey, sample } from 'lodash';
import Vue from 'vue'
import Cookies from 'js-cookie'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state:{
        counter:1000,
        deleteModalObj : {
            showDeleteModal:false,
            deleteUrl:'',
            data:null,
            deletingIndex:-1,
            isDeleted:false,
        },
        user:false,
        userPermission:null,
        gradeModal:false,
        memberView:false,
        classView:false,
        actionView:false,
        modalView:false,
        showQuestionModal:false,
        showAnswerDetail:false,
        questionItemCnt:0,
        chartView:false,
        postDetailsView:false,
        totalNewMsgCnt:0,
        totalNewVideoCnt:0,
        aboutDetailsView:false,
        inputModalView:false,
        gradeInfo:null,
        accessToken:Cookies.get('accessToken'),
        refreshToken:Cookies.get('refreshToken')
    },

    getters:{
        getCounter(state){
            return state.counter;
        },
        getDeleteModalObj(state){
            return state.deleteModalObj;
        },
        getUserPermission(state){
            return state.userPermission;
        },
        getGradeModal(state){
            return state.gradeModal
        },
        getMemberView(state){
            return state.memberView
        },
        getClassView(state){
            return state.classView
        },
        getActionView(state){
            return state.actionView
        },
        getModalView(state){
            return state.modalView
        },
        getShowQuestionModal(state){
            return state.showQuestionModal
        },
        getShowAnswerDetail(state){
            return state.showAnswerDetail
        },
        getQuestionItemCnt(state){
            return state.questionItemCnt
        },
        getChartView(state){
            return state.chartView
        },
        getPostDetailsView(state){
            return state.postDetailsView
        },
        getTotalNewMsgCnt(state){
            return state.totalNewMsgCnt
        },
        getTotalNewMsgCnt(state){
            return state.totalNewVideoCnt
        },
        getUserInfo(state){
            return state.user
        },
        getAboutDetailsView(state){
            return state.aboutDetailsView
        },
        getInputModalView(state){
            return state.inputModalView
        },
        getGradeInfo(state){
            return state.gradeInfo
        },
        getAccessToken(state){
            return state.accessToken
        },
        getRefreshToken(state){
            return state.refreshToken
        }
    },

    mutations:{
        changeCounter(state,data){
            state.counter += data;
        },
        setDeleteModal(state,data){
            const deleteModalObj = {
                showDeleteModal:false,
                deleteUrl:'',
                data:null,
                deletingIndex:-1,
                isDeleted:false,
            };
            state.deleteModalObj = deleteModalObj;
            state.deleteModalObj.isDeleted = data;

        },
        setDeleteModalObj(state,data){
            state.deleteModalObj = data;
        },
        setUpdateUser(state,data){
            state.user = data;
        },

        setUserPermission(state,data){
            state.userPermission = data;
        },
        setGradeModal(state,data){
            state.gradeModal = data;
        },
        setMemberView(state,data){
            state.memberView = data
        },
        setClassView(state,data){
            state.classView = data
        },
        setActionView(state,data){
            state.actionView = data
        },
        setModalView(state,data){
            state.modalView = data;
        },
        setShowQuestionModal(state,data){
            state.showQuestionModal = data;
        },setShowAnswerDetail(state,data){
            state.showAnswerDetail = data
        },
        setQuestionItemCnt(state,data){
            state.questionItemCnt = state.questionItemCnt + data
        },
        setChartView(state,data){
            state.chartView = data
        },
        setPostDetailsView(state,data){
            state.postDetailsView = data
        },
        setTotalNewMsgCnt(state,data){
            state.totalNewMsgCnt = data
        },
        setTotalNewVideoCnt(state,data){
            state.totalNewVideoCnt = data
        },
        setAboutDetailsView(state,data){
            state.aboutDetailsView = data
        },
        setInputModalView(state,data){
            state.inputModalView = data
        },
        setGradeInfo(state,data){
            state.gradeInfo = data
        },
        setAccessToken(state,data){
            Cookies.set('accessToken',data,{expires:1/100})
        },
        setRefreshToken(state,data){
            Cookies.set('refreshToken',data)
        }
    },

    actions:{
        changeCounterAction({commit},data){
            commit('changeCounter',data);
        }
    }
})