import DashboardLayout from '../layout/DashboardLayout.vue'
// GeneralViews
import NotFound from '../pages/NotFoundPage.vue'
// Admin pages
import Overview from '../pages/Overview.vue'
import UserProfile from '../pages/UserProfile.vue'
import Login from '../pages/Login.vue'
import Tree from '../pages/Tree.vue'
import AllUsers from '../pages/AllUsers.vue'
import Admin from '../pages/Admin.vue'
import App from '../App.vue'
import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name:'login',
    component: Login,
  },

  {
    name:'dashboard',
    path: '/admin',
    component: DashboardLayout,
    redirect: '/admin/overview',
    meta: {
      Authorization: true
    },
    children: [
      {
        path: 'overview',
        name: 'Overview',
        component: Overview
      },
      {
        path: 'user',
        name: 'User',
        component: UserProfile
      },
      {
        path: 'tree',
        name: 'Tree',
        component: Tree
      },
      {
        path: 'allUsers',
        name: 'AllUsers',
        component: AllUsers
      },
    ]
  },
  { path: '*', component: NotFound }
]

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes
// export default new VueRouter({
//     mode: "history",
//     routes,
// });
