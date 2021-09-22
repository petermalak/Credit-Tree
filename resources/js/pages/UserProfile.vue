<template>
  <div class="content">
    <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
        <div class="card" >
          <div class="card-header">
              <div class="row">
                  <div class="col">
                    <h2 class="card-title">{{User.name}}'s Profile Data</h2>
                  </div>
                  <div class="col">
                      <button @click="editName" class="btn btn-primary float-right" >
                          Change Name
                      </button>
                      <button @click="editPass" class="btn btn-primary float-right">
                          Change Password
                      </button>
                  </div>
              </div>
          </div>
          <div class="card-body" style="margin: 1rem;">
                  <div class="row">
                      <div class="col-md-6">
                          <h4>Name: {{User.name}}</h4>
                          <h4>Email: {{User.email}}</h4>
                          <h4>Credit: {{UserCredit}}</h4>
                          <h4>Code: {{User.code}}</h4>
                          <div v-if="User.is_admin">
                            <h4>You are Admin</h4>
                          </div>
                      </div>
                      <div class="col-md-6">
                        <h5>List of Customers:</h5>
                        <div v-if="User.customers" style="overflow-y: scroll; height:15rem;">
                            <div v-for="customer in User.customers" :key="customer">
                              <span>{{customer}}</span>
                            </div>
                        </div>
                      </div>
                  </div>
              <div class="card-body table-responsive p-0 mt-5">
                  <table class="table table-hover table-sm">
                      <thead>
                      <tr>
                          <th width="5%">#</th>
                          <th width="20%">Type</th>
                          <th width="25%">Amount</th>
                          <th width="25%">Sender</th>
                          <th width="25%">Reciver</th>
                      </tr>
                      </thead>
                      <tbody v-if="CreditLogs">
                      <tr v-for="(creditLog, index) in CreditLogs" :key="creditLog.id" >
                          <td>{{ index+1 }}</td>
                          <td>
                              <div class="badge h5" v-if="creditLog.type === 'UserProfit'" style="background-color: #6cb2eb">{{ creditLog.type }}</div>
                              <div class="badge h5" v-else>{{ creditLog.type }}</div>
                          </td>
                          <td>
                              <div class="badge h5">
                                  {{ creditLog.amount }}
                              </div>
                          </td>
                          <td v-if="creditLog.sender">
                              <div class="badge h5">
                                {{ creditLog.sender.name }}
                              </div>
                          </td>
                          <td v-if="creditLog.receiver">
                            <div class="badge h5">
                                {{ creditLog.receiver.name }}
                            </div>
                          </td>
                      </tr>
                      </tbody>
                  </table>
              </div>
          </div>
        </div>
      </div>
    </div>
    </div>

      <div
          class="modal fade"
          id="modalName"
          tabindex="-1"
          role="dialog"
          aria-labelledby="newModalLabel"
          aria-hidden="true"
      >
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="newModalLabel">Edit Name</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      </button>
                  </div>
                  <div class="modal-body">
                      <label>Enter new Name :</label>
                      <input type="text" v-model="name">
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" @click ="updateProfile('name')">Save</button>
                  </div>
              </div>
          </div>
      </div>

      <div
          class="modal fade"
          id="modalPass"
          tabindex="-1"
          role="dialog"
          aria-labelledby="newModalLabel"
          aria-hidden="true"
      >
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="newModalLabel">Edit Password</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      </button>
                  </div>
                  <div class="modal-body">
                      <label>Enter New Password :</label>
                      <input type="password" v-model="password">
                      <label>Enter Confirm Password :</label>
                      <input type="password" v-model="confirmPassword">

                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" @click ="updateProfile('password')">Save</button>
                  </div>
              </div>
          </div>
      </div>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </div>
</template>

<script>

  import {mapGetters} from "vuex"
  export default {
    name: "Login",
    data: function() {
      return {
          name:'',
          password:'' ,
          confirmPassword:'',
        userData: {
          name: '',
          email: '',
          is_admin: '',

        }
      };
    },
    methods:{
        editName() {
            $("#modalName").modal("show");
        },
        editPass() {
            $("#modalPass").modal("show");
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
      },
        getUserCredit(){
            this.$Progress.start();
            this.$store
                .dispatch("getUserCredit")
                .then( ()=>{
                })
                .catch(error => {
                    this.$Progress.fail();
                });
        },
        updateProfile(field){
            let data ={}
            if(field == 'name'){
                data ={
                    new_password:'',
                    name: this.name,
                    field: field
                }
                $("#modalName").modal("hide");
            }
            else if (field == 'password'){
                if (this.password === this.confirmPassword){
                    data = {
                        name: '',
                        new_password: this.password,
                        field: field
                    }
                }
                else {
                    Toast.fire({
                        icon: "error",
                        title: "Enter the password and Confirm Password Equal"
                    });
                    return ;
                }
                $("#modalPass").modal("hide");
            }
            this.$Progress.start();
            this.$store
                .dispatch("updateProfile", data)
                .then( () =>{
                    this.name ='';
                    this.password ='';
                    this.confirmPassword ='';
                    Toast.fire({
                        icon: "success",
                        title: "Profile Updated Successfully"
                    });
                })
                .catch(error => {
                    this.$Progress.fail();
                    Toast.fire({
                        icon: "error",
                        title: error.response.data
                    });
                });
        }

    },
    computed: {
        ...mapGetters({
            User: "user",
            CreditLogs: "creditLogs",
            UserCredit: "userCredit"
        })
    },
    mounted() {
      this.getCreditLogs();
      this.getUserCredit();
    }

  }

</script>
<style>

</style>
