import { findLastKey } from 'lodash';
import Vue from 'vue'
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
        }
    },

    actions:{
        changeCounterAction({commit},data){
            commit('changeCounter',data);
        }
    }
})