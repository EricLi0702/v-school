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
        classView:false,
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
        getClassView(state){
            return state.classView
        },
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
        setClassTabs(state,data){
            state.classView = data
        },
    },

    actions:{
        changeCounterAction({commit},data){
            commit('changeCounter',data);
        }
    }
})