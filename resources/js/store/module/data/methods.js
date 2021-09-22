import axios from 'axios';

export default {
   state : {
    user: {},
    childrens1:{},
    childrens2:{},
    childrens3:{},
    childrens4:{},
    childrens5:{},
    allUsers:[],
    creditLogs:{},
    userCredit:{},
  },

   getters : {
    user: state => state.user,
    allUsers: state => state.allUsers,
    childrens1: state => state.childrens1,
    childrens2: state => state.childrens2,
    childrens3: state => state.childrens3,
    childrens4: state => state.childrens4,
    childrens5: state => state.childrens5,
    creditLogs: state => state.creditLogs,
    userCredit: state => state.userCredit,
   },

   actions : {
    async login({ commit }, data) {
      const response = await axios.post(
        'api/login',data
      );
      const token = response.data.data.api_token;
       commit('setUser', response.data.data);
    },
    async logout({ commit }) {
        axios.post('api/logout');
        const user = {};
       commit('setUser', user);
    },
    async addCredit({ commit }, data) {
      const response = await axios.put(
        `api/admin/credit/${data.id}/add`,{amount: data.amount, user_id: data.id}
      );
       commit('setCreditAdd', data);
    },
    async removeCredit({ commit }, data) {
      const response = await axios.put(
        `api/admin/credit/${data.id}/remove`,{amount: data.amount, user_id: data.id}
      );
       commit('setCreditRemove', data);
    },
    async addToTree({ commit }, data) {
      const response = await axios.post(
        'api/tree/add',data
      );
       commit('addToTree', data);
    },
    async getAllUsers({ commit }) {
      const response = await axios.get(
        'api/admin/getUsers'
      );
       commit('getAllUsers', response.data);
    },
    async getChildrensFirst({ commit }, data) {
      const response = await axios.get(
        `api/tree/${data.user_id}/childes`
      );
      let setData = {
        childrens:response.data ,
        generation: data.generation
      }
       commit('getChildrensFirst', response.data);
    },
    async getChildrensSecond({ commit }, data) {
      const response = await axios.get(
        `api/tree/${data.user_id}/childes`
      );
      let setData = {
        childrens:response.data ,
        generation: data.generation
      }
       commit('getChildrensSecond', response.data);
    },
    async getChildrensThird({ commit }, data) {
      const response = await axios.get(
        `api/tree/${data.user_id}/childes`
      );
      let setData = {
        childrens:response.data ,
        generation: data.generation
      }
       commit('getChildrensThird', response.data);
    },
    async getChildrensForth({ commit }, data) {
      const response = await axios.get(
        `api/tree/${data.user_id}/childes`
      );
      let setData = {
        childrens:response.data ,
        generation: data.generation
      }
       commit('getChildrensForth', response.data);
    },
    async getChildrensFifth({ commit }, data) {
      const response = await axios.get(
        `api/tree/${data.user_id}/childes`
      );
      let setData = {
        childrens:response.data ,
        generation: data.generation
      }
       commit('getChildrensFifth', response.data);
    },
    async transferCredit({ commit }, data) {
      const response = await axios.put(
        'api/users/credit/transfer',data
      );
      commit('transferCredit', data);
    },
    async getCreditLogs({ commit }){
        const response = await axios.get(
            'api/users/credit/logs'
        );
        commit('getCreditLogs', response.data.data);
    },
       async getUserCredit({ commit }){
           const response = await axios.get(
               'api/users/credit'
           );
           commit('getUserCredit', response.data);
       },
       async updateProfile({ commit } , data){
           const response = await axios.put(
               `api/users/update/${data.field}`, data
           );
           if(data.field == 'name'){
               commit('updateProfile', data.name);
           }
       }
  },

   mutations : {
       setUser(state, userData) {
           state.user = Object.assign({}, userData);
           state.childrens1 = Object.assign({}, userData);
           state.childrens2 = Object.assign({}, userData);
           state.childrens3 = Object.assign({}, userData);
           state.childrens4 = Object.assign({}, userData);
           state.childrens5 = Object.assign({}, userData);
           state.creditLogs = Object.assign({}, userData);
           state.userCredit = Object.assign({}, userData);
           state.allUsers = [];
       },
     setCreditAdd(state, data) {
       const userObj = state.allUsers.find(user => user.id == data.id);
       userObj.credit = Number(userObj.credit) + Number(data.amount) ;
     },
     setCreditRemove(state, data) {
       const userObj = state.allUsers.find(user => user.id == data.id);
       userObj.credit = Number(userObj.credit) - Number(data.amount) ;
     },
     addToTree(state, data) {
         const childObj = {
           name : data.name,
           email: data.email,
           password :data.password
         };
         state.childrens.data.unshift(childObj);
     },
     getAllUsers(state, users) {
         state.allUsers = users;
     },
     getChildrensFirst(state, data) {
       state.childrens1 = data;
     },
     getChildrensSecond(state, data) {
       state.childrens2 = data;
     },
     getChildrensThird(state, data) {
       state.childrens3 = data;
     },
     getChildrensForth(state, data) {
       state.childrens4 = data;
     },
     getChildrensFifth(state, data) {
       state.childrens5 = data;
     },
     transferCredit(state, data){
       state.user.credit = state.user.credit - data.amount;
       const childObj = state.childrens1.find(child => child.email == data.email);
       childObj.credit = Number(childObj.credit) + Number(data.amount);
     },
       getCreditLogs(state, data){
           state.creditLogs = data;
       },
       getUserCredit(state, data){
           state.userCredit = data;
       },
       updateProfile(state, data){
           state.user.name = data;
       },
  }
};
