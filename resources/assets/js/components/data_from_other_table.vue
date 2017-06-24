<script>
    
export default{
    props:["fields" , "table_id" , "field_id"],
    methods:{
        set_field_id(event){
            var other_field = event.target.value
            document.getElementById("send_data_other_table-"+this.field_id).value = this.table_id + "," + other_field;
        }
    }
}

</script>

<template>
<div>
<div v-for="fielder in fields" class="radio">
    <label>
        <input type="radio" style="transform: scale(1.5);height: 30px;" name="fields_from_other" @click="set_field_id($event)" v-bind:value="fielder.id" ><span style="font-size:24px;"> {{ fielder.field_name }}</span><br>
    </label>
</div>
</div>
</template>