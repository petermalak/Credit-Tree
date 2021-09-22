<template>
  <div class="content">
    <div class="container-fluid">
       <div class="row">
        <div class="col-md-8">
        <div class="card" >
          <div class="card-header">
            <h3>Admin Credit</h3>
          </div>
          <div class="card-body" >
            <span class="h5" >Credit</span>
            <span>{{User.credit}}</span>
            <br>
            <input type="text" name="" v-model="credit">
            <a href="#" @click="addCredit" class="btn btn-primary btn">Test</a>
            <button type="button" name="remove" @click="addToTree">Add To Tree</button>
              <button type="button" name="remove" @click="getCreditLogs">Credit logs</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>
<script>
  // import EditProfileForm from './UserProfile/EditProfileForm.vue'
  // import UserCard from './UserProfile/UserCard.vue'
  import {mapGetters} from "vuex"
  export default {
    name: "Login",
    data: function() {
      return {
        credit: 0,
        name:'Test7',
        email: 'test7@test7.com',
        password:'password'
      };
    },
    methods:{
      addCredit(){
        console.log("Done");
      },
      addToTree(){
        let data = {
          name:this.name,
          email: this.email,
          password:this.password
        };
        this.$Progress.start();
        this.$store
            .dispatch("addToTree",data)
            .then( ()=>{
            })
            .catch(error => {
              this.$Progress.fail();
            });
      },
        getCreditLogs(){
            this.$Progress.start();
            this.$store
                .dispatch("getCreditLogs")
                .then( ()=>{
                })
                .catch(error => {
                    this.$Progress.fail();
                });
        }
    },
    computed: {
        ...mapGetters({
            User: "user",
        })
    },

  }

</script>
