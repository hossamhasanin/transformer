<script>
    export default{
        props: ["order"],
        data(){
            return{}
        },
        methods:{
            remove_thecomponent(){
                $(".multi_component-"+this.order).remove()
            }
        }
    }
</script>
<template>

<div v-bind:class="'row multi_component-'+order" style="margin: 10px;">
    <div class="col-md-5">
        <input type="text" class="form-control value_multi" placeholder="Value will send to the server">
    </div>
    <div class="col-md-6">
        <input type="text" class="form-control text_multi"placeholder="Text will be available to see in select box" >        
    </div>
    <div class="col-md-1">
        <div class="btn btn-danger" @click="remove_thecomponent()"><i class="fa fa-times" aria-hidden="true"></i></div>
    </div>    
</div>

</template>