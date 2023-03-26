import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        user: null,
        order: null,
    },

    getters: {
        GET_USER: state => {
            return state.user;
        },
        GET_ORDER: state => {
            return state.order;
        },
    },
    mutations: {
        SET_USER: (state, payload) => {
            state.user = payload;
        },
        SET_ORDER: (state, payload) => {
            state.order = payload;
        },
    },
    actions: {
        SET_USER: (state, payload) => {
            state.user = payload;
        },
        SET_ORDER: (state, payload) => {
            state.order = payload;
        },
    },
});
