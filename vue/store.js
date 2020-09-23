import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        count: 0
    },
    mutations: {
        SET_COUNT(state, number) {
            state.count = number;
        }
    },
    actions: {
        setCount(context, number) {
            context.commit('SET_COUNT', number);
        }
    },
    getters: {
        getCount(state) {
            return state.count;
        }
    }
});