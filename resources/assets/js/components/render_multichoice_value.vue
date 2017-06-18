<script>
    export default {
        props: ["order","value" , "text" , "target_field"],
        methods:{
            remove_this(){
                $("#r_data-"+this.order).remove()
                var exp = /this.value@\|this.text\$\|/g
                var new_val = document.getElementById("multichoice_value-"+this.target_field).value.replace(this.value + "@|" + this.text+"$|",'')
                document.getElementById("multichoice_value-"+this.target_field).value = new_val
            }
        }
    }
</script>
<template>
<tr v-bind:id="'r_data-'+order ">
    <td>{{ value }}</td>
    <td>{{ text }}</td>
    <td><div class="btn btn-danger" @click="remove_this()"><i class="fa fa-times" aria-hidden="true"></i></div></td>
</tr>
</template>