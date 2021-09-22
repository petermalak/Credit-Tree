<template>
  <div class="wrapper">
    <side-bar>
       <mobile-menu slot="content"></mobile-menu>
      <sidebar-link to="/admin/user">
        <i class="nc-icon nc-circle-09"></i>
        <p>User Profile</p>
      </sidebar-link>
      <sidebar-link to="/admin/tree">
        <i class="nc-icon nc-circle-09"></i>
        <p>Tree</p>
      </sidebar-link>
      <sidebar-link v-if="User.is_admin" to="/admin/allUsers">
        <i class="nc-icon nc-circle-09"></i>
        <p>All Users</p>
      </sidebar-link>
    </side-bar>
    <div class="main-panel">
      <top-navbar></top-navbar>

      <dashboard-content @click="toggleSidebar">

      </dashboard-content>
    </div>
  </div>
</template>
<style lang="scss">

</style>
<script>
  import TopNavbar from './TopNavbar.vue'
  import ContentFooter from './ContentFooter.vue'
  import DashboardContent from './Content.vue'
  import MobileMenu from './MobileMenu.vue'
  import {mapGetters} from "vuex"
  export default {
    components: {
      TopNavbar,
      ContentFooter,
      DashboardContent,
      MobileMenu
    },
    methods: {
      toggleSidebar () {
        if (this.$sidebar.showSidebar) {
          this.$sidebar.displaySidebar(false)
        }
      }
    },
    computed: {
        ...mapGetters({
            User: "user",
        })
    },
  }

</script>
