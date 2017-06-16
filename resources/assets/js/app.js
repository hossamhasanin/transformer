import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

// importing custom components
import add_relation from './components/add_relation';

import add_field from './components/add_field';

import edit_relation from './components/edit_relation';

require("./bootstrap-toggle.min.js");

// Adding the X-CSRF-Token to all axios request
axios.interceptors.request.use(function(config){
  config.headers['X-CSRF-TOKEN'] = window.Laravel.csrfToken
  return config
})

Vue.filter("slugify" , function (title) {
  var slug = title.replace(/ /g , "-");
  if (slug.slice(-1) == "-"){
    slug = slug.slice(0 ,-1)
  }
  return slug;
})

// Making axios available as $http 
// so that the ajax calls are not axios dependent
Vue.prototype.$http = axios

Vue.use(VueAxios, axios)
Vue.use(VueRouter)

Vue.component('add_relation', add_relation);

Vue.component('add_field', add_field);

Vue.component('edit_relation', edit_relation);

var back_again = [];

Vue.component("old_field" , {
    template: `<tr v-bind:class="'old_field-' + old_field.id">
                    <td><input type="text" class="form-control" v-bind:name="'field_name['+ old_field.id +']'" v-bind:value="old_field.name"></td>
                    <td>
                        <select v-bind:name="'field_type['+ old_field.id +']'" class="form-control">
                            <option v-for="field_type in field_types" :value="field_type" :selected="field_type == old_field.type ? true : false">{{ field_type == "varchar(255)" ? "varchar" : field_type == "int(11)" ? "int" : field_type }}</option>
                        </select>
                    </td>
                    <td>
                    <div class="checkbox checkbox-slider--b checkbox-slider-md">
                       <label>
                        <input type="checkbox" v-bind:name="'nullable['+ old_field.id +']'" :checked="old_field.nullable == 1 ? true : false" value="1"><span></span>
                       </label>
                    </div>
                    </td>
                    <td>
                        <input type="hidden" v-bind:name="'visibility['+ old_field.id +']'" v-bind:id="'send_c_value-'+old_field.id" v-bind:value="old_field.visibility">
                              <div>
                                 <label>
                                   <input type="checkbox"  @change="check_all(old_field.id)" v-bind:class="'check_all-'+old_field.id" ><span> All</span>
                                 </label>
                              </div>
                                 <div>
                                   <label>
                                       <input type="checkbox" @change="send_v_checks(old_field.id , 'show')" v-bind:class="'check_this-'+old_field.id + ' check_show-'+old_field.id" value="show"><span> show page</span>
                                   </label>
                                 </div>
                                 <div>
                                   <label>
                                       <input type="checkbox" @change="send_v_checks(old_field.id , 'add')" v-bind:class="'check_this-'+old_field.id + ' check_add-'+old_field.id" value="add"><span> add page</span>
                                   </label>
                                 </div>
                                 <div>
                                    <label>
                                        <input type="checkbox" @change="send_v_checks(old_field.id , 'edit')" v-bind:class="'check_this-'+ old_field.id + ' check_edit-'+old_field.id" value="edit"><span> edit page</span>
                                    </label>
                                 </div>
                    </td>
                    <td>{{ old_field.default_value }}</td>
                    <td>{{ old_field.label_name }}</td>
                    <td><div class="btn btn-danger" v-on:click="remove_old(old_field.id)">Remove</div></td>
               </tr>`,
    props: ["old_field"],
    data() {
      return{
          field_types: ["float" , "dateTime" , "int(11)" , "longText" , "mediumText" , "varchar(255)" , "text"]
      }
    },
    methods:{
        remove_old(id){
            $(".old_field-"+id).remove();
            this.$http.post("/api/v1/restore_field" , {deleted_field: this.old_field}).then(response => {
                console.log(response);
            });
        },
        check_all(id){
            $(".check_this-"+id).prop('checked', $(".check_all-"+id).prop("checked"));
            document.getElementById("send_c_value-"+id).value = "show,add,edit,";
            if (!$(".check_all-"+id).prop("checked")){
                document.getElementById("send_c_value-"+id).value = "";
            }
        },
        check_exist(field){
            if (field.visibility.search("show") > -1 && field.visibility.search("edit") > -1 && field.visibility.search("add") > -1){
                $(".check_all-"+field.id).prop("checked" , true);
                //$(".check_this-"+id).prop('checked', true);
                document.getElementById("send_c_value-"+id).value = "show,add,edit,";
            }
                if (field.visibility.search("show") > -1) {
                    $(".check_show-" + field.id).prop("checked", true);
                    document.getElementById("send_c_value-" + id).value += "show,";
                }
                if (field.visibility.search("edit") > -1) {
                    $(".check_edit-" + field.id).prop("checked", true);
                    document.getElementById("send_c_value-" + id).value += "edit,";
                }
                if (field.visibility.search("add") > -1) {
                    $(".check_add-" + field.id).prop("checked", true);
                    document.getElementById("send_c_value-" + id).value += "add,,";
                }
        },
        send_v_checks(order , val){
            var chosed_allready = document.getElementById("send_c_value-"+order).value;
            if ($(".check_"+ val +"-"+order).prop("checked") && chosed_allready.search(val) == -1) {
                document.getElementById("send_c_value-"+order).value += val + ","
                if (document.getElementById("send_c_value-"+order).value.search("add") > -1 && document.getElementById("send_c_value-"+order).value.search("edit") > -1 && document.getElementById("send_c_value-"+order).value.search("show") > -1){
                    $(".check_all-"+order).prop("checked" , true)
                }
            }
            if (!$(".check_"+ val +"-"+order).prop("checked")){
                var new_value = document.getElementById("send_c_value-"+order).value.replace(val + "," , '');
                document.getElementById("send_c_value-"+order).value = new_value
                //if (document.getElementById("send_c_value-"+order).value == ""){
                $(".check_all-"+order).prop("checked" , false)
                //}
            }
        }
    },
    mounted(){
        this.check_exist(this.old_field);
    }
});

Vue.component("noti_undo" , {
    template: `<div v-bind:class="'alert alert-info deleted_field-' + deleted_field.id"><h4 style="font-family: Mada, sans-serif" class="text-center">استعد الحقل مجددا من <span v-on:click="field_undo(deleted_field)" style="cursor: pointer;" class="text-warning"> هنا </span></h4></div>`,
    props: ["deleted_field"],
    data () {
      return {
          delete_fields: [],
          collapsed: false
      }
    },
    methods: {
        field_undo(deleted_field){
            back_again.push({component: "old_field" , props: {old_field: deleted_field}});
            this.$http.post("/api/v1/restore_field" , {deleted_field: this.deleted_field}).then(response => {
                console.log(response);
            });
            $(".deleted_field-"+deleted_field.id).remove();
            this.collapsed = true;
        }
    }
});



/*var tmp = Vue.extend({ 
    template: '<add_relation></add_relation>'
})*/



 new Vue({
  el: '#app',
  data: {
    message: 'Hello World!',
    all_relations: [],
    field_names: [],
    field_ids: [],
    relation_order: 0,
    field_order: 1,
    all_fields: [],
    send_checks: [],
    deleted_field: null,
    noti_undo: [],
    back_again: back_again,
    add_new_relations: [],
    order_add_relaion: parseInt($("#last_relationship").val()),
    order_add_field: parseInt($("#last_field").val()),
    add_new_field: [],
    cat_name: "",
    edit_cat_name: $("#cat_name").attr("cat_name")
  },
  methods: {
  	add_relation() {
  		var inputCount = document.getElementById("fields").getElementsByClassName("f-name").length;
  		for (var r = 0; r < inputCount; r++) {
          if (this.field_names.indexOf($(".f_name-"+r).val()) === -1 && $(".f_name-"+r).val() !== ""){
            this.field_names.push($(".f_name-"+r).val())
            this.field_ids.push();
          }
        }
 		
 		this.relation_order += 1;        
        this.all_relations.push({component: 'add_relation', props: {relation_field: this.field_names , order: this.relation_order}});
  	},
  	add_field() {
  		this.field_order += 1;
        this.all_fields.push({component: 'add_field', props: {order: this.field_order , page: "add"}});
  	},
    
    // put the chosen values in the field that will send to the controller to store in database
    // .v_check represent fields that refer to visibility the values of it (show , add , edit)
    // #check_all is check box to check all the check box
    // #send_c_value-(order) that refer to the hidden input that will send to store it in database
    // .v_check_(val)-(order) that class have been created to use it to detect which input choosed (show or add or , edit)

  	chose_all(ord){
        //document.getElementsByClassName("v_check-"+ord).checked = true;
        //console.log($(".v_check-1").checked = true)
        $(".v_check-"+ord).prop('checked', $("#check_all-"+ord).prop("checked"));
        document.getElementById("send_c_value-"+ord).value = "show,add,edit,";
        if (!$("#check_all-"+ord).prop("checked")){
            document.getElementById("send_c_value-"+ord).value = "";
        }
  	},
    check_exist(){
        var checkers = document.getElementsByClassName("checks").length;
        var get_current_id = document.getElementsByClassName("ids");
        for (var c = 0; c < checkers; c++) {
            if ($("#check_all-"+get_current_id[c].value).prop("checked")) {
                $(".v_check-"+get_current_id[c].value).prop('checked', $("#check_all-"+get_current_id[c].value).prop("checked"));
                document.getElementById("send_c_value-"+get_current_id[c].value).value = "show,add,edit,"
            }
        }
    },
    // put the chosen values in the field that will send to the controller to store in database
    // .v_check represent fields that refer to visibility the values of it (show , add , edit)
    // #check_all is check box to check all the check box
    // #send_c_value-(order) that refer to the hidden input that will send to store it in database
    // .v_check_(val)-(order) that class have been created to use it to detect which input choosed (show or add or , edit)
    send_v_checks(order,val){
      var chosed_allready = document.getElementById("send_c_value-"+order).value;
      if ($(".v_check_"+ val +"-"+order).prop("checked") && chosed_allready.search(val) == -1) {
        document.getElementById("send_c_value-"+order).value += val + ","
        if (document.getElementById("send_c_value-"+order).value.search("add") > -1 && document.getElementById("send_c_value-"+order).value.search("edit") > -1 && document.getElementById("send_c_value-"+order).value.search("show") > -1){
          $("#check_all-"+order).prop("checked" , true)
        } 
      }
      if (!$(".v_check_"+ val +"-"+order).prop("checked")){
        var new_value = document.getElementById("send_c_value-"+order).value.replace(val + "," , '');
        document.getElementById("send_c_value-"+order).value = new_value
        //if (document.getElementById("send_c_value-"+order).value == ""){
        $("#check_all-"+order).prop("checked" , false)
        //}
      }
    },

    delete_field(id){
        $("#edit_field-"+id).remove();
        this.$http.post("/api/v1/delete_field" , {id: id}).then(response => {
            this.deleted_field = response.data;
            console.log(this.deleted_field);
            this.noti_undo.push({component: "noti_undo" , props: {deleted_field: this.deleted_field}});
            setTimeout(function() {
                $('.deleted_field-'+id).fadeOut();
            }, 7000 );
        });
    },
    add_new_relation(all_fields){
        //this.order_add_relaion = last_field;
        this.order_add_relaion += 1;
        this.add_new_relations.push({component: "edit_relation" , props: {field: "" , ids: this.order_add_relaion , relation_table: "" , table_id: "" , all_fields: all_fields , relation_name: ""}});
    },
    add_new_field_in_edit(){
        this.order_add_field += 1;
        this.add_new_field.push({component: "add_field" , props: {order: this.order_add_field , page: "edit"}});
    },
    delete_confirm() {
      confirm("Are you sure you wanna delete this ?")
    }

  },
  mounted (){
    this.check_exist();
  }

})

var myTextarea = document.getElementById("koko");

var editor = CodeMirror.fromTextArea(document.getElementById("parent_code"), {
    matchBrackets: true,
    mode: "application/x-httpd-php",
    indentUnit: 4,
    indentWithTabs: true
});

var editor2 = CodeMirror.fromTextArea(document.getElementById("child_code"), {
    matchBrackets: true,
    mode: "application/x-httpd-php",
});





