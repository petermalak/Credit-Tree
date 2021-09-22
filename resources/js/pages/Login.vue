<template>
<div>
  <div class="sidenav">
        </div>
        <div class="main">
           <div class="container">
             <div id="login" class="">
               <div class="col-md-6 col-sm-12">
                  <div class="login-form">
                    <h2>Login</h2><br />
                    <form @submit.prevent="sign_in" method="POST">
                        <div class="form-group">
                           <label>Email</label>
                           <input
                             type="email"
                             class="form-control"
                             id="email"
                             v-model="details.email"
                             placeholder="Enter email"
                           />
                        </div>
                        <div class="form-group">
                           <label>Password</label>
                           <input
                             type="password"
                             class="form-control"
                             id="password"
                             v-model="details.password"
                             placeholder="Password"
                           />
                        </div>
                        <button type="submit" class="btn btn-black">Login</button>
                     </form>
                  </div>
               </div>
             </div>
           </div>
        </div>
    <div
        class="modal fade"
        id="Modal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="newModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newModalLabel">Add 50 Customer Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="sign_in" method="POST">
                        <div class="form-group">
                            <label>Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                v-model="details.email"
                                placeholder="Enter email"
                            />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                v-model="details.password"
                                placeholder="Password"
                            />
                        </div>
                        <div class="form-group">
                            <label>Enter 50 Name for your Customer List</label> <br>
                            <span style="color:#D3D3D3;">PRESS ENTER AT THE END OF EACH NAME</span>
                            <textarea name="name" rows="8" cols="40" v-model="customers"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" @click ="sign_in">Login</button>
                </div>
            </div>
        </div>
    </div>
      </div>
</template>

<script>
import {mapGetters} from "vuex"
import axios from 'axios'

export default {
  name: "Login",
  data: function() {
    return {
        addingCustomers: false,
      customers:'',
      details: {
        email: '',
        password: '',
        customers:[],
      }
    };
  },
  methods: {
      sign_in(){
        this.details.customers = this.customers.split("\n");
          let user ={};
          if(this.details.customers[0] === null || this.details.customers[0] === "")
          {
              user = {
                  email: this.details.email,
                  password: this.details.password,
                  customers: []
              };
          }
          else
          {
              user = {
                  email: this.details.email,
                  password: this.details.password,
                  customers: this.details.customers
              };
          }
          if(this.addingCustomers){
            if(this.details.customers.length >= 50){
                this.$Progress.start();
                this.$store
                    .dispatch("login",user)
                    .then( () =>{
                        this.$Progress.finish();
                        $("#Modal").modal("hide");
                        if(this.User.api_token){
                            this.$router.push({ name: 'User', query: { redirect: '/admin/user' } });
                            axios.defaults.headers.common['Authorization'] = `Bearer ${this.User.api_token}`;
                            Toast.fire({
                                icon: "success",
                                title: "Login Successfully"
                            });
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        if(error.response.status === 401){
                            Toast.fire({
                                icon: "error",
                                title: error.response.data
                            });
                        }
                        else{
                            $("#Modal").modal("show");
                            this.addingCustomers = true;
                            Toast.fire({
                                icon: "error",
                                title: error.response.data
                            });
                        }
                    });
            }
            else{
                Toast.fire({
                    icon: "error",
                    title: "Enter 50 Name of Customers or More"
                });
            }
          }
          else{
              this.$Progress.start();
              this.$store
                  .dispatch("login",user)
                  .then( () =>{
                      this.$Progress.finish();
                      $("#Modal").modal("hide");
                      if(this.User.api_token){
                          this.$router.push({ name: 'User', query: { redirect: '/admin/user' } });
                          axios.defaults.headers.common['Authorization'] = `Bearer ${this.User.api_token}`;
                          Toast.fire({
                              icon: "success",
                              title: "Login Successfully"
                          });
                      }
                  })
                  .catch(error => {
                      this.$Progress.fail();
                      if(error.response.status === 401){
                          Toast.fire({
                              icon: "error",
                              title: error.response.data
                          });
                      }
                      else{
                          $("#Modal").modal("show");
                          this.addingCustomers = true;
                          Toast.fire({
                              icon: "error",
                              title: error.response.data
                          });
                      }
                  });
          }
      },
  },
  computed: {
      ...mapGetters({
          User: "user",
      })
  },
};
</script>
<style scoped>

body {
    font-family: "Lato", sans-serif;
}



.main-head{
    height: 150px;
    background: #FFF;

}

.sidenav {
    height: 100%;
    background-color: #000;
    background-image:url('../../../public/star5_1.jpeg');
    overflow-x: hidden;
    padding-top: 20px;
    background-repeat: no-repeat;
    background-size: cover;
}


.main {
    padding: 0px 10px;
}

@media screen and (max-height: 45px) {
    .sidenav {padding-top: 15px;}
}

@media screen and (max-width: 450px) {
    .login-form{
        margin-top: 10%;
    }

}

@media screen and (min-width: 70px){
    .main{
        margin-left: 40%;
    }

    .sidenav{
        width: 40%;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
    }

    .login-form{
        margin-top: 50%;
    }

}


.login-main-text{
    margin-top: 20%;
    padding: 60px;
    color: #fff;
}

.login-main-text h2{
    font-weight: 300;
}

.btn-black{
    background-color: #000 !important;
    color: #fff;
}
</style>
