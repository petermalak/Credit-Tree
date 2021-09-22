<template>
  <div class="content">
    <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
        <div class="card" >
          <div class="card-header" style="overflow: hidden;">
            <h3 style="float: left;">Tree View</h3>
            <button type="button" name="button" @click="newModal" style="float:right; padding:5px; margin:5px;" class="btn btn-primary  btn-lg"> Add new User</button>
            <button v-if="!User.is_admin" type="button" name="button" @click="creditModal" style="float:right; padding:5px; margin:5px;" class="btn btn-primary  btn-lg"> Transfer Credit</button>
          </div>
          <div class="card-body">
              <div class="card">
                <div class="card-body">
                  <div class="row">
<!--                    <div class="col-md-3">-->
<!--                    </div>-->
                    <div class="col-md-12">
                      <div class="tree">
                          <ul>
                          <li>
                            <a  @click="getChildrensFirst(User.id)" class="btn btn-primary btn">{{User.name}}</a>
                            <ul v-if="childrens1.name !== User.name" id="firstGeneration">
                              <li v-for="child1 in childrens1" :key="child1.id">
                                <a  @click="getChildrensSecond(child1.id)" >
                                  {{child1.name}} <br>
                                  {{child1.credit}}
                                </a>
                                <ul id="SecondGeneration" >
                                  <li v-for="child2 in childrens2" :key="child2.id" v-if="showing2 === child1.id">
                                    <a @click="getChildrensThird(child2.id)" class="btn btn-primary btn">
                                      {{child2.name}} <br>
                                      {{child2.credit}}
                                    </a>
                                    <ul id="ThirdGeneration">
                                      <li v-for="child3 in childrens3" :key="child3.id" v-if="showing3 === child2.id">
                                        <a @click="getChildrensForth(child3.id)" class="btn btn-primary btn">
                                          {{child3.name}}<br>
                                          {{child3.credit}}
                                        </a>
                                        <ul id="ForthGeneration">
                                          <li v-for="child4 in childrens4" :key="child4.id" v-if="showing4 === child3.id">
                                            <a @click="getChildrensFifth(child4.id)" class="btn btn-primary btn">
                                              {{child4.name}} <br>
                                              {{child4.credit}}
                                            </a>
                                            <ul id="FifthGeneration">
                                              <li v-for="child5 in childrens5" :key="child5.id" v-if="showing5 === child4.id">
                                                <a class="btn btn-primary btn">
                                                  {{child5.name}} <br>
                                                  {{child5.credit}}
                                                 </a>
                                              </li>
                                            </ul>
                                          </li>
                                        </ul>
                                      </li>
                                    </ul>
                                  </li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
<!--                    <div class="col-md-3">-->
<!--                    </div>-->
                  </div>
                </div>
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
            <h5 class="modal-title" id="newModalLabel">Add new User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <label >Name</label><br>
              <input type="text" v-model="newUser.name"><br>
              <label >Email</label><br>
              <input type="text" v-model="newUser.email"><br>
              <label >Password</label><br>
              <input type="password" v-model="newUser.password">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" @click ="addToTree()">Add</button>
            </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="CreditModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newModalLabel">Transfer Credit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <label>Amount of credit you have : {{User.credit}}</label><br><br>
              <label>Enter The Amount Of Credit To Transfer</label><br>
              <input type="text" v-model="transferData.amount"><br>
              <label>The Reciver Email</label><br>
                <input type="text" v-model = "reciver"><br>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" @click ="transferCredit()">Add</button>
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
      reciver:'',
      showing2:0,
      showing3:0,
      showing4:0,
      showing5:0,
      generation:0,
      credit: 0,
      creditToTransfer:0,
      newUser:{
        name:'',
        email: '',
        password:''
      },
      transferData:{
        email:'',
        amount:''
      }
    };
  },
  methods:{
    newModal() {
      $("#Modal").modal("show");
    },
    creditModal(){
      $("#CreditModal").modal("show");
    },
    addToTree(){
      this.$Progress.start();
      $("#Modal").modal("hide");
      this.$store
          .dispatch("addToTree",this.newUser)
          .then( () =>{
              this.newUser.name ='';
              this.newUser.email ='';
              this.newUser.password ='';
              Toast.fire({
                  icon: "success",
                  title: "User added successfully"
              });
              this.getChildrensFirst(this.User.id);
          })
          .catch(error => {
              Toast.fire({
                  icon: "error",
                  title: error.response.data
              });
            this.$Progress.fail();
          });

    },
    transferCredit(){
      this.transferData.email = this.reciver;
      this.$Progress.start();
      $("#CreditModal").modal("hide");
      this.$store
          .dispatch("transferCredit",this.transferData)
          .then( ()=>{
              this.transferData.amount ='';
              this.transferData.email ='';
              Toast.fire({
                  icon: "success",
                  title: "Transfer completed successfully"
              });
          })
          .catch(error => {
              Toast.fire({
                  icon: "error",
                  title: error.response.data
              });
            this.$Progress.fail();
          });

    },
    getChildrensFirst(id){
      let data ={
        user_id: id,
        generation:this.generation
      }
      this.$Progress.start();
      this.$store
          .dispatch("getChildrensFirst",data)
          .then( ()=>{
          })
          .catch(error => {
            this.$Progress.fail();
          });
    },
    getChildrensSecond(id){
      this.showing2 = id;
      let data ={
        user_id: id,
        generation:this.generation
      }
      this.$Progress.start();
      this.$store
          .dispatch("getChildrensSecond",data)
          .then( ()=>{
          })
          .catch(error => {
            this.$Progress.fail();
          });
    },
    getChildrensThird(id){
      this.showing3 = id;
      let data ={
        user_id: id,
        generation:this.generation
      }
      this.$Progress.start();
      this.$store
          .dispatch("getChildrensThird",data)
          .then( ()=>{
          })
          .catch(error => {
            this.$Progress.fail();
          });
    },
    getChildrensForth(id){
      this.showing4 = id;
      let data ={
        user_id: id,
        generation:this.generation
      }
      this.$Progress.start();
      this.$store
          .dispatch("getChildrensForth",data)
          .then( ()=>{
          })
          .catch(error => {
            this.$Progress.fail();
          });
    },
    getChildrensFifth(id){
      this.showing5 = id;
      let data ={
        user_id: id,
        generation:this.generation
      }
      this.$Progress.start();
      this.$store
          .dispatch("getChildrensFifth",data)
          .then( ()=>{
          })
          .catch(error => {
            this.$Progress.fail();
          });
    },
  },
  computed: {
      ...mapGetters({
          User: "user",
          childrens1: "childrens1",
          childrens2: "childrens2",
          childrens3: "childrens3",
          childrens4: "childrens4",
          childrens5: "childrens5",
      })
  },
}

</script>
<style scoped>
  /*Now the CSS Created by R.S*/
  * {margin: 0; padding: 0;}

  .tree ul {
    padding-top: 20px;
    position: relative;
  	transition: all 0.5s;
  	-webkit-transition: all 0.5s;
  	-moz-transition: all 0.5s;
  }

  .tree li {
  	float: left;
    text-align: center;
  	list-style-type: none;
  	position: relative;
  	padding: 20px 5px 0 5px;
  	transition: all 0.5s;
  	-webkit-transition: all 0.5s;
  	-moz-transition: all 0.5s;
  }

  /*We will use ::before and ::after to draw the connectors*/

  .tree li::before, .tree li::after{
  	content: '';
  	position: absolute;
    top: 0;
    right: 50%;
  	border-top: 1px solid #ccc;
  	width: 50%;
    height: 20px;
  }
  .tree li::after{
  	right: auto;
    left: 50%;
  	border-left: 1px solid #ccc;
  }

  /*We need to remove left-right connectors from elements without
  any siblings*/
  .tree li:only-child::after, .tree li:only-child::before {
  	display: none;
  }

  /*Remove space from the top of single children*/
  .tree li:only-child{ padding-top: 0;}

  /*Remove left connector from first child and
  right connector from last child*/
  .tree li:first-child::before, .tree li:last-child::after{
  	border: 0 none;
  }
  /*Adding back the vertical connector to the last nodes*/
  .tree li:last-child::before{
  	border-right: 1px solid #ccc;
  	border-radius: 0 5px 0 0;
  	-webkit-border-radius: 0 5px 0 0;
  	-moz-border-radius: 0 5px 0 0;
  }
  .tree li:first-child::after{
  	border-radius: 5px 0 0 0;
  	-webkit-border-radius: 5px 0 0 0;
  	-moz-border-radius: 5px 0 0 0;
  }

  /*Time to add downward connectors from parents*/
  .tree ul ul::before{
  	content: '';
  	position: absolute; top: 0;
    left: 50%;
  	border-left: 1px solid #ccc;
  	width: 0;
    height: 20px;
  }

  .tree li a{
  	border: 1px solid #ccc;
  	padding: 5px 10px;
  	text-decoration: none;
  	color: #666;
  	font-family: arial, verdana, tahoma;
  	font-size: 30px;
  	display: inline-block;

  	border-radius: 5px;
  	-webkit-border-radius: 5px;
  	-moz-border-radius: 5px;

  	transition: all 0.5s;
  	-webkit-transition: all 0.5s;
  	-moz-transition: all 0.5s;
  }

  /*Time for some hover effects*/
  /*We will apply the hover effect the the lineage of the element also*/
  .tree li a:hover, .tree li a:hover+ul li a {
  	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
  }
  /*Connector styles on hover*/
  .tree li a:hover+ul li::after,
  .tree li a:hover+ul li::before,
  .tree li a:hover+ul::before,
  .tree li a:hover+ul ul::before{
  	border-color:  #94a0b4;
  }

  .modal {
    text-align: center;
  }

  @media screen and (min-width: 768px) {
    .modal:before {
      display: inline-block;
      vertical-align: middle;
      content: " ";
    }
  }

  .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
  }
  /*Thats all. I hope you enjoyed it.
  Thanks :)*/
</style>
