import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import VueProgressBar from "vue-progressbar";
import store from "./store/index";
import Multiselect from "vue-multiselect";
import LightBootstrap from './light-bootstrap-main'
import routes from './routes/routes'
import './registerServiceWorker'
import axios from "axios";
import Swal from "sweetalert2";

axios.defaults.headers.common['Authorization'] = `Bearer ${store.getters.user.api_token}`;

require("./bootstrap");
// plugin setup
Vue.use(VueRouter)
Vue.use(LightBootstrap)
Vue.component("multiselect", Multiselect);

Vue.use(VueProgressBar, {
    color: "#3b8070",
    failedColor: "red",
    height: "2px"
});
// sweetalert
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});
window.Swal = Swal;
// global toaster alert
window.Toast = Toast;

// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  linkActiveClass: 'nav-item active',
  scrollBehavior: (to) => {
    if (to.hash) {
      return {selector: to.hash}
    } else {
      return { x: 0, y: 0 }
    }
  }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store,
})
