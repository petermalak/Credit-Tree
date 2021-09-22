<template>
  <div class="content">
    <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
        <div class="card" >
          <div class="card-header">
          </div>
          <div class="card-body" >
            <div class="card-body table-responsive p-0">
              <table class="table table-hover table-sm">
                <thead>
                  <tr>
                    <th width="10%">#</th>
                    <th width="20%">User Name</th>
                    <th width="20%">Email</th>
                    <th width="20%">Credit</th>
                    <th width="10%">Role</th>
                    <th width="10%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in allUsers" :key="user.id">
                    <td>{{ index+1 }}</td>
                    <td>
                      <div class="badge h5">{{ user.name }}</div>
                    </td>
                    <td>
                      <div class="badge h5">{{ user.email }}</div>
                    </td>
                    <td>
                      <div class="badge h5">{{ user.credit }}</div>
                    </td>
                    <td>
                      <div class="badge h5" v-if="user.is_admin">Admin</div>
                      <div class="badge h5" v-else>User</div>
                    </td>
                    <td>
                      <button @click="editModal(user)" class="btn btn-primary btn-sm">
                        <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                      </button>
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
      id="Modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newModalLabel">Name: {{name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
            <div class="modal-body">
              <label >Credit: {{credit}}</label>
              <input type="text" v-model="amount">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" @click ="addCredit()">Add</button>
              <button type="submit" class="btn btn-primary" @click ="removeCredit()">Remove</button>
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
    name: "AllUsers",
    data: function() {
      return {
          id:'',
          name: '',
          email:'',
          credit: '',
          amount:''
      };
    },
    methods:{
      getAllUsers(){
        this.$Progress.start();
        this.$store
            .dispatch("getAllUsers")
            .then( ()=>{

            })
            .catch(error => {
              this.$Progress.fail();
            });
      },
      editModal(item) {
        this.id = item.id;
        this.name = item.name;
        this.email = item.email;
        this.credit = item.credit;
        $("#Modal").modal("show");
      },
      addCredit(){
        let data = {
          amount : this.amount,
          id: this.id
        };

        this.$Progress.start();
        this.$store
            .dispatch("addCredit",data)
            .then(response => {
                Toast.fire({
                    icon: "success",
                    title: "Credit added successfully"
                });
              $("#Modal").modal("hide");
                this.amount = "";
            })
            .catch(error => {
                Toast.fire({
                    icon: "error",
                    title: error.response.data
                });
              this.$Progress.fail();
            });
      },
      removeCredit(){
        let data = {
          amount : this.amount,
          id: this.id
        };
        this.$Progress.start();
        this.$store
            .dispatch("removeCredit",data)
            .then( ()=>{
              $("#Modal").modal("hide");
                Toast.fire({
                    icon: "success",
                    title: "Credit removed successfully"
                });
                this.amount = "";
            })
            .catch(error => {
                Toast.fire({
                    icon: "error",
                    title: error.response.data
                });
              this.$Progress.fail();
            });
      },
    },
    mounted(){
      this.getAllUsers();
    },
    computed: {
        ...mapGetters({
            allUsers: "allUsers",
        })
    },

  }

</script>
