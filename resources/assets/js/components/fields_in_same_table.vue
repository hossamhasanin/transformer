<script>

export default{
    props: ["table_fields" , "field_id"],
    methods:{
        fetch_table_fields(){
            var t = 0
            while(t < table_fields.data.length){
                var fetch_fields = $("#table_fields-"+this.order);
                fetch_fields.append("<input type='checkbox' value='" + Object.values(this.other_tables.data[t])[0] + "'>")
                t += 1
            }
        }
    }
}

</script>
<template>

<div class="row">
    <div class="col-md-12">
        <div v-bind:id="'table_fields-'+field_id"></div>
    </div>
</div>

</template>