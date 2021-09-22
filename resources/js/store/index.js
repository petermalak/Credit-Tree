import Vue from "vue";
import Vuex from "vuex";
import methods from "./module/data/methods";
import VuexPersist from "vuex-persist";
import axios from "axios";

Vue.use(Vuex);

const vuexLocalStorage = new VuexPersist({
    key: "Star5",
    storage: window.localStorage,
});
const debug = process.env.NODE_ENV !== "production";


export default new Vuex.Store({
    modules: {
      methods,
    },
    plugins: [  vuexLocalStorage.plugin],
    strict: debug

});
