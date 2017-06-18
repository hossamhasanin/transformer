<script>
    export default{
        props: ["order" , "target_field"],
        // mounted (){
        //     this.destroy_yourself();
        // },
        data(){
            return{
                parent_id: $(".multi_component-"+this.order).parent().attr("modal_id")
            }
        },
        methods:{
            remove_thecomponent(){
                $(".multi_component-"+this.order).remove()
            },
            destroy_yourselfars(){
                var parent_id = $(".multi_component-"+this.order).parent().attr("modal_id")
                this.parent_id = parent_id
                console.log(parent_id)                
                if (this.target_id != parent_id){
                    //console.log(parent_id)
                    $(".multi_component-"+this.order).remove()
                }
            }
        }
    }
</script>
<template>

<div v-bind:class="'row multi_component-'+order" style="margin: 10px;">
    <div class="col-md-6">
        <input type="text" class="form-control value_multi" v-bind:id="'multi_key-'+target_field" placeholder="Value will send to the server">
    </div>
    <div class="col-md-6">
        <input type="text" class="form-control text_multi" v-bind:id="'multi_value-'+target_field" placeholder="Text will be available to see in select box" >        
    </div>  
</div>

</template>