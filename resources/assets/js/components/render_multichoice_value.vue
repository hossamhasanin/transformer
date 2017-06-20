<script>
    export default {
        props: ["order","value" , "text" , "target_field" , "mode"],
        data(){
            return {
                key_val: "",
                key_text: "",
                my_val: "",
                my_text: "",
            }
        },
        methods:{
            remove_this(){
                $("#r_data-"+this.order).remove()
                var exp = /this.value@\|this.text\$\|/g
                var new_val = document.getElementById("multichoice_value-"+this.target_field).value.replace(this.value + "@|" + this.text+"$|",'')
                document.getElementById("multichoice_value-"+this.target_field).value = new_val
            },
            save_this(){
                var container = document.getElementById("multichoice_value-"+this.target_field).value;
                var new_val = container.replace(this.value + "@|" + this.text+"$|", this.my_val + "@|" + this.my_text + "$|")
                document.getElementById("multichoice_value-"+this.target_field).value = new_val
                console.log(new_val)
                this.mode = "new"
            },
            reset_vals(){
                this.my_val = this.value
                this.my_text = this.text
            }
        },
        mounted(){
            this.reset_vals();
        }
    }
</script>
<template>
<tr v-bind:id="'r_data-'+order " v-if = "mode == 'new'">
    <td>{{ my_val }}</td>
    <td>{{ my_text }}</td>
    <td><div class="btn btn-danger" @click="remove_this()"><i class="fa fa-times" aria-hidden="true"></i></div></td>
</tr>
<tr v-bind:id="'r_data-'+order " v-else>
    <td><input type="text" v-model="my_val"></td>
    <td><input type="text" v-model="my_text"></td>
    <td><div class="btn btn-danger" @click="remove_this()"><i class="fa fa-times" aria-hidden="true"></i></div></td>
    <td><div class="btn btn-success" @click="save_this()"><i class="fa fa-check" aria-hidden="true"></i></div></td>            
</tr>
</template>